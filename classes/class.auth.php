<?php
	/*
	 * Authentication of the user
	 * 
	 * @param 	$uid	string	Username, given by form
	 * @param	$pw		string	Password, given by form
	 * @param	$return	boolean	Contains the result of authentication
	 * 
	 *
	 */

	// Parent class that is called
	class auth implements IAuthConfig
	{
		private $authBackends;

		public function addBackend(IAuthConfig $backend)
		{
			$this->authBackends[] = $backend;
		}
		
		public function authUser($uid, $pw)
		{
/*			
			$classes = get_declared_classes();

			foreach($classes as $class)
			{
				$reflectionClass = new ReflectionClass($class);
				if($reflectionClass->isSubClassOf(__CLASS__))
				{
					$children[] = $class;
				}
			}
*/
			foreach($this->authBackends as $backend)
			{
				//$auth = new $class;
				if($backend->authUser($uid, $pw))
				{
					return true;
				}
			}
			
			return false;
		}
	}
