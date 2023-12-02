<?php
declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

class Settings
{
    private int $id = 0;
    private string $blogName;
    private string $blogDescription;
    private ?string $logoPath = null;
    private string $contactEmail;
    private string $defaultLanguage = 'en';
    private string $timezone;
    private ?string $analyticsId = null;
    private ?string $footerText;
    private bool $maintenanceMode = false;

    // Setters
    public function setId(int $id) : void
    {
        $this->id = $id;
    }

    public function setBlogName(string $blogName) : void 
    {
        $this->blogName = $blogName;
    }

    public function setBlogDescription(string $blogDescription) : void 
    {
        $this->blogDescription = $blogDescription;
    }

    public function setLogoPath(string $logoPath) : void 
    {
        $this->logoPath = $logoPath;
    }

    public function setContactEmail(string $contactEmail) : void 
    {
        $this->contactEmail = $contactEmail;
    }

    public function setDefaultLanguage(string $defaultLanguage) : void 
    {
        $this->defaultLanguage = $defaultLanguage;
    }

    public function setTimezone(string $timezone) : void 
    {
        $this->timezone = $timezone;
    }

    public function setAnalyticsId(?string $analyticsId) : void 
    {
        $this->analyticsId = $analyticsId;
    }

    public function setFooterText(string $footerText) : void 
    {
        $this->footerText = $footerText;
    }

    public function setMaintenanceMode(string $maintenanceMode) : void 
    {
        $this->maintenanceMode = $maintenanceMode;
    }

    // Getter
    public function getId() : int
    {
        return $this->id;
    }

    public function getBlogName() : string
    {
        return $this->blogName;
    }

    public function getBlogDescription() : string
    {
        return $this->blogDescription;
    }

    public function getLogoPath() : ?string
    {
        return $this->logoPath;
    }

    public function getContactEmail() : string
    {
        return $this->contactEmail;
    }

    public function getDefaultLanguage() : string
    {
        return $this->defaultLanguage;
    }

    public function getTimezone() : string
    {
        return $this->timezone;
    }

    public function getAnalyticsId() : ?string
    {
        return isset($this->analyticsId) ? $this->analyticsId : null;
    }

    public function getFooterText() : ?string
    {
        return isset($this->footerText) ? $this->footerText : null;
    }   
    
    public function getMaintenanceMode() : bool 
    {
        return $this->maintenanceMode;
    }
}



