<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Helpers\StringUtil;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Models\CommentManager;

class CommentController
{
    private Database $db;
    private StringUtil $stringUtil;

    public function __construct(Database $db, StringUtil $stringUtil)
    {
        $this->db = $db;
        $this->stringUtil = $stringUtil;
    }
    
    public function getCommentsByPostId(int $postId) : array | bool
    {
        $commentManager = new CommentManager($this->db, $this->stringUtil);
        $comments = $commentManager->getPostComments($postId);
        
        return $comments;
    }
}
    



