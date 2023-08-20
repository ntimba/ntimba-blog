<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Models\CategoryManager;
use Portfolio\Ntimbablog\Models\Category;

use Portfolio\Ntimbablog\Controllers\UserController;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;

use Portfolio\Ntimbablog\Service\EnvironmentService;
use Portfolio\Ntimbablog\Service\MailService;
use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;

use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Http\SessionManager;




class PageController
{
    protected ErrorHandler $errorHandler;

    private MailService $mailService;
    private TranslationService $translationService;
    private ValidationService $validationService;
    private Request $request;
    private Database $db;
    private HttpResponse $response;
    private SessionManager $sessionManager;
    private UserController $userController;
    
    
    public function __construct(
        ErrorHandler $errorHandler, 
        MailService $mailService, 
        TranslationService $translationService, 
        ValidationService $validationService, 
        Request $request, 
        Database $db, 
        HttpResponse $response, 
        SessionManager $sessionManager,
        UserController $userController
    )
    {
        $this->errorHandler = $errorHandler;
        $this->mailService = $mailService;
        $this->translationService = $translationService;
        $this->validationService = $validationService;
        $this->request = $request;
        $this->db = $db;
        $this->response = $response;
        $this->sessionManager = $sessionManager;
        $this->userController = $userController;
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

        $this->userController->handleAdminPage();
        require("./views/backend/dashboard.php");
    }

    public function handleCommentsPage() : void
    {
        $this->userController->handleAdminPage();
        require("./views/backend/comments.php");
    }
        
    public function handleUsersPage() : void
    {
        $this->userController->handleAdminPage();
        require("./views/backend/users.php");
    }
    
    public function handleSettingsPage() : void
    {
        $this->userController->handleSomeAuditedProtectedPage();
        require("./views/backend/settings.php");
    }

    public function handleDefault() : void
    {
        $this->handleHomePage();
    }

    public function handleAddPage() : void
    {
        $this->userController->handleAdminPage();
        $pageData = $_POST;
        $page = isset($pageData['page']) ? $pageData['page'] : null;
    }

    public function handleEditPage() : void
    {
        $this->userController->handleAdminPage();
        $pageData = $_POST;
        $page = isset($pageData['page']) ? $pageData['page'] : null;
    }

    public function handleDeletePage() : void
    {
        $this->userController->handleAdminPage();
        $pageData = $_POST;
        $page = isset($pageData['page']) ? $pageData['page'] : null;
    }
    
}



