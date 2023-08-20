<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;

class TranslationService
{
    private $currentLanguage;

    public function __construct(string $language)
    {
        $this->currentLanguage = $language;
    }

    public function load(string $context) : mixed
    {
        $filePath = "./translations/{$this->currentLanguage}/{$context}.php";
        if (file_exists($filePath)) {
            return include $filePath;
        }
        throw new \Exception("Translation file not found: {$filePath}");
    }

    public function get(string $key, string $context) : string
    {
        $translations = $this->load($context);
        return $translations[$key] ?? $key;
    }
}


