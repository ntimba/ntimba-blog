<?php
// Src/Lib/Database.php

declare(strict_types=1);

namespace Ntimbablog\Portfolio\Lib;

use \PDO;

class Database
{
    private $host = 'mysql';
    private $db_name = 'ntimbablog';
    private $username = 'root';
    private $password = 'rootpassword';
    public ?PDO $connection = null;

    public function getConnection(): PDO
    {
        if( $this->connection === null ) {
            $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8', $this->username, $this->password);
        }

        return $this->connection;
    }
}
