<?php

declare(strict_types=1);

namespace Ntimbablog\Portfolio\Models;

use \InvalidArgumentException;

class Category
{
    private int $id;
    private string $name;
    private string $slug;
    private ?string $description;
    private string $creationDate;
    private ?int $idParent;

    public function __construct(array $categoryData = [])
    {
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
        // Créer une fonction qui va supprimer les espaces
        if( is_string($slug) && !empty( $slug ) )
        {
            $slug = str_replace(' ', '_', $slug);
            $this->slug = strtolower($slug); 
        }
    }

    public function setDescription(?string $description) : void
    {
        if( is_string($description) && !empty( $description ))
        {
            $this->description = $description;
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
        if(is_numeric($idParent) || $idParent === NULL)
        {
            $this->idParent = $idParent;
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

    public function getDescription() : string
    {
        return $this->description;
    }

    public function getCreationDate() : string
    {
        return $this->creationDate;
    }

    public function getIdParent() : ?int
    {
        return $this->idParent;
    }
}

