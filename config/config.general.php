<?php

	abstract class config
	{
		// Enable debug mode
		public static $debug = true;
		
		// Use SSL for the site
		public static $siteUseSSL = true;
		
		// Enable profile download on Non Apple Devices
		public static $enableProfileDownloadForNonAppleUsers = true;	// on false you can define what the user should see. Configuration in  	config.displayThisToNonAppleUsers.php
		
		// Configure organization information
		protected static $organizationIdentifier	= 'MYORGANIZATIONNAME';
		protected static $organizationFullName		= 'MYORGANIZATIONFULLNAME';
		
		// Configure profile information
		protected static $profileName				= 'MyProfileName';			// If this is not set, it will be set automatically with organization name and user's name
		protected static $profileIdentifier			= 'profileIdentifier';	// If this is not set, it will be set automatically with organization name and user's name
		protected static $profileDesc				= 'ProfileDesc';
		
		// Configure mail
		protected static $mailMailUserMatchesUsername = true;	// If your mail account username is the same one as your user's username, set this true
																// On shared webspace your mail user might be 'mail12345' but your username you use for different services (CMS, cloud, SSO, ...) is 'myUserName' - set this option to false		

	}
