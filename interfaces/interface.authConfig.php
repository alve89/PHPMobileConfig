<?php
interface IAuthConfig
{
    // Force child classes to implement this method
    public function authBackend($uid, $pw);
}
