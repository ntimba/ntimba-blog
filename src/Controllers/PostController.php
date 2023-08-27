<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;


use Portfolio\Ntimbablog\Models\CategoryManager;
use Portfolio\Ntimbablog\Models\Category;

use Portfolio\Ntimbablog\Models\PostManager;
use Portfolio\Ntimbablog\Models\Post;

use Portfolio\Ntimbablog\Models\CommentManager;
use Portfolio\Ntimbablog\Models\comment;

use Portfolio\Ntimbablog\Models\FilesManager;
use Portfolio\Ntimbablog\Models\UserManager;


class PostController extends BaseController
{

    public function handlePost(): void
    {
        $data = $this->request->getAllGet();

        $postId = (int) $this->request->get('id');

        // recupérer l'article complet
        $postManager = new PostManager($this->db, $this->stringUtil);
        $post = $postManager->getPost($postId);

        $userManager = new UserManager($this->db, $this->stringUtil);
        $user = $userManager->getUser($post->getUserId());

        $categoryManager = new CategoryManager($this->db, $this->stringUtil);
        $category = $categoryManager->getCategory($post->getCategoryId());
        
        $postData = [];
        $postData['post_id'] = $post->getId();
        $postData['post_title'] = $post->getTitle();
        $postData['post_content'] = $post->getContent();
        $postData['post_publication_date'] = $post->getPublicationDate();
        $postData['post_update_date'] = $post->getUpdateDate();
        $postData['post_category'] = $category->getName();
        $postData['post_user'] = $user->getUsername() ?? $user->getFullName();
        $postData['post_featured_image_path'] = $post->getFeaturedImagePath();
        
        $commentController = new CommentController($this->db, $this->stringUtil, $this->request, $this->validationService, $this->sessionManager, $this->errorHandler, $this->translationService, $this->response, $this->userController);
        // Contient les objets comments
        $comments = $commentController->getCommentsByPostId($postId);

        $commentsData = [];
        foreach( $comments as $comment ){
            /*  Ce code permet d'afficher uniquement 
            *  les commentaires vérifié.
            */
            $userManager = new UserManager($this->db, $this->stringUtil);
            $user = $userManager->getUser( $comment->getUserId() );

            if( $comment->getStatus() ){
                $commentData['comment_id'] = $comment->getId();
                $commentData['comment_content'] = $comment->getContent();
                $commentData['comment_date'] = $comment->getCommentedDate();
                $commentData['comment_post_id'] = $comment->getPostId();
                $commentData['comment_user'] = $user->getUsername() ?? $user->getFullName();
                $commentData['comment_user_image'] = $user->getProfilePicture();
                $commentData['comment_status'] = $comment->getStatus();
                $commentData['comment_ipAddress'] = $comment->getIpAddress();
    
                $commentsData[] = $commentData; 
            } 
        }
        
        $errorHandler = $this->errorHandler;
        require("./views/frontend/post.php");
    }

    private function togglePostStatus(int $postId, bool $newStatus) : void
    {
        $data = $this->request->getAllPost();

        $postManager = new PostManager($this->db, $this->stringUtil);

        // recupérer le post qui correspond à cet id
        // changer le status de la méthode
        $post = $postManager->getPost($postId);
        $post->setStatus($newStatus); 

        $postManager->updatePost($post);
        
    }

    public function handlePostsPage() : void
    {
        $this->authenticator->ensureAdmin();

        $data = $this->request->getAllPost();
        $postManager = new PostManager($this->db, $this->stringUtil);

        if( $this->request->post('posts_modify') === 'delete' ) {

            if(!isset($data['post_items'])){
                $errorMessage = $this->translationService->get('CHOOSE_AN_ACTION','posts');
                $this->errorHandler->addFlashMessage($errorMessage, "warning");
    
                $this->response->redirect('index.php?action=posts');
                return;
            }
            
            foreach( $data['post_items'] as $postItemId ){
                $postItemId = (int) $postItemId;
                // Supprimer les éléments
                $postManager->deletePost($postItemId);
            }

            $successMessage = $this->translationService->get('POST_DELETED','posts');
            $this->errorHandler->addFlashMessage($successMessage, "success");

            $this->response->redirect('index.php?action=posts');
            return;
            
        }elseif( $this->request->post('posts_modify') === 'publish'  ){

            // Publiser les pages
            if(!isset($data['post_items'])){
                $errorMessage = $this->translationService->get('CHOOSE_AN_ACTION','posts');
                $this->errorHandler->addFlashMessage($errorMessage, "warning");
    
                $this->response->redirect('index.php?action=posts');
                return;
            }

            $newStatus = true;

            foreach( $data['post_items'] as $postItemId ){
                $postId = (int) $postItemId;
                // Supprimer les éléments
                $this->togglePostStatus($postId, $newStatus);
            }

            $successMessage = $this->translationService->get('POST_PUBLISHED','posts');
            $this->errorHandler->addFlashMessage($successMessage, "success");

            $this->response->redirect('index.php?action=posts');
            return;


            
        } elseif($this->request->post('posts_modify') === 'unpublish'){
            
            // Publiser les pages
            if(!isset($data['post_items'])){
               $errorMessage = $this->translationService->get('CHOOSE_AN_ACTION','posts');
               $this->errorHandler->addFlashMessage($errorMessage, "warning");
           
               $this->response->redirect('index.php?action=posts');
               return;
           }
           
           $newStatus = false ?? 0;
           
           foreach( $data['post_items'] as $postItemId ){
               $postId = (int) $postItemId;
               // Supprimer les éléments
               $this->togglePostStatus($postId, $newStatus);
           }
           
           $successMessage = $this->translationService->get('POST_UNPUBLISHED','posts');
           $this->errorHandler->addFlashMessage($successMessage, "success");
           
           $this->response->redirect('index.php?action=posts');
           return;

        }
        else{
            $errorMessage = $this->translationService->get('CHOOSE_AN_ACTION','posts');
            $this->errorHandler->addFlashMessage($errorMessage, "warning");
        }


        /* Cette logique permet d'afficher la liste des articles dans la page :
         * views/backend/posts.php 
         */  
        $postManager = new PostManager($this->db, $this->stringUtil);

        $posts = $postManager->getAllPosts();

        $postsData = [];
        foreach( $posts as $post ){

            $categoryManager = new CategoryManager($this->db, $this->stringUtil);
            $category = $categoryManager->getCategory($post->getCategoryId());
            
            $postData['post_id'] = $post->getId();
            $postData['title'] = $post->getTitle();
            $postData['slug'] = $post->getSlug();
            $postData['content'] = $post->getContent();
            $postData['publication_date'] = $post->getPublicationDate();
            $postData['update_date'] = $post->getUpdateDate();
            $postData['featured_image_path'] = $post->getFeaturedImagePath();
            $postData['status'] = $post->getStatus();
            $postData['category_name'] = $category->getName();
            $postData['user_id'] = $post->getUserId();

            $postsData[] = $postData; 
        }

        $errorHandler = $this->errorHandler;
        require("./views/backend/posts.php");
    }
    
    public function handleAddPost() : void
    {
        $this->authenticator->ensureAdmin();


        $data = $this->request->getAllPost();


        // Afficher la catégorie des articles 
        
        if( $this->request->file('featured_image', '') ){
            $data['featured_image'] = $this->request->file('featured_image');
        }

        // valider les données du formulaire
        if($this->validationService->validatePostData($data)){
            
            $postManager = new PostManager($this->db, $this->stringUtil);
            $post = new Post($this->stringUtil);
            
            // l'identitiant de la personne qui enregistre l'article
            $userId = $this->sessionManager->get('user_id');

            // le status de l'article 
            $status = 0;
            if( $data['action'] == 'publish_post' ){
                $status = 1;
            }

            $post->setTitle($data['title']);
            $post->setSlug($data['slug']);
            $post->setContent($data['content']);
            $post->setStatus($status);

            // Le champ catégorie ne doit pas être vide
            if( $data['id_category'] == 'none' ){
                $warningMessage = $this->translationService->get('CHOOSE_A_CATEGORY','posts');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");
                
                $this->response->redirect('index.php?action=add_post');
                return;
            }

            // Si la la catégorie existe on ajoute

            $categoryId = (int) $data['id_category'];

            $post->setCategoryId( $categoryId );

            $post->setUserId($userId);

            // traiter l'import de limage
            if(isset($data['featured_image']) && $data['featured_image']['size'] > 0)
            {
                $fileManager = new FilesManager($this->response);
                $documentRoot = $this->request->getDocumentRoot();
                $featuredImage = $fileManager->importFile($data['featured_image'],  $documentRoot .'/assets/uploads/');
                $post->setFeaturedImagePath($featuredImage);
            }

            // Si le slug ou le nom du fichier est trouvable, 
            // il a les vacances jusqu'au 25 september 
            $postId = $postManager->getPostId( $data['title'] );
            if( !$postManager->getPostId( $data['title'] ) ){
                $postManager->createPost($post);

                $successMessage = $this->translationService->get('POST_ADDED_SUCCESS','posts');
                $this->errorHandler->addFlashMessage($successMessage, "success");
                
                $this->response->redirect('index.php?action=add_post');
                return;
            }else{

                $warningMessage = $this->translationService->get('POST_EXIST','posts');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");
                    
                $this->response->redirect('index.php?action=add_post');
                return;
            }
            
        }

        // Lister toute les catégories
        $categoryManager = new CategoryManager($this->db, $this->stringUtil);
        $categories = $categoryManager->getCategories();

        $categoriesData = [];
        foreach( $categories as $category )
        {
            $categoryData = [];
    
            $categoryData['category_id'] = $category->getId();
            $categoryData['category_name'] = $category->getName();
            $categoryData['category_slug'] = $category->getSlug();
            $categoryData['category_total_posts'] = '300 Articles';
            if($category->getIdParent())
            {
                $parent = $categoryManager->getCategory($category->getIdParent());
                $categoryData['category_parent_name'] = $parent->getName();
            }else{
                $categoryData['category_parent_name'] = '-';
            }

            $categoriesData[] = $categoryData;
        }

        $errorHandler = $this->errorHandler;
        require("./views/backend/formpost.php");
    }
    
    public function handleEditPost() : void
    {
        $this->authenticator->ensureAdmin();

        $postData = $_GET;
        $id = isset($postData['id']) ? $postData['id'] : null;
    }
    
    public function handleDeletePost() : void {
        $this->authenticator->ensureAdmin();

        $postData = $_GET;
        $id = isset($postData['id']) ? $postData['id'] : null;
    }
    
    public function handleBlogPage() : void 
    {
        $data = $this->request->getAllPost();                
        $postManager = new PostManager($this->db, $this->stringUtil);

        $pageValue = $this->request->get('page');
        $page = isset($pageValue) ? intval($pageValue) : 1;
        $page = intval($this->request->get('page') ?? 1);

        
        
        $postsPerPage = 1;

        $totalPages = $postManager->getTotalPages($postsPerPage);
        $posts = $postManager->getPostsByPage($page, $postsPerPage);

        $postsData = [];
        foreach( $posts as $post )
        {
            $categoryData = [];

            /*  Ce code permet d'afficher uniquement 
             *  les articles qui on été publié.
             */
            if( $post->getStatus() ){
                $postData['post_id'] = $post->getId();
                $postData['post_title'] = $post->getTitle();
                $postData['post_content'] = $this->stringUtil->displayFirst150Characters( $post->getContent() );
                $postData['post_image'] = $post->getFeaturedImagePath();
                $postData['post_status'] = $post->getStatus();

                $postsData[] = $postData;
            }     
        }
        
        require("./views/frontend/blog.php");
    }
}



