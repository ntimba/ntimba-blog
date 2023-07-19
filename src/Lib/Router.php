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

class Router {

    private $actions = [
        'post' => ['controller' => PostController::class, 'method' => 'handlePost'],
        'page' => ['controller' => PageController::class, 'method' => 'handlePage'],
        'add_post' => ['controller' => PostController::class, 'method' => 'handleAddPost'],
        'edit_post' => ['controller' => PostController::class, 'method' => 'handleEditPost'],
        'delete_post' => ['controller' => PostController::class, 'method' => 'handleDeletePost'],
        'add_page' => ['controller' => PageController::class, 'method' => 'handleAddPage'],
        'edit_page' => ['controller' => PageController::class, 'method' => 'handleEditPage'],
        'delete_page' => ['controller' => PageController::class, 'method' => 'handleDeletePage'],
        'home' => ['controller' => PageController::class, 'method' => 'handleHomePage'],
        'portfolio' => 'handlePortfolioPage',
        'blog' => 'handleBlogPage',
        'contact' => ['controller' => PageController::class, 'method' => 'handleContactPage'],
        'register' => ['controller' => UserController::class, 'method' => 'handleRegisterPage'],
        'login' => ['controller' => UserController::class, 'method' => 'handleLoginPage'],
        'dashboard' => ['controller' => PageController::class, 'method' => 'handleDashboardPage'],
        'posts' => 'handlePostsPage',
        'categories' => 'handleCategoriesPage',
        'comments' => 'handleCommentsPage',
        'users' => 'handleUsersPage',
        'settings' => ['controller' => PageController::class, 'method' => 'handleSettingsPage'],
        'logout' => 'handleLogoutPage'
    ];

    private $pageController;

    public function __construct()
    {
        $this->pageController = new PageController();
    }
    
    public function routeRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'default';
    
        if (array_key_exists($action, $this->actions)) {
            $controllerName = $this->actions[$action]['controller'];
            $methodName = $this->actions[$action]['method'];
            $controller = new $controllerName();
            $controller->$methodName();
            // $this->callPageControllerMethod($this->actions[$action]);
        } else {
            $pageController = new PageController();
            $pageController->handleDefault();
            // $this->callPageControllerMethod('handleDefault');
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

