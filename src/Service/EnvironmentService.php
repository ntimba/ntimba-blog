<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;

class EnvironmentService {

    public function getDatabaseUser() : string 
    {
        return $_SERVER['MYSQL_USER'] ?? '';
    }

    public function getDatabasePass() : string 
    {
        return $_SERVER['MYSQL_PASSWORD'] ?? '';
    }
}




