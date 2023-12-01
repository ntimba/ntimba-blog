<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Helpers;

use Portfolio\Ntimbablog\http\SessionManager;
use Portfolio\Ntimbablog\Service\TranslationService;

class ErrorHandler {
    private $sessionManager;
    private array $errors = [];

    private $translationService;

    public function __construct(SessionManager $sessionManager, TranslationService $translationService)
    {
        $this->sessionManager = $sessionManager;
        $this->translationService = $translationService;
    }

    public function addError(string $message, string $type = 'primary') : void
    {
        $this->errors[] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public function addFlashMessage(string $message, string $type = 'primary') : void
    {
        $flashMessages = $this->sessionManager->get('flash_messages', []);

        $flashMessages[] = [
            'message' => $message,
            'type' => $type
        ];
        $this->sessionManager->set('flash_messages', $flashMessages);
    }

    public function displayErrors() : string
    {
        $output = '';
        foreach ($this->errors as $error) {
            $output .= '<div class="alert-dismissible fade show alert alert-' . $error['type'] . '" role="alert">' . $error['message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' . '</div>';
        }

        $flashMessages = $this->sessionManager->get('flash_messages', []);

        foreach ($flashMessages as $flash) {
            $output .= '<div class="alert-dismissible fade show alert alert-' . $flash['type'] . '" role="alert">' . $flash['message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' . '</div>';
        }
        
        $this->sessionManager->remove('flash_messages');
        
        return $output;
    }

    public function addMessage(string $messageCode, string $domain, string $type) : void
    {
        $message = $this->translationService->get($messageCode,$domain);
        $this->addFlashMessage($message, $type);
    }
     
}



