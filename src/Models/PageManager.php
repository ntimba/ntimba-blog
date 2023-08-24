<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use mysqli_sql_exception;
use Portfolio\Ntimbablog\Models\Post;

use Portfolio\Ntimbablog\lib\Database;

use Portfolio\Ntimbablog\Helpers\StringUtil;

use \PDO;

class PageManager
{    
    // Get User Id
    private $stringUtil;
    private Database $db;

    public function __construct(Database $db, StringUtil $stringUtil){
        $this->db = $db;
        $this->stringUtil = $stringUtil;
    }

    // Get user ID
    public function getPageId( string $title ): int
    {
        $query = 'SELECT post_id FROM post WHERE post_title = :post_title';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":post_title", $title);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['post_id'] ?? 0;
    }

    public function getPage( int $page_id ): mixed
    {
        $query = 'SELECT post_id, post_title, post_content, post_creation_date, post_update_date, post_slug, post_category_id, post_user_id, post_featured_image_path FROM post WHERE post_id = :post_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'post_id' => $page_id
        ]);

        $pageData = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $pageData === false ) {
            return false;
        }
        
        $post = new Post($this->stringUtil);
        $post->setId( $pageData['post_id'] );
        $post->setTitle( $pageData['post_title'] );
        $post->setContent( $pageData['post_content'] );
        $post->setPublicationDate( $pageData['post_creation_date'] );
        $post->setUpdateDate( $pageData['post_update_date'] );
        $post->setSlug( $pageData['post_slug'] );
        $post->setCategoryId( $pageData['post_category_id'] );
        $post->setUserId( $pageData['post_user_id'] );
        $post->setFeaturedImagePath( $pageData['post_featured_image_path'] );

        return $post;
        
    }

    public function getAllPages() : array|bool
    {
        $query = 'SELECT post_id, post_title, post_content, post_creation_date, post_update_date, post_slug, post_category_id, post_user_id, post_featured_image_path FROM post';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();

        $pagesData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $pagesData === false ) {
            return false;
        }

        $posts = [];
        foreach( $pagesData as $pageData ){
            $page = new Post($this->stringUtil);

            $page->setId( $pageData['post_id'] );
            $page->setTitle( $pageData['post_title'] );
            $page->setContent( $pageData['post_content'] );
            $page->setPublicationDate( $pageData['post_creation_date'] );
            $page->setUpdateDate( $pageData['post_update_date'] );
            $page->setSlug( $pageData['post_slug'] );
            $page->setCategoryId( $pageData['post_category_id'] );
            $page->setUserId( $pageData['post_user_id'] );
            $page->setFeaturedImagePath( $pageData['post_featured_image_path'] );
            $pages[] = $page;
        }

        return $posts;   
    }
    

    public function createPage(Post $post) : void
    {
        // code
        $query = 'INSERT INTO post(post_title, post_content, post_update_date, post_slug, post_category_id, post_user_id, post_featured_image_path) 
                  VALUES(:post_title, :post_content, :NOW(), :post_slug, :post_category_id, :post_user_id, :post_featured_image_path)';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'post_title' => $post->getTitle(),
            'post_content' => $post->getContent(),
            'post_slug' => $post->getSlug(), 
            'post_category_id' => $post->getCategoryId(),
            'post_user_id' => $post->getUserId(),
            'post_featured_image_path' => $post->getFeaturedImagePath()
        ]);
    }

    public function updatePage(Post $post) : void
    {
        $query = 'UPDATE post SET post_title = :post_title, post_content = :post_content, post_update_date = NOW(), post_slug = :post_slug, post_category_id = :post_category_id, post_user_id = :post_user_id, post_featured_image_path = :post_featured_image_path WHERE post_id = :post_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'post_id' => $post->getId(),
            'post_title' => $post->getTitle(),
            'post_content' => $post->getContent(),
            'post_slug' => $post->getSlug(), 
            'post_category_id' => $post->getCategoryId(),
            'post_user_id' => $post->getUserId(),
            'post_featured_image_path' => $post->getFeaturedImagePath()
        ]);
    }

    public function deletePage( int $postId ) : void
    {
        $query = 'DELETE FROM post WHERE post_id = :post_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'post_id' => $postId
        ]);
    }

    public function importImage(array $file, string $destination) : string|NULL
    {
        if( isset($file['name']) && $file['error'] == 0 ) {
            if( $file['size'] <= 2000000 )
            {
                $fileInfo = pathinfo($file['name']);
                $extension = $fileInfo['extension'];
                $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png', 'ico'];

                if( in_array( $extension, $allowedExtensions ))
                {
                    $newFileName = str_replace(' ', '_', basename($file['name']) );
                    $filePath = $destination . $newFileName;
                    if( move_uploaded_file($file['tmp_name'], $filePath) )
                    {
                        return $filePath;
                    }else {
                        return NULL;
                    }
                }
            }
        }
    }  
}


