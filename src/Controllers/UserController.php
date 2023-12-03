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
use Portfolio\Ntimbablog\Helpers\Paginator;

use \Exception;
use Portfolio\Ntimbablog\Models\Socialnetwork;
use Portfolio\Ntimbablog\Models\SocialnetworkManager;

use Portfolio\Ntimbablog\Service\Authenticator;

class UserController extends CRUDController
{   

    private $userManager;
    private $user;
    private $fileManager;
    private $commentManager;
    private $socialNetwork;
    private $socialnetworkManager;
    private $environmentService;

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
        LayoutHelper $layoutHelper,
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

        $this->socialNetwork = new Socialnetwork;
        $this->socialnetworkManager = new SocialnetworkManager($db);
        $this->environmentService = new EnvironmentService($request);
    }
     
    /**
     * This method handles the login page.
     * It verifies if the credentials entered by the user correspond to those in the database.
     */
    public function handleLoginPage() : void 
    {
        if($this->authenticator->isAuthenticated()){
            $this->response->redirect('index.php?action=home');
            return;
        }

        $data = $this->request->getAllPost();
        if( $this->validationService->validateLoginData($data) )
        {
            $loginData = [
                'email' => $this->request->post('email'),
                'password' => $this->request->post('password'),
                'remember_me' => $this->request->post('remember_me'),
            ];
            
            $userManager = new UserManager($this->db);
            $userId = $userManager->getUserId($loginData['email']);
            if( !$userId )
            {
                $errorMessage = $this->translationService->get('USER_NOT_EXIST','login');
                $this->errorHandler->addError($errorMessage, "warning");
                return;
            }

            $userData = $userManager->read($userId);
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
                
                $this->sessionManager->set('user_id', $userId);
                $this->sessionManager->set('user_email', $loginData['email']);
                $this->sessionManager->set('user_role', $userData->getRole());
                $this->sessionManager->set('full_name', $userData->getFullName());
                $this->sessionManager->set('profile_picture', $userData->getProfilePicture());

                $successMessage = $this->translationService->get('SUCCESS_CONNECTED', 'login');
                $this->errorHandler->addFlashMessage($successMessage, "success");
                
                $this->response->redirect('index.php');
            }
        }
        
        $errorHandler = $this->errorHandler;
        require("./views/frontend/login.php");
    }

    /**
     * This method lists all the registered users in the database.
     */
    public function handleUsersPage(): void
    {
        $this->authenticator->ensureAdmin();

        $totalItems = $this->userManager->getTotalUsersCount();
        $itemsPerPage = 10;
        $currentPage = intval($this->request->get('page')) ?? 1;
        $linkParam = 'users';

        $fetchUsersCallback = function($offset, $limit){
            return $this->userManager->getUsersByPage($offset, $limit);
        };

        $paginator = new Paginator($this->request, $totalItems, $itemsPerPage, $currentPage,$linkParam , $fetchUsersCallback);

        $users = $paginator->getItemsForCurrentPage(); 

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

        $paginationLinks = $paginator->getPaginationLinks($currentPage, $paginator->getTotalPages());
        
        $errorHandler = $this->errorHandler;
        require("./views/backend/users.php");         
    }


    /**
     * This method handles the actions chosen by the user to delete, activate, or deactivate a user.
     */
    public function userModify(): void
    {
        $this->authenticator->ensureAdmin();
        $data = $this->request->getAllPost();

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
                $userId = (int) $userId; 

                $commentIds = $this->commentManager->getCommentIdsByUserId($userId);

                $this->deleteComments($commentIds);
                
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

    /** 
     * This method receives an array as a parameter
     * and reads the array line by line, using
     * the deleteComment() method of the CommentManager class.
     */
    public function deleteComments(array $commentIds): void
    {
        foreach($commentIds as $commentId)
        {
            $this->commentManager->deleteComment($commentId);
        }
    }

    private function toggleUserStatus(int $userId, bool $newStatus) : void
    {
        $data = $this->request->getAllPost();
        $post = $this->userManager->read($userId);
        $post->setStatus($newStatus); 

        $this->userManager->update($post);
    }

    /**
     * This method handles the registration of a user.
     */
    private function registerUser(array $data) : ?array
    {
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
        $user->setProfilePicture('/assets/img/avatar.png');

        if (!$userManager->getUserId($data['email'])) {
            
            // $environmentService = new EnvironmentService($this->request);
            $mailService = new MailService($this->request, $this->environmentService);

            $userManager->insertUser($user);

            $userId = $userManager->getUserId($data['email']);
            $domainName = $this->request->getDomainName();
            $protocol = $this->request->getProtocol();
            
            $fullName = $data['firstname'] . ' ' . $data['lastname'];
            $email = $data['email'];
            $replyTo = 'no-reply@ntimba.me';
            $subject = "Confirmation d'inscription au blog de ntimba.me";
            $messageContent = $protocol . $domainName . "?action=confirmation&token=" . $token . "&id=" . $userId;     
            $emailBodyTemplate = './views/emails/confirmaccount.php';
            
            $wasSent = $mailService->prepareEmail($fullName, $email, $replyTo, $subject, $messageContent, $emailBodyTemplate);

            if($wasSent){
                $errorMessage = $this->translationService->get('ACCOUNT_CONFIRMATION_SENT','register');
                $this->errorHandler->addFlashMessage($errorMessage, "primary");
                session_write_close();
                $this->response->redirect('index.php?action=login');
            }else{
                $errorMessage = $this->translationService->get('ACCOUNT_CONFIRMATION_NOT_SENT','register');
                $this->errorHandler->addError($errorMessage, "danger");
            }

        }else{
            $errorMessage = $this->translationService->get('USER_EXIST','register');
            $this->errorHandler->addError($errorMessage, "danger");
        }
        
        return $data;
    }    

    /**
     * This method uses the ValidationService class to validate the data entered by the user
     * and handles the display of the registration page.
     */
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

        $errorHandler = $this->errorHandler;
        require("./views/frontend/register.php");
    }

    /**
     * This method handles the update of the user's information.
     */
    public function updateUser() : void
    {
        $this->authenticator->ensureAuditedUserAuthentication();
        
        $userId = $this->sessionManager->get('user_id');
        $user = $this->userManager->read($userId);

        $userData = $this->request->getAllPost();
        if($this->validationService->validateUpdateUserData($userData)){

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

            $this->userManager->update($user);

            $this->sessionManager->set('full_name', $user->getFullName());
            $this->sessionManager->set('profile_picture', $user->getProfilePicture());
        }

        $errorHandler = $this->errorHandler;
        require("./views/backend/settings.php");
    }
    

    /**
     * This method handles the update of the password.
     */
    public function updatePassword() : void
    {
        $this->authenticator->ensureAuditedUserAuthentication();

        $userId = $this->sessionManager->get('user_id');
        $user = $this->userManager->read($userId);

        $userData = $this->request->getAllPost();

        if( $this->validationService->validateNewPassword($userData) )
        {
            if( password_verify($userData['old_password'], $user->getPassword()) )
            {            
                if( $this->validationService->validatePasswordMatch($userData['new_password'], $userData['repeat_password'], 'PASSWORD_NOT_IDENTICAL', 'users') )
                {
                    if( $this->validationService->validatePasswordStrength($userData['new_password'], 'PASSWORD_NOT_STRENGTH' , 'users') )
                    {
                        $hashedPassword = password_hash($userData['new_password'], PASSWORD_DEFAULT);
                        $user->setPassword($hashedPassword);

                        $this->userManager->update($user);

                        $successMessage = $this->translationService->get('PASSWORD_CHANGED','users');
                        $this->errorHandler->addFlashMessage($successMessage, "success");
                    }
                }
            }else{
                $warningMessage = $this->translationService->get('PASSWORD_NOT_CORRECT', 'users');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");
            }
        }
        
        $errorHandler = $this->errorHandler; 
        require("./views/backend/update_password.php");
    }

    /**
     * This method handles the initial setup and creation of the administrator account.
     */
    public function handleSetupAdminPage() : void
    {
        if( $this->userManager->doesUserExist('admin') ){
            $this->response->redirect('index.php');
        }
        
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

            if( $settingsManager->getSettingId($settingsData['blog_name']) )
            {
                $settingsManager->updateSetting($settings);
            }else{
                $settingsManager->insertSettings($settings);
            }
            
            $this->registerUser($settingsData);

            $successMessage = $this->translationService->get('ACCOUNT_CREATED_SUCCESS', 'register');
            $this->errorHandler->addFlashMessage($successMessage, "success");
            $this->response->redirect('index.php?action=login');
        }

        $errorHandler = $this->errorHandler;
        require("./views/backend/setup_admin.php"); 
    }


    /**
     * This method handles the confirmation of the user account.
     */
    public function handleAccountConfirmation() : void
    {
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
    
        $userManager->confirmAccount($userId);
    
        $successMessage = $this->translationService->get('ACCOUNT_CONFIRMED_SUCCESS', 'confirmation');
        $this->errorHandler->addFlashMessage($successMessage, "success");
        $this->response->redirect('index.php?action=login');
    }
    

    /**
     * This method handles the user's logout.
     */
    public function handleLogoutPage() : void
    {
        $this->sessionManager->remove('user_id');
        $this->sessionManager->remove('user_email');
        $this->sessionManager->remove('user_role');
        $this->sessionManager->remove('limited_access');
    
        $logoutMessage = $this->translationService->get('LOGGED_OUT', 'logout');
        $this->errorHandler->addFlashMessage($logoutMessage, "info");
        
        $this->response->redirect('index.php');
    }

    /**
     * This method handles the registration of a social network in the database
     * and retrieves all social networks to store them in a variable.
     */
    public function handleSocialNetwork(): void
    {
        $this->authenticator->ensureAdmin();

        $networkData = $this->request->getAllPost();
        if( $this->validationService->validateSocialNetwork($networkData) )
        {
            $this->socialNetwork->setNetworkName($networkData['network_name']);
            $this->socialNetwork->setNetworkUrl($networkData['network_link']);
            $this->socialNetwork->setNetworkIconClass($networkData['network_css_class']);

            if( !$this->socialnetworkManager->getNetworkId($networkData['network_name']) ){
                $this->socialnetworkManager->create($this->socialNetwork);
                $message = $this->translationService->get('NETWORK_ADDED', 'users');
                $this->errorHandler->addError($message, "success");
            }else{
                $warningMessage = $this->translationService->get('NETWORK_EXIST', 'users');
                $this->errorHandler->addError($warningMessage, "warning");
            }
        }

        $networks = $this->socialnetworkManager->getAll();
        
        $errorHandler = $this->errorHandler;
        require("./views/backend/social-media.php");
    }


    /**
     * This method handles the deletion of a social network.
     */
    public function deleteNetwork(): void
    {
        $networkData = $this->request->getAllGet();

        if( isset( $networkData['id'] ) && !empty( $networkData['id'] ) )
        {
            $networkId = intval( $networkData['id'] );
            if($this->socialnetworkManager->delete($networkId))
            {
                $successMessage = $this->translationService->get('NETWORK_DELETED', 'users');
                $this->errorHandler->addError($successMessage, "success");
                session_write_close();
                $this->response->redirect('index.php?action=social_network');
            }
        }
        
    }

    /**
     * This method handles the update of the social network.
     */
    public function updateNetwork(): void
    {
        $networkData = $this->request->getAllGet();
        if( isset( $networkData['id'] ) && !empty( $networkData['id'] ) )
        {
            $networkId = intval( $networkData['id'] );
            $network = $this->socialnetworkManager->read($networkId);
        }
    
        $networkUpdatedData = $this->request->getAllPost();
        if( $this->validationService->validateSocialNetwork($networkUpdatedData) )
        {
            $network->setNetworkName($networkUpdatedData['network_name']);
            $network->setNetworkUrl($networkUpdatedData['network_link']);
            $network->setNetworkIconClass($networkUpdatedData['network_css_class']);

            if( $this->socialnetworkManager->update($network) ){
                $successMessage = $this->translationService->get('NETWORK_UPDATED', 'users');
                $this->errorHandler->addError($successMessage, "success");
                $this->response->redirect('index.php?action=social_network');
            } 
        }

        $errorHandler = $this->errorHandler;
        require("./views/backend/edit-social-media.php");
    }

}



