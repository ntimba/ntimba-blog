<?php
// Src/Lib/Database.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Lib;

use \PDO;
use \PDOException;

class Database
{
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    public ?PDO $connection = null;

    public function __construct()
    {
        $this->host = 'mysql';
        $this->db_name = 'ntimbablog';
        $this->username = isset($_SERVER['MYSQL_USER']) ? $_SERVER['MYSQL_USER'] : 'user';
        $this->password = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'password';
        

        // $this->username = 'root';
        // $this->password = 'rootpassword';
    }
    
    public function getConnection(): PDO
    {
        if( $this->connection === null ) {
            try {
                $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8', $this->username, $this->password);
            } catch (PDOException $e) {
                // Ici, vous pouvez gérer l'exception comme vous le souhaitez, par exemple en affichant un message d'erreur.
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        return $this->connection;
    }
}
