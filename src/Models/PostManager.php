<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use mysqli_sql_exception;
use Portfolio\Ntimbablog\Models\Post;

use Portfolio\Ntimbablog\lib\Database;

use Portfolio\Ntimbablog\Helpers\StringUtil;

use \PDO;


class PostManager extends CRUDManager
{    
    public function create(Object $post): ?bool
    {
        $query = 'INSERT INTO posts(title, slug, content, publication_date, featured_image_path, status, category_id, user_id ) 
                  VALUES(:title, :slug, :content, NOW(), :featured_image_path, :status, :category_id, :user_id)';
        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
            'title' => $post->getTitle(),
            'slug' => $post->getSlug(), 
            'content' => $post->getContent(),
            'featured_image_path' => $post->getFeaturedImagePath(),
            'status' => $post->getStatus() ? 1 : 0,  
            'category_id' => $post->getCategoryId(),
            'user_id' => $post->getUserId(),
        ]);
    }
    
    public function read(int $id): Post|bool
    {
        $query = 'SELECT post_id, title, slug, content, publication_date, update_date, featured_image_path, status, category_id, user_id  FROM posts WHERE post_id = :post_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'post_id' => $id
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
        $post->setStatus((int)$postData['status'] === 1);
        $post->setCategoryId( $postData['category_id'] );
        $post->setUserId( $postData['user_id'] );

        return $post;   
    }



    public function lastPost(): Post|bool
    {
        $query = 'SELECT post_id, title, slug, content, publication_date, update_date, featured_image_path, status, category_id, user_id  FROM posts ORDER BY post_id DESC LIMIT 1';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();

        $lastPostData = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $lastPostData === false ) {
            return false;
        }
        
        $post = new Post($this->stringUtil);
        $post->setId( $lastPostData['post_id'] );
        $post->setTitle( $lastPostData['title'] );
        $post->setSlug( $lastPostData['slug'] );
        $post->setContent( $lastPostData['content'] );
        $post->setPublicationDate( $lastPostData['publication_date'] );
        $post->setUpdateDate( $lastPostData['update_date'] );
        $post->setFeaturedImagePath( $lastPostData['featured_image_path'] );
        $post->setStatus((int)$lastPostData['status'] === 1);
        $post->setCategoryId( $lastPostData['category_id'] );
        $post->setUserId( $lastPostData['user_id'] );

        return $post;   
    }
    
    


    public function update(Object $post): ?bool
    {
        $query = 'UPDATE posts SET title = :title, slug = :slug, content = :content, update_date = NOW(), featured_image_path = :featured_image_path, status = :status, category_id = :category_id, user_id = :user_id WHERE post_id = :post_id';
        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
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

    public function delete( int $postId ) : bool
    {
        $query = 'DELETE FROM posts WHERE post_id = :post_id';
        $statement = $this->db->getConnection()->prepare($query);
        return $statement->execute([
            'post_id' => $postId
        ]);
    }

    public function getAll() : ?array
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
            $post->setStatus((int)$postData['status'] === 1);
            $post->setCategoryId( $postData['category_id'] );
            $post->setUserId( $postData['user_id'] );
            $posts[] = $post;
        }
        return $posts;  
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

    public function slugExists(string $slug): bool
    {
        $query = 'SELECT slug FROM posts WHERE slug = :slug';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":slug", $slug);
        $statement->execute();
    
        $result = $statement->fetch(PDO::FETCH_ASSOC);
    
        return isset($result['slug']);
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
            $post->setStatus((int)$postData['status'] === 1);
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

    public function getTotalPublishedPosts() : int
    {
        $allPosts = $this->getAll();
        $publishedPosts = [];
        foreach( $allPosts as $post ){
            if( $post->getStatus() ){
                $publishedPosts[] = $post->getStatus();
            }
        }

        return count($publishedPosts);
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


