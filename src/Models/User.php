<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use \OutOfBoundsException;
use \InvalidArgumentException;

class User
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private ?string $username;
    private string $email;
    private string $password;
    private string $registrationDate;
    private string $role;
    private ?string $token;
    private ?string $profilePicture;
    private ?string $biography;
    private bool $status;
    private bool $auditedAccount;

    private array $errors = [];

    protected const INVALID_ID = "Le format de l'identifiant est invalid";
    protected const INVALID_FIRSTNAME = "Le format de firstname est invalid";
    protected const INVALID_LASTNAME = "Le format de lastname est invalid";
    protected const INVALID_USERNAME = "Le format du nom d'utilisateur est invalid";
    protected const INVALID_EMAIL = "Le format d'email est invalid";
    protected const INVALID_PASSWORD = "Le format de password est invalid";
    protected const INVALID_REGISTRATION_DATE = "Le format de registration_date est invalid";
    protected const INVALID_ROLE = "Le format de role est invalid";
    protected const INVALID_TOKEN = "Le format de token est invalid";
    protected const INVALID_PROFILE_PICTURE = "Le format de profile_picture est invalid";
    protected const INVALID_BIOGRAPHY = "Le format de biography est invalid";
    protected const INVALID_STATUT = "Le format de statut est invalid";
    protected const INVALID_AUDITED_ACCOUNT = "Le format de audited_account est invalid";
    protected const DO_NOT_EXCEED_1 = "Le paramètre ne doit pas dépasser 1.";
    protected const BOOLEAN_OR_INTEGER = "Le paramètre doit être un booléen ou un entier.";
    
    public function __construct( array $userdata = [])
    {
        $this->hydrate($userdata);
    }

    public function hydrate(array $data) : void
    {
        foreach ($data as $attribut => $value) 
        {
            $setters = 'set'. ucfirst($attribut);
            $this->$setters($value);
        }
    }
    

    /*****************************
     *          SETTERS          *
     *****************************/

    public function setId(int $id) : void
    {
        if(is_numeric($id) && !empty($id))
        {
            $this->id = $id;
        }else {
            $this->errors[] = self::INVALID_ID;
        }
    }

    public function setFirstname(string $firstName) : void
    {
        if( is_string( $firstName ) && !empty($firstName) )
        {
            $this->firstName = $firstName;
        } else {
            $this->errors[] = self::INVALID_FIRSTNAME;
        } 
    }

    public function setLastname(string $lastName) : void
    {
        if( is_string( $lastName ) && !empty($lastName) )
        {
            $this->lastName = $lastName;
        } else {
            $this->errors[] = self::INVALID_LASTNAME;
        } 
    }

    public function setUsername(?string $username = null) : void
    {
        if (is_string($username) && !empty($username)) {
            $this->username = $username;
        } elseif ($username === null) {
            $this->username = null;
        } else {
            $this->errors[] = self::INVALID_USERNAME;
        }
    }
    
    public function setEmail(string $email) : void
    {
        if( is_string( $email ) && !empty($email) )
        {
            $this->email = $email;
        } else {
            $this->errors[] = self::INVALID_EMAIL;
        } 
    }

    public function setPassword(string $pass) : void
    {
        if( is_string( $pass ) && !empty($pass) )
        {
            $this->password = $pass;
        } else {
            $this->errors[] = self::INVALID_PASSWORD;
        }
    }

    public function setRegistrationDate(string $registrationDate) : void
    {
        if( is_string( $registrationDate ) && !empty($registrationDate) )
        {
            $this->registrationDate = $registrationDate;
        } else {
            $this->errors[] = self::INVALID_REGISTRATION_DATE;
        } 
    }

    public function setRole(string $role) : void
    {
        if( is_string( $role ) && !empty($role) )
        {
            $this->role = $role;
        } else {
            $this->errors[] = self::INVALID_ROLE;
        } 
    }

    public function setToken(?string $token): void
    {
        if ($token === '') {
            throw new \Exception(self::INVALID_TOKEN);
        }
        $this->token = $token;
    }    

    public function setProfilePicture(?string $profilePicture): void
    {
        if ($profilePicture === null) {
            $this->profilePicture = $profilePicture;
            return;
        }
    
        if (is_string($profilePicture) && !empty($profilePicture)) {
            $this->profilePicture = $profilePicture;
        } else {
            $this->errors[] = self::INVALID_PROFILE_PICTURE;
        }
    }
    
    public function setBiography(?string $biography): void
    {
        $this->biography = $biography;
    
        if (!is_string($biography) && !is_null($biography)) {
            $this->errors[] = self::INVALID_BIOGRAPHY;
        }
    }

    public function setStatus(bool $status): void
    {
        if (is_bool($status) || is_int($status)) {
            if ($status <= 1) {
                $this->status = $status;
            } else {
                throw new OutOfBoundsException(self::DO_NOT_EXCEED_1);
            }
        } else {
            throw new InvalidArgumentException(self::BOOLEAN_OR_INTEGER);
        }
    }
    
    public function setAuditedAccount(bool $audited): void
    {
        if (is_bool($audited)) {
            $this->auditedAccount = $audited;
        } elseif (is_int($audited)) {
            $this->auditedAccount = ($audited === 1);
        } else {
            $this->errors[] = self::INVALID_AUDITED_ACCOUNT;
        }
    }
    

    /*****************************
     *          GETTERS          *
     *****************************/
    public function getId() : int
    {
        return $this->id;
    }

    public function getFirstname() : string 
    {
        return $this->firstName;
    }

    public function getLastname() : string
    {
        return $this->lastName;
    }

    public function getUsername() : ?string
    {
        return isset($this->username) ? $this->username : null;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getRegistrationdate() : string
    {
        return $this->registrationDate;
    }

    public function getRole() : string
    {
        return $this->role;
    }

    public function getToken() : ?string
    {
        return $this->token;
    }

    public function getProfilePicture() : ?string
    {
        return $this->profilePicture;
    }

    public function getBiography() : ?string
    {
        return $this->biography;
    }

    public function getStatus() : int
    {
        return isset( $this->status ) ? (int) $this->status : 0;
    }

    public function getAuditedaccount() : int
    {
        return isset( $this->auditedAccount ) ? (int) $this->auditedAccount : 0;
    }

    public function isEmailValid(string $email) : bool
    {
        if( filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            return true;
        } else {
            return false;
        }
    }

    public function getFullName() : string
    {
        return $this->firstName . ' ' . $this->lastName;
    }  
}


