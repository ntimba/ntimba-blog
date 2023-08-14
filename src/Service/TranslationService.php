<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;

class TranslationService
{
    private $currentLanguage;

    public function __construct($language)
    {
        $this->currentLanguage = $language;
    }

    public function load($context)
    {
        $filePath = "./translations/{$this->currentLanguage}/{$context}.php";
        if (file_exists($filePath)) {
            return include $filePath;
        }
        throw new \Exception("Translation file not found: {$filePath}");
    }

    public function get($key, $context)
    {
        $translations = $this->load($context);
        return $translations[$key] ?? $key;
    }
}


