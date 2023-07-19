<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

class PostController
{
    
    public function handlePostsPage() {
        require("./views/backend/posts.php");
    }
    
    public function handleAddPost() {
        $title = isset($_POST['title']) ? $_POST['title'] : null;
    }
    
    public function handleEditPost() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
    }
    
    public function handleDeletePost() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
    }
    
    public function handleBlogPage() {
        require("./views/frontend/blog.php");
    }
}
