<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

class PostController
{
    
    public function handlePostsPage() {
        require("./views/backend/posts.php");
    }
    
    public function handleAddPost() {
        // $title = isset($_POST['title']) ? $_POST['title'] : null;
        require("./views/backend/formpost.php");
    }
    
    public function handleEditPost() {
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



