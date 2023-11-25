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

    private function validateCheckbox(string|null $inputValue, string $errorKey, string $domain) : bool
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

    private function isFieldSet(string|array $field, string $errorKey, string $domain) : bool
    {
        if (is_array($field)) {
            foreach ($field as $item) {
                if ($item === null) {
                    $this->addError($errorKey, $domain);
                    return false;
                }
            }
        } elseif ($field === null) {
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
    public function validatePasswordMatch(string  $password, string $repeatPassword, string $errorKey, string $domain ) : bool
    {
        if( empty($password) || $password !== $repeatPassword )
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

    public function validatePageData(array $data) : bool {
        if( !isset($data['action']) ){
            return false;
        }
        
        $isValid = true;
        if(!$this->validateField($data['title'], 'EMPTY_PAGE_TITLE','pages')) $isValid = false;
        if(!$this->validateField($data['slug'], 'EMPTY_PAGE_SLUG','pages')) $isValid = false;
        if(!$this->validateField($data['content'], 'EMPTY_PAGE_CONTENT','pages')) $isValid = false;
        if(!$this->isFieldSet($data['featured_image'] ?? null, 'EMPTY_FEATURED_IMAGE', 'register')) $isValid = false;
        return $isValid; 
    }

    public function validatePostAction( array $data) : bool {
        if( !isset($data['action']) ){
            return false;
        }

        if(!$this->isFieldSet($data['post_items'] ?? null, 'EMPTY_FEATURED_IMAGE', 'register')) $isValid = false;

    }

    // Comments
    public function addCommentValidateField(array $data) : bool
    {
        
        if(!$this->isFormSubmitted($data) ){
            return false;
        }

        $isValid = true;
        if(!$this->validateField($data['post_id'], 'EMPTY_POST_ID','comments')) $isValid = false;
        if(!$this->validateField($data['comment_content'], 'EMPTY_COMMENT_CONTENT','comments')) $isValid = false;

        return $isValid;        
    }

    // Pages
    public function addPageValidateField( array $data) : bool
    {
        if(!$this->isFormSubmitted($data) ){
            return false;
        }

        $isValid = true;
        if(!$this->validateField($data['page_title'], 'EMPTY_PAGE_TITLE','pages')) $isValid = false;
        if(!$this->validateField($data['page_slug'], 'EMPTY_PAGE_SLUG','pages')) $isValid = false;
        if(!$this->validateField($data['page_content'], 'EMPTY_PAGE_CONENT','pages')) $isValid = false;
        if(!$this->isFieldSet($data['page_featured_image'] ?? null, 'EMPTY_FEATURED_IMAGE', 'register')) $isValid = false;

        return $isValid;
    }

    public function validateContactForm(array $data): bool
    {
        if (!$this->isFormSubmitted($data)) {
            return false;
        }
    
        $isValid = true;
        if (!$this->validateField($data['full_name'], 'EMPTY_NAME', 'contact')) $isValid = false;
        if (!$this->validateEmailField($data['email'], 'EMPTY_EMAIL', 'contact')) $isValid = false;
        if (!$this->validateField($data['subject'], 'EMPTY_SUBJECT', 'contact')) $isValid = false;
        if (!$this->validateField($data['message'], 'EMPTY_MESSAGE', 'contact')) $isValid = false;
        if (!$this->validateCheckbox($data['terms'] ?? null, 'TERMS_NOT_ACCEPTED', 'contact')) $isValid = false;
    
        return $isValid;
    }
    

    public function validatePasswordStrength(string $password, string $errorKey, string $domain) : bool
    {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)(?!.*\s).{8,}$/', $password)) 
        {
            $this->addError($errorKey, $domain);   
            return false;
        }
        return true;
    }

    
    // Valider les données pour modifier les informations de l'utilisateur
    public function validateUpdateUserData(array $data) : bool
    {

        if (!$this->isFormSubmitted($data)) {
            return false;
        }

        $isValid = true;
        if (!$this->validateField($data['firstname'], 'EMPTY_FIRSTNAME', 'users')) $isValid = false;
        if (!$this->validateField($data['lastname'], 'EMPTY_LASTNAME', 'users')) $isValid = false;
        if (!$this->isFieldSet($data['username'], 'EMPTY_USERNAME', 'users')) $isValid = false;
        if (!$this->isFieldSet($data['biography'], 'EMPTY_BIOGRAPHY', 'users')) $isValid = false;

        return $isValid;
    }
    
    public function validateOldPassword(array $data)
    {
        $isValid = true;
        if (!$this->validateField($data['firstname'], 'EMPTY_FIRSTNAME', 'users')) $isValid = false;
    }

    public function validateNewPassword(array $data)
    {
        $isValid = true;
        
        if (!isset($data['old_password']) || !$this->validateField($data['old_password'], 'EMPTY_OLD_PASSWORD', 'users')) {
            $isValid = false;
        }
        
        if (!isset($data['new_password']) || !$this->validateField($data['new_password'], 'EMPTY_NEW_PASSWORD', 'users')) {
            $isValid = false;
        }
        
        if (!isset($data['repeat_password']) || !$this->validateField($data['repeat_password'], 'EMPTY_REPEAT_PASSWORD', 'users')) {
            $isValid = false;
        }
    
        return $isValid;
    }
    
}

