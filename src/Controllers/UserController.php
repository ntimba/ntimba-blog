<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Models\User;
use Portfolio\Ntimbablog\Models\UserManager;
use Portfolio\Ntimbablog\Models\Settings;
use Portfolio\Ntimbablog\Models\SettingsManager;
use Portfolio\Ntimbablog\Models\FilesManager;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Service\MailService;

use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;
use Portfolio\Ntimbablog\Service\EnvironmentService;

use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Http\SessionManager;

use Portfolio\Ntimbablog\Lib\Database;

use \Exception;



class UserController
{
    private $errorHandler;
    private $mailService;
    private $translationService;
    private $validationService;
    private $request; 
    private $db;
    private $response;
    private $sessionManager;


    public function __construct(
        ErrorHandler $errorHandler, 
        MailService $mailService, 
        TranslationService $translationService, 
        ValidationService $validationService, 
        Request $request,
        Database $db,
        HttpResponse $response,
        SessionManager $sessionManager

        ){
        $this->errorHandler = $errorHandler;
        $this->mailService = $mailService;
        $this->translationService = $translationService;
        $this->validationService = $validationService;
        $this->request = $request;
        $this->db = $db;
        $this->response = $response;
        $this->sessionManager = $sessionManager;
    }

    private function isAuthenticated(): bool {
        return (bool) $this->sessionManager->get('user_id');
    }

    private function isAuditedAccount(): bool {
        $userManager = new UserManager($this->db);
        $user = $userManager->getUser( $this->sessionManager->get('user_id') );
        return (bool) $user->getAuditedAccount();  // Supposant que vous avez une méthode getAuditedAccount() dans votre modèle User
    }
    
    private function isAdmin() : bool
    {
        return $this->sessionManager->get('user_role') === 'admin';
    }

    public function handleAdminPage() : void
    {
        if(!$this->isAdmin() || !$this->isAuditedAccount())
        {
            $errorMessage = $this->translationService->get('ACCESS_DENIED','login');
            $this->errorHandler->addFlashMessage($errorMessage, "danger");
            $this->response->redirect('index.php?action=login');

            return;
        }
    }

    public function handleSomeAuditedProtectedPage() : void
    {
        if(!$this->isAuthenticated() || !$this->isAuditedAccount())
        {
            $errorMessage = $this->translationService->get('PROTECTED_PAGE','login');
            $this->errorHandler->addFlashMessage($errorMessage, "danger");

            $this->response->redirect('index.php?action=login');
            return;   
        }
    }
        
    public function handleLoginPage() : void 
    {
        if($this->isAuthenticated()){
            $this->response->redirect('index.php?action=home');
            return;
        }

        $data = $this->request->getAllPost();

        if( $this->validationService->validateLoginData($data) )
        {
            // recupérér les données de connexion
            $loginData = [
                'email' => $this->request->post('email'),
                'password' => $this->request->post('password'),
                'remember_me' => $this->request->post('remember_me'),
            ];
            
            $userManager = new UserManager($this->db);
            $userId = $userManager->getUserId($loginData['email']);

            // vérifier si l'utilisateur existe
            if( !$userId )
            {
                $errorMessage = $this->translationService->get('USER_NOT_EXIST','login');
                $this->errorHandler->addError($errorMessage, "warning");
                return;
            }

            $userData = $userManager->getUser($userId);
            
            if (!$userManager->verifyPassword($loginData['password'], $userData->getPassword())) 
            {
                $errorMessage = $this->translationService->get('WRONG_PASSWORD','login');
                $this->errorHandler->addFlashMessage($errorMessage, "danger");
                $this->response->redirect('index.php?action=login');
                return;
            }

            $auditedAccount = $userData->getAuditedAccount();
            if(!$auditedAccount) {
                $errorMessage = $this->translationService->get('NOT_AUDITED_ACCOUNT','login');
                $this->errorHandler->addFlashMessage($errorMessage, "warning");

                $this->sessionManager->set('limited_access', true);
            }else{
                $this->sessionManager->set('limited_access', false);
            }
            
            $this->sessionManager->set('user_id', $userId);
            $this->sessionManager->set('user_email', $loginData['email']);
            $this->sessionManager->set('user_role', $userData->getRole());
            $this->sessionManager->set('full_name', $userData->getFullName());
            $this->sessionManager->set('profile_picture', $userData->getProfilePicture());

            
            if( isset($loginData['remember_me']) && $loginData['remember_me'] == 1 )
            {
                // Création d'un cookie
            }
            
            $this->response->redirect('index.php?action=home');
        }
        

        $errorHandler = $this->errorHandler;
        require("./views/frontend/login.php");
    }

    private function registerUser(array $data) : ?array
    {
        // Enregistrer l'administrateur
        $user = new User();
        $userManager = new UserManager($this->db);

        $token = bin2hex(random_bytes(32));

        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setBiography($data['biography']);

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);

        $user->setToken($token);
        $user->setRole($data['is_admin'] ? 'admin' : 'subscriber');
        $user->setProfilePicture('../../assets/img/avatar.png');

        // Check if email already exists
        if (!$userManager->getUserId($data['email'])) {
            $userManager->insertUser($user);

            // Envoyer un mail confirmation
            $mailService = new MailService($this->request);
            $environmentService = new EnvironmentService();

            $domainName = $this->request->getDomainName();
            $fullName = $data['firstname'] . ' ' . $data['lastname'];

            // recupérer l'identifiant de l'utilisateur
            $userId = $userManager->getUserId($data['email']);

            $protocol = $this->request->getProtocol();
            $confirmationLink = $protocol . $domainName . "?action=confirmation&token=" . $token . "&id=" . $userId;

            $wasSent = $mailService->prepareConfirmationEmail($fullName, $data['email'], $confirmationLink);

            if($wasSent){
                $errorMessage = $this->translationService->get('ACCOUNT_CONFIRMATION_SENT','register');
                $this->errorHandler->addFlashMessage($errorMessage, "primary");
                $this->response->redirect('index.php?action=login');
            }else{
                $errorMessage = $this->translationService->get('ACCOUNT_CONFIRMATION_NOT_SENT','register');
                $this->errorHandler->addError($errorMessage, "danger");
            }

        }else{
            $errorMessage = $this->translationService->get('USER_EXIST','register');
            $this->errorHandler->addError($errorMessage, "danger");
        }
        
        // return userdata
        return $data;
    }    

    public function handleRegisterPage() : void
    {
        if($this->isAuthenticated()){
            $this->response->redirect('index.php?action=home');
            return;
        }
        
        $data = $this->request->getAllPost();

        if(
            $this->validationService->validateRegistrationData($data)
        )  
        {
            $inputData = [
                'firstname' => $this->request->post('firstname'),
                'lastname' => $this->request->post('lastname'),
                'email' => $this->request->post('email'),
                'username' => $this->request->post('username', ''),
                'password' => $this->request->post('password'),
                'repeat_password' => $this->request->post('repeat_password'),
                'biography' => $this->request->post('biography',''),
                'is_admin' => false
            ];

            $this->registerUser($inputData);
             
        }

        // la variable $errorHandler est nécessaire pour afficher les erreur dans la page ./views/frontend/register.php
        $errorHandler = $this->errorHandler;
        
        require("./views/frontend/register.php");
    }
    
    public function handleSetupAdminPage() : void
    {
        $data = $this->request->getAllPost();
        if( $this->request->file('logo_path', '') ) {
            $data['logo_path'] = $this->request->file('logo_path', '');
        }
        
        
        if(
            $this->validationService->validateSetupAdminData($data)
            )
        {
            $settingsData = [
                'blog_name' => $this->request->post('blog_name'),
                'blog_description' => $this->request->post('blog_description'),
                'logo_path' => $this->request->post('logo_path'),
                'contact_email' => $this->request->post('contact_email'),
                'default_language' => $this->request->post('default_language'),
                'timezone' => $this->request->post('timezone'),

                'firstname' => $this->request->post('firstname'),
                'lastname' => $this->request->post('lastname'),
                'email' => $this->request->post('email'),
                'username' => $this->request->post('username'),
                'biography' => $this->request->post('biography'),
                'password' => $this->request->post('password'),
                'repeat_password' => $this->request->post('repeat_password'),
                'is_admin' => true
            ];

            // Enregistrer les paramètres 
            $settings = new Settings();
            $settingsManager = new SettingsManager($this->db);

            $settings->setBlogName($settingsData['blog_name']);
            $settings->setBlogDescription($settingsData['blog_description']);

            if(isset($settingsData['logo_path']) && $settingsData['logo_path']['size'] > 0)
            {
                $fileManager = new FilesManager($this->response);
                $documentRoot = $this->request->getDocumentRoot();
                $filePath = $fileManager->importFile($settingsData['logo_path'],  $documentRoot .'/assets/img/');
                $settings->setLogoPath($filePath);
            }

            $settings->setContactEmail($settingsData['contact_email']);
            $settings->setDefaultLanguage($settingsData['default_language']);
            $settings->setTimezone($settingsData['timezone']);

            // vérifier si le nom du blog existe déjà ou pas
            if( $settingsManager->getSettingId($settingsData['blog_name']) )
            {
                $settingsManager->updateSetting($settings);
            }else{
                $settingsManager->insertSettings($settings);
            }
           
            // Enregistrer l'administrateur
            $this->registerUser($settingsData);

            $successMessage = $this->translationService->get('ACCOUNT_CREATED_SUCCESS', 'register');
            $this->errorHandler->addFlashMessage($successMessage, "success");
            $this->response->redirect('index.php?action=login');
        }

        // la variable $errorHandler est nécessaire pour afficher les erreur dans la page ./views/backend/setup_admin.php
        $errorHandler = $this->errorHandler;
        require("./views/backend/setup_admin.php"); 
    }

    public function handleAccountConfirmation() : void
    {
        // Récupérer le token et l'ID depuis le lien
        $token = $this->request->get('token');
        $userId = intVal($this->request->get('id'));
    
        if (!$token || !$userId) {
            $errorMessage = $this->translationService->get('INVALID_CONFIRMATION_LINK', 'confirmation');
            $this->errorHandler->addError($errorMessage, "danger");
            $this->response->redirect('index.php?action=login');
            return;
        }
    
        $userManager = new UserManager($this->db);
        $user = $userManager->getUser($userId);
    
        if (!$user) {
            $errorMessage = $this->translationService->get('USER_NOT_FOUND', 'confirmation');
            $this->errorHandler->addError($errorMessage, "danger");
            $this->response->redirect('index.php?action=login');
            return;
        }
    
        if ($user->getToken() !== $token) {
            $errorMessage = $this->translationService->get('INVALID_CONFIRMATION_LINK', 'confirmation');
            $this->errorHandler->addError($errorMessage, "danger");
            $this->response->redirect('index.php?action=login');
            return;
        }
    
        // Tout est correct, confirmer le compte
        $userManager->confirmAccount($userId);
    
        $successMessage = $this->translationService->get('ACCOUNT_CONFIRMED_SUCCESS', 'confirmation');
        $this->errorHandler->addFlashMessage($successMessage, "success");
        $this->response->redirect('index.php?action=login');
    }
    

    public function handleLogoutPage() : void
    {
        // Clear out user-related session data
        $this->sessionManager->remove('user_id');
        $this->sessionManager->remove('user_email');
        $this->sessionManager->remove('user_role');
        $this->sessionManager->remove('limited_access');
    
        // Add a flash message to notify user they've been logged out
        $logoutMessage = $this->translationService->get('LOGGED_OUT', 'logout');
        $this->errorHandler->addFlashMessage($logoutMessage, "info");
        
        $this->response->redirect('index.php');

    }

}




