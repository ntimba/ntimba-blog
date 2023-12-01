<?php 

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Http;

use \RedirectException;

class HttpResponse {

    private $headers = [];
    private $content;
    private $statusCode = 200;

    public function redirect(string $url): void 
    {
        $this->setHeader('Location', $url);
        $this->setStatusCode(302);
        $this->send();
        return;
    }

    public function setHeader(string $name, string $value) : void
    {
        $this->headers[$name] = $value;
    }

    public function setContent(string $content) : void
    {
        $this->content = $content;
    }

    public function setStatusCode(int $code) : void
    {
        $this->statusCode = $code;
    }

    public function send() : void
    {
        http_response_code($this->statusCode);
        
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        echo $this->content;
    }
}
