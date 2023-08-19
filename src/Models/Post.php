<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use Portfolio\Ntimbablog\Helpers\StringUtil;

use \DateTime;

class Post
{
    private int $id;
    private string $title;
    private string $content;
    private string $publicationDate = '';
    private ?string $updateDate;
    private string $slug;
    private bool $status;
    private int $categoryId;
    private int $userId;
    private ?string $featuredImagePath = NULL;
    private StringUtil $stringUtil;
        
    public function __construct( StringUtil $stringUtil, array $userdata = [])
    {
        $this->stringUtil = $stringUtil;
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

    public function setStatus($status) : void
    {
        if( is_bool( $status ) ){
            $this->status = $status;
        }
    }
   
    public function setCategoryId(int $categoryId) : void
    {
        $categoryId = (int) $categoryId;
        if($categoryId === 0){
            $categoryId = 1;
        }
        $this->categoryId = $categoryId;
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

    public function getPublicationDate() : ?string
    {
        return $this->publicationDate;
    }

    public function getUpdateDate() : string
    {
        return $this->updateDate;
    }

    public function getSlug() : string
    {
        return $this->slug;
    }

    public function getStatus() : bool
    {
        return $this->status;
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

