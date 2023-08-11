<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use Portfolio\Ntimbablog\lib\Database;
use \PDO;


class UserManager
{    
    private Database $db;

    public function __construct(){
        $this->db = new Database();
    }

    // Get user ID
    public function getUserId( string $email ): int
    {
        $query = 'SELECT user_id FROM users WHERE email = :email';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":email", $email);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['user_id'] ?? 0;

    }

    public function getUser( int $id ): object | bool
    {
        $query = 'SELECT user_id, first_name, last_name, email, username, password, registration_date, role, token, profile_picture, biography, status, audited_account FROM users WHERE user_id = :user_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'user_id' => $id
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $result === false ) {
            return false;
        }

        $user = new User();
        $user->setId($result['user_id']);
        $user->setFirstname($result['first_name']);
        $user->setLastname($result['last_name']);
        $user->setEmail($result['email']);
        $user->setUsername($result['username']);
        $user->setPassword($result['password']);
        $user->setRegistrationDate($result['registration_date']);
        $user->setRole($result['role']);
        $user->setToken($result['token']);
        $user->setProfilePicture($result['profile_picture']);
        $user->setBiography($result['biography']);
        $user->setStatus((bool)$result['status']);
        $user->setAuditedAccount((bool)$result['audited_account']);
        
        return $user;
    }

    public function getAllUsers(): array|bool
    {
        $query = 'SELECT user_id, user_firstname, user_lastname, user_email, user_password, user_registration_date, user_role, user_token, user_profile_picture, user_biography, user_statut, user_audited_account FROM user';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();

        $usersData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $usersData === false ) {
            return false;
        }

        $users = [];

        foreach( $usersData as $userData )
        {
            $user = new User();
            $user->setId($userData['user_id']);
            $user->setFirstname($userData['user_firstname']);
            $user->setLastname($userData['user_lastname']);
            $user->setEmail($userData['user_email']);
            $user->setPassword($userData['user_password']);
            $user->setRegistrationDate($userData['user_registration_date']);
            $user->setRole($userData['user_role']);
            $user->setToken($userData['user_token']);
            $user->setProfilePicture($userData['user_profile_picture']);
            $user->setBiography($userData['user_biography']);
            $user->setStatus((bool)$userData['user_statut']);
            $user->setAuditedAccount((bool)$userData['user_audited_account']);
            $users[] = $user;
        }
        

        return $users;
    }

    public function insertUser(object $newuser) : void
    {
        // code
        $query = 'INSERT INTO users(first_name, last_name, email, username, password, registration_date, role, token, profile_picture, biography, status, audited_account) 
                           VALUES(:first_name, :last_name, :email, :username, :password, NOW(), :role, :token, :profile_picture, :biography, :status, :audited_account)';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'first_name' => $newuser->getFirstname(),
            'last_name' => $newuser->getLastname(),
            'email' => $newuser->getEmail(),
            'username' => $newuser->getUsername(),
            'password' => $newuser->getPassword(),
            'role' => $newuser->getRole(), 
            'token' => $newuser->getToken(),
            'profile_picture' => $newuser->getProfilePicture(),
            'biography' => NULL,
            'status' => $newuser->getStatus(),
            'audited_account' => 0
        ]);
    }

    public function updateUser(User $user): void
    {
        $query = 'UPDATE user SET user_firstname = :user_firstname, user_lastname = :user_lastname, user_email = :user_email, user_password = :user_password, user_registration_date = :user_registration_date, user_role = :user_role, user_token = :user_token, user_profile_picture = :user_profile_picture, user_biography = :user_biography, user_statut = :user_statut, user_audited_account = :user_audited_account WHERE user_id = :user_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'user_id' => $user->getId(),
            'user_firstname' => $user->getFirstname(),
            'user_lastname' => $user->getLastname(),
            'user_email' => $user->getEmail(),
            'user_password' => $user->getPassword(),
            'user_role' => $user->getRole(),
            'user_registration_date' => $user->getRegistrationDate(),
            'user_token' => $user->getToken(),
            'user_profile_picture' => $user->getProfilePicture(),
            'user_biography' => $user->getBiography(),
            'user_statut' => $user->getStatus(),
            'user_audited_account' => $user->getAuditedAccount(),
        ]);
    }

    public function deleteUser( int $userId ): void
    {
        $query = 'DELETE FROM user WHERE user_id = :user_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'user_id' => $userId
        ]);
    }
}

