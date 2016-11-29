<?php
	class addTheseAccounts extends addAccounts
	{
		private $accountsToAdd = array();
		private $user;
		/*
		 *
		 * BEGIN CONFIGURATION
		 *
		 */
		// Add here all accounts you want to add to the profile
		protected function getAccountsToAdd()
		{
			
		
			$this->accountsToAdd = array(

									array('accountName' => 'caldav',
								  		'configuration' => array('host' => 'cloud.die-herzogs.com',
								  								 'user' => $this->user->username,
								  								 'password' => $this->user->password,
																 'port' => 443,
																 'useSSL' => true)
								  		),
									  array('accountName' => 'carddav',
								  		'configuration' => array('host' => 'cloud.die-herzogs.com',
								  								 'user' => $this->user->username,
								  								 'password' => $this->user->password,
																 'port' => 443,
																 'useSSL' => true)
								  		),


									  array('accountName' => 'mail',
								  		'configuration' => array('incomingHost' => 'w013e4e2.kasserver.com',
								  								 'incomingUser' => $this->user->email,
								  								 'incomingPassword' => $this->user->password,
																 'incomingPort' => 993,
																 'incomingUseSSL' => true,
																 'outgoingHost' => 'w013e4e2.kasserver.com',
																 'outgoingUser' => $this->user->email,
																 'outgoingPassword' => $this->user->password,
																 'outgoingPort' => 465,
																 'outgoingUseSSL' => true
																)
								  		),
								  		
/*
								  	  array('accountName' => 'wifi',
								  			'configuration' => array('ssid' => 'ntwAlSt9289',
								  									'key' => 'ThpVh6JZ8JWNcpCAD6gSfaaHawjbfDmY'
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
		