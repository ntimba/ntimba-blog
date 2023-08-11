<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Lib;

use Portfolio\Ntimbablog\Controllers\PostController;
use Portfolio\Ntimbablog\Controllers\PostCategoryController;
use Portfolio\Ntimbablog\Controllers\CommentController;
use Portfolio\Ntimbablog\Controllers\PageController;
use Portfolio\Ntimbablog\Controllers\UserController;
use Portfolio\Ntimbablog\Controllers\SocialnetworkController;
use Portfolio\Ntimbablog\Controllers\ProjectController;
use Portfolio\Ntimbablog\Controllers\ProjectCategoryController;
use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Service\MailService;
use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;

class Router {

    private $actions = [
        'setup_admin' => ['controller' => UserController::class, 'method' => 'handleSetupAdminPage'],
        'post' => ['controller' => PostController::class, 'method' => 'handlePost'],
        'page' => ['controller' => PageController::class, 'method' => 'handlePage'],
        'add_post' => ['controller' => PostController::class, 'method' => 'handleAddPost'],
        'edit_post' => ['controller' => PostController::class, 'method' => 'handleEditPost'],
        'delete_post' => ['controller' => PostController::class, 'method' => 'handleDeletePost'],
        'add_page' => ['controller' => PageController::class, 'method' => 'handleAddPage'],
        'edit_page' => ['controller' => PageController::class, 'method' => 'handleEditPage'],
        'delete_page' => ['controller' => PageController::class, 'method' => 'handleDeletePage'],
        'home' => ['controller' => PageController::class, 'method' => 'handleHomePage'],
        'projects' => ['controller' => ProjectController::class, 'method' => 'handleProjectPage'],
        'blog' => ['controller' => PostController::class, 'method' => 'handleBlogPage'],
        'contact' => ['controller' => PageController::class, 'method' => 'handleContactPage'],
        'register' => ['controller' => UserController::class, 'method' => 'handleRegisterPage'],
        'login' => ['controller' => UserController::class, 'method' => 'handleLoginPage'],
        'dashboard' => ['controller' => PageController::class, 'method' => 'handleDashboardPage'],
        'posts' => ['controller' => PostController::class, 'method' => 'handlePostsPage'],
        'categories' => ['controller' => PageController::class, 'method' => 'handleCategoriesPage'],
        'comments' => ['controller' => PageController::class, 'method' => 'handleCommentsPage'],
        'users' => ['controller' => PageController::class, 'method' => 'handleUsersPage'],
        'settings' => ['controller' => PageController::class, 'method' => 'handleSettingsPage'],
        'logout' => 'handleLogoutPage'
    ];

    private $errorHandler;
    private $pageController;
    private $mailService;
    private $translationService;
    private $validationService;

    public function __construct(ErrorHandler $errorHandler = null, MailService $mailService = null, TranslationService $translationService = null, ValidationService $validationService = null)
    {
        $this->errorHandler = $errorHandler ?? new ErrorHandler();
        $this->mailService = $mailService ?? new MailService();

        $language = $_GET['lang'] ?? 'fr';  // 'fr' est la valeur par défaut

        $this->translationService = $translationService ?? new TranslationService($language);
        $this->pageController = new PageController($this->errorHandler);

        $this->validationService = new ValidationService($this->errorHandler, $this->translationService);
    }
    
    public function routeRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'default';
    
        if (array_key_exists($action, $this->actions)) {
            $controllerName = $this->actions[$action]['controller'];
            $methodName = $this->actions[$action]['method'];
            $controller = new $controllerName($this->errorHandler, $this->mailService, $this->translationService, $this->validationService);
            $controller->$methodName();
        } else {
            $this->pageController->handleDefault();
        }
    }

    private function callPageControllerMethod( string $methodName) : void
    {
        if( method_exists($this->pageController, $methodName) ) {
            $this->pageController->$methodName();
        }else{
            throw new \Exception("Method {$methodName} does not exist on PageController");
        }
    }
   
}

