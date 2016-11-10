<?php
	// Your user might be authenticated against MySQL database and filled out a login form
	$usernameFromLoginForm = 'myUserName';
	
	// Your user information might be loaded from a MySQL database
	// "SELECT * FROM `users` WHERE 'username' = $usernameFromLoginForm";
	
	// Apply result from MySQL to object $user
	// Variable name needs to be $user!!!
	$user = $mySQLResultAsObject;
	
	/*
	 *	This might contain:
	 *
	 *	$user->username = 'myUserName';
	 *	$user->password = 'myPassword';
	 *	$user->firstname= 'myFirstname';
	 *	$user->lastname = 'myLastname';
	 *	$user->email	= 'myFirstname@myLastnam.tld';
	 *
	 */