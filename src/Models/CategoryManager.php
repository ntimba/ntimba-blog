<?php

namespace Portfolio\Ntimbablog\Models;

use Portfolio\Ntimbablog\Lib\Database;

use Portfolio\Ntimbablog\Helpers\StringUtil;

use \PDO;

class CategoryManager
{    
    // Get User Id

    private Database $db;

    private StringUtil $stringUtil;

    public function __construct(Database $db, StringUtil $stringUtil)
    {
        $this->db = $db;
        $this->stringUtil = $stringUtil;
    }

    // Get user ID
    public function getCategoryId( string $categoryName ): int
    {
        $query = 'SELECT category_id FROM post_categories WHERE name = :name';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":name", $categoryName);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['category_id'] ?? 0;
    }

    public function getCategory( int $id ): mixed
    {
        $query = 'SELECT category_id, name, slug, description, creation_date, id_parent FROM post_categories WHERE category_id = :category_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'category_id' => $id
        ]);

        $categoryData = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $categoryData === false ) {
            return false;
        }
        
        $category = new Category($this->stringUtil);
        $category->setId($categoryData['category_id']);
        $category->setName($categoryData['name']);
        $category->setSlug($categoryData['slug']);
        $category->setDescription($categoryData['description']);
        $category->setCreationDate($categoryData['creation_date']);
        $category->setIdParent($categoryData['id_parent']);

        return $category;
    }

    public function getCategories() : array|bool
    {
        /**
         * La fonction retourne un tableau des objets
         * 
        */
        $query = 'SELECT category_id, name, slug, description, creation_date, id_parent FROM post_categories';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();

        $categoriesData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $categoriesData === false ) {
            return false;
        }

        $categories = [];
        foreach( $categoriesData as $categoryData ){
            $category = new Category($this->stringUtil);
            $category->setId($categoryData['category_id']);
            $category->setName($categoryData['name']);
            $category->setSlug($categoryData['slug']);
            $category->setDescription($categoryData['description']);
            $category->setCreationDate($categoryData['creation_date']);
            $category->setIdParent($categoryData['id_parent']);
            $categories[] = $category;
        }

        return $categories;
    }

    public function insertCategory(object $category) : void
    {
        // code
        $query = 'INSERT INTO post_categories(name, slug, description, creation_date, id_parent) 
                  VALUES(:name, :slug, :description, NOW(), :id_parent)';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'description' => $category->getDescription(),
            'id_parent' => $category->getIdParent(),
        ]);
    }

    public function updateCategory(Category $category) : void
    {
        $query = 'UPDATE post_categories SET name = :name, slug = :slug, description = :description, id_parent = :id_parent WHERE category_id = :category_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'category_id' => $category->getId(),
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'description' => $category->getDescription(),
            'id_parent' => $category->getIdParent(),
        ]);
    }

    public function deleteCategory( int $id ) : void
    {
        $query = 'DELETE FROM post_categories WHERE category_id = :category_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'category_id' => $id
        ]);
    }

    public function hasParent(int $idChild) : bool
    {
        // 1. Avant de supprimer une catégorie
        // 2. On prend son identifiant
        // 3. On fait une raquette dans la base de données pour voir si on trouve l’identtiant dans le champ de parentId

        $hasParent = false;
        $categoryChild = $this->getCategory($idChild);
        if($categoryChild->getIdParent()) $hasParent = true;
        
        return $hasParent;
    }



    // Cette fonction retourne l'identifiant du 

    public function isParent( int $idCategory ): int
    {
        $query = 'SELECT id_parent FROM post_categories WHERE id_parent = :id_parent';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":id_parent", $idCategory);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return isset($result['id_parent']);
    }


 




    
    

}



