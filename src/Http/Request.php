<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Http;

class Request
{
    private array $postData;
    private array $getData;
    private array $fileData;
    private array $serverData;

    public function __construct(array $postData = [], array $getData = [], array $fileData = [], array $serverData = [])
    {
        $this->postData = $postData;
        $this->getData = $getData;
        $this->fileData = $fileData;
        $this->serverData = $serverData;
    }

    public function getAllPost() : mixed
    {
        return $this->postData;
    }

    public function post($key, $default = null) : mixed
    {
        return $this->postData[$key] ?? $default;
    }

    public function get($key, $default = null) : mixed
    {
        return htmlspecialchars($this->getData[$key] ?? $default);
    }

    public function file($key, $default = null) : mixed
    {
        return $this->fileData[$key] ?? $default;
    }

    public function hasFile($key) : bool
    {
        return isset($this->fileData[$key]) && $this->fileData[$key]['error'] == 0;
    }

    public function getDomainName() : string
    {
        return $this->serverData['HTTP_HOST'] ?? 'localhost';
    }

    public function getProtocol()
    {
        return (!empty($this->serverData['HTTPS']) && $this->serverData['HTTPS'] !== 'off' || $this->serverData['SERVER_PORT'] == 443) ? "https://" : "http://";
    }

    public function getDocumentRoot() : string
    {
        return $this->serverData['DOCUMENT_ROOT'];
    }
    
}
