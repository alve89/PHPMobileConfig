<?php
	// By default it's supposed SSL is available and the app is accessible with HTTPS
	//(isset($_GET['tryssl'])) ? $ssl = $_GET['tryssl'] : $ssl = true;
	$ssl = true;
	
	// Set error handler due to multiple possible erros (Exceptions, Throwables, ...)
	set_error_handler
	(
		function($errno, $errstr, $errfile, $errline )
		{
		    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
		}
	);	
	
	// Check if SSL is available on this server
	try
	{
		if(!extension_loaded('openssl'))
		{
		    throw new Exception('This app needs the Open SSL PHP extension. Continue with HTTP.');
		}
	}
	catch(Exception $e)
	{
		// It's not available
		//echo $e->getMessage() . '<br />';
		$ssl = false;
	}
	// If OpenSSL is installed
	if($ssl)
	{	
		$protocol = 'https';
		$url = HELPER::setURL($protocol);
		// Test if this app is available via https
		try
		{
			$code = HELPER::getHTTPStatusCode($url); // last status code
		}
		catch(ErrorException $e)
		{
			// Something went wrong - no SSL available
			$code = 0;
			$ssl = false;
		}
		
		if($code !== 200)
		{
			//echo 'No encrypted connection with HTTPS available, continue with HTTP.<br />';
			$protocol = 'http';
		}
	}

	// If all checks were passed
	if($ssl)
	{
		// Set additional parameters
		$params = array('sslokay' => $ssl);
		// Redirect to new address
		//header("Location: " . HELPER::setURL($protocol, '', $params));
		header("Location: " . HELPER::setURL($protocol));
	}
