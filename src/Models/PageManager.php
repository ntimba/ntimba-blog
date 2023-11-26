<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use mysqli_sql_exception;
use Portfolio\Ntimbablog\Models\Page;

use Portfolio\Ntimbablog\lib\Database;

use Portfolio\Ntimbablog\Helpers\StringUtil;

use \PDO;


class PageManager extends CRUDManager
{    
    public function create(Object $page): ?bool
    {
        $query = 'INSERT INTO pages(title, slug, content, publication_date, featured_image_path, status, user_id ) 
                  VALUES(:title, :slug, :content, NOW(), :featured_image_path, :status, :user_id)';
        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
            'title' => $page->getTitle(),
            'slug' => $page->getSlug(), 
            'content' => $page->getContent(),
            'featured_image_path' => $page->getFeaturedImagePath(),
            'status' => $page->getStatus() ? 1 : 0,  
            'user_id' => $page->getUserId(),
        ]);
    }
    
    public function read(int $id): Page|bool
    {
        $query = 'SELECT page_id, title, slug, content, publication_date, update_date, featured_image_path, status, user_id  FROM pages WHERE page_id = :page_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'page_id' => $id
        ]);

        $pageData = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $pageData === false ) {
            return false;
        }
        
        $page = new Page($this->stringUtil);
        $page->setId( $pageData['page_id'] );
        $page->setTitle( $pageData['title'] );
        $page->setSlug( $pageData['slug'] );
        $page->setContent( $pageData['content'] );
        $page->setPublicationDate( $pageData['publication_date'] );
        $page->setUpdateDate( $pageData['update_date'] );
        $page->setFeaturedImagePath( $pageData['featured_image_path'] );
        $page->setStatus((int)$pageData['status'] === 1);
        $page->setUserId( $pageData['user_id'] );

        return $page;   
    }


    public function update(Object $page): ?bool
    {
        $query = 'UPDATE pages SET title = :title, slug = :slug, content = :content, update_date = NOW(), featured_image_path = :featured_image_path, status = :status, user_id = :user_id WHERE page_id = :page_id';
        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
            'page_id' => $page->getId(),
            'title' => $page->getTitle(),
            'slug' => $page->getSlug(), 
            'content' => $page->getContent(),
            'featured_image_path' => $page->getFeaturedImagePath(),
            'status' => $page->getStatus() ? 1 : 0,
            'user_id' => $page->getUserId(),
        ]);
    }

    public function delete( int $pageId ) : bool
    {
        $query = 'DELETE FROM pages WHERE page_id = :page_id';
        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
            'page_id' => $pageId
        ]);
    }

    public function getAll() : array|bool
    {
        $query = 'SELECT page_id, title, slug, content, publication_date, update_date, featured_image_path, status, user_id FROM pages';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();

        $pagesData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $pagesData === false ) {
            return false;
        }

        $pages = [];
        foreach( $pagesData as $pageData ){
            $page = new Page($this->stringUtil);

            $page->setId( $pageData['page_id'] );
            $page->setTitle( $pageData['title'] );
            $page->setSlug( $pageData['slug'] );
            $page->setContent( $pageData['content'] );
            $page->setPublicationDate( $pageData['publication_date'] );
            $page->setUpdateDate( $pageData['update_date'] );
            $page->setFeaturedImagePath( $pageData['featured_image_path'] );
            $page->setStatus((int)$pageData['status'] === 1);
            $page->setUserId( $pageData['user_id'] );
            $pages[] = $page;
        }
        return $pages;  
    }

    // Get user ID
    public function getPageId( string $title ): int
    {
        $query = 'SELECT page_id FROM pages WHERE title = :title';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":title", $title);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['page_id'] ?? 0;
    }

    public function slugExists(string $slug): bool
    {
        $query = 'SELECT slug FROM pages WHERE slug = :slug';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":slug", $slug);
        $statement->execute();
    
        $result = $statement->fetch(PDO::FETCH_ASSOC);
    
        return isset($result['slug']);
    }
}


