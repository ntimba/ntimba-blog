<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Helpers;

class ErrorHandler {
    private array $errors = [];

    public function addError(string $message, string $type = 'primary') : void
    {
        $this->errors[] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public function addFlashMessage(string $message, string $type = 'primary') : void
    {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        $_SESSION['flash_messages'][] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public function displayErrors() : string
    {
        $output = '';
        foreach ($this->errors as $error) {
            $output .= '<div class="alert-dismissible fade show alert alert-' . $error['type'] . '" role="alert">' . $error['message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' . '</div>';
        }
        if (isset($_SESSION['flash_messages'])) {
            foreach ($_SESSION['flash_messages'] as $flash) {
                $output .= '<div class="alert-dismissible fade show alert alert-' . $flash['type'] . '" role="alert">' . $flash['message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' . '</div>';
            }
            unset($_SESSION['flash_messages']);
        }
        return $output;
    }
}
