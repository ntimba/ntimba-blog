<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

class UserController
{
    
    public function handleLoginPage() {
        require("./views/frontend/login.php");
    }

    public function handleRegisterPage() {
        require("./views/frontend/register.php");
    }

    public function handleLogoutPage() {
        header('Location: index.php');
    }
}