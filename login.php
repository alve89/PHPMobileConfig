<?php
	session_start();

	// Load necessary files
	require_once('classes/class.helper.php');
	require_once('classes/class.auth.php');
	require_once('interfaces/interface.authConfig.php');


	// The following if-clause is needed for testing purposes. It's false in release packages.
	if(file_exists($incFile = 'test/config.authenticationBackends.php'))
	{
		require_once($incFile);
	}
	else
	{
		require_once('config/config.authenticationBackends.php');
	}
	

	// Check if $uid and $pw are set
	if(isset($_POST['uid']) && isset($_POST['pw']))
	{
		$uid = $_POST['uid'];
		$pw = $_POST['pw'];
		
		// Authenticate user
		$auth = new auth;
		
		// If authentication was successful redirect user to profile download
		if($auth->authUser($uid, $pw) !== false)
		{
			$_SESSION = $_POST;
			header("Location: " . HELPER::setURL(HELPER::getCurrentProtocol(), 'index.php'));
		}
		else
		{
			echo 'Incorrect username / password';
		}
	}
?>

<form method="post" action="" name="login">
	<input type="text" placeholder="Username" name="uid" required="true" /><br />
	<input type="password" placeholder="Password" name="pw" required="true" /><br />
	<input type="submit" />
</form>

