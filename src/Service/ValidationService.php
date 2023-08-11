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

    private function isFormSubmitted($data) : bool
    {
        return isset($data['submit']);
    }

    // la méthode vérifie si le champ terms est coché
    // elle utilise la fonction addError pour afficher le message d'erreur
    private function validateCheckbox($inputValue, $errorKey, $domain) : bool
    {
        if (!isset($inputValue) || $inputValue != '1') {
            $this->addError($errorKey, $domain);
            return false;
        }
        return true;
    }


    // La méthode validateField, vérifie si le champ est défini et n'est pas vide
    // elle utiliser la méthode addError pour afficher les erreurs
    private function validateField($field, $errorKey, $domain) : bool
    {
        if(!isset($field) || empty($field))
        {
            $this->addError($errorKey, $domain);
            return false;
        }
        return true;
    }

    private function isFieldSet($field, $errorKey, $domain) : bool
    {
        if(!isset($field))
        {
            $this->addError($errorKey, $domain);
            return false;
        }
        return true;
    }

    private function validateEmailField($field, $errorKey, $domain)
    {
        if(!isset($field) || empty($field) || !$this->isValidEmail($field))
        {
            $this->addError($errorKey, $domain);
            return false;
        }
        return true;
    }

    // La méthode validatePasswordMatch vérifie que les deux mot de passe correspondent
    // Elle utilise la méthode addError pour afficher le message d'erreur
    private function validatePasswordMatch( $password, $repeatPassword, $errorKey, $domain ) : bool
    {
        if( !isset($password) || empty($password) || 
        !isset($repeatPassword) || $password !== $repeatPassword )
        {
            $this->addError($errorKey, $domain);   
            return false;
        }
        return true;
    }

    private function validatePasswordStrength($password, $errorKey, $domain) : bool
    {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)(?!.*\s).{8,}$/', $password)) 
        {
            $this->addError($errorKey, $domain);   
            return false;
        }
        return true;
    }

    public function validateRegistrationData($data): bool 
    {
        if(!$this->isFormSubmitted($data) ){
            return false;
        }
        
        $isValid = true;

        if(!$this->validateField($data['firstname'], 'EMPTY_FIRSTNAME','register')) $isValid = false;
        if(!$this->validateField($data['lastname'], 'EMPTY_LASTNAME','register')) $isValid = false;
        if(!$this->validateField($data['email'], 'EMPTY_USER_EMAIL','register')) $isValid = false;
        if(!$this->validatePasswordStrength($data['password'],'PASSWORD_NOT_STRENGTH', 'register')) $isValid = false;
        if(!$this->validatePasswordMatch($data['password'], $data['repeat_password'],'PASSWORD_NOT_IDENTICAL', 'register')) $isValid = false;
        if(!$this->validateCheckbox($data['terms'] ?? null, 'TERMS_NOT_ACCEPTED', 'register')) $isValid = false;
                
        return $isValid;
    }

    public function validateSetupAdminData($data) : bool
    {
        if(!$this->isFormSubmitted($data) ){
            return false;
        }

        $isValid = true;

        if(!$this->validateField($data['blog_name'], 'EMPTY_BLOG_NAME','register')) $isValid = false;
        if(!$this->isFieldSet($data['blog_description'], 'EMPTY_BLOG_DESCRIPTION', 'register')) $isValid = false;
        if(!$this->isFieldSet($data['logo_path'], 'EMPTY_LOGO_PATH', 'register')) $isValid = false;
        if(!$this->validateEmailField($data['contact_email'], 'WRONG_CONTACT_EMAIL_FORMAT', 'register')) $isValid = false;
        if(!$this->validateField($data['default_language'], 'EMPTY_DEFAULT_LANGUAGE','register')) $isValid = false;
        if(!$this->validateField($data['timezone'], 'EMPTY_TIMEZONE','register')) $isValid = false;
        
        if(!$this->validateField($data['firstname'], 'EMPTY_FIRSTNAME','register')) $isValid = false;
        if(!$this->validateField($data['lastname'], 'EMPTY_LASTNAME','register')) $isValid = false;
        if(!$this->validateEmailField($data['email'], 'WRONG_EMAIL_FORMAT', 'register')) $isValid = false;
        if(!$this->validateField($data['username'], 'EMPTY_USERNAME','register')) $isValid = false;
        if(!$this->isFieldSet($data['biography'], 'EMPTY_BIOGRAPHY', 'register')) $isValid = false;
        if(!$this->validatePasswordStrength($data['password'],'PASSWORD_NOT_STRENGTH', 'register')) $isValid = false;
        if(!$this->validatePasswordMatch($data['password'], $data['repeat_password'],'PASSWORD_NOT_IDENTICAL', 'register')) $isValid = false;

        return $isValid;
    }

    private function isValidEmail($email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function addError($errorCode, $domain) {
        $errorMessage = $this->translationService->get($errorCode, $domain);
        $this->errorHandler->addError($errorMessage, "danger");
    }

    // private function validateLoginData(array $data)
    // {

    // }
}
