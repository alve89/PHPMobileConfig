<?php

// Name doesn't have to be "authConfig...."!
class authConfig1 extends auth implements IAuthConfig
{
    public function authUser($uid, $pw)
    {
        // Do some authentication stuff
        return false;
    }
}

$auth->addBackend(new authConfig1);

class authConfig2 extends auth implements IAuthConfig
{
    public function authUser($uid, $pw)
    {
        // Do some authentication stuff
        return false;
    }
}

$auth->addBackend(new authConfig2);