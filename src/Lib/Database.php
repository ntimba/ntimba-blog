<?php
// Src/Lib/Database.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Lib;

use Portfolio\Ntimbablog\Service\EnvironmentService;

use \PDO;
use \PDOException;

/**
 * This class manages the connection to a database.
 * It utilizes the EnvironnementService class that retrieves environment variables.
 */
class Database
{
    private string $db_host;
    private string $db_name;
    private string $db_username;
    private string $db_password;
    public ?PDO $connection = null;
    private EnvironmentService $environmentService;

    public function __construct(EnvironmentService $environmentService)
    {
        $this->environmentService = $environmentService;

        $this->db_host = $this->environmentService->getDatabaseHost();
        $this->db_name = $this->environmentService->getDatabaseName();
        $this->db_username = $this->environmentService->getDatabaseUser();
        $this->db_password = $this->environmentService->getDatabasePass();

    }
    
    public function getConnection(): PDO
    {
        if( $this->connection === null ) {
            try {
                $this->connection = new PDO('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . ';charset=utf8', $this->db_username, $this->db_password);
            } catch (PDOException $e) {
                throw new \Exception("Database connection error : " . $e->getMessage());
            }
        }
        return $this->connection;
    }
}


