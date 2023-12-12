<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use \InvalidArgumentException;

use Portfolio\Ntimbablog\Helpers\StringUtil;

class Category
{
    private int $id;
    private string $name;
    private string $slug;
    private ?string $description = null;
    private string $creationDate;
    private ?int $idParent = null;
    private $stringUtil;

    public function __construct(StringUtil $stringUtil, array $categoryData = [])
    {
        $this->stringUtil = $stringUtil;
        $this->hydrate($categoryData);
    }

    public function hydrate(array $data) : void
    {
        foreach ($data as $attribut => $value) {
            $setters = 'set' . ucfirst($attribut);
            $this->$setters($value);
        }
    }

    // SETTERS
    public function setId(int $id) : void
    {
        if(is_numeric($id) && !empty($id))
        {
            $this->id = $id;
        }
    }

    public function setName(string $name) : void
    {
        if(is_string($name) && !empty( $name ))
        {
            $this->name = $name;
        } else {
            throw new InvalidArgumentException( 'Nom de la catégorie invalid' );
        }
    }

    public function setSlug(string $slug) : void
    {
        if( is_string($slug) && !empty( $slug ) )
        {
            $slugWithoutSpecialCharacters = $this->stringUtil->removeAccentsAndSpecialCharacters($slug);
            $slugWithoutSpaces = $this->stringUtil->removeStringsSpaces($slugWithoutSpecialCharacters);
            $this->slug = strtolower($slugWithoutSpaces); 
        }
    }

    public function setDescription(?string $description) : void
    {
        if( is_string($description) && !empty( $description ))
        {
            $this->description = $description;
        } else{
            $this->description = null;
        }
    }

    public function setCreationDate(string $creationDate) : void
    {
        if(is_string($creationDate) && !empty($creationDate))
        {
            $this->creationDate = $creationDate;
        }
    }

    public function setIdParent(mixed $idParent) : void
    {
        if( $idParent === NULL )
        {
            $this->idParent = $idParent;
        } elseif( is_string($idParent) )
        {
            $this->idParent = intval($idParent);
        }else{
            $this->idParent = intval($idParent);
        }
    }


    // GETTERS
    public function getId() : int
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getSlug() : string 
    {
        return $this->slug;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function getCreationDate() : string
    {
        return $this->creationDate;
    }

    public function getIdParent() : int | null
    {
        return $this->idParent;
    }
}



