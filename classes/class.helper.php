<?php
	abstract class HELPER
	{
		/*
		 * Generates a random string
		 *
		 * @param		int			$length				Length of random string
		 * @param		bool		$numbers			String contains numbers
		 * @param		bool		$letters_lc			String contains lower case letters
		 * @param		bool		$letters_uc			String contains upper case letters
		 * @param		bool		$symbols			String contains special characters
		 *
		 * return string
		 */  
		static public function generateRandomString($length = 32, $symbols = false, $numbers = true, $letters_lc = true, $letters_uc = true)
		{
			$characters = '';
			
			if($numbers)
			{
				$characters .= '0123456789';
			}
			if($letters_lc)
			{
				$characters .= 'abcdefghijklmnopqrstuvwxyz';
			}
			if($letters_uc)
			{
				$characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			}
			if($symbols)
			{
				$characters .= ',.-;:_!§%()=?@{}[]$#+*´`"';
			}
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		    }
		    return $randomString;
		}
		
		/*
		 * Generates a unique UUID
		 *
		 * @param		string		$version				Desired ersion of UUID
		 * @param		string		$uuid					Current UUID
		 *
		 * return string
		 */  
		public static function get_uuid($version = '1', $uuid = null)
		{
			// Import global array that contains used UUIDs to prevent duplicates
			global $uuid_array;
			
			$version = strval($version);
			$uuid = file_get_contents('https://www.uuidgenerator.net/api/version' . $version);
			
			// Check if current UUID has already been used
			if(!in_array(md5($uuid), $uuid_array))
			{
				$uuid_array[] = md5($uuid);
				return substr($uuid,0,36);
			}
			else
			{
				get_uuid(file_get_contents('https://www.uuidgenerator.net/api/version' . $version, $uuid));
			}
		}
		
		/*
		 * Check which device is requesting
		 *
		 */
		
		public static function client()
		{
			//Detect special conditions devices
			$iPod	= stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
			$iPhone	= stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
			$iPad	= stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
			$Android= stripos($_SERVER['HTTP_USER_AGENT'],"Android");
			$webOS	= stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
			$Mac	= stripos($_SERVER['HTTP_USER_AGENT'],"Macintosh");
			$Windows	= stripos($_SERVER['HTTP_USER_AGENT'],"Windows");
			
			//do something with this information
			if($iPod || $iPhone || $iPad || $Mac)
			{
			    //browser reported as an iPhone/iPod touch -- do something here
			    return 'apple';
			}
			elseif($Android || $Windows)
			{
			    //browser reported as an iPad -- do something here
			    return 'other';
			}
		}

		
		/*
		 * Improved version of var_dump()
		 *
		 * 
		 * @param		variant			$var				Variable to dump
		 *
		 *
		 */	
		public static function varDump($var)
		{
			echo '<pre>';
			var_dump($var);
			echo '</pre>';
		}
		
		
		/*
		 *
		 * Print current status message with a short delay
		 *
		 *
		 */
		public static function printStatus($status)
		{
			echo $status . "<br /> ";
		    echo str_pad('',4096)."\n";
		    ob_flush();
		    flush();
		    //sleep(1);
		}

		/*
		 *
		 * Print current debug message if enabled in config.general.php
		 *
		 *
		 */
		
		public static function debug($status)
		{
			if(config::$debug)
			{
				echo '<strong>'.$status . "</strong><br /> ";
				echo str_pad('',4096)."\n";
				ob_flush();
				flush();
				//sleep(1);
			}
		}

		/*
		 *
		 * Adding style information after header was sent by PHP
		 *
		 *
		 */
		
		public static function addCSS()
		{
			echo <<< END
			<style type="text/css">
				body {
					font-family: 'Helvetica',serif;
				}
			</style>
END;
		}
		
		
		/*
		 * Function to check all dependencies relevant for creating the profile
		 * @param		object			$files				Contains all relevant file names
		 *
		 *
		 *
		 */
		
		
		public static function checkDependencies($files)
		{

			// If writing content into it fails the profile is invalid (empty)
			self::debug('Raw file is prepared');
			if(file_put_contents($files->infile, '') === false)
			{
				// Content of profile xml could not be written into file
				return array(false, 'infile');
			}
			// else everything's fine
			
			self::debug('Designed file is prepared');
			if(file_put_contents($files->outfile, '') === false)
			{
				// File not exists. Only an unsigned profile is possible.
				return array(true, 'outfile');
			}
			// else everything's fine
		
			self::debug('Check certificate files');
			foreach(array($files->signcert, $files->privkey, $files->extracerts) as $file)
			{
				// Check if one of the files necessary to sign is missing
				if(!file_exists($file))
				{
					// If yes profile can't be signed, so there's no need to check the rest of the files
					//self::printStatus('The following file necessary to sign the profile is missing: ' . $file);
					//self::printStatus('Profile signing will be skipped');
					return array(true, pathinfo($file, PATHINFO_BASENAME));
				}
				// else continue checking remaining files
			}
			// Everything's fine, all dependecies exist
			return true;
		}


		/**
		 * getHTTPStatusCode
		 *
		 * @param array $headers as in $http_response_header
		 * @return array status and headers grouped by response, last first
		 */
		public static function getHTTPStatusCode($url)
		{
			$options['http'] = array(
			    'method' => "HEAD",
			    'ignore_errors' => 1,
			);
			$context = stream_context_create($options);
			$body = @file_get_contents($url, NULL, $context);
			$headers = $http_response_header;

		    $responses = array();
		    $buffer = NULL;
		    foreach ($headers as $header)
		    {
		        if ('HTTP/' === substr($header, 0, 5))
		        {
		            // add buffer on top of all responses
		            if ($buffer) array_unshift($responses, $buffer);
		            $buffer = array();
		
		            list($version, $code, $phrase) = explode(' ', $header, 3) + array('', FALSE, '');
		
		            $buffer['status'] = array(
		                'line' => $header,
		                'version' => $version,
		                'code' => (int) $code,
		                'phrase' => $phrase
		            );
		            $fields = &$buffer['fields'];
		            $fields = array();
		            continue;
		        }
		        list($name, $value) = explode(': ', $header, 2) + array('', '');
		        // header-names are case insensitive
		        $name = strtoupper($name);
		        // values of multiple fields with the same name are normalized into
		        // a comma separated list (HTTP/1.0+1.1)
		        if (isset($fields[$name]))
		        {
		            $value = $fields[$name].','.$value;
		        }
		        $fields[$name] = $value;
		    }
		    unset($fields); // remove reference
		    array_unshift($responses, $buffer);

			$code = $responses[0]['status']['code']; // last status code
			/*
				// Status code (after all redirects)
				echo $code . "<br />";
				// Number of responses
				$number = count($responses);
				echo $number . "<br />";
				// Number of redirects
				$redirects = $number - 1;
				echo $redirects . "<br />";
			
				if ($redirects)
				{
				    $from = $url;
				
				    foreach (array_reverse($responses) as $response)
				    {
				        if (!isset($response['fields']['LOCATION']))
				            break;
				        $location = $response['fields']['LOCATION'];
				        $code = $response['status']['code'];
				
				        echo " * $from -- $code --> $location<br>\n";
				        $from = $location;
				    }
				    echo "<br>\n";
				}
			*/

			return $code;	// returns only the status code
		    //return $responses;	// returns the whole response
		}

		
		public static function setURL($protocol, $file = null, $params = null)
		{

			//$_SERVER['REQUEST_URI'];	// dir + file
			//$_SERVER['QUERY_STRING'];	// GET parameters
			//$_SERVER['HTTP_HOST']; // domain
			if(substr($protocol,-3) != '://')
			{
				$protocol .= '://';
			}
			$query = explode('&', $_SERVER['QUERY_STRING']);
			(empty($query[0])) ? $query = array() : $query;
			(is_null($params)) ? $params = array() : $params;
			$params = array_merge($params, $query);
			(empty($params)) ? $query = '' : $query = '?'.http_build_query($params);
			
			$domain = $_SERVER['HTTP_HOST']; // domain
			$dir = pathinfo($_SERVER['REDIRECT_URL'])['dirname'];	// dir
			// Check if there's a relative directory path
			(empty($dir) || $dir == '/') ? $dir=DIRECTORY_SEPARATOR : $dir.=DIRECTORY_SEPARATOR;
			(is_null($file)) ? $file = pathinfo($_SERVER['REDIRECT_URL'])['basename'] : $file;	// file

			return $protocol.$domain.$dir.$file.$query;
		}


		public static function getCurrentProtocol()
		{
			if(isset($_SERVER['HTTPS']))
			{
				$protocol = 'https';
			}
			else
			{
				$protocol = 'http';
			}
			return $protocol;
		}






}





?>