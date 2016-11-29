<?php
	class mail
	{
		public $incoming;
		public $outgoing;
		
		public function __construct($config)
		{
			$this->incoming = new stdClass;
			$this->incoming->host		= $config['incomingHost'];
			$this->incoming->port		= $config['incomingPort'];
			$this->incoming->useSSL		= $config['incomingUseSSL'];
			$this->incoming->user		= $config['incomingUser'];
			$this->incoming->password	= $config['incomingPassword'];

			$this->outgoing = new stdClass;
			$this->outgoing->host 		= $config['outgoingHost'];
			$this->outgoing->port 		= $config['outgoingPort'];
			$this->outgoing->useSSL		= $config['outgoingUseSSL'];
			$this->outgoing->user		= $config['outgoingUser'];
			$this->outgoing->password	= $config['outgoingPassword'];
			return $this;
		}
	}