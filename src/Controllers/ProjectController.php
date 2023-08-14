<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Models\User;
use Portfolio\Ntimbablog\Models\UserManager;
use Portfolio\Ntimbablog\Helpers\ErrorHandler;

use Portfolio\Ntimbablog\Http\SessionManager;

class ProjectController
{
    private $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }
    
    public function handleProjectPage() : void
    {
        require("./views/frontend/portfolio.php");
    }
}





