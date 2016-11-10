<?php
	$infilename = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'profile.mobileconfig';
	$outfilename = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'profile-sig.mobileconfig';
	if(isset($_GET['error']))
	{
		//var_dump($_SERVER["HTTP_REFERER"]);
		die('Das Profile enth&auml;lt nicht alle notwendigen Daten. Bitte kontaktiere den Administrator.');
	}
	else
	{
		// Einlesen der signierten Datei und Ausgabe als Download
		$file = file_get_contents($outfilename);
	
		header('Content-type: application/octet-stream; charset=utf-8');
		header('Content-Disposition: attachment; filename="profile-sig.mobileconfig"');
		echo $file;
		// Löschen der erstellten Dateien
		unlink($infilename);
		//unlink($outfilename);	
	}

	exit;

