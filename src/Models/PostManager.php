<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use mysqli_sql_exception;
use Portfolio\Ntimbablog\Models\Post;

use Portfolio\Ntimbablog\lib\Database;

use Portfolio\Ntimbablog\Helpers\StringUtil;

use \PDO;


class PostManager
{    
    // Get User Id

    private Database $db;
    private StringUtil $stringUtil;

    public function __construct(Database $db, StringUtil $stringUtil){
        $this->db = $db;
        $this->stringUtil = $stringUtil;
    }

    // Get user ID
    public function getPostId( string $title ): int
    {
        $query = 'SELECT post_id FROM posts WHERE title = :title';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":title", $title);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['post_id'] ?? 0;
    }

    public function getPost( int $post_id ): mixed
    {
        $query = 'SELECT post_id, title, slug, content, publication_date, update_date, featured_image_path, status, category_id, user_id  FROM posts WHERE post_id = :post_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'post_id' => $post_id
        ]);

        $postData = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $postData === false ) {
            return false;
        }
        
        $post = new Post($this->stringUtil);
        $post->setId( $postData['post_id'] );
        $post->setTitle( $postData['title'] );
        $post->setSlug( $postData['slug'] );
        $post->setContent( $postData['content'] );
        $post->setPublicationDate( $postData['publication_date'] );
        $post->setUpdateDate( $postData['update_date'] );
        $post->setFeaturedImagePath( $postData['featured_image_path'] );
        $post->setStatus( $postData['status'] );
        $post->setCategoryId( $postData['category_id'] );
        $post->setUserId( $postData['user_id'] );

        return $post;
        
    }

    public function getAllPosts() : array|bool
    {
        $query = 'SELECT post_id, title, slug, content, publication_date, update_date, featured_image_path, status, category_id, user_id FROM posts';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();

        $postsData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $postsData === false ) {
            return false;
        }

        $posts = [];
        foreach( $postsData as $postData ){
            $post = new Post($this->stringUtil);

            $post->setId( $postData['post_id'] );
            $post->setTitle( $postData['title'] );
            $post->setSlug( $postData['slug'] );
            $post->setContent( $postData['content'] );
            $post->setPublicationDate( $postData['publication_date'] );
            $post->setUpdateDate( $postData['update_date'] );
            $post->setFeaturedImagePath( $postData['featured_image_path'] );
            $post->setStatus( $postData['status'] );
            $post->setCategoryId( $postData['category_id'] );
            $post->setUserId( $postData['user_id'] );
            $posts[] = $post;
        }

        return $posts;   
    }


    public function getPostsByPage(int $page, int $postsPerPage) : array|bool
    {
        // Calculer le point de départ pour la pagination
        $start = ($page - 1) * $postsPerPage;
        
        // Modifier la requête pour inclure la pagination
        $query = 'SELECT post_id, title, slug, content, publication_date, update_date, featured_image_path, status, category_id, user_id FROM posts LIMIT :start, :postsPerPage';
        
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':postsPerPage', $postsPerPage, PDO::PARAM_INT);
        $statement->execute();
    
        $postsData = $statement->fetchAll(PDO::FETCH_ASSOC);
    
        if ( $postsData === false ) {
            return false;
        }
    
        $posts = [];
        foreach( $postsData as $postData ){
            $post = new Post($this->stringUtil);
    
            $post->setId( $postData['post_id'] );
            $post->setTitle( $postData['title'] );
            $post->setSlug( $postData['slug'] );
            $post->setContent( $postData['content'] );
            $post->setPublicationDate( $postData['publication_date'] );
            $post->setUpdateDate( $postData['update_date'] );
            $post->setFeaturedImagePath( $postData['featured_image_path'] );
            $post->setStatus( $postData['status'] );
            $post->setCategoryId( $postData['category_id'] );
            $post->setUserId( $postData['user_id'] );
            $posts[] = $post;
        }
    
        return $posts;   
    }
    

    public function getTotalPages(int $postsPerPage) : float 
    {
        $statement = $this->db->getConnection()->query("SELECT COUNT(*) as total FROM posts");
        $totalPosts = $statement->fetchColumn();
        return ceil($totalPosts / $postsPerPage);
    }
    

    
    

    public function createPost(Post $post) : void
    {
        // code
        $query = 'INSERT INTO posts(title, slug, content, publication_date, featured_image_path, status, category_id, user_id ) 
                  VALUES(:title, :slug, :content, NOW(), :featured_image_path, :status, :category_id, :user_id)';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'title' => $post->getTitle(),
            'slug' => $post->getSlug(), 
            'content' => $post->getContent(),
            'featured_image_path' => $post->getFeaturedImagePath(),
            'status' => $post->getStatus() ? 1 : 0,  
            'category_id' => $post->getCategoryId(),
            'user_id' => $post->getUserId(),
        ]);
    }

    public function updatePost(Post $post) : void
    {
        $query = 'UPDATE posts SET title = :title, slug = :slug, content = :content, update_date = NOW(), featured_image_path = :featured_image_path, status = :status, category_id = :category_id, user_id = :user_id WHERE post_id = :post_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'post_id' => $post->getId(),
            'title' => $post->getTitle(),
            'slug' => $post->getSlug(), 
            'content' => $post->getContent(),
            'featured_image_path' => $post->getFeaturedImagePath(),
            'status' => $post->getStatus() ? 1 : 0,
            'category_id' => $post->getCategoryId(),
            'user_id' => $post->getUserId(),
        ]);
    }

    public function deletePost( int $postId ) : void
    {
        $query = 'DELETE FROM posts WHERE post_id = :post_id';
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


