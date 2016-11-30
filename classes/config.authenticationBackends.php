<?php

// Name doesn't have to be "authConfig...."!
class authConfig1 extends auth implements IAuthConfig
{
    public function authBackend($uid, $pw)
    {
        // Do some authentication stuff
        return false;
    }
}

class authConfig2 extends auth implements IAuthConfig
{
    public function authBackend($uid, $pw)
    {
        // Do some authentication stuff
        return false;
    }
}