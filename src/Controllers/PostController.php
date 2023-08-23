<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;


use Portfolio\Ntimbablog\Models\CategoryManager;
use Portfolio\Ntimbablog\Models\Category;

use Portfolio\Ntimbablog\Models\PostManager;
use Portfolio\Ntimbablog\Models\Post;

use Portfolio\Ntimbablog\Models\FilesManager;

use Portfolio\Ntimbablog\Controllers\UserController;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Helpers\StringUtil;

use Portfolio\Ntimbablog\Service\EnvironmentService;
use Portfolio\Ntimbablog\Service\MailService;
use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;

use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Http\SessionManager;

class PostController
{

    protected ErrorHandler $errorHandler;
    private StringUtil $stringUtil;
    private MailService $mailService;
    private TranslationService $translationService;
    private ValidationService $validationService;
    private Request $request;
    private Database $db;
    private HttpResponse $response;
    private SessionManager $sessionManager;
    private UserController $userController;

    

    public function __construct(
        ErrorHandler $errorHandler,
        TranslationService $translationService, 
        ValidationService $validationService, 
        Request $request, 
        Database $db, 
        HttpResponse $response, 
        SessionManager $sessionManager,
        UserController $userController,
        StringUtil $stringUtil
    )
    {
        $this->errorHandler = $errorHandler;
        $this->translationService = $translationService;
        $this->validationService = $validationService;
        $this->request = $request;
        $this->db = $db;
        $this->response = $response;
        $this->sessionManager = $sessionManager;
        $this->userController = $userController;
        $this->stringUtil = $stringUtil;
    }

    private function togglePostStatus($postId, $newStatus)
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
        $this->userController->handleAdminPage();

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


        // Cette logique permet d'afficher la liste des articles dans la page :
        // views/backend/posts.php
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
        $this->userController->handleAdminPage();


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
            $status = false;
            if( $data['action'] == 'publish_post' ){
                $status = true;
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
        $this->userController->handleAdminPage();

        $postData = $_GET;
        $id = isset($postData['id']) ? $postData['id'] : null;
    }
    
    public function handleDeletePost() : void {
        $this->userController->handleAdminPage();

        $postData = $_GET;
        $id = isset($postData['id']) ? $postData['id'] : null;
    }
    
    public function handleBlogPage() : void {
        require("./views/frontend/blog.php");
    }
}



