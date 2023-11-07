<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use \DateTime;
use Portfolio\Ntimbablog\Helpers\StringUtil;

abstract class Content
{
    protected int $id;
    protected string $title;
    protected string $slug;
    protected ?string $featuredImagePath = NULL;
    protected string $content;
    protected string $publicationDate = '';
    protected ?string $updateDate = null;
    protected bool $status;
    protected int $userId;
    private StringUtil $stringUtil;

    public function __construct(StringUtil $stringUtil, array $userdata = [])
    {
        $this->stringUtil = $stringUtil;
        $this->hydrate($userdata);
    }

    public function hydrate(array $data) : void
    {
        foreach ($data as $attribut => $value) {
            $setter = 'set'. ucfirst($attribut);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    // les setters
    public function setId(int $id) : void
    {
        if(is_numeric($id) && !empty($id))
        {
            $this->id = $id;
        }
    }
    
    public function setTitle(string $title) : void
    {
        if( is_string( $title ) && !empty($title) )
        {
            $this->title = $title;
        }
    }

    public function setSlug(string $slug) : void
    {
        if( is_string( $slug ) && !empty($slug) )
        {
            $this->slug = $slug;
            $slugWithoutSpecialCharacters = $this->stringUtil->removeAccentsAndSpecialCharacters($slug);
            $slugWithoutSpaces = $this->stringUtil->removeStringsSpaces($slugWithoutSpecialCharacters);
            $this->slug = strtolower($slugWithoutSpaces); 
        } 
    }

    public function setFeaturedImagePath(?string $featuredImagePath) : void
    {
        if( is_string( $featuredImagePath ) || $featuredImagePath === '' )
        {
            $this->featuredImagePath = $featuredImagePath;
        }
    }
    
    public function setContent(string $content) : void
    {
        if( is_string( $content ) && !empty($content) )
        {
            $this->content = $content;
        }
    }

    public function setPublicationDate(string $publicationDate) : void
    {
        if( is_string( $publicationDate ) && !empty($publicationDate) )
        {
            $this->publicationDate = $publicationDate;
        }
    }

    public function setUpdateDate(?string $updateDate) : void
    {
        if( is_string( $updateDate ) && !empty($updateDate) )
        {
            $this->updateDate = $updateDate;  
        } 
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }
    
    public function setUserId(int $userId) : void
    {
        if( is_numeric( $userId ) && !empty($userId) )
        {
            $this->userId = $userId;
        }
    }   

    // les setters
    public function getId() : int
    {
        return $this->id;
    }

    public function getTitle() : string 
    {
        return $this->title;
    }

    public function getSlug() : string
    {
        return $this->slug;
    }

    public function getFeaturedImagePath() : ?string
    {
        return $this->featuredImagePath;
    } 

    public function getContent() : string
    {
        return $this->content;
    }

    public function getPublicationDate() : ?string
    {
        return $this->publicationDate;
    }

    public function getUpdateDate() : ?string
    {
        return $this->updateDate;
    }

    public function getStatus() : bool
    {
        return $this->status;
    }

    public function getUserId() : int
    {
        return $this->userId;
    }

}


