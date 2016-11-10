<?php
	class site extends config
	{
		public $useSSL;
		
		public function __construct()
		{
			$this->useSSL	= config::$siteUseSSL;
			return $this;
		}
	}