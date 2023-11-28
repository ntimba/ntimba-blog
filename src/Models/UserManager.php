<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use Portfolio\Ntimbablog\lib\Database;
use \PDO;


class UserManager
{    
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
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

    // public function getAdminId()

    public function usernameExist( string $username ): int
    {
        $query = 'SELECT user_id FROM users WHERE username = :username';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":username", $username);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['user_id'] ?? 0;
    }

    public function read( int $id ): object | bool
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

    public function getTotalUsersCount() : float 
    {
        $statement = $this->db->getConnection()->query("SELECT COUNT(*) as total FROM users");
        $totalUsers = $statement->fetchColumn();
        return $totalUsers; 
    }

    public function getUsersByPage(int $offset, int $limit): array|bool
    {   
        if($offset < 0){
            $offset = 0;
        }     
        
        $query = 'SELECT user_id, first_name, last_name, email, password, registration_date, role, token, profile_picture, biography, status, audited_account FROM users LIMIT :offset, :limit';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $this->db->getConnection()->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

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
            $user->setFirstname($userData['first_name']);
            $user->setLastname($userData['last_name']);
            $user->setEmail($userData['email']);
            $user->setPassword($userData['password']);
            $user->setRegistrationDate($userData['registration_date']);
            $user->setRole($userData['role']);
            $user->setToken($userData['token']);
            $user->setProfilePicture($userData['profile_picture']);
            $user->setBiography($userData['biography']);
            $user->setStatus((bool)$userData['status']);
            $user->setAuditedAccount((bool)$userData['audited_account']);
            $users[] = $user;
        }
        return $users;
    }

    public function getAllUsers(): array|bool
    {
        $query = 'SELECT user_id, first_name, last_name, email, password, registration_date, role, token, profile_picture, biography, status, audited_account FROM users';
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
            $user->setFirstname($userData['first_name']);
            $user->setLastname($userData['last_name']);
            $user->setEmail($userData['email']);
            $user->setPassword($userData['password']);
            $user->setRegistrationDate($userData['registration_date']);
            $user->setRole($userData['role']);
            $user->setToken($userData['token']);
            $user->setProfilePicture($userData['profile_picture']);
            $user->setBiography($userData['biography']);
            $user->setStatus((bool)$userData['status']);
            $user->setAuditedAccount((bool)$userData['audited_account']);
            $users[] = $user;
        }
        

        return $users;
    }

    public function insertUser(object $newuser) : void
    {
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

    public function update(User $user): bool
    {
        try{
            $query = 'UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, username = :username, password = :password, registration_date = :registration_date, role = :role, token = :token, profile_picture = :profile_picture, biography = :biography, status = :status, audited_account = :audited_account WHERE user_id = :user_id';
            $statement = $this->db->getConnection()->prepare($query);
            $statement->execute([
                'user_id' => $user->getId(),
                'first_name' => $user->getFirstname(),
                'last_name' => $user->getLastname(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'role' => $user->getRole(),
                'registration_date' => $user->getRegistrationDate(),
                'token' => $user->getToken(),
                'profile_picture' => $user->getProfilePicture(),
                'biography' => $user->getBiography(),
                'status' => $user->getStatus(),
                'audited_account' => $user->getAuditedAccount()
            ]);

            return true;
        } catch (\Exception $e){
            return false;
        }
    }

    public function delete( int $userId ): void
    {
        $query = 'DELETE FROM users WHERE user_id = :user_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'user_id' => $userId
        ]);
    }

    public function getTotalActiveUsers() : int
    {
        $allUsers = $this->getAllUsers();
        $activeUsers = [];
        foreach( $allUsers as $user ){
            if( $user->getStatus() ){
                $activeUsers[] = $user;
            }
        }
        
        return count($activeUsers);
    }

    public function verifyPassword(string $providedPassword, string $storedHash): bool
    {
        return password_verify($providedPassword, $storedHash);
    }

    public function confirmAccount(int $userId) : void
    {
        $query = 'UPDATE users SET status = 1, audited_account = 1, token = NULL WHERE user_id = :user_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute(['user_id' => $userId]);
    }

}


