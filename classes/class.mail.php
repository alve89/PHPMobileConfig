<?php
	class mail
	{
		public $incoming;
		public $outgoing;
		
		public function __construct($config)
		{
			$this->incoming = new stdClass;
			$this->incoming->host	= $config['incomingHost'];
			$this->incoming->port	= $config['incomingPort'];
			$this->incoming->useSSL	= $config['incomingUseSSL'];

			$this->outgoing = new stdClass;
			$this->outgoing->host 	= $config['outgoingHost'];
			$this->outgoing->port 	= $config['outgoingPort'];
			$this->outgoing->useSSL	= $config['outgoingUseSSL'];
			return $this;
		}
	}