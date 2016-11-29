<?php
	session_start();
	// Include all relevant dependencies
	require_once('config/config.general.php');
	require_once('classes/class.helper.php');	
	
	// Redirect to ssl encrypted address if available and current address is only http
	// Use $siteUseSSL in config to deactivate this
	if(config::$siteUseSSL && !isset($_SERVER['HTTPS']))
	{
		// Only redirects if all tests are passed, otherwise it just continues
		require_once('ssl.php');
	}

	// If no user details are set redirect to login page; use current protocol
	if(empty($_SESSION))
	{
		header("Location: " . HELPER::setURL(HELPER::getCurrentProtocol(), 'login.php'));
	}

	// Include all necessary files
	require_once('classes/class.user.php');
	require_once('classes/class.mail.php');
	require_once('classes/class.caldav.php');
	require_once('classes/class.carddav.php');
	require_once('classes/class.wifi.php');
	require_once('classes/class.addaccounts.php');
	require_once('config/config.addTheseAccounts.php');

	// Create relevant instances
	$user = new user;	// Contains all relevant user information

	// Fill object with information
	require_once('config/config.getUser.php');
	
	// Add all accounts configured in config.addTheseAccounts.php
	$accounts = new addTheseAccounts($user);

	// Array contains all dynamically generated UUIDs to prevent duplicates
	$uuid_array = array();	// This needs to be placed before profile.php

	// Include the profile creating class
	require_once('classes/class.profile.php');

	// Check the accessing device
	if(HELPER::client() === 'apple')
	{
		// iPod, iPhone, iPad, Mac
		goto apple;
	}
	elseif(HELPER::client() === 'other')
	{
		// Anything different than Apple devices
		//goto other;
		goto apple;
	}
	else
	{
		// An error message just for sure
		die('You use an unknown device, please contact the administrator');
	}


	other:	// shows users data
	header('Content-Type: text/html; charset=UTF-8');
	/*
	 *
	 *
	 *	PRINT WHAT EVER YOU WANT, e.g.
	 *	echo 'username: ' . $user->username;
	 *	echo 'firstname: ' . $user->firstname;
	 *	.
	 *	.
	 *	.
	 *
	 *
	 */
	exit;



	apple:	// Downloads a personalized signed profile for Apple devices
	header('Refresh: .02; download.php');
	HELPER::addCSS();
	
	// Start output while creating profile
	if (ob_get_level() == 0) ob_start();

	// new instance of the profile class
	$xml = new profile($user, $accounts);

	// get the compiled profile as a string
	HELPER::printStatus('Retrieving all data');
	if(!$xml = $xml->get_xml())
	{
		?>
		<script type="text/javascript">
			alert("Your profile doesn't contain all necessary data, please contact an administrator.");
		</script>
		<?php
		HELPER::printStatus('An error occured');
		exit;
	}

	$path = new stdClass;

	// Set all necessary variables
	$path->profile	= __DIR__ . DIRECTORY_SEPARATOR;
	$path->data		= __DIR__ . '/data/';
	$path->certs	= __DIR__ . '/data/certs/';

	$files = new stdClass;

	$files->infile		= $path->data . 'profile.mobileconfig';
	$files->outfile		= $path->data . 'profile-sig.mobileconfig';
	$files->signcert	= $path->certs . 'cert.pem';
	$files->privkey		= $path->certs . 'key.pem';
	$files->extracerts	= $path->certs . 'ca-bundle.pem';


	HELPER::printStatus('Checking all dependencies');
	
	// Check all dependencies
	$check = HELPER::checkDependencies($files);	// Set this variable to be able to get return value all the time
	if($check === true)
	   {
			HELPER::printStatus('All dependencies found');
			HELPER::printStatus('Write data to profile');
			if(file_put_contents($files->infile, $xml) === false)
			{
				die('Error while writing data');
			}
			// All dependencies exist and work properly
			HELPER::printStatus('Beginn signing');
			// Run the commandline
			if(exec('openssl smime -sign -signer ' . $files->signcert . ' -inkey ' . $files->privkey . ' -certfile ' . $files->extracerts . ' -nodetach -outform der -in ' . $files->infile . ' -out ' . $files->outfile) == "")
			{
				HELPER::printStatus('Signing successful');
				@unlink($files->infile);
				HELPER::printStatus('Signed profile successfully generated');
			}
	   }
	   else
	   {
		   // At least one necessary file is missing
		   HELPER::debug('A necessary file is missing: ' . $check[1]);
		   HELPER::printStatus('Not all dependencies found');
		   if($check[1] != 'infile')
		   {
			   // Write data to raw file
			   HELPER::printStatus('Write data to profile');
			   if(file_put_contents($files->infile, $xml) === false)
			   {
				   die('Error while writing data');
			   }

			   HELPER::printStatus('Signing not available, will be skipped.');

			   // Remove designed profile file for signing to be able to rename raw one
			   @unlink($files->outfile);
			   if(rename($files->infile, $files->outfile))
			   {
				   HELPER::printStatus('Unsigned profile successfully generated');
			   }
			   else
			   {
				   die('Unsigned profile could not be created');
			   }
		   }
	   }
		

	HELPER::printStatus('<span style="font-size:10px">If download does not start within 10 seconds try this <a href="download.php"><strong>direct link</strong></a></span>');

	ob_end_flush();
?>