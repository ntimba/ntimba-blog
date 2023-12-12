<?php

namespace Portfolio\Ntimbablog\Models;

use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Models\Comment;

use PDO;

use \App\Models;
use Portfolio\Ntimbablog\Helpers\StringUtil;

class CommentManager
{    
    private Database $db;
    private StringUtil $stringUtil;

    public function __construct(Database $db, StringUtil $stringUtil){
        $this->db = $db;
        $this->stringUtil = $stringUtil;
    }

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
        $query = 'SELECT  comment_id, content, date, post_id, user_id, status FROM comments WHERE comment_id = :comment_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'comment_id' => $comment_id
        ]);

        $commentData = $statement->fetch(PDO::FETCH_ASSOC);

        if ( $commentData === false ) {
            return false;
        }

        $comment = new Comment();

        $comment->setId($commentData['comment_id']);
        $comment->setContent($commentData['content']);
        $comment->setCommentedDate($commentData['date']);
        $comment->setPostId($commentData['post_id']);
        $comment->setUserId($commentData['user_id']);
        $comment->setStatus($commentData['status']);
        
        return $comment;

    }

    public function getCommentIdsByUserId(int $userId): mixed
    {
        $query = 'SELECT  comment_id FROM comments WHERE user_id = :user_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(":user_id", $userId);
        $statement->execute();

        $commentIds = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $commentIds === false ) {
            return false;
        }

        $ids = [];
        foreach( $commentIds as $commentId )
        {
            $ids[] = $commentId['comment_id'];
        }
        
        return $ids;
    }


    public function getTotalCommentsCount() : float 
    {
        $statement = $this->db->getConnection()->query("SELECT COUNT(*) as total FROM comments");
        $totalCategories = $statement->fetchColumn();
        return $totalCategories; 
    }

    public function getCommentsByPage(int $offset, int $limit) : array | bool
    {
        if($offset < 0){
            $offset = 0;
        }   

        $query = 'SELECT  comment_id, content, date, post_id, user_id, status FROM comments ORDER BY comment_id DESC LIMIT :offset, :limit';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $commentsData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $commentsData === false ) {
            return false;
        }

        $comments = [];
        foreach( $commentsData as $commentData ) {
            $comment = new Comment();
            $comment->setId($commentData['comment_id']);
            $comment->setContent($commentData['content']);
            $comment->setCommentedDate($commentData['date']);
            $comment->setPostId($commentData['post_id']);
            $comment->setUserId($commentData['user_id']);
            $comment->setStatus($commentData['status']);
            
            $comments[] = $comment;
        }
        
        return $comments;
    }


    public function getAllComments(): mixed
    {
        $query = 'SELECT  comment_id, content, date, post_id, user_id, status FROM comments ORDER BY comment_id DESC';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();

        $commentsData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $commentsData === false ) {
            return false;
        }

        $comments = [];
        foreach( $commentsData as $commentData ) {
            $comment = new Comment();
            $comment->setId($commentData['comment_id']);
            $comment->setContent($commentData['content']);
            $comment->setCommentedDate($commentData['date']);
            $comment->setPostId($commentData['post_id']);
            $comment->setUserId($commentData['user_id']);
            $comment->setStatus($commentData['status']);
            
            $comments[] = $comment;
        }
        
        return $comments;
    }

    public function getTotalApprovedComments() : int
    {
        $allComments = $this->getAllComments();
        $approvedComments = [];
        foreach( $allComments as $comment ){
            if( $comment->getStatus() ){
                $approvedComments[] = $comment;
            }
        }
        return count($approvedComments);
    }


    public function getPostComments( int $postId ): mixed
    {
        $query = 'SELECT  comment_id, content, date, post_id, user_id, status FROM comments WHERE post_id = :post_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'post_id' => $postId
        ]);

        $commentData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ( $commentData === false ) {
            return false;
        }

        $comments = [];
        foreach( $commentData as $commentData ) {
            $comment = new Comment();
            $comment->setId($commentData['comment_id']);
            $comment->setContent($commentData['content']);
            $comment->setCommentedDate($commentData['date']);
            $comment->setPostId($commentData['post_id']);
            $comment->setUserId($commentData['user_id']);
            $comment->setStatus($commentData['status']);

            $comments[] = $comment;
        }
        return $comments;
    }
    
    public function updateComment(Comment $comment) : void
    {
        $query = 'UPDATE comments SET comment_id = :comment_id, content = :content, date = :date, post_id = :post_id, user_id = :user_id, status = :status WHERE comment_id = :comment_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'comment_id' => $comment->getId(),
            'content' => $comment->getContent(),
            'date' => $comment->getCommentedDate(),
            'post_id' => $comment->getPostId(),
            'user_id' => $comment->getUserId(),
            'status' => $comment->getStatus()
        ]);
    }

    public function addComment(Comment $comment) : void
    {
        $query = 'INSERT INTO comments(content, date, post_id, user_id , status) 
                  VALUES(:content, NOW(), :post_id, :user_id , :status)';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'content' => $comment->getContent(), 
            'post_id' => $comment->getPostId(),
            'user_id' => $comment->getUserId(),
            'status' => $comment->getStatus() ? 1 : 0
        ]);
    }

    public function deleteComment( int $comment_id ) : void
    {
        $query = 'DELETE FROM comments WHERE comment_id = :comment_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'comment_id' => $comment_id
        ]);
    }

    public function deleteByPostId( int $postId ) : void
    {
        $query = 'DELETE FROM comments WHERE post_id = :post_id';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute([
            'post_id' => $postId
        ]);
    }
}



