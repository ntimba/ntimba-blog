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

    private function registerUser(array $data) : ?array
    {
        // Enregistrer l'administrateur
        $user = new User();
        $userManager = new UserManager();

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
            $mailService = new MailService();
            $domainName = $_SERVER['HTTP_HOST'];
            $fullName = $data['firstname'] . ' ' . $data['lastname'];

            // recupérer l'identifiant de l'utilisateur
            $userId = $userManager->getUserId($data['email']);

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $confirmationLink = $protocol . $domainName . "/confirmation?token=" . $token . "&id=" . $userId;

            $wasSent = $mailService->prepareConfirmationEmail($fullName, $data['email'], $confirmationLink);

            if($wasSent){
                $errorMessage = $this->translationService->get('ACCOUNT_CONFIRMATION_SENT','register');
                $this->errorHandler->addError($errorMessage, "primary");
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

        $data = $_POST;
        
        if(
            $this->validationService->validateRegistrationData($data)
        )  
        {
            $inputData = [
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'username' => $data['username'] ?? '',
                'password' => $data['password'],
                'repeat_password' => $data['repeat_password'],
                'biography' => $data['biography'] ?? '',
                'is_admin' => false
            ];

            $this->registerUser($inputData);
            header('Location: index.php?action=login');
        }


        // la variable $errorHandler est nécessaire pour afficher les erreur dans la page ./views/frontend/register.php
        $errorHandler = $this->errorHandler;
        
        require("./views/frontend/register.php");
    }
    
    public function handleSetupAdminPage() : void
    {
        $data = $_POST;
        if( isset($_FILES['logo_path']) ) {
            $data['logo_path'] = $_FILES['logo_path'];
        }
        
        if(
            $this->validationService->validateSetupAdminData($data)
            )
        {
            $settingsData = [
                'blog_name' => $data['blog_name'],
                'blog_description' => $data['blog_description'],
                'logo_path' => $data['logo_path'],
                'contact_email' => $data['contact_email'],
                'default_language' => $data['default_language'],
                'timezone' => $data['timezone'],

                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'username' => $data['username'],
                'biography' => $data['biography'],
                'password' => $data['password'],
                'repeat_password' => $data['repeat_password'],
                'is_admin' => true
            ];

            // Enregistrer les paramètres 
            $settings = new Settings();
            $settingsManager = new SettingsManager();

            $settings->setBlogName($settingsData['blog_name']);
            $settings->setBlogDescription($settingsData['blog_description']);

            if(isset($settingsData['logo_path']) && $settingsData['logo_path']['size'] > 0)
            {
                $fileManager = new FilesManager();
                $filePath = $fileManager->importFile($settingsData['logo_path'],  $_SERVER['DOCUMENT_ROOT'].'/assets/img/');
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

            header('Location: index.php?action=login');
        }

        // la variable $errorHandler est nécessaire pour afficher les erreur dans la page ./views/backend/setup_admin.php
        $errorHandler = $this->errorHandler;
        require("./views/backend/setup_admin.php");
        
    }

    public function handleLogoutPage() : void
    {
        header('Location: index.php');
    }

}




