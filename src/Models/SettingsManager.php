<?php
declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;
use Portfolio\Ntimbablog\Lib\Database;
use \PDO;

class SettingsManager
{
    private Database $db;


    public function __construct(Database $db){
        $this->db = $db;
    }

    public function getSettingId(string $blogName) : int
    {
        $query = 'SELECT setting_id FROM settings WHERE blog_name = :blog_name';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":blog_name", $blogName);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['setting_id'] ?? 0;  
    }

    public function getSetting(int $id) : mixed
    {
        $query = 'SELECT setting_id, blog_name, blog_description, logo_path, contact_email, timezone, default_language, analytics_id, footer_text, maintenance_mode FROM settings WHERE setting_id = :setting_id';

        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'setting_id' => $id
        ]);

        $settingData = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $settingData === false ) {
            return false;
        }
        
        $setting = new Settings();
        $setting->setId($settingData['setting_id']);
        $setting->setBlogName($settingData['blog_name']);
        $setting->setBlogDescription($settingData['blog_description']);
        $setting->setLogoPath($settingData['logo_path']);
        $setting->setContactEmail($settingData['contact_email']);
        $setting->setTimezone($settingData['timezone']);
        $setting->setDefaultLanguage($settingData['default_language']);
        $setting->setAnalyticsId($settingData['analytics_id']);
        $setting->setFooterText($settingData['footer_text']);
        $setting->setMaintenanceMode($settingData['maintenance_mode']);

        return $setting;
    }

    public function insertSettings(object $setting) : void
    {
        // code
        $query = 'INSERT INTO settings(blog_name, blog_description, logo_path, contact_email, timezone, default_language, analytics_id, footer_text, maintenance_mode ) 
                  VALUES(:blog_name, :blog_description, :logo_path, :contact_email, :timezone, :default_language, :analytics_id, :footer_text, :maintenance_mode)';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'blog_name' => $setting->getBlogName(),
            'blog_description' => $setting->getBlogDescription(),
            'logo_path' => $setting->getLogoPath(),
            'contact_email' => $setting->getContactEmail(),
            'timezone' => $setting->getTimezone(),
            'default_language' => $setting->getDefaultLanguage(),
            'analytics_id' => $setting->getAnalyticsId(),
            'footer_text' => $setting->getFooterText(),
            'maintenance_mode' => $setting->getMaintenanceMode() ? 1 : 0 // convert bool to integer
        ]);
    }


    public function updateSetting(object $setting) : void
    {
        // code
        $query = 'UPDATE settings SET 
            blog_name = :blog_name, 
            blog_description = :blog_description,
            logo_path = :logo_path,
            contact_email = :contact_email,
            timezone = :timezone,
            default_language = :default_language,
            analytics_id = :analytics_id,
            footer_text = :footer_text,
            maintenance_mode = :maintenance_mode
            WHERE setting_id = :setting_id
        ';

        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'setting_id' => $setting->getId(),
            'blog_name' => $setting->getBlogName(),
            'blog_description' => $setting->getBlogDescription(),
            'logo_path' => $setting->getLogoPath(),
            'contact_email' => $setting->getContactEmail(),
            'timezone' => $setting->getTimezone(),
            'default_language' => $setting->getDefaultLanguage(),
            'analytics_id' => $setting->getAnalyticsId(),
            'footer_text' => $setting->getFooterText(),
            'maintenance_mode' => $setting->getMaintenanceMode() ? 1 : 0 // convert bool to integer
        ]);
    }
}



