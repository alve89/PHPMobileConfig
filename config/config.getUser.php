<?php

	$user->username = $_SESSION['uid'];
	$user->password = $_SESSION['pw'];

	// Get additional user details from database
	#
	#
	# $res is the result of a MySQL query as an object to get user details
	
	$user->firstname = $res->firstname;
	$user->lastname = $res->lastname;
	$user->email = $res->email;
	
	// Generate missing information automatically
	$user->createUser();