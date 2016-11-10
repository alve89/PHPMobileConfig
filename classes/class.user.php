<?php
	class user
	{
		public $username;
		public $password;
		public $firstname;
		public $lastname;
		public $name;
		public $email;
		
		public function __construct()
		{
			$this->name = $this->firstname . ' ' . $this->lastname;
			return $this;
		}


}
?>
