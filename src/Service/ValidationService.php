<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;

class ValidationService {

    /** 
     * Valide les champs des formulaires
     * @param array $userData les données d'enregistrement de l'utilisateur
     * @return array|bool Retourne un tableau d'erreurs, si des erreurs son trouvées, sinon retourne true.
    */

    private $errorHandler;
    private $translationService;

    public function __construct(ErrorHandler $errorHandler, TranslationService $translationService) {
        $this->errorHandler = $errorHandler;
        $this->translationService = $translationService;
    }

    private function isFormSubmitted(array $data) : bool
    {
        return isset($data['submit']);
    }

    private function validateCheckbox(string $inputValue, string $errorKey, string $domain) : bool
    {
        if ($inputValue != '1') {
            $this->addError($errorKey, $domain);
            return false;
        }
        return true;
    }
    
    // La méthode validateField, vérifie si le champ est défini et n'est pas vide
    // elle utiliser la méthode addError pour afficher les erreurs
    private function validateField(string $field, string $errorKey, string $domain) : bool
    {
        if(empty($field))
        {
            $this->addError($errorKey, $domain);
            return false;
        }
        return true;
    }

    private function isFieldSet(mixed $field, string $errorKey, string $domain) : bool
    {
        if(!isset($field))
        {
            $this->addError($errorKey, $domain);
            return false;
        }
        return true;
    }

    private function validateEmailField(string $field, string $errorKey, string $domain) : bool
    {
        if(empty($field) || !$this->isValidEmail($field))
        {
            $this->addError($errorKey, $domain);
            return false;
        }
        return true;
    }
    
    // La méthode validatePasswordMatch vérifie que les deux mot de passe correspondent
    // Elle utilise la méthode addError pour afficher le message d'erreur
    private function validatePasswordMatch(string  $password, string $repeatPassword, string $errorKey, string $domain ) : bool
    {
        if( empty($password) || $password !== $repeatPassword )
        {
            $this->addError($errorKey, $domain);   
            return false;
        }
        return true;
    }

    private function validatePasswordStrength(string $password, string $errorKey, string $domain) : bool
    {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)(?!.*\s).{8,}$/', $password)) 
        {
            $this->addError($errorKey, $domain);   
            return false;
        }
        return true;
    }

    public function validateLoginData(array $data) : bool
    {
        if(!$this->isFormSubmitted($data) ){
            return false;
        }
        
        $isValid = true;
        if(!$this->validateField($data['email'], 'EMPTY_USER_EMAIL','login')) $isValid = false;
        if(!$this->validateField($data['password'], 'EMPTY_PASSWORD','login')) $isValid = false;
        return $isValid;
    }

    public function validateRegistrationData(array $data): bool 
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

    public function validateSetupAdminData(array $data) : bool
    {
        if(!$this->isFormSubmitted($data) ){
            return false;
        }

        $isValid = true;

        if(!$this->validateField($data['blog_name'], 'EMPTY_BLOG_NAME','register')) $isValid = false;
        if(!$this->isFieldSet($data['blog_description'], 'EMPTY_BLOG_DESCRIPTION', 'register')) $isValid = false;
        if(!$this->isFieldSet($data['logo_path'] ?? null, 'EMPTY_LOGO_PATH', 'register')) $isValid = false;
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


    private function isValidEmail(string $email): bool 
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function addError(string $errorCode, string $domain) : void 
    {
        $errorMessage = $this->translationService->get($errorCode, $domain);
        $this->errorHandler->addFlashMessage($errorMessage, "danger");
    }


    // Validation categories
    public function validateCategoryData(array $data) : bool
    {
        if(!$this->isFormSubmitted($data) ){
            return false;
        }

        $isValid = true;

        if(!$this->validateField($data['category_name'], 'EMPTY_CATEGORY_NAME','categories')) $isValid = false;
        if(!$this->validateField($data['category_slug'], 'EMPTY_CATEGORY_SLUG','categories')) $isValid = false;
        if(!$this->validateField($data['id_category_parent'], 'EMPTY_BLOG_NAME','categories')) $isValid = false;
        if(!$this->isFieldSet($data['category_description'], 'EMPTY_CATEGORY_DESCRIPTION','categories')) $isValid = false;

        return $isValid;
    }

    public function validatePostData(array $data) : bool {

        if( !isset($data['action']) ){
            return false;
        }
        
        $isValid = true;
        if(!$this->validateField($data['title'], 'EMPTY_POST_TITLE','posts')) $isValid = false;
        if(!$this->validateField($data['slug'], 'EMPTY_POST_SLUG','posts')) $isValid = false;
        if(!$this->validateField($data['content'], 'EMPTY_POST_CONTENT','posts')) $isValid = false;
        if(!$this->isFieldSet($data['featured_image'] ?? null, 'EMPTY_FEATURED_IMAGE', 'register')) $isValid = false;
        return $isValid;
    }

    public function validatePostAction( array $data) : bool {
        if( !isset($data['action']) ){
            return false;
        }

        if(!$this->isFieldSet($data['post_items'] ?? null, 'EMPTY_FEATURED_IMAGE', 'register')) $isValid = false;

    }
    
}



