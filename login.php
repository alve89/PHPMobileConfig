<?php
	require_once('classes/class.helper.php');
	session_start();
	if(isset($_POST['uid']) && isset($_POST['pw']))
	{
		$uid = $_POST['uid'];
		$pw = $_POST['pw'];
		// Authenticate your user here
		#
		# @param 	$uid	string	Username, given by form
		# @param	$pw		string	Password, given by form
		# @param	$auth	boolean	Contains the result of authentication
		# 
		# $auth = anyAuthFunction($uid, $pw);
		#
		#
		
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

