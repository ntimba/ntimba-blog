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
use Portfolio\Ntimbablog\Controllers\CategoryController;
use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Service\MailService;

use Portfolio\Ntimbablog\Helpers\StringUtil;

use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;
use Portfolio\Ntimbablog\Service\EnvironmentService;

use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Http\SessionManager;
use Portfolio\Ntimbablog\Http\HttpResponse;

use Portfolio\Ntimbablog\Lib\Database;



class Router {

    private $actions = [
        'setup_admin' => ['controller' => UserController::class, 'method' => 'handleSetupAdminPage'],
        'post' => ['controller' => PostController::class, 'method' => 'handlePost'],
        'page' => ['controller' => PageController::class, 'method' => 'handlePage'],
        'add_post' => ['controller' => PostController::class, 'method' => 'handleAddPost'],
        'publish_post' => ['controller' => PostController::class, 'method' => 'publishPost'],
        'draft_post' => ['controller' => PostController::class, 'method' => 'draftPost'],
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
        'confirmation' => ['controller' => UserController::class, 'method' => 'handleAccountConfirmation'],
        'login' => ['controller' => UserController::class, 'method' => 'handleLoginPage'],
        'dashboard' => ['controller' => PageController::class, 'method' => 'handleDashboardPage'],
        'posts' => ['controller' => PostController::class, 'method' => 'handlePostsPage'],
        'categories' => ['controller' => CategoryController::class, 'method' => 'handleCategoriesPage'],
        'add_category' => ['controller' => CategoryController::class, 'method' => 'handleAddCategory'],
        'modify_category' => ['controller' => CategoryController::class, 'method' => 'modifyCategory'],
        'update_category' => ['controller' => CategoryController::class, 'method' => 'updateCategory'],
        'comments' => ['controller' => PageController::class, 'method' => 'handleCommentsPage'],
        'users' => ['controller' => PageController::class, 'method' => 'handleUsersPage'],
        'settings' => ['controller' => PageController::class, 'method' => 'handleSettingsPage'],
        'logout' => ['controller' => UserController::class, 'method' => 'handleLogoutPage']
    ];

    private $stringUtil;
    private $errorHandler;
    private $pageController;
    private $categoryController;
    private $mailService;
    private $translationService;
    private $validationService;
    private $request;
    private $sessionManager;
    private $environmentService;
    private $db;
    private $response;
    private $userController;



    public function __construct(
        StringUtil $stringUtil = null,
        ErrorHandler $errorHandler = null, 
        MailService $mailService = null, 
        TranslationService $translationService = null, 
        ValidationService $validationService = null,
        Request $request = null, 
        Database $db = null,
        SessionManager $sessionManager = null,
        EnvironmentService $environmentService = null,
        HttpResponse $response = null
        )
    {
        $this->sessionManager = $sessionManager ?? new SessionManager();
        $this->request = $request ?? new Request($_POST, $_GET, $_FILES, $_SERVER);
        $this->errorHandler = $errorHandler ?? new ErrorHandler($this->sessionManager);
        $this->stringUtil = $stringUtil ?? new StringUtil();
        $this->mailService = $mailService ?? new MailService($this->request);
        $language = $this->request->get('lang', 'fr'); 
        $this->translationService = $translationService ?? new TranslationService($language);
        $this->validationService = new ValidationService($this->errorHandler, $this->translationService);
        $this->environmentService = $environmentService ?? new EnvironmentService();
        $this->db = $db ?? new Database($this->environmentService);
        $this->response = $response ?? new HttpResponse();
        $this->userController = $userController ?? new UserController(
            $this->errorHandler,
            $this->mailService,
            $this->translationService,
            $this->validationService,
            $this->request,
            $this->db,
            $this->response,
            $this->sessionManager
        );
        $this->pageController = new PageController(
            $this->errorHandler,
            $this->mailService,
            $this->translationService,
            $this->validationService,
            $this->request,
            $this->db,
            $this->response,
            $this->sessionManager,
            $this->userController
        );
        $this->categoryController = new CategoryController(
            $this->stringUtil,
            $this->errorHandler,
            $this->mailService,
            $this->translationService,
            $this->validationService,
            $this->request,
            $this->db,
            $this->response,
            $this->sessionManager,
            $this->userController
        );
    }

    public function routeRequest() : void {
        $action = $this->request->get('action', 'default');
    
        if (array_key_exists($action, $this->actions)) {
            $controllerName = $this->actions[$action]['controller'];
            $methodName = $this->actions[$action]['method'];

            if( $controllerName === PageController::class ) {
                $controller = $this->pageController;
            }elseif($controllerName === UserController::class) {
                $controller = $this->userController;
            }elseif($controllerName === ProjectController::class){
                $controller = new $controllerName($this->sessionManager);
            }elseif($controllerName === PostController::class){
                $controller = new $controllerName(
                    $this->errorHandler,
                    $this->translationService,
                    $this->validationService,
                    $this->request,
                    $this->db,
                    $this->response,
                    $this->sessionManager,
                    $this->userController,
                    $this->stringUtil
                );
            }elseif( $controllerName === CategoryController::class ){
                $controller = new $controllerName(
                    $this->stringUtil,
                    $this->errorHandler, 
                    $this->mailService, 
                    $this->translationService, 
                    $this->validationService, 
                    $this->request, 
                    $this->db, 
                    $this->response, 
                    $this->sessionManager, 
                    $this->userController
                );
            }
            else {
                $controller = new $controllerName(
                    $this->errorHandler, 
                    $this->mailService, 
                    $this->translationService, 
                    $this->validationService, 
                    $this->request, $this->db, 
                    $this->response, 
                    $this->sessionManager, 
                    $this->environmentService
                );
            }
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

