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

use \Exception;



class UserController
{
    private $errorHandler;
    private $mailService;
    private $translationService;
    private $validationService;

    public function __construct(ErrorHandler $errorHandler, MailService $mailService, TranslationService $translationService, ValidationService $validationService){
        $this->errorHandler = $errorHandler;
        $this->mailService = $mailService;
        $this->translationService = $translationService;
        $this->validationService = $validationService;
    }
    
    public function handleLoginPage() {
        require("./views/frontend/login.php");
    }

    private function validateUserRegistrationFields() : bool 
    {
        return (
            isset($_POST['firstname']) && !empty($_POST['firstname']) &&
            isset($_POST['lastname']) && !empty($_POST['lastname']) &&
            isset($_POST['email']) && !empty($_POST['email']) &&
            isset($_POST['password']) && !empty($_POST['password']) &&
            isset($_POST['repeat_password']) && !empty($_POST['repeat_password'])
        );
    }

    private function registerUser(array $data) : ?array
    {
        $user = new User();
        $userManager = new UserManager();
    
        // Set user details from $data
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);

        // Check if email format is correct 
        if( !$user->isEmailValid($user->getEmail()) )
        {
            $errorMessage = $this->translationService->get('WRONG_EMAIL_FORMAT','register');
            $this->errorHandler->addError($errorMessage, "danger");
            return null;
        }

        // Check if email already exists
        if ($userManager->getUserId($data['email'])) {
            $errorMessage = $this->translationService->get('USER_EXIST','register');
            $this->errorHandler->addError($errorMessage, "danger");
            return null;
        }

        // Check if password and repeat password match
        if ($data['password'] !== $data['repeat_password']) {
            $errorMessage = $this->translationService->get('PASSWORD_NOT_IDENTICAL','register');
            $this->errorHandler->addError($errorMessage, "danger");
            return null;
        }
    
        // Check if the password matches the required format
        if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)(?!.*\s).{8,}$/', $data['password'])) {
            // Using the password hashing function for security
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $user->setPassword($hashedPassword);
        } else {
            $errorMessage = $this->translationService->get('WRONG_PASSWORD_FORMAT','register');
            $this->errorHandler->addError($errorMessage, "danger");
            return null; // Exit the method since the password doesn't match the required format
        }
        
        // Generate a token for email confirmation
        $token = bin2hex(random_bytes(32));
        $data['token'] = $token;
        $user->setToken($token);

    
        // Set role and default profile picture
        $user->setRole($data['isAdmin'] ? 'admin' : 'subscriber');
        $user->setProfilePicture('../../assets/img/avatar.png');

        $user->setUsername($data['username']);
        $user->setBiography($data['biography']);
    
        // Insert the user into the database
        $userManager->insertUser($user);

        // return userdata
        return $data;
    }    

    public function handleRegisterPage(bool $isAdmin = false) : void
    {      
        if( $this->validationService->validateRegistrationData($_POST) )
        {
            // filtrage des données pour des raison de sécurité
            $inputData = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'email' => $_POST['email'],
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'repeat_password' => $_POST['repeat_password'],
                'biography' => $_POST['biography'],
                'isAdmin' => $isAdmin
            ];
            
            // Enregistrement de l'utilisateur
            $registeredData = $this->registerUser($inputData);
    
            $token = $registeredData['token'];
    
            // Recupération de l'identifiant qui va permettre de
            $userManager = new UserManager();
            $userId = $userManager->getUserId( $inputData['email'] );
            
            $userDbData = $userManager->getUser( $userId );
    
            $host = $_SERVER['HTTP_HOST'];
    
            $confirmationLink = sprintf('%s/index.php?action=verifyuser&token=%s&id=%s', $host, $token, $userId);
    
            if( $this->mailService->prepareConfirmationEmail($userDbData->getFullName(), $userDbData->getEmail(), $confirmationLink) )
            {
                $errorMessage = $this->translationService->get('ACCOUNT_CONFIRMATION_EMAIL_MESSAGE','register');
                $this->errorHandler->addFlashMessage($errorMessage, "primary");
            }else{
                $errorMessage = $this->translationService->get('EMAIL_SEND_ERROR_MESSAGE','register');
                $this->errorHandler->addFlashMessage($errorMessage, "danger");
            }

        }else{

            if ($this->validationService->isFormSubmitted($_POST))
            {
                $errorMessage = $this->translationService->get('FORM_NOT_COMPLETED','register');
                $this->errorHandler->addError($errorMessage, "danger");
            }
        } 
        
        $errorHandler = $this->errorHandler;

        if($isAdmin)
        {
            $timezones = timezone_identifiers_list();
            require("./views/backend/setup_admin.php");
        }else{
            require("./views/frontend/register.php");
        }
    }
    
    public function handleSetupAdminPage() : void
    {
        if( $this->validationService->validateSetupAdminData($_POST) )
        {
            $filesManager = new FilesManager();

            $settings = new Settings(); 
            $settingsManager = new SettingsManager();
            
            // filtrage des données pour des raison de sécurité
            $settingsData = [
                'blog_name' => $_POST['blog_name'],
                'blog_description' => $_POST['blog_description'],
                'logo_path' => $_FILES['logo_path'],
                'contact_email' => $_POST['contact_email'],
                'default_language' => $_POST['default_language'],
                'timezone' => $_POST['timezone'],

                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'email' => $_POST['email'],
                'username' => $_POST['username'],
                'biography' => $_POST['biography'],
                'password' => $_POST['password'],
                'repeat_password' => $_POST['repeat_password']
            ];

            $settings->setBlogName($settingsData['blog_name']);
            $settings->setBlogDescription($settingsData['blog_description']);
            // traiter l'envoi du logo
            $absoluteLogoPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/';
            $logoPath = $filesManager->importFile($settingsData['logo_path'], $absoluteLogoPath);
            $settings->setLogoPath($logoPath);

            $settings->setContactEmail($settingsData['contact_email']);
            $settings->setDefaultLanguage($settingsData['default_language']);
            $settings->setTimezone($settingsData['timezone']);

            // vérifier si les paramètre existe déjà (en se basant sur le nom du blog)
            if( !$settingsManager->getSettingId($settingsData['blog_name']) )
            {
                $settingsManager->insertSetting($settings);
            }else{
                $settingsManager->updateSetting($settings);
            }
            
            // Les éléments qui sont enregistrer dans la table users
            $this->registerUser($settingsData);
            
        }else{
            echo "un problème s'est produit";
        }
        
        // On va enregistrer l'administrateur dans la base de données
        // $this->handleRegisterPage(true);
    }

    public function handleLogoutPage() : void
    {
        header('Location: index.php');
    }

}




