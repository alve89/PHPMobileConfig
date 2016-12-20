<?php
class profile extends config
{

	private $user;
	private $profile;
	private $organization;
	private $aliases;
	private $mails		= array();
	private $caldavs	= array();
	private $carddavs	= array();
	private $wifis		= array();

	
	public function __construct($user, $accounts)
	{
		$accounts = $accounts->getAccounts();
		foreach($accounts as $account)
		{
			if(is_a($account, 'mail'))
			{
				$this->mails[] = $account;
			}
			elseif(is_a($account, 'caldav'))
			{
				$this->caldavs[] = $account;
			}
			elseif(is_a($account, 'carddav'))
			{
				$this->carddavs[] = $account;
			}
			elseif(is_a($account, 'wifi'))
			{
				$this->wifis[] = $account;
			}
		}
		
		$this->user					= $user;

		$this->organization			= new stdClass;
		$this->organization->fullName = config::$organizationFullName;
		$this->organization->identifier = config::$organizationIdentifier;

		
		$this->profile				= new stdClass;
		$this->profile->desc		= config::$profileDesc;
		
		if(!isset(config::$profileName) || is_null(config::$profileName) || config::$profileName == "")
		{
			$this->profile->name = str_replace(' ', '_', $this->organization->fullName.'-'.$this->user->lastname.'_'.$this->user->firstname);
		}
		

		if(!isset(config::$profileIdentifier) || is_null(config::$profileIdentifier) || config::$profileIdentifier == "")
		{
			$this->profile->identifier = strtolower(str_replace(' ', '_', $this->organization->fullName.'.'.$this->user->lastname.'_'.$this->user->firstname));
		}





		if(is_null($this->profile->name))
		{
			$this->profile->name = 'Profile.'.$this->user->lastname.'_'.$this->user->firstname.'.'.$this->organization->fullName;
		}

		if(is_null($this->profile->identifier))
		{
			$this->profile->identifier = 'profile.'.strtolower($this->user->lastname.$this->user->firstname).'.'.$this->organization->identifier;
		}

		if(is_null($this->profile->desc))
		{
			$this->profile->desc = $this->organization->fullName;
		}
		
		$this->controlProfileContainsData = false;
		
		return $this;
	}
	
	private function wifi()
	{
		if(!isset($this->wifi->encryption) || is_null($this->wifi->encryption) || $this->wifi->encryption == "")
		{
			$this->wifi->encryption = 'WPA';
		}
		

		$wifi = "
				<dict>
					<key>AutoJoin</key>
					<true/>
					<key>EncryptionType</key>
					<string>".$this->wifi->encryption."</string>
					<key>HIDDEN_NETWORK</key>
					<false/>
					<key>Password</key>
					<string>".$this->wifi->key."</string>
					<key>PayloadDescription</key>
					<string>Verbindungseinstellungen für drahtloses Netzwerk konfigurieren.</string>
					<key>PayloadDisplayName</key>
					<string>Wi-Fi (".$this->wifi->ssid.")</string>
					<key>PayloadIdentifier</key>
					<string>".$this->identifier().".wifi</string>
					<key>PayloadOrganization</key>
					<string>".$this->organization->fullName."</string>
					<key>PayloadType</key>
					<string>com.apple.wifi.managed</string>
					<key>PayloadUUID</key>
					<string>".HELPER::get_uuid()."</string>
					<key>PayloadVersion</key>
					<integer>1</integer>
					<key>ProxyType</key>
					<string>None</string>
					<key>SSID_STR</key>
					<string>".$this->wifi->ssid."</string>
				</dict>";
		$this->controlProfileContainsData = true;
				return $wifi;
	}
	
	private function caldav()
	{

		if(!isset($this->caldav->user) || is_null($this->caldav->user) || $this->caldav->user == "")
		{
			$this->caldav->user = $this->user->username;
		}
		
		
		if(!isset($this->caldav->password) || is_null($this->caldav->password) || $this->caldav->password == "")
		{
			$this->caldav->password = $this->user->password;
		}
		
		
		if(!isset($this->caldav->desc) || is_null($this->caldav->desc) || $this->caldav->desc == "")
		{
			$this->caldav->desc = $this->profile->desc . ': Kalender';
		}

		$caldav = "
					<dict>
						<key>CalDAVAccountDescription</key>
						<string>".$this->caldav->desc."</string>
						<key>CalDAVHostName</key>
						<string>".$this->caldav->host."</string>
						<key>CalDAVPassword</key>
						<string>".$this->caldav->password."</string>
						<key>CalDAVPort</key>".
						$this->returnValue($this->caldav->port)."
						<key>CalDAVUseSSL</key>".
						$this->returnValue($this->caldav->useSSL)."
						<key>CalDAVUsername</key>
						<string>".$this->caldav->user."</string>
						<key>PayloadDescription</key>
						<string>CalDAV-Account konfigurieren</string>
						<key>PayloadDisplayName</key>
						<string>CalDAV (".$this->profile->desc.": Kalender)</string>
						<key>PayloadIdentifier</key>
						<string>".$this->identifier().".caldav</string>
						<key>PayloadOrganization</key>
						<string>".$this->organization->fullName."</string>
						<key>PayloadType</key>
						<string>com.apple.caldav.account</string>
						<key>PayloadUUID</key>
						<string>" . HELPER::get_uuid() . "</string>
						<key>PayloadVersion</key>
						<integer>1</integer>
					</dict>";
		$this->controlProfileContainsData = true;
		return $caldav;
		
	}
	
	private function mail()
	{		
		if($this->mail->incoming->password === $this->mail->outgoing->password)
		{
			$this->mail->outgoing->password = "<key>OutgoingPasswordSameAsIncomingPassword</key>
												<true/>";
		}
		else
		{
			$this->mail->outgoing->password = "<key>OutgoingPassword</key>
												<string>".$this->mail->outgoing->password."</string>
												<key>OutgoingPasswordSameAsIncomingPassword</key>
												<false/>";
		}

		if(!isset($this->mail->incoming->user) || is_null($this->mail->incoming->user) || $this->mail->incoming->user == "")
		{
			$this->mail->incoming->user = $this->user->username;
		}
		
		if(!isset($this->mail->outgoing->password) || is_null($this->mail->outgoing->password) || $this->mail->outgoing->password == "")
		{
			$this->mail->outgoing->password = $this->user->password;
		}
		
		if(!isset($this->mail->email) || is_null($this->mail->email) || $this->mail->email == "")
		{
			$this->mail->email = $this->user->email;
		}
		
		if(!isset($this->mail->desc) || is_null($this->mail->desc) || $this->mail->desc == "")
		{
			$this->mail->desc = $this->profile->desc . ': Email';
		}

		
		if(!isset($this->mail->name) || is_null($this->mail->name) || $this->mail->name == "")
		{
			$this->mail->name = $this->user->name;
		}

		
		if(!empty($this->aliases))
		{
			array_unshift($this->aliases, $email);
			$email = implode(', ', $this->aliases);
			unset($this->aliases);
		}
		
		
		$mail = "
					<dict>
						<key>EmailAccountDescription</key>
						<string>".$this->mail->desc."</string>
						<key>EmailAccountName</key>
						<string>".$this->mail->name."</string>
						<key>EmailAccountType</key>
						<string>EmailTypeIMAP</string>
						<key>EmailAddress</key>
						<string>".$this->mail->email."</string>
						<key>IncomingMailServerAuthentication</key>
						<string>EmailAuthPassword</string>
						<key>IncomingMailServerHostName</key>
						<string>".$this->mail->incoming->host."</string>
						<key>IncomingMailServerPortNumber</key>
						<integer>".$this->mail->incoming->port."</integer>
						<key>IncomingMailServerUseSSL</key>".
						$this->returnValue($this->mail->incoming->useSSL)."
						<key>IncomingMailServerUsername</key>
						<string>".$this->mail->incoming->user."</string>
						<key>IncomingPassword</key>
						<string>".$this->mail->incoming->password."</string>
						<key>OutgoingMailServerAuthentication</key>
						<string>EmailAuthPassword</string>
						<key>OutgoingMailServerHostName</key>
						<string>".$this->mail->outgoing->host."</string>
						<key>OutgoingMailServerPortNumber</key>
						<integer>".$this->mail->outgoing->port."</integer>
						<key>OutgoingMailServerUseSSL</key>".
						$this->returnValue($this->mail->outgoing->useSSL)."
						<key>OutgoingMailServerUsername</key>
						<string>".$this->mail->outgoing->user."</string>
						".$this->mail->outgoing->password."
						<key>PayloadDescription</key>
						<string>E-Mail-Account konfigurieren.</string>
						<key>PayloadDisplayName</key>
						<string>IMAP-Account (Firmenaccount)</string>
						<key>PayloadIdentifier</key>
						<string>".$this->organization->identifier.".email</string>
						<key>PayloadOrganization</key>
						<string>".$this->organization->fullName."</string>
						<key>PayloadType</key>
						<string>com.apple.mail.managed</string>
						<key>PayloadUUID</key>
						<string>".HELPER::get_uuid()."</string>
						<key>PayloadVersion</key>
						<integer>1</integer>
					</dict>";
		$this->controlProfileContainsData = true;
		return $mail;
	}
	
	private function carddav()
	{
		if(!isset($this->carddav->user) || is_null($this->carddav->user) || $this->carddav->user == "")
		{
			$this->carddav->user = $this->user->username;
		}
		
		
		if(!isset($this->carddav->password) || is_null($this->carddav->password) || $this->carddav->password == "")
		{
			$this->carddav->password = $this->user->password;
		}
				
		
		if(!isset($this->carddav->desc) || is_null($this->carddav->desc) || $this->carddav->desc == "")
		{
			$this->carddav->desc = $this->profile->desc . ': Kontakte';
		}
		
		$carddav = "
					<dict>
						<key>CardDAVAccountDescription</key>
						<string>".$this->carddav->desc."</string>
						<key>CardDAVHostName</key>
						<string>".$this->carddav->host."</string>
						<key>CardDAVPassword</key>
						<string>".$this->carddav->password."</string>
						<key>CardDAVPort</key>".
						$this->returnValue($this->carddav->port)."
						<key>CardDAVUseSSL</key>".
						$this->returnValue($this->carddav->useSSL)."
						<key>CardDAVUsername</key>
						<string>".$this->carddav->user."</string>
						<key>PayloadDescription</key>
						<string>CardDAV-Accounts konfigurieren</string>
						<key>PayloadDisplayName</key>
						<string>CardDAV</string>
						<key>PayloadIdentifier</key>
						<string>".$this->identifier().".carddav</string>
						<key>PayloadOrganization</key>
						<string>".$this->organization->fullName."</string>
						<key>PayloadType</key>
						<string>com.apple.carddav.account</string>
						<key>PayloadUUID</key>
						<string>" . HELPER::get_uuid() . "</string>
						<key>PayloadVersion</key>
						<integer>1</integer>
					</dict>";
		$this->controlProfileContainsData = true;
		return $carddav;
	}
	
	
	protected function certificate($name, $cert, $type, $pw = null)
	{
		$cert = file_get_contents($cert);
		$certificate = "
						<dict>
							<key>PayloadCertificateFileName</key>
							<string>wlan.h-da.de.cer</string>
							<key>PayloadContent</key>
							<data>".
							$cert.
							"</data>
							<key>PayloadDescription</key>
							<string>Sorgt für die Geräte-Authentifizierung (Zertifikat oder Identität).</string>
							<key>PayloadDisplayName</key>
							<string>".$name."</string>
							<key>PayloadIdentifier</key>
							<string>".$this->profile->identifier."Zertifikat</string>
							<key>PayloadOrganization</key>
							<string>".$this->organization->fullName."</string>
							<key>PayloadType</key>
							<string>com.apple.security.pkcs1</string>". // add type
							"<key>PayloadUUID</key>
							<string>".HELPER::get_uuid()."</string>
							<key>PayloadVersion</key>
							<integer>1</integer>
						</dict>";
			return $certificate;
	}
	
	
	protected function relevantData()
	{
		$relevantData = $this->set_element('PayloadDescription', 'Dieses Profil konfiguriert und erstellt automatisch alle wichtigen Email-, Kalender- und Adressbuchaccounts.').
						$this->set_element('PayloadDisplayName', $this->profile->name).
						$this->set_element('PayloadIdentifier', $this->organization->identifier).
						$this->set_element('PayloadOrganization', $this->organization->fullName).
						$this->set_element('PayloadRemovalDisallowed', false).
						$this->set_element('PayloadType', 'Configuration').
						$this->set_element('PayloadUUID', HELPER::get_uuid()).
						$this->set_element('PayloadVersion', 1);
		return $relevantData;
	}

	private function identifier()
	{
		return strtolower($this->profile->identifier);
	}
	
	

	private function returnValue($value)
	{
		// Return boolean value as string
		if(is_bool($value))
		{
			if($value)
			{
				$value = 'true';
			}
			else
			{
				$value = 'false';
			}
			$value = "\n						<".$value."/>";
		}
		// Return integer
		elseif(is_int($value))
		{
			$value = "\n						<integer>$value</integer>";
		}
		// Return string
		elseif(is_string($value))
		{
			$value	= "\n						<string>$value</string>";
		}
		else
		{
			//die('FEHLER' . __LINE__.'['.$value.']');
			return false;
		}
		return $value;

	}
	
	private function set_element($key,$value)
	{

		if(!$value = $this->returnValue($value))
		{
			die('Fehler bei Key: ' . $key);
		}
		
		$key	= "\n						<key>$key</key>";

		return $key.$value;
	}
	
	public function get_xml()
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
		<plist version="1.0">'."
		<dict>
			<key>PayloadContent</key>
				<array>";
						// CalDAV
						foreach($this->caldavs as &$this->caldav)
						{
							$xml .= $this->caldav($this->caldav);
						}
		
						// CardDAV
						foreach($this->carddavs as &$this->carddav)
						{
							$xml .= $this->carddav($this->carddav);
						}
		
						// Mail
						foreach($this->mails as &$this->mail)
						{
							$xml .= $this->mail($this->mail);
						}
						
						// WLAN
						
						foreach($this->wifis as &$this->wifi)
						{
							$xml .= $this->wifi($this->wifi);
						}
						
						// WLANs

						// Apple: Einschränkungen
						
						// Apple: Code

					$xml .="</array>
					".
					// Notwendige allgemeine Informationen
						$this->relevantData().
				"</dict>
				</plist>";
		if(!$this->controlProfileContainsData)
		{
			return false;
		}
		else
		{
			return $xml;			
		}
	}
}



