<?php

declare(strict_types=1);

namespace Ntimbablog\Portfolio\Models;
use \DateTime;

class Post
{
    private int $id;
    private string $title;
    private string $content;
    private string $creationDate = '';
    private ?string $updateDate;
    private string $slug;
    private int $categoryId;
    private int $userId;
    private ?string $featuredImagePath = NULL;
        
    public function __construct( array $userdata = [])
    {
        $this->hydrate($userdata);
    }

    // hydrater
    public function hydrate(array $data) : void
    {
        foreach ($data as $attribut => $value) {
            $setters = 'set'. ucfirst($attribut);
            $this->$setters($value);
        }
    }
    

    /*****************************
     *          SETTERS          *
     *****************************/

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

    public function setContent(string $content) : void
    {
        if( is_string( $content ) && !empty($content) )
        {
            $this->content = $content;
        }
    }
    
    public function setCreationDate(string $creationDate) : void
    {
        if( is_string( $creationDate ) && !empty($creationDate) )
        {
            $this->creationDate = $creationDate;
        }
    }
    
    public function setUpdateDate(?string $updateDate) : void
    {
        if( is_string( $updateDate ) && !empty($updateDate) )
        {
            $this->updateDate = $updateDate;  
        } 
    }
    
    public function setSlug(string $slug) : void
    {
        if( is_string( $slug ) && !empty($slug) )
        {
            $this->slug = $slug;
        } 
    }
   
    public function setCategoryId(int $categoryId) : void
    {
        if( is_numeric( $categoryId ) && !empty($categoryId) )
        {
            $this->categoryId = $categoryId;
        }
    }

    public function setUserId(int $userId) : void
    {
        if( is_numeric( $userId ) && !empty($userId) )
        {
            $this->userId = $userId;
        }
    }  

    public function setFeaturedImagePath(?string $featuredImagePath) : void
    {
        if( is_string( $featuredImagePath ) || $featuredImagePath === '' )
        {
            $this->featuredImagePath = $featuredImagePath;
        }
    }  

    /*****************************
     *          GETTERS          *
     *****************************/

    public function getId() : int
    {
        return $this->id;
    }

    public function getTitle() : string 
    {
        return $this->title;
    }

    public function getContent() : string
    {
        return nl2br($this->content);
    }

    public function getCreationDate() : ?string
    {
        return $this->creationDate;
    }

    public function getUpdateDate() : string
    {
        return $this->updateDate;
    }

    public function getSlug() : string
    {
        return $this->slug;
    }

    public function getCategoryId() : int
    {
        return $this->categoryId;
    }

    public function getUserId() : int
    {
        return $this->userId;
    }

    public function getFeaturedImagePath() : ?string
    {
        return $this->featuredImagePath;
    }

    public function getCategoryName() : void
    {
        
    }    
}

