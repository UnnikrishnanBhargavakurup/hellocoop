<?php

namespace HelloCoop\Handler;

use HelloCoop\Type\AuthUpdates;
use HelloCoop\Type\Auth as AuthType;

class Auth{
    //TODO: we can use a builder patter here
    public function __construct()
    {

    }

    public function handleAuth(): bool 
    {
       return false; 
    }
    public function updateAuth(AuthUpdates $authUpdates): ?AuthType 
    {
       return null; 
    }
    public function clearAuth(): bool 
    {
       return false; 
    }
}
