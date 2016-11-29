<?php
	class wifi
	{
		public function __construct($config)
		{
			$this->ssid = $config['ssid'];
			$this->key = $config['key'];
			$this->encryption = $config['encryption'];

			return $this;
		}
	}