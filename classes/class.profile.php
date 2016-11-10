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
		}
		$this->user					= $user;
		
		$this->profile				= new stdClass;
		$this->profile->name		= config::$profileName;
		$this->profile->identifier	= config::$profileIdentifier;
		$this->profile->desc		= config::$profileDesc;

		$this->organization			= new stdClass;
		$this->organization->fullName = config::$organizationFullName;
		$this->organization->identifier = config::$organizationIdentifier;

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
	
	private function wifi($ssid, $key, $encryption = 'WPA')
	{
		$wifi = "<dict>
					<key>AutoJoin</key>
					<true/>
					<key>EncryptionType</key>
					<string>$encryption</string>
					<key>HIDDEN_NETWORK</key>
					<false/>
					<key>Password</key>
					<string>$key</string>
					<key>PayloadDescription</key>
					<string>Verbindungseinstellungen für drahtloses Netzwerk konfigurieren.</string>
					<key>PayloadDisplayName</key>
					<string>Wi-Fi ($ssid)</string>
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
					<string>$ssid</string>
				</dict>";
		$this->controlProfileContainsData = true;
				return $wifi;
	}
	
	private function caldav()
	{
		$caldav = "<dict>
						<key>CalDAVAccountDescription</key>
						<string>".$this->profile->desc.": Kalender</string>
						<key>CalDAVHostName</key>
						<string>".$this->caldav->host."</string>
						<key>CalDAVPassword</key>
						<string>".$this->user->password."</string>
						<key>CalDAVPort</key>".
						$this->returnValue($this->caldav->port)."
						<key>CalDAVUseSSL</key>".
						$this->returnValue($this->caldav->useSSL)."
						<key>CalDAVUsername</key>
						<string>".$this->user->username."</string>
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
		
		// Check if mail account username matches user' username
		// Configurable in /config/config.general.php
		if(config::$mailMailUserMatchesUsername)
		{
			$this->mail->incoming->username = $this->user->username;
			$this->mail->outgoing->username = $this->user->username;
		}
		
		if(!empty($this->aliases))
		{
			array_unshift($this->aliases, $email);
			$email = implode(', ', $this->aliases);
			unset($this->aliases);
		}
		
		
		$mail = "<dict>
						<key>EmailAccountDescription</key>
						<string>".$this->profile->desc.": Email</string>
						<key>EmailAccountName</key>
						<string>".$this->user->name."</string>
						<key>EmailAccountType</key>
						<string>EmailTypeIMAP</string>
						<key>EmailAddress</key>
						<string>".$this->user->email."</string>
						<key>IncomingMailServerAuthentication</key>
						<string>EmailAuthPassword</string>
						<key>IncomingMailServerHostName</key>
						<string>".$this->mail->incoming->host."</string>
						<key>IncomingMailServerPortNumber</key>
						<integer>".$this->mail->incoming->port."</integer>
						<key>IncomingMailServerUseSSL</key>".
						$this->returnValue($this->mail->incoming->useSSL)."
						<key>IncomingMailServerUsername</key>
						<string>".$this->mail->incoming->username."</string>
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
						<string>".$this->mail->outgoing->username."</string>
						".$this->mail->outgoing->password.
						"<key>PayloadDescription</key>
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
		$carddav = "<dict>
						<key>CardDAVAccountDescription</key>
						<string>".$this->profile->desc.": Kontakte</string>
						<key>CardDAVHostName</key>
						<string>".$this->carddav->host."</string>
						<key>CardDAVPassword</key>
						<string>".$this->user->password."</string>
						<key>CardDAVPort</key>".
						$this->returnValue($this->carddav->port)."
						<key>CardDAVUseSSL</key>".
						$this->returnValue($this->carddav->useSSL)."
						<key>CardDAVUsername</key>
						<string>".$this->user->username."</string>
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
		$certificate = "<dict>
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
			$value = "\n<".$value."/>";
		}
		// Return integer
		elseif(is_int($value))
		{
			$value = "\n<integer>$value</integer>";
		}
		// Return string
		elseif(is_string($value))
		{
			$value	= "\n<string>$value</string>";
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
		
		$key	= "<key>$key</key>\n";

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
						
						// WLANs

						// Apple: Einschränkungen
						
						// Apple: Code

					$xml .="</array>".
					// Notwendige allgemeine Informationen
						$this->relevantData().
				"</dict>".
				"</plist>";
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



