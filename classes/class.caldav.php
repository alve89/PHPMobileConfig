<?php
	class caldav
	{
		public $host;
		public $port;
		public $useSSL;
	
		public function __construct($config)
		{
			$this->host		= $config['host'];
			$this->port		= $config['port'];
			$this->useSSL	= $config['useSSL'];

			return $this;
		}
	}