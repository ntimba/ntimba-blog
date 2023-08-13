<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Http;

class SessionManager
{
    public function __construct() {
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    public function set(string $key, $value) : void 
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, $default = null) 
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key) : bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key) : void
    {
        unset($_SESSION[$key]);
    }

    public function destroy() : void
    {
        session_destroy();
    }
    
    
}
