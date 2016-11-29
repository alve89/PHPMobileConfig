<?php
	class addAccounts
	{
		// Is filled from /config/config.addtheseaccounts.php
		private $accounts;
				
		
		private function __construct()
		{
			//return $this->addAccounts();
		}
		
		protected function addAccounts()
		{
			foreach($this->getAccountsToAdd() as $account)
			{
				$this->accounts[] = new $account['accountName']($account['configuration']);
			}
		}
		
		
		/*
		 * Return added accounts
		 *
		 *
		 */
		
		public function getAccounts()
		{
			return $this->accounts;
		}
	}


