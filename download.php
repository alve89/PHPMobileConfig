<?php
	$outfilename = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'profile-sig.mobileconfig';
	if(isset($_GET['error']))
	{
		//var_dump($_SERVER["HTTP_REFERER"]);
		die('Profile does not contain all relevant data, please contact administrator');
	}
	else
	{
		// Read created profile file
		$file = file_get_contents($outfilename);
	
		header('Content-type: application/octet-stream; charset=utf-8');
		header('Content-Disposition: attachment; filename="profile-sig.mobileconfig"');
		echo $file;
		// Remove created files
		// unlink($outfilename); // Commented to be able to use direct link
	}

	exit;

