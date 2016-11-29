<?php
	class user
	{
		public $username;
		public $password;
		public $firstname;
		public $lastname;
		public $name;
		public $email;
		
		public function __set($name, $value)
		{
			$this->$name = $value;
			return $this->$name;
		}
		
		public function createUser()
		{
			$this->name = $this->firstname . ' ' . $this->lastname;
			return $this;
		}
	}
?>
