<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;
use \DateTime;


class Comment
{
    private int $id;
    private string $content;
    private string $commentedDate;
    private int $postId;
    private int $userId;
    private bool $status;
    private string $ipAddress;
        
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
        $this->id = $id;
    }

    public function setContent(string $content) : void
    {
        if( is_string( $content ) && !empty($content) )
        {
            $this->content = $content;
        }
    }
    
    public function setCommentedDate(string $commentedDate) : void
    {
        if( is_string( $commentedDate ) && !empty($commentedDate) )
        {
            $this->commentedDate = $commentedDate;
        }
    }
        
    public function setPostId(int $postId) : void
    {
        if( is_numeric( $postId ) && !empty($postId) )
        {
            $this->postId = $postId;
        }
    }

    public function setUserId(int $userId) : void
    {
        if( is_numeric( $userId ) && !empty($userId) )
        {
            $this->userId = $userId;
        }
    } 

    public function setStatus(bool $status) : void
    {
        if ($status == 1 || $status === true) {
            $this->status = true;
        } else {
            $this->status = false;
        }
    }
    
    public function setIpAddress(string $ipAddress) : void
    {
        if( is_string( $ipAddress ) && !empty($ipAddress) )
        {
            $this->ipAddress = $ipAddress;
        }
    }

    /*****************************
     *          GETTERS          *
     *****************************/

    public function getId() : int
    {
        return $this->id;
    }
    
    public function getContent() : string 
    {
        return $this->content ?? '';
    }

    public function getCommentedDate() : ?string
    {
        return $this->commentedDate;
    }
    
    public function getFormatedDate($dateToBeFormatted) : ?string
    {
        $date = new DateTime($dateToBeFormatted);
    
        $formatter = new \IntlDateFormatter(
            'fr_FR', 
            \IntlDateFormatter::LONG, 
            \IntlDateFormatter::NONE
        );

        return $formatter->format($date);
    }
    
    public function getPostId() : int
    {
        return $this->postId;
    }

    public function getUserId() : int
    {
        return $this->userId;
    }

    public function getStatus() : bool
    {
        return $this->status;
    }

    public function getIpAddress() : string
    {
        return $this->ipAddress;
    }
    
}


