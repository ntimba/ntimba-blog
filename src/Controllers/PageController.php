<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

class PageController extends BaseController
{    
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

        $this->authenticator->ensureAdmin();

        $errorHandler = $this->errorHandler;
        require("./views/backend/dashboard.php");
    }

    public function handleCommentsPage() : void
    {
        $this->authenticator->ensureAdmin();
        require("./views/backend/comments.php");
    }
        
    public function handleSettingsPage() : void
    {
        $this->authenticator->ensureAuditedUserAuthentication();

        $errorHandler = $this->errorHandler;
        require("./views/backend/settings.php");
    }


    public function handlePages() : void
    {
        $this->authenticator->ensureAdmin();

        $errorHandler = $this->errorHandler;
        require("./views/backend/pages.php");
    }
    

    public function handleDefault() : void
    {
        $this->handleHomePage();
    }

    public function handleAddPage() : void
    {
        $this->authenticator->ensureAdmin();
        $pageData = $_POST;
        $page = isset($pageData['page']) ? $pageData['page'] : null;
    }

    public function handleEditPage() : void
    {
        $this->authenticator->ensureAdmin();
        $pageData = $_POST;
        $page = isset($pageData['page']) ? $pageData['page'] : null;
    }

    public function handleDeletePage() : void
    {
        $this->authenticator->ensureAdmin();
        $pageData = $_POST;
        $page = isset($pageData['page']) ? $pageData['page'] : null;
    }
    
}



