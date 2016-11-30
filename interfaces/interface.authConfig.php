<?php
interface IAuthConfig
{
    // Force child classes to implement this method
    public function authUser($uid, $pw);
}
