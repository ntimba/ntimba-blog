<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

class PageController
{
    public function handleHomePage() {
        require("./views/frontend/home.php");
    }

    public function handlePortfolioPage() {
        require("./views/frontend/portfolio.php");
    }

    public function handleContactPage() {
        require("./views/frontend/contact.php");
    }

    public function handleDashboardPage() {
        require("./views/backend/dashboard.php");
    }

    public function handleCategoriesPage() {
        require("./views/backend/categories.php");
    }

    public function handleCommentsPage() {
        require("./views/backend/comments.php");
    }
        
    public function handleUsersPage() {
        require("./views/backend/users.php");
    }
    
    public function handleSettingsPage() {
        require("./views/backend/settings.php");
    }

    public function handleDefault() {
        $this->handleHomePage();
    }

    public function handleAddPage() {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
    }

    public function handleEditPage() {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
    }

    public function handleDeletePage() {
        $page = isset($_GET['page']) ? $_GET['page'] : null;
    }
    
}

