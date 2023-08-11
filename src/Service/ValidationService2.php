<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;

class ValidationService {

    /** 
     * Valide les champs des formulaires
     * @param array $userData les données d'enregistrement de l'utilisateur
     * @return array|bool Retourne un tableau d'erreurs, si des erreurs son trouvées, sinon retourne true.
    */

    private $errorHandler;
    private $translationService;

    public function __construct($errorHandler, $translationService) {
        $this->errorHandler = $errorHandler;
        $this->translationService = $translationService;
    }

    private function validateField($field, $errorKey, $context = 'register', $type = 'danger') : bool
    {
        if(empty($field)) {
            $errorMessage = $this->translationService->get($errorKey, $context);
            $this->errorHandler->addFlashMessage($errorMessage, $type);

            return false;
        }
        return true;
    }

    private function validatePasswordMatch($password, $repeatPassword) : bool
    {
        if( empty($repeatPassword) || $password !== $repeatPassword ) {
            $errorMessage = $this->translationService->get('PASSWORD_NOT_IDENTICAL', 'register');
            $this->errorHandler->addFlashMessage($errorMessage, "danger");

            return false;
        }
        return true;
    }

    private function validateFileUpload($fileKey) : bool
    {
        if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
            $errorMessage = $this->translationService->get('LOGO_UPLOAD_ERROR', 'register');
            $this->errorHandler->addFlashMessage($errorMessage, "warning");
            return false;
        }
        return true;
    }

    private function validateTermsAccepted($termsValue) : bool
    {
        if (!isset($termsValue) || $termsValue != '1') {
            $errorMessage = $this->translationService->get('TERMS_NOT_ACCEPTED', 'register');
            $this->errorHandler->addFlashMessage($errorMessage, "danger");
            return false;
        }
        return true;
    }

    public function isFormSubmitted($data) : bool
    {
        return isset($data['submit']);
    }

    public function validateRegistrationData($data): bool 
    {
        if (!$this->validateField($data['firstname'], 'EMPTY_FIRSTNAME')) return false;
        if (!$this->validateField($data['lastname'], 'EMPTY_LASTNAME')) return false;
        if (!$this->validateField($data['email'], 'WRONG_EMAIL_FORMAT') || !$this->isValidEmail($data['email'])) return false;
        if (!$this->validateField($data['password'], 'EMPTY_PASSWORD')) return false;
        if (!$this->validatePasswordMatch($data['password'], $data['repeat_password'])) return false;
        if (!$this->validateTermsAccepted($data['terms'])) return false;
    
        return true;
    }
    

  
    // public function validateSetupAdminData($data) : bool
    // {
    //     if (!isset($data['blog_name']) || !$this->validateField($data['blog_name'], 'EMPTY_BLOGNAME')) return false;
    //     if (!$this->validateField($data['blog_description'], 'EMPTY_BLOG_DESCRIPTION', 'register', 'warning')) return false;
    //     if (!$this->validateFileUpload('logo_path')) return false;
    //     if (!$this->validateField($data['contact_email'], 'EMPTY_CONTACT_EMAIL')) return false;
    //     if (!$this->validateField($data['default_language'], 'EMPTY_DEFAULT_LANGUAGE')) return false;
    //     if (!$this->validateField($data['timezone'], 'EMPTY_TIMEZONE')) return false;

    //     if (!$this->validateField($data['firstname'], 'EMPTY_FIRSTNAME')) return false;
    //     if (!$this->validateField($data['lastname'], 'EMPTY_LASTNAME')) return false;
    //     if (!$this->validateField($data['username'], 'EMPTY_LASTNAME')) return false;
    //     if (!$this->validateField($data['email'], 'WRONG_EMAIL_FORMAT') || !$this->isValidEmail($data['email'])) return false;
    //     if (!$this->validateField($data['biography'], 'EMPTY_BIOGRAPHY')) return false;
    //     if (!$this->validateField($data['password'], 'EMPTY_PASSWORD')) return false;
    //     if (!$this->validatePasswordMatch($data['password'], $data['repeat_password'])) return false;
    //     return true;
    // }

    public function validateSetupAdminData($data) : bool
    {
       debug( $data );

       return true;


       if( isset( $data['blog_name'] ) )
       {
        echo "vérifier";
       }
       
    }



    private function isValidEmail($email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function addError($errorCode, $domain) {
        $errorMessage = $this->translationService->get($errorCode, $domain);
        $this->errorHandler->addError($errorMessage, "danger");
    }
}
