<?php
	class addTheseAccounts extends addAccounts
	{
		private $accountsToAdd = array();
		private $user;

		protected function getAccountsToAdd()
		{
		/*
		 *
		 * BEGIN CONFIGURATION
		 *
		 */
		// Add here all accounts you want to add to the profile
			$this->accountsToAdd = array(

									array('accountName' => 'caldav',
								  		'configuration' => array('host' => 'caldav.myHost.tld',
								  								 'user' => $this->user->username,
								  								 'password' => $this->user->password,
																 'port' => 443,
																 'useSSL' => true)
								  		),
									  array('accountName' => 'carddav',
								  		'configuration' => array('host' => 'carddav.myHost.tld',
								  								 'user' => $this->user->username,
								  								 'password' => $this->user->password,
																 'port' => 443,
																 'useSSL' => true)
								  		),


									  array('accountName' => 'mail',
								  		'configuration' => array('incomingHost' => 'imap.myHost.tld',
								  								 'incomingUser' => $this->user->email,
								  								 'incomingPassword' => $this->user->password,
																 'incomingPort' => 993,
																 'incomingUseSSL' => true,
																 'outgoingHost' => 'smtp.myHost.tld',
																 'outgoingUser' => $this->user->email,
																 'outgoingPassword' => $this->user->password,
																 'outgoingPort' => 465,
																 'outgoingUseSSL' => true
																)
								  		),
								  		
/*
								  	  array('accountName' => 'wifi',
								  			'configuration' => array('ssid' => 'anyWifiSSID',
								  									'key' => 'thisIsTheKey'
								  								)
								  		),
*/

				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
			
		/*
		 *
		 * END CONFIGURATION
		 *
		 *
		 * Do not change anything below!
		 */
									);
			return $this->accountsToAdd;
		}
		
		public function __construct(&$user)
		{
			// Add user as class property
			$this->user = $user;
			
			// Call function to get all accounts to add
			$this->getAccountsToAdd();
			
			return $this->addAccounts();
		}
	}
		
