<?php
	session_start();
	
	// Include class to use functions
	require_once('classes/class.helper.php');
	
	// Check if $uid and $pw are set
	if(isset($_POST['uid']) && isset($_POST['pw']))
	{
		$uid = $_POST['uid'];
		$pw = $_POST['pw'];
		
		
		
		// Authenticate your user here
		
		
		#
		# Authentication of the user
		# 
		# @param 	$uid	string	Username, given by form
		# @param	$pw		string	Password, given by form
		# @param	$auth	boolean	Contains the result of authentication
		# 
		# $auth = anyAuthFunction($uid, $pw);
		#
		#
		
		// If authentication was successful redirect user to profile download
		if($auth !== false)
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

