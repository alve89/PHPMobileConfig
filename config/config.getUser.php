<?php
	require_once('lib/hSys/framework.php');	

	$user->username = $_SESSION['uid'];
	$user->password = $_SESSION['pw'];

	// Get additional user details from database
	$_DB->select('*')
		->from('#_users')
		->where('username', '=', $user->username)
		->query();
	$res = $_DB->fetch('object')[0];
	
	$user->firstname = $res->firstname;
	$user->lastname = $res->lastname;
	$user->email = $res->email;
	
	// Generate missing information automatically
	$user->createUser();