<?php

declare(strict_types=1);

namespace Ntimbablog\Portfolio\Models;

use Ntimbablog\Portfolio\lib\Database;
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
        $query = 'SELECT user_id FROM user WHERE user_email = :user_email';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":user_email", $email);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['user_id'] ?? 0;

    }

    public function getUser( int $id ): object | bool
    {
        $query = 'SELECT user_id, user_firstname, user_lastname, user_email, user_password, user_registration_date, user_role, user_token, user_profile_picture, user_biography, user_statut, user_audited_account FROM user WHERE user_id = :user_id';
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
        $user->setFirstname($result['user_firstname']);
        $user->setLastname($result['user_lastname']);
        $user->setEmail($result['user_email']);
        $user->setPassword($result['user_password']);
        $user->setRegistrationDate($result['user_registration_date']);
        $user->setRole($result['user_role']);
        $user->setToken($result['user_token']);
        $user->setProfilePicture($result['user_profile_picture']);
        $user->setBiography($result['user_biography']);
        $user->setStatut((bool)$result['user_statut']);
        $user->setAuditedAccount((bool)$result['user_audited_account']);
        
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
            $user->setStatut((bool)$userData['user_statut']);
            $user->setAuditedAccount((bool)$userData['user_audited_account']);
            $users[] = $user;
        }
        

        return $users;
    }

    public function createUser(object $newuser) : void
    {
        // code
        $query = 'INSERT INTO user(user_firstname, user_lastname, user_email, user_password, user_registration_date, user_role, user_token, user_profile_picture, user_biography, user_statut, user_audited_account) 
                  VALUES(:user_firstname, :user_lastname, :user_email, :user_password, NOW(), :user_role, :user_token, :user_profile_picture, :user_biography, :user_statut, :user_audited_account)';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'user_firstname' => $newuser->getFirstname(),
            'user_lastname' => $newuser->getLastname(),
            'user_email' => $newuser->getEmail(),
            'user_password' => $newuser->getPassword(),
            'user_role' => $newuser->getRole(), 
            'user_token' => $newuser->getToken(),
            'user_profile_picture' => $newuser->getProfilePicture(),
            'user_biography' => NULL,
            'user_statut' => $newuser->getStatut(),
            'user_audited_account' => 0
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
            'user_statut' => $user->getStatut(),
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

