<?php
	class addTheseAccounts extends addAccounts
	{
		/*
		 *
		 * BEGIN CONFIGURATION
		 *
		 */
		// Add here all accounts you want to add to the profile
		protected $accountsToAdd = array(array('accountName' => 'caldav',
								  		'configuration' => array('host' => 'myCalDAVHost',
																 'port' => 98765,
																 'useSSL' => true)
								  		),
									  array('accountName' => 'carddav',
								  		'configuration' => array('host' => 'myCardDAVHost',
																 'port' => 12345,
																 'useSSL' => true)
								  		),
									  array('accountName' => 'mail',
								  		'configuration' => array('incomingHost' => 'myIncomingHost.tld',
																 'incomingPort' => 143,
																 'incomingUseSSL' => true,
																 'outgoingHost' => 'myOutgoingHost.tld',
																 'outgoingPort' => 993,
																 'outgoingUseSSL' => true
																)
								  		),

									);
		/*
		 *
		 * END CONFIGURATION
		 *
		 *
		 * Do not change anything below!
		 */
		
		
		
		public function __construct()
		{
			return $this->addAccounts();
		}
	}
		