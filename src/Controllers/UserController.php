<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Models\User;
use Portfolio\Ntimbablog\Models\UserManager;
use Portfolio\Ntimbablog\Models\Settings;
use Portfolio\Ntimbablog\Models\SettingsManager;
use Portfolio\Ntimbablog\Models\FilesManager;
use Portfolio\Ntimbablog\Models\CommentManager;

use Portfolio\Ntimbablog\Service\EnvironmentService;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Service\MailService;
use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;
use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Http\SessionManager;
use Portfolio\Ntimbablog\Helpers\StringUtil;
use Portfolio\Ntimbablog\Helpers\LayoutHelper;

use \Exception;
use Portfolio\Ntimbablog\Service\Authenticator;

class UserController extends CRUDController
{   

    private $userManager;
    private $user;
    private $fileManager;
    private $commentManager;

    public function __construct(
        ErrorHandler $errorHandler,
        MailService $mailService,
        TranslationService $translationService,
        ValidationService $validationService,
        Request $request,
        Database $db,
        HttpResponse $response,
        SessionManager $sessionManager,
        StringUtil $stringUtil,
        Authenticator $authenticator,
        LayoutHelper $layoutHelper
    )
    {
        parent::__construct(
            $errorHandler,
            $mailService,
            $translationService,
            $validationService,
            $request,
            $db,
            $response,
            $sessionManager,
            $stringUtil,
            $authenticator, 
            $layoutHelper
        );
        
        $this->userManager = new UserManager($db);
        $this->user = new User();
        $this->fileManager = new FilesManager($response);
        $this->commentManager = new CommentManager($db, $stringUtil);
    }
     
    public function handleLoginPage() : void 
    {
        if($this->authenticator->isAuthenticated()){
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

            $userData = $userManager->read($userId);
            // Ce bout de code vérifie si le mot de passe entré par l'utilisateur 
            // correspond au mot de passe stocker dans la base de données
            if (!$userManager->verifyPassword($loginData['password'], $userData->getPassword())) 
            {
                $errorMessage = $this->translationService->get('WRONG_PASSWORD','login');
                $this->errorHandler->addFlashMessage($errorMessage, "danger");
                $this->response->redirect('index.php?action=login');
                return;
            }


            $auditedAccount = $userData->getAuditedAccount();

            if(!$auditedAccount) {
                // echo( 'test' );
                $errorMessage = $this->translationService->get('NOT_AUDITED_ACCOUNT','login');
                $this->errorHandler->addFlashMessage($errorMessage, "warning");

                $this->sessionManager->set('limited_access', true);

            }else{
                $this->sessionManager->set('limited_access', false);
                
                $this->sessionManager->set('user_id', $userId);
                $this->sessionManager->set('user_email', $loginData['email']);
                $this->sessionManager->set('user_role', $userData->getRole());
                $this->sessionManager->set('full_name', $userData->getFullName());
                $this->sessionManager->set('profile_picture', $userData->getProfilePicture());


                $successMessage = $this->translationService->get('SUCCESS_CONNECTED', 'login');
                $this->errorHandler->addFlashMessage($successMessage, "success");
                
                $this->response->redirect('index.php');
                // $this->response->redirect('index.php?action=blog');
            }
            
            // à vérifier
            // $this->response->redirect('index.php?action=login');

            
            if( isset($loginData['remember_me']) && $loginData['remember_me'] == 1 )
            {
                // Création d'un cookie
            }
            
        }
        
        $errorHandler = $this->errorHandler;
        require("./views/frontend/login.php");
    }

    public function handleUsersPage() : void
    {
        $this->authenticator->ensureAdmin();
        $users = $this->userManager->getAllUsers();

        $usersData = [];
        foreach( $users as $user ){
            if( $user->getId() != $this->sessionManager->get('user_id') ){
                $userData['user_id'] = $user->getId();
                $userData['username'] = $user->getUsername() ?? $user->getFullName();
                $userData['user_profile'] = $user->getProfilePicture();
                $userData['register_datum'] = $this->stringUtil->getForamtedDate($user->getRegistrationDate());
                $userData['email'] = $user->getEmail();
                $userData['comments'] = '23 commentaires';
                $userData['status'] = $user->getStatus();
    
                $usersData[] = $userData;
            }
        }

        $errorHandler = $this->errorHandler;
        require("./views/backend/users.php");
    }

    public function userModify(){
        $this->authenticator->ensureAdmin();
        $data = $this->request->getAllPost();

        // Valider le formulaire et afficher les messages
        if( !isset($data['action']) ){
            $this->errorHandler->addMessage('CHOOSE_AN_ACTION', 'users', 'warning');
            $this->response->redirect('index.php?action=users');
            return;
        }

        if( !isset($data['user_ids'])){
            $this->errorHandler->addMessage('CHOOSE_A_USER', 'users', 'warning');
            $this->response->redirect('index.php?action=users');
            return;
        }

        if( count($data['user_ids']) === 0 ){
            $this->errorHandler->addMessage('CHOOSE_A_USER', 'users', 'warning');
            $this->response->redirect('index.php?action=users');
            return;
        }
        
        if( $data['action'] === 'activate' ){
            
            foreach( $data['user_ids'] as $userId ){
                $userId = (int) $userId;
                $this->toggleUserStatus($userId, true);
            }

            $this->errorHandler->addMessage('USER_ACTIVATED', 'users', 'success');
            $this->response->redirect('index.php?action=users');

        }elseif( $data['action'] === 'deactivate' ){
            foreach( $data['user_ids'] as $userId ){
                $userId = (int) $userId;
                $this->toggleUserStatus($userId, false);
            }

            $this->errorHandler->addMessage('USER_DEACTIVATED', 'users', 'success');
            $this->response->redirect('index.php?action=users');

        }elseif( $data['action'] === 'delete' ){
            
            foreach( $data['user_ids'] as $userId ){
                $userId = (int) $userId; // l'identifiant de l'utilisateur

                // commentIds
                $commentIds = $this->commentManager->getCommentIdsByUserId($userId);

                // delete commentIds
                $this->deleteComments($commentIds);
                
                // Delete user
                $this->userManager->delete($userId);
            }
            $this->errorHandler->addMessage('USER_DELETED', 'users', 'success');
            $this->response->redirect('index.php?action=users');
        } else{
            $this->errorHandler->addMessage('CHOOSE_AN_ACTION', 'users', 'warning');
            $this->response->redirect('index.php?action=users');
            return;
        }
    }

    public function deleteComments(array $commentIds)
    {
        /** 
         * This method receives an array as a parameter
         * and reads the array line by line, using
         * the deleteComment() method of the CommentManager class.
         */
        foreach($commentIds as $commentId)
        {
            $this->commentManager->deleteComment($commentId);
        }
    }

    private function toggleUserStatus(int $userId, bool $newStatus) : void
    {
        $data = $this->request->getAllPost();

        // recupérer le post qui correspond à cet id
        // changer le status de la méthode
        $post = $this->userManager->read($userId);
        $post->setStatus($newStatus); 

        $this->userManager->update($post);
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

        // Ce bout de code enregistre l'image de profile par défaut de l'utilisateur.
        $user->setToken($token);
        $user->setRole($data['is_admin'] ? 'admin' : 'subscriber');
        $user->setProfilePicture('/assets/img/avatar.png');

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
            // $confirmationLink = $protocol . $domainName . "?action=confirmation&token=" . $token . "&id=" . $userId;
            $messageContent = $protocol . $domainName . "?action=confirmation&token=" . $token . "&id=" . $userId;

            $wasSent = $mailService->prepareEmail($fullName, $data['email'],'webmaster@' . $domainName, "Confirmation d'inscription au blog de ntimba.com.", $messageContent, 'Views/emails/confirmaccount.php');

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
        if($this->authenticator->isAuthenticated()){
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

    public function updateUser() : void
    {
        $this->authenticator->ensureAuditedUserAuthentication();
        
        // recupérer les données de l'utilisateur
        $userId = $this->sessionManager->get('user_id');
        $user = $this->userManager->read($userId);

        // 1. Les nouvelles données entré par l'utilisateur
        $userData = $this->request->getAllPost();

        if($this->validationService->validateUpdateUserData($userData)){

            // importer l'image
            if( $this->request->file('image') ){
                $userData['image'] = $this->request->file('image');
            }

            if(isset($userData['image']) && $userData['image']['size'] > 0)
            {
                $documentRoot = $this->request->getDocumentRoot();
    
                $profileImage = $this->fileManager->importFile($userData['image'], './assets/uploads/');
                $fileName = basename($profileImage);
                $user->setProfilePicture($profileImage);
            }

            $user->setFirstname($userData['firstname']);
            $user->setLastname($userData['lastname']);
            $user->setUsername($userData['username']);
            $user->setBiography($userData['biography']);


            // Mettre à jour la base de données
            $this->userManager->update($user);

            // Mettre à jour les variables des sessions
            $this->sessionManager->set('full_name', $user->getFullName());
            $this->sessionManager->set('profile_picture', $user->getProfilePicture());
            
        }


        
        $errorHandler = $this->errorHandler;
        require("./views/backend/settings.php");
    }
    
    public function updatePassword() : void
    {
        $this->authenticator->ensureAuditedUserAuthentication();
        
        // recupérer les données de l'utilisateur
        $userId = $this->sessionManager->get('user_id');
        $user = $this->userManager->read($userId);

        // 1. Les nouvelles données entré par l'utilisateur
        $userData = $this->request->getAllPost();

        
        // Mettre à jour le mot de passe
        if( $this->validationService->validateNewPassword($userData) )
        {
            // vérifier que l'ancien mot de passe correspond
            if( password_verify($userData['old_password'], $user->getPassword()) )
            {            
                // vérifier si les deux autres mot de passe sont identique
                if( $this->validationService->validatePasswordMatch($userData['new_password'], $userData['repeat_password'], 'PASSWORD_NOT_IDENTICAL', 'users') )
                {
                    // Vérifier la dificulté du mot de passe
                    if( $this->validationService->validatePasswordStrength($userData['new_password'], 'PASSWORD_NOT_STRENGTH' , 'users') )
                    {
                        // hasher le mot de passe
                        $hashedPassword = password_hash($userData['new_password'], PASSWORD_DEFAULT);
                        $user->setPassword($hashedPassword);

                        // Mettre à jour la base de données
                        $this->userManager->update($user);

                        // Afficher le message flash
                        $successMessage = $this->translationService->get('PASSWORD_CHANGED','users');
                        $this->errorHandler->addFlashMessage($successMessage, "success");
                    }

                }
                
            }else{
                // Afficher un flash message
                $warningMessage = $this->translationService->get('PASSWORD_NOT_CORRECT', 'users');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");
            }
        }
        

        $errorHandler = $this->errorHandler; 
        require("./views/backend/update_password.php");
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
        $user = $userManager->read($userId);

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



