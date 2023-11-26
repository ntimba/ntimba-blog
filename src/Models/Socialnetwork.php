<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

class Socialnetwork
{
    private $networkId;
    private $networkName;
    private $networkUrl;
    private $networkIconClass;


    // SETTERS
    public function setNetworkId(int $networkId) : void
    {        
        if(is_numeric($networkId) && !empty($networkId))
        {
            $this->networkId = $networkId;
        }
    }

    public function setNetworkName(string $networkName) : void
    {        
        if(is_string($networkName) && !empty($networkName))
        {
            $this->networkName = $networkName;
        }
    }

    public function setNetworkUrl(string $networkUrl) : void
    {        
        if(is_string($networkUrl) && !empty($networkUrl))
        {
            $this->networkUrl = $networkUrl;
        }
    }

    public function setNetworkIconClass(string $networkIconClass) : void
    {        
        if(is_string($networkIconClass) && !empty($networkIconClass))
        {
            $this->networkIconClass = $networkIconClass;
        }
    }


    // Getters 
    public function getNetworkId(): int
    {
        return $this->networkId;
    }

    public function getNetworkName(): string
    {
        return $this->networkName;
    }

    public function getNetworkUrl(): string
    {
        return $this->networkUrl; 
    }

    public function getNetworkIconClass(): string
    {
        return $this->networkIconClass;
    }

    
}



