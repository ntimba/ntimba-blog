<?php

namespace Portfolio\Ntimbablog\Models;

use \PDO;

class CategoryManager extends CRUDManager
{    
    public function read(int $id): Category | bool
    {
        $query = 'SELECT category_id, name, slug, description, creation_date, parent_id FROM post_categories WHERE category_id = :category_id';
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
        $category->setIdParent($categoryData['parent_id']);

        return $category; 
    }

    public function create(Object $category): ?bool
    {
        $query = 'INSERT INTO post_categories(name, slug, description, creation_date, parent_id) 
                  VALUES(:name, :slug, :description, NOW(), :parent_id)';
        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'description' => $category->getDescription(),
            'parent_id' => $category->getIdParent(),
        ]);

        return true;        
    }

    public function update(Object $category): ?bool
    {
        $query = 'UPDATE post_categories SET name = :name, slug = :slug, description = :description, parent_id = :parent_id WHERE category_id = :category_id';
        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
            'category_id' => $category->getId(),
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'description' => $category->getDescription(),
            'parent_id' => $category->getIdParent(),
        ]);
    }

    public function delete(int $id): bool
    {
        $query = 'DELETE FROM post_categories WHERE category_id = :category_id';
        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
            'category_id' => $id
        ]);   
    }

    public function getAll() : array | bool
    {
        $query = 'SELECT category_id, name, slug, description, creation_date, parent_id FROM post_categories ORDER BY category_id DESC';
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
            $category->setIdParent($categoryData['parent_id']);
            $categories[] = $category;
        }
        return $categories;
    }

    public function getTotalCategoriesCount() : float 
    {
        $statement = $this->db->getConnection()->query("SELECT COUNT(*) as total FROM post_categories");
        $totalCategories = $statement->fetchColumn();
        return $totalCategories; 
    }
    
    public function getCategoriesByPage(int $offset, int $limit) : array | bool
    {
        if($offset < 0){
            $offset = 0;
        }   

        $query = 'SELECT category_id, name, slug, description, creation_date, parent_id FROM post_categories ORDER BY category_id DESC LIMIT :offset, :limit';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        
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
            $category->setIdParent($categoryData['parent_id']);
            $categories[] = $category;
        }
        return $categories;
    }

    public function getCategoryId( string $categoryName ): int
    {
        $query = 'SELECT category_id FROM post_categories WHERE name = :name';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":name", $categoryName);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['category_id'] ?? 0;
    }

    public function slugExists(string $slug): bool
    {
        $query = 'SELECT slug FROM post_categories WHERE slug = :slug';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":slug", $slug);
        $statement->execute();
    
        $result = $statement->fetch(PDO::FETCH_ASSOC);
    
        return isset($result['slug']);
    }
    
    public function isParent( int $idCategory ): bool
    {
        $query = 'SELECT parent_id FROM post_categories WHERE parent_id = :parent_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":parent_id", $idCategory);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return isset($result['parent_id']);
    }

    public function getCategoryNameById(int $id) : string
    {
        $category = $this->read($id);
        return $categoryName = $category->getName();
    }

}



