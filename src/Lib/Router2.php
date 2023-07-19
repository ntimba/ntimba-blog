<?php

declare(strict_types=1);

// namespace Portfolio\Ntimbablog\Lib;

use Portfolio\Ntimbablog\Controllers\PageController;

class Router {
    private $actions = [
        'post' => 'handlePost',
        'page' => 'handlePage',
        'add_post' => 'handleAddPost',
        'edit_post' => 'handleEditPost',
        'delete_post' => 'handleDeletePost',
        'add_page' => 'handleAddPage',
        'edit_page' => 'handleEditPage',
        'delete_page' => 'handleDeletePage',
        'home' => 'handleHomePage',
        'portfolio' => 'handlePortfolioPage',
        'blog' => 'handleHomePage',
        'contact' => 'handleContactPage',
        'register' => 'handleRegisterPage',
        'login' => 'handleLoginPage',
        'dashboard' => 'handleDashboardPage',
        'posts' => 'handlePostsPage',
        'categories' => 'handleCategoriesPage',
        'comments' => 'handleCommentsPage',
        'users' => 'handleUsersPage',
        'settings' => 'handleSettingsPage',
        'logout' => 'handleLogoutPage'
    ];

    // private $pageController;

    // public function __construct()
    // {
    //     $this->pageController = new PageController();
    // }
    
    public function routeRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'default';
        $page = isset($_GET['page']) ? $_GET['page'] : 'default';       

        // debug($this->actions);

        if (array_key_exists($action, $this->actions)) {
            $this->{$this->actions[$action]}();
        } else {
            $this->handleDefault();
        }
    }

    // Actions
    private function handlePost() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        $pageController = new PageController();
        $pageController->getPost($id);
    }

    private function handlePage() {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
    }

    private function handleAddPost() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
    }

    private function handleEditPost() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
    }

    private function handleDeletePost() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
    }

    private function handleAddPage() {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
    }

    private function handleEditPage() {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
    }

    private function handleDeletePage() {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
    }

    private function handleHomePage() {
        $pageController = new PageController();
        $pageController->getHome();
    }

    private function handlePortfolioPage() {
        $pageController = new PageController();
        $pageController->getPortfolio();
    }

    private function handleBlogPage() {
        $pageController = new PageController();
        $pageController->getBlog();
    }

    private function handleContactPage() {
        $pageController = new PageController();
        $pageController->getContact();
    }

    private function handleLoginPage() {
        $pageController = new PageController();
        $pageController->getLogin();
    }

    private function handleRegisterPage() {
        $pageController = new PageController();
        $pageController->getRegister();
    }

    private function handleDashboardPage() {
        $pageController = new PageController();
        $pageController->getDashboard();
    }

    private function handlePostsPage() {
        $pageController = new PageController();
        $pageController->getPosts();
    }

    private function handleCategoriesPage() {
        $pageController = new PageController();
        $pageController->getCategories();
    }

    private function handleCommentsPage() {
        $pageController = new PageController();
        $pageController->getComments();
    }

    private function handleUsersPage() {
        $pageController = new PageController();
        $pageController->getUsers();
    }

    private function handleSettingsPage() {
        $pageController = new PageController();
        $pageController->getSettings();
    }

    private function handleLogoutPage() {
        $pageController = new PageController();
        $pageController->getLogout();
    }

    private function handleDefault() {
        $pageController = new PageController();
        $pageController->getHome();
    }
}

