<?php
declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use Portfolio\Ntimbablog\Models\Socialnetwork;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Helpers\StringUtil;
use \PDO;

class SocialnetworkManager
{
    private Database $db;
    private StringUtil $stringUtil;

    public function __construct(Database $db){
        $this->db = $db;

        $this->stringUtil = new StringUtil();
    }

    public function getNetworkId(string $name) : int
    {
        $query = 'SELECT link_id FROM admin_social_links WHERE name = :name';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":name", $name);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['link_id'] ?? 0;  
    }

    public function read(int $id) : mixed
    {
        $query = 'SELECT link_id, name, url, icon_class FROM admin_social_links WHERE link_id = :link_id';

        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'link_id' => $id
        ]);

        $networkData = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $networkData === false ) {
            return false;
        }
        
        $network = new Socialnetwork();
        $network->setNetworkId($networkData['link_id']);
        $network->setNetworkName($networkData['name']);
        $network->setNetworkUrl($networkData['url']);
        $network->setNetworkIconClass($networkData['icon_class']);

        return $network;
    }


    public function create(object $network) : bool
    {
        // code
        $query = 'INSERT INTO admin_social_links(name, url, icon_class) 
                  VALUES(:name, :url, :icon_class)';
        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
            'name' => $network->getNetworkName(),
            'url' => $network->getNetworkUrl(),
            'icon_class' => $network->getNetworkIconClass()
        ]);
    }

    public function update(object $network) : bool
    {
        $query = 'UPDATE admin_social_links SET 
            name = :name, 
            url = :url,
            icon_class = :icon_class
            WHERE link_id = :link_id
        ';

        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
            'link_id' => $network->getNetworkId(),
            'name' => $network->getNetworkName(),
            'url' => $network->getNetworkUrl(),
            'icon_class' => $network->getNetworkIconClass()
        ]);
    }

    public function getAll() : mixed
    {
        $query = 'SELECT link_id, name, url, icon_class FROM admin_social_links';

        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();

        $networksData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $networksData === false ) {
            return false;
        }
        
        $networks = [];
        foreach($networksData as $networkData){
            $network = new Socialnetwork();
            $network->setNetworkId($networkData['link_id']);
            $network->setNetworkName($networkData['name']);
            $network->setNetworkUrl($networkData['url']);
            $network->setNetworkIconClass($networkData['icon_class']);

            $networks[] = $network;
        }
        
        return $networks;
    }


    public function delete(int $id): bool
    {
        $query = 'DELETE FROM admin_social_links WHERE link_id = :id';
        $statement = $this->db->getConnection()->prepare($query);
        
        return $statement->execute([
            'id' => $id
        ]);
    }
    
    
}



