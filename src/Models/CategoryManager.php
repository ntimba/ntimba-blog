<?php

namespace Ntimbablog\Portfolio\Models;
use Ntimbablog\Portfolio\Lib\Database;
use \PDO;

class CategoryManager
{    
    // Get User Id

    private Database $db;

    public function __construct(){
        $this->db = new Database();
    }

    // Get user ID
    public function getCategoryId( string $categoryName ): int
    {
        $query = 'SELECT category_id FROM category WHERE category_name = :category_name';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":category_name", $categoryName);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['category_id'] ?? 0;
    }

    public function getCategory( int $id ): mixed
    {
        $query = 'SELECT category_id, category_name, category_slug, category_description, category_creation_date, category_id_parent FROM category WHERE category_id = :category_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'category_id' => $id
        ]);

        $categoryData = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $categoryData === false ) {
            return false;
        }
        
        $category = new Category();
        $category->setId($categoryData['category_id']);
        $category->setName($categoryData['category_name']);
        $category->setSlug($categoryData['category_slug']);
        $category->setDescription($categoryData['category_description']);
        $category->setCreationDate($categoryData['category_creation_date']);
        $category->setIdParent($categoryData['category_id_parent']);

        return $category;
    }

    public function getCategories() : array|bool
    {
        /**
         * La fonction retourne un tableau des objets
         * 
        */
        $query = 'SELECT category_id, category_name, category_slug, category_description, category_creation_date, category_id_parent FROM category';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();

        $categoriesData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $categoriesData === false ) {
            return false;
        }

        $categories = [];
        foreach( $categoriesData as $categoryData ){
            $category = new Category();
            $category->setId($categoryData['category_id']);
            $category->setName($categoryData['category_name']);
            $category->setSlug($categoryData['category_slug']);
            $category->setDescription($categoryData['category_description']);
            $category->setCreationDate($categoryData['category_creation_date']);
            $category->setIdParent($categoryData['category_id_parent']);
            $categories[] = $category;
        }

        return $categories;
    }

    public function insertCategory(object $category) : void
    {
        // code
        $query = 'INSERT INTO category(category_name, category_slug, category_description, category_creation_date, category_id_parent) 
                  VALUES(:category_name, :category_slug, :category_description, NOW(), :category_id_parent)';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'category_name' => $category->getName(),
            'category_slug' => $category->getSlug(),
            'category_description' => $category->getDescription(),
            'category_id_parent' => $category->getIdParent(),
        ]);
    }

    public function updateCategory(Category $category) : void
    {
        $query = 'UPDATE category SET name = :name, slug = :slug, description = :description, creationDate = :creationDate, idParent = :idParent';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'id' => $category->getId(),
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'description' => $category->getDescription(),
            'creationDate' => $category->getCreationDate(),
            'idParent' => $category->getIdParent(),
        ]);
    }

    public function deleteCategory( int $id ) : void
    {
        $query = 'DELETE FROM article WHERE id = :id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'id' => $id
        ]);
    }

}
