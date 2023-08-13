<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Http;

use Portfolio\Ntimbablog\Http\Request;

class SessionManager
{

    private Request $request;
    
    public function __construct() {
        // $this->request = $request;

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
