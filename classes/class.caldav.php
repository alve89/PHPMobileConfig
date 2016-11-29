<?php
	class caldav
	{
		public $host;
		public $port;
		public $useSSL;
	
		public function __construct($config)
		{
			foreach($config as $key => $value)
			{
				$this->$key = $value;
			}
			return $this;
		}
	}