<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;
use Portfolio\Ntimbablog\Http\Request;

/**
 * EnvironmentService class is responsible for retrieving environment variables.
 * It utilizes the Request class to encapsulate HTTP request data, ensuring that
 * environment variables such as database configurations are accessed in a safe
 * and abstracted manner, rather than relying on global state.
 */
class EnvironmentService {

    private Request $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getDatabaseHost(): string
    {
        return $this->request->getServerVariable('DB_HOST') ?? 'default_host';
    }

    public function getDatabaseUser(): string 
    {
        return $this->request->getServerVariable('DB_USERNAME') ?? 'default_user';
    }

    public function getDatabaseName(): string
    {
        return $this->request->getServerVariable('DB_NAME') ?? 'default_database';
    }

    public function getDatabasePass(): string 
    {
        return $this->request->getServerVariable('DB_USER_PASSWORD') ?? 'default_password';
    }

    public function getDatabasePort(): int
    {
        return (int)($this->request->getServerVariable('DB_PORT') ?? 3306);
    }

    public function getMailHost(): string
    {
        return $this->request->getServerVariable('MAIL_HOST') ?? 'smtp.mailserver.com';
    }

    public function getMailUsername(): string
    {
        return $this->request->getServerVariable('MAIL_USERNAME') ?? 'hello@ntimba.me';
    }

    public function getMailPassword(): string
    {
        return $this->request->getServerVariable('MAIL_PASSWORD') ?? 'password';
    }

    public function getMailPort(): int
    {
        return $this->request->getServerVariable('MAIL_PORT') ?? 465;
    }
}




