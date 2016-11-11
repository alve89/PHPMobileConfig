<?php
	// Your user might be authenticated against MySQL database and filled out a login form
	$usernameFromLoginForm = 'myUserName';
	
	// Your user information might be loaded from a MySQL database
	// "SELECT * FROM `users` WHERE 'username' = $usernameFromLoginForm";
	
	// Apply result from MySQL to object $user
	// If you filled mySQLResultAsObject from database, it's automatically an object (when fetching an object).
	$mySQLResultAsObject = new stdClass;  // This line is only for testing purposes to not overwrite already in index.php defined $user and have not an object anymore.
	// Variable name needs to be $user!!!
	$user = $mySQLResultAsObject;;
	
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
