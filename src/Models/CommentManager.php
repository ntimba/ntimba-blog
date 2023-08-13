<?php

namespace Ntimbablog\Portfolio\Models;

use Ntimbablog\Portfolio\Lib\Database;
use Ntimbablog\Portfolio\Models\Comment;

use PDO;

use \App\Models;

class CommentManager
{    
    // Get User Id

    private Database $db;

    public function __construct(){
        $this->db = new Database();
    }

    // Get user ID
    public function getCommentId( string $commentContent ): int
    {
        $query = 'SELECT comment_id FROM comment WHERE comment_content = :comment_content';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":comment_content", $commentContent);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['id'] ?? 0;
    }

    public function getComment( int $comment_id ): mixed
    {
        $query = 'SELECT comment_id, comment_content, comment_date, comment_id_post, comment_user_id , comment_verify FROM comment WHERE comment_id = :comment_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'comment_id' => $comment_id
        ]);

        $commentData = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $commentData === false ) {
            return false;
        }

        
        $comment = new Comment();

        $comment = new Comment();
        $comment->setId($commentData['comment_id']);
        $comment->setContent($commentData['comment_content']);
        $comment->setCommentedDate($commentData['comment_date']);
        $comment->setPostId($commentData['comment_id_post']);
        $comment->setUserId($commentData['comment_user_id']);
        $comment->setCommentVerify($commentData['comment_verify']);
        
        return $comment;

    }



    public function getAllComments(): mixed
    {
        $query = 'SELECT  comment_id, comment_content, comment_date, comment_id_post, comment_user_id , comment_verify FROM comment';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();

        $commentsData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $commentsData === false ) {
            return false;
        }

        foreach( $commentsData as $commentData ) {
            $comment = new Comment();
            $comment->setId($commentData['comment_id']);
            $comment->setContent($commentData['comment_content']);
            $comment->setCommentedDate($commentData['comment_date']);
            $comment->setPostId($commentData['comment_id_post']);
            $comment->setUserId($commentData['comment_user_id']);
            $comment->setCommentVerify($commentData['comment_verify']);

            $comments[] = $comment;
        }
        
        return $comments;
    }


    public function getPostComments( int $postId ): mixed
    {
        $query = 'SELECT  comment_id, comment_content, comment_date, comment_id_post, comment_user_id , comment_verify FROM comment WHERE comment_id_post = :comment_id_post';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'comment_id_post' => $postId
        ]);

        $commentData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $commentData === false ) {
            return false;
        }

        foreach( $commentData as $commentData ) {
            $comment = new Comment();
            $comment->setId($commentData['comment_id']);
            $comment->setContent($commentData['comment_content']);
            $comment->setCommentedDate($commentData['comment_date']);
            $comment->setPostId($commentData['comment_id_post']);
            $comment->setUserId($commentData['comment_user_id']);
            $comment->setCommentVerify($commentData['comment_verify']);

            $comments[] = $comment;
        }
        return $comments;
    }
    

    public function updateComment(Comment $comment) : void
    {
        $query = 'UPDATE comment SET comment_verify = :comment_verify WHERE comment_id = :comment_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'comment_id' => $comment->getId(),
            'comment_verify' => $comment->getCommentVerify(),
        ]);
    }

    

    public function addComment(Comment $comment) : void
    {
        $query = 'INSERT INTO comment(comment_content, comment_date, comment_id_post, comment_user_id , comment_verify) 
                  VALUES(:comment_content, NOW(), :comment_id_post, :comment_user_id , :comment_verify)';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'comment_content' => $comment->getContent(), 
            'comment_id_post' => $comment->getPostId(),
            'comment_user_id' => $comment->getUserId(),
            'comment_verify' => $comment->getCommentVerify() ? 1 : 0 // convert to boolean
        ]);
    }

    public function deleteComment( int $comment_id ) : void
    {
        $query = 'DELETE FROM comment WHERE comment_id = :comment_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'comment_id' => $comment_id
        ]);
    }
}

