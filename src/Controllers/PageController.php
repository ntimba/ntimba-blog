<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;
use Portfolio\Ntimbablog\Helpers\ErrorHandler;


class PageController
{

    protected $errorHandler;
    
    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function handleHomePage() : void
    {
        $errorHandler = $this->errorHandler;
        require("./views/frontend/home.php");
    }

    public function handlePortfolioPage() : void
    {
        require("./views/frontend/portfolio.php");
    }

    public function handleContactPage() : void
    {
        require("./views/frontend/contact.php");
    }

    public function handleDashboardPage() : void
    {
        require("./views/backend/dashboard.php");
    }

    public function handleCategoriesPage() : void
    {
        require("./views/backend/categories.php");
    }

    public function handleCommentsPage() : void
    {
        require("./views/backend/comments.php");
    }
        
    public function handleUsersPage() : void
    {
        
        require("./views/backend/users.php");
    }
    
    public function handleSettingsPage() : void
    {
        require("./views/backend/settings.php");
    }

    public function handleDefault() : void
    {
        $this->handleHomePage();
    }

    public function handleAddPage() : void
    {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
    }

    public function handleEditPage() : void
    {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
    }

    public function handleDeletePage() : void
    {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
    }
    
}

