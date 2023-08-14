<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;
use Portfolio\Ntimbablog\Http\SessionManager;

class PostController
{

    private $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }
    
    public function handlePostsPage() : void
    {
        require("./views/backend/posts.php");
    }
    
    public function handleAddPost() : void
    {
        require("./views/backend/formpost.php");
    }
    
    public function handleEditPost() : void
    {
        $postData = $_GET;
        $id = isset($postData['id']) ? $postData['id'] : null;
    }
    
    public function handleDeletePost() {
        $postData = $_GET;
        $id = isset($postData['id']) ? $postData['id'] : null;
    }
    
    public function handleBlogPage() {
        require("./views/frontend/blog.php");
    }
}



