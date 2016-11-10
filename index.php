<?php

	// Redirect to ssl encrypted address if current address is only http
	// Use useSSL in config to deactivate this
	if($config->site->useSSL && !isset($_SERVER['HTTPS']))
	{
		header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']);
	}
	

	// Include all necessary files
	require_once('config/config.general.php');
	require_once('classes/class.helper.php');
	require_once('classes/class.user.php');
	require_once('classes/class.mail.php');
	require_once('classes/class.caldav.php');
	require_once('classes/class.carddav.php');
	require_once('classes/class.addaccounts.php');
	require_once('config/config.addTheseAccounts.php');

	// Create relevant instances
	$user = new stdClass;	// Contains all relevant user information
	// Fill object with information
	require_once('config/config.getUser.php');
	
	// Add all accounts configured in config.addTheseAccounts.php
	$accounts = new addTheseAccounts;

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
	if(HELPER::checkDependencies($files, $xml))
	{
		// All dependencies exist and work properly
			HELPER::printStatus('Beginn signing');
		// Run the commandline
		if(exec('openssl smime -sign -signer ' . $files->signcert . ' -inkey ' . $files->privkey . ' -certfile ' . $files->extracerts . ' -nodetach -outform der -in ' . $files->infile . ' -out ' . $files->outfile) == "")
		{
			HELPER::printStatus('Signing successful');
			unlink($files->infile);
		}
		
	}
	elseif(is_array(HELPER::checkDependencies($files, $xml)) && HELPER::checkDependencies($files, $xml)[0])
	{
		// At least one of the files necessary to sign is missing. Skip signing.
		// Remove profile-sig.mobileconfig
		@unlink($files->outfile);
		// Use unsigned profile instead
		if(rename($files->infile, $files->outfile))
		{
			HELPER::printStatus('Unsigned profile successfully generated');
		}
	}
	else
	{
		// Check returns false. Necessary file profile.mobileconfig could not be created or content could not be written
		HELPER::printStatus('Error while generating profile');
	}
	
	HELPER::printStatus('Signed profile successfully generated');
	HELPER::printStatus('<span style="font-size:10px">If download does not start within 10 seconds try this <a href="download.php"><strong>direct link</strong></a></span>');
	
	ob_end_flush();
?>
