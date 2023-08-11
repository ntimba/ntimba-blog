<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Models\User;
use Portfolio\Ntimbablog\Models\UserManager;
use Portfolio\Ntimbablog\Helpers\ErrorHandler;

class ProjectController
{
    public function handleProjectPage() : void
    {
        require("./views/frontend/portfolio.php");
    }
}