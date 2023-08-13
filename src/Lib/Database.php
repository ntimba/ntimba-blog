<?php
// Src/Lib/Database.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Lib;

use Portfolio\Ntimbablog\Service\EnvironmentService;

use \PDO;
use \PDOException;

class Database
{
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    public ?PDO $connection = null;
    private EnvironmentService $environmentService;

    public function __construct(EnvironmentService $environmentService)
    {
        $this->environmentService = $environmentService;

        $this->host = 'mysql';
        $this->db_name = 'ntimbablog';
        $this->username = $this->environmentService->getDatabaseUser();
        $this->password = $this->environmentService->getDatabasePass();
        
        
    }
    
    public function getConnection(): PDO
    {
        if( $this->connection === null ) {
            try {
                $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8', $this->username, $this->password);
            } catch (PDOException $e) {
                // Ici, vous pouvez gérer l'exception comme vous le souhaitez, par exemple en affichant un message d'erreur.
                throw new \Exception("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return $this->connection;
    }
}
