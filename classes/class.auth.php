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
	class auth
	{
		public function authUser($uid, $pw)
		{
			$classes = get_declared_classes();

			foreach($classes as $class)
			{
				$reflectionClass = new ReflectionClass($class);
				if($reflectionClass->isSubClassOf(__CLASS__))
				{
					$children[] = $class;
				}
			}

			foreach($children as $class)
			{
				$auth = new $class;
				if($return = $auth->authBackend($uid, $pw))
				{
					break;
				}
			}
			return $return;
		}
	}
