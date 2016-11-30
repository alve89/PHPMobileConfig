<?php

	if(!class_exists('auth'))
	{
		class auth
		{
			public static function authUser($uid, $pw)
			{
				# 
				# Add any authentication algorithms here
				#
				$auth = true;
				
				// $auth needs to be boolean
				return $auth;
			}
		}
	}