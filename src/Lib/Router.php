<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Lib;

use Portfolio\Ntimbablog\Controllers\PostController;
use Portfolio\Ntimbablog\Controllers\PostCategoryController;
use Portfolio\Ntimbablog\Controllers\CommentController;
use Portfolio\Ntimbablog\Controllers\PageController;
use Portfolio\Ntimbablog\Controllers\UserController;
use Portfolio\Ntimbablog\Controllers\SocialnetworkController;
use Portfolio\Ntimbablog\Controllers\CategoryController;
use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Helpers\LayoutHelper;
use Portfolio\Ntimbablog\Service\MailService;

use Portfolio\Ntimbablog\Helpers\StringUtil;

use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;
use Portfolio\Ntimbablog\Service\EnvironmentService;
use Portfolio\Ntimbablog\Service\Authenticator;

use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Http\SessionManager;
use Portfolio\Ntimbablog\Http\HttpResponse;

use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Models\PageManager;
use Portfolio\Ntimbablog\Models\SocialnetworkManager;
use Portfolio\Ntimbablog\Models\UserManager;

class Router {
    private $actions = [
        'setup_admin' => ['controller' => UserController::class, 'method' => 'handleSetupAdminPage'],
        'post' => ['controller' => PostController::class, 'method' => 'handlePost'],
        'page' => ['controller' => PageController::class, 'method' => 'handlePage'],
        'post_modify' => ['controller' => PostController::class, 'method' => 'postModify'],
        'page_modify' => ['controller' => PageController::class, 'method' => 'pageModify'],
        'add_post' => ['controller' => PostController::class, 'method' => 'create'],
        'publish_post' => ['controller' => PostController::class, 'method' => 'publishPost'],
        'draft_post' => ['controller' => PostController::class, 'method' => 'draftPost'],
        'edit_post' => ['controller' => PostController::class, 'method' => 'handleEditPost'],
        'delete_post' => ['controller' => PostController::class, 'method' => 'handleDeletePost'],
        'update_post' => ['controller' => PostController::class, 'method' => 'update'],
        'edit_page' => ['controller' => PageController::class, 'method' => 'handleEditPage'],
        'delete_page' => ['controller' => PageController::class, 'method' => 'handleDeletePage'],
        'home' => ['controller' => PageController::class, 'method' => 'handleHomePage'],
        'blog' => ['controller' => PostController::class, 'method' => 'handleBlogPage'],
        'contact' => ['controller' => PageController::class, 'method' => 'handleContactPage'],
        'register' => ['controller' => UserController::class, 'method' => 'handleRegisterPage'],
        'confirmation' => ['controller' => UserController::class, 'method' => 'handleAccountConfirmation'],
        'login' => ['controller' => UserController::class, 'method' => 'handleLoginPage'],
        'dashboard' => ['controller' => PageController::class, 'method' => 'handleDashboardPage'],
        'posts' => ['controller' => PostController::class, 'method' => 'handlePosts'],
        'pages' => ['controller' => PageController::class, 'method' => 'handlePages'],
        'add_page' => ['controller' => PageController::class, 'method' => 'create'],
        'update_page' => ['controller' => PageController::class, 'method' => 'update'],
        'categories' => ['controller' => CategoryController::class, 'method' => 'handleAdminCategories'],
        'create_category' => ['controller' => CategoryController::class, 'method' => 'create'],
        'read_category' => ['controller' => CategoryController::class, 'method' => 'read'],
        'update_category' => ['controller' => CategoryController::class, 'method' => 'update'],
        'modify_category' => ['controller' => CategoryController::class, 'method' => 'modifyCategory'],
        'add_comment' => ['controller' => CommentController::class, 'method' => 'addComment'],
        'comments' => ['controller' => CommentController::class, 'method' => 'handleComments'],
        'modify_comment' => ['controller' => CommentController::class, 'method' => 'modifyComment'],
        'users' => ['controller' => UserController::class, 'method' => 'handleUsersPage'],
        'user_modify' => ['controller' => UserController::class, 'method' => 'userModify'],
        'update_user' => ['controller' => UserController::class, 'method' => 'updateUser'],
        'update_password' => ['controller' => UserController::class, 'method' => 'updatePassword'],
        'social_network' => ['controller' => UserController::class, 'method' => 'handleSocialNetwork'],
        'logout' => ['controller' => UserController::class, 'method' => 'handleLogoutPage'],
        'delete_social_media' => ['controller' => UserController::class, 'method' => 'deleteNetwork'],
        'update_social_media' => ['controller' => UserController::class, 'method' => 'updateNetwork']
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
    private $userManager;
    private $authenticator = null;
    private $layoutHelper;
    private $pageManager;
    private $networkManager;



    public function __construct( StringUtil $stringUtil = null, ErrorHandler $errorHandler = null, 
        MailService $mailService = null, 
        TranslationService $translationService = null, 
        Request $request = null, 
        Database $db = null,
        SessionManager $sessionManager = null,
        EnvironmentService $environmentService = null,
        HttpResponse $response = null,
        Authenticator $authenticator = null,
        LayoutHelper $layoutHelper = null, 
        PageManager $pageManager = null,
        SocialnetworkManager $networkManager = null,
        )
    {
        $this->sessionManager = $sessionManager ?? new SessionManager();
        $this->request = $request ?? new Request($_POST, $_GET, $_FILES, $_SERVER);
        $language = $this->request->get('lang', 'fr'); 
        $this->translationService = $translationService ?? new TranslationService($language);
        $this->errorHandler = $errorHandler ?? new ErrorHandler($this->sessionManager, $this->translationService);
        $this->stringUtil = $stringUtil ?? new StringUtil();
        $this->mailService = $mailService ?? new MailService($this->request);
        $this->validationService = new ValidationService($this->errorHandler, $this->translationService);
        $this->environmentService = $environmentService ?? new EnvironmentService();
        $this->db = $db ?? new Database($this->environmentService);
        $this->response = $response ?? new HttpResponse();
        $this->userManager = $userManager ?? new UserManager($this->db);
        $this->authenticator = $authenticator ?? new Authenticator(
            $this->db,
            $this->sessionManager,
            $this->userManager,
            $this->translationService,
            $this->errorHandler,
            $this->response
        );
        $this->networkManager = $networkManager ?? new SocialnetworkManager($this->db);
        $this->pageManager = $pageManager ?? new PageManager($this->db, $this->stringUtil);
        $this->layoutHelper = $layoutHelper ?? new LayoutHelper($this->pageManager, $this->networkManager, $this->request);
        $this->userController = $userController ?? new UserController(
            $this->errorHandler,
            $this->mailService,
            $this->translationService,
            $this->validationService,
            $this->request,
            $this->db,
            $this->response,
            $this->sessionManager,
            $this->stringUtil,
            $this->authenticator,
            $this->layoutHelper
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
            $this->stringUtil, 
            $this->authenticator,
            $this->layoutHelper
        );
        $this->categoryController = new CategoryController(
            $this->errorHandler,
            $this->mailService,
            $this->translationService,
            $this->validationService,
            $this->request,
            $this->db,
            $this->response,
            $this->sessionManager,
            $this->stringUtil,
            $this->authenticator,
            $this->layoutHelper
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
            }elseif($controllerName === PostController::class){
                $controller = new $controllerName(
                    $this->errorHandler,
                    $this->mailService,
                    $this->translationService,
                    $this->validationService,
                    $this->request,
                    $this->db,
                    $this->response,
                    $this->sessionManager,
                    $this->stringUtil,
                    $this->authenticator,
                    $this->layoutHelper
                );
            }elseif( $controllerName === CategoryController::class ){
                $controller = new $controllerName(
                    $this->errorHandler,
                    $this->mailService,
                    $this->translationService,
                    $this->validationService,
                    $this->request,
                    $this->db,
                    $this->response,
                    $this->sessionManager,
                    $this->stringUtil,
                    $this->authenticator,
                    $this->layoutHelper
                );
            }elseif( $controllerName === CommentController::class ){
                $controller = new $controllerName(
                    $this->errorHandler,
                    $this->mailService,
                    $this->translationService,
                    $this->validationService,
                    $this->request,
                    $this->db,
                    $this->response,
                    $this->sessionManager,
                    $this->stringUtil,
                    $this->authenticator,
                    $this->layoutHelper
                );
            }
            else {                
                $controller = new $controllerName(
                    $this->errorHandler,
                    $this->mailService,
                    $this->translationService,
                    $this->validationService,
                    $this->request,
                    $this->db,
                    $this->response,
                    $this->sessionManager,
                    $this->stringUtil,
                    $this->authenticator,
                    $this->layoutHelper
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

