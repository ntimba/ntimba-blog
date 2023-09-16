<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Helpers\StringUtil;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Http\SessionManager;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Models\CategoryManager;
use Portfolio\Ntimbablog\Models\Category;

use Portfolio\Ntimbablog\Models\PostManager;
use Portfolio\Ntimbablog\Models\Post;

use Portfolio\Ntimbablog\Models\CommentManager;
use Portfolio\Ntimbablog\Models\comment;

use Portfolio\Ntimbablog\Models\FilesManager;
use Portfolio\Ntimbablog\Models\UserManager;
use Portfolio\Ntimbablog\Service\Authenticator;
use Portfolio\Ntimbablog\Service\MailService;
use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;

class PostController extends CRUDController
{
    private $postManager;
    private $post;
    private $categoryManager;
    private $fileManager;
    private $category;
    private $commentController;

    public function __construct( 
        ErrorHandler $errorHandler,
        MailService $mailService,
        TranslationService $translationService,
        ValidationService $validationService,
        Request $request,
        Database $db,
        HttpResponse $response,
        SessionManager $sessionManager,
        StringUtil $stringUtil,
        Authenticator $authenticator
        )
    {
        parent::__construct(
            $errorHandler,
            $mailService,
            $translationService,
            $validationService,
            $request,
            $db,
            $response,
            $sessionManager,
            $stringUtil,
            $authenticator
        );
        $this->postManager = new PostManager($db, $stringUtil);
        $this->post = new Post($stringUtil);

        $this->categoryManager = new CategoryManager($db, $stringUtil);
        $this->category = new Category($stringUtil);

        $this->fileManager = new FilesManager($response);
        $this->commentController = new CommentController(
            $errorHandler,
            $mailService,
            $translationService,
            $validationService,
            $request,
            $db,
            $response,
            $sessionManager,
            $stringUtil,
            $authenticator
        );
    }

    public function create(): void 
    {
        // Afficher le formulaire de création
        $this->authenticator->ensureAdmin();

        $data = $this->request->getAllPost();

        // Afficher la catégorie des articles 
        if( $this->request->file('featured_image', '') ){
            $data['featured_image'] = $this->request->file('featured_image');
        }

        // valider les données du formulaire
        if($this->validationService->validatePostData($data)){
            if( $this->postManager->getPostId($data['title']) ){
                $warningMessage = $this->translationService->get('POST_TITLE_EXIST','posts');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");

                $this->response->redirect('index.php?action=posts');
                return;
            }

            if( $this->postManager->slugExists($data['slug']) ){
                $warningMessage = $this->translationService->get('POST_SLUG_EXIST','posts');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");
                
                $this->response->redirect('index.php?action=posts');
                return;
            }

            $this->post->setTitle($data['title']);
            $this->post->setContent($data['content']);
            $this->post->setSlug($data['slug']);
            if( $data['action'] === 'publish' ){
                $this->post->setStatus(true);
            }else{
                $this->post->setStatus(false);
            }
            $categoryId = (int) $data['id_category'];
            $this->post->setCategoryId($categoryId);
            $this->post->setUserId($this->sessionManager->get('user_id'));

            // importer l'image s'il y en a une 
            if(isset($data['featured_image']) && $data['featured_image']['size'] > 0)
            {
                $documentRoot = $this->request->getDocumentRoot();
                $featuredImage = $this->fileManager->importFile($data['featured_image'],  $documentRoot .'/assets/uploads/');
                $this->post->setFeaturedImagePath($featuredImage);
            }

            if( $this->postManager->create($this->post) ){
                $successMessage = $this->translationService->get('POST_ADDED','posts');
                $this->errorHandler->addFlashMessage($successMessage, "success");

                $this->response->redirect('index.php?action=posts');
                return;
            }else{
                $warningMessage = $this->translationService->get('POST_NOT_ADDED','posts');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");

                $this->response->redirect('index.php?action=posts');
                return;
            }
        }

        // Ce code permet d'afficher la liste des catégories 
        // dans page, qui permet de créer un article
        $categories = $this->categoryManager->getAll();
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
                $parent = $this->categoryManager->read($category->getIdParent());
                $categoryData['category_parent_name'] = $parent->getName();
            }else{
                $categoryData['category_parent_name'] = '-';
            }

            $categoriesData[] = $categoryData;
        }
        
        $errorHandler = $this->errorHandler;
        require("./views/backend/formpost.php");
    }
    
    public function read(): void {
        // Afficher les détails d'un élément
        $this->authenticator->ensureAdmin();

    }
    
    public function update(): void 
    {
        // Afficher un formulaire de mise à jour
        $this->authenticator->ensureAdmin();

        $postData = $this->request->getAllPost();
        $postId = (int) $this->request->get('id');


        $post = $this->postManager->read($postId);
        
        if( !$post ){
            $this->errorHandler->addMessage("POST_DOES_NOT_EXIST", 'posts', 'warning');
            $this->response->redirect('index.php?action=posts');
            return;
        }

        
        $this->post->setId($postId);
        $this->post->setTitle($postData['title']);
        $this->post->setContent($postData['content']);
        $this->post->setSlug($postData['slug']);
        if( $postData['action'] === 'publish' ){
            $this->post->setStatus(true);
        }else{
            $this->post->setStatus(false);
        }
        $idCategory = (int) $postData['id_category'];
        $this->post->setCategoryId($idCategory);
        $this->post->setUserId($this->sessionManager->get('user_id'));
      // $this->post->setTitle
        if( $this->request->file('featured_image', '') ){
            $postData['featured_image'] = $this->request->file('featured_image');
        }
            // importer le nouveau image
        if(isset($postData['featured_image']) && $postData['featured_image']['size'] > 0)
        {
            $documentRoot = $this->request->getDocumentRoot();
            $featuredImage = $this->fileManager->importFile($postData['featured_image'],  $documentRoot .'/assets/uploads/');
            $this->post->setFeaturedImagePath($featuredImage);
        }
      
        // Enregistrer l'image dans la base de données
        if( $this->postManager->update($this->post) ){
            $this->errorHandler->addMessage("POST_UPDATED", 'posts', 'success');
            $this->response->redirect('index.php?action=posts');
            return;
        }else{

            $this->errorHandler->addMessage("CANT_UPDATE_POST", 'posts', 'warning');
            $this->response->redirect('index.php?action=posts');
            return;
        }
        // Renvoyer l'utilisateur à la page posts.php
        
    }
    
    public function delete(int $id): void {
        // pour supprimer un élément 
        $this->authenticator->ensureAdmin();

        $this->postManager->delete($id);
    }

    public function handlePosts() : void
    {
        $this->authenticator->ensureAdmin();

     
        // $postManager = new PostManager($this->db, $this->stringUtil);

        $posts = $this->postManager->getAll();

        $postsData = [];
        foreach( $posts as $post ){

            $categoryManager = new CategoryManager($this->db, $this->stringUtil);
            $category = $this->categoryManager->read($post->getCategoryId());
            
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
    
    
    public function postModify() : void
    {
        $this->authenticator->ensureAdmin();
        // recois tout les pare
        $data = $this->request->getAllPost();

        if( !isset($data['action']) ){
            $this->errorHandler->addMessage("CHOOSE_AN_ACTION", 'posts', 'warning');
            $this->response->redirect('index.php?action=posts');
            return;
        }

        if( !isset($data['post_ids']) ){
            $this->errorHandler->addMessage("CHOOSE_A_POST", 'posts', 'warning');
            $this->response->redirect('index.php?action=posts');
            return;
        }

        if( $data['action'] === 'publish' ){
            if( count($data['post_ids']) > 0 ){
                foreach( $data['post_ids'] as $id ){
                    // publier les articles
                    $id = (int) $id;
                    $this->togglePostStatus($id, true);
                }
            }

            $this->errorHandler->addMessage("POST_PUBLISHED", 'posts', 'success');
            $this->response->redirect('index.php?action=posts');
            
        }elseif( $data['action'] === 'unpublish' ){
            if( count($data['post_ids']) > 0 ){
                foreach( $data['post_ids'] as $id ){
                    $id = (int) $id;
                    $this->togglePostStatus($id, false);
                }
            }

            $this->errorHandler->addMessage("POST_UNPUBLISHED", 'posts', 'success');
            $this->response->redirect('index.php?action=posts');

        }elseif( $data['action'] === 'update' ){
            if( count($data['post_ids']) === 1 ){
                // envoyer à la page qui permet de modifier l'article

                $postId = (int) $data['post_ids'][0];
                $post = $this->postManager->read($postId);
                
                // Afficher les catégories
                $categories = $this->categoryManager->getAll();
                
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
                        $parent = $this->categoryManager->read($category->getIdParent());
                        $categoryData['category_parent_name'] = $parent->getName();
                    }else{
                        $categoryData['category_parent_name'] = '-';
                    }
        
                    $categoriesData[] = $categoryData;
                }
                
                $errorHandler = $this->errorHandler;
                require("./views/backend/editpost.php");
            }else{
                $this->errorHandler->addMessage("CHOOSE_ONLY_ONE_POST", 'posts', 'warning');
                $this->response->redirect('index.php?action=posts');
                return;
            }
        }elseif( $data['action'] === 'delete' ){
            if( count($data['post_ids']) > 0 ){
                // supprimer l'article
                foreach( $data['post_ids'] as $id ){
                    $id = (int) $id;
                    $this->delete($id);
                }
            }
            $this->errorHandler->addMessage("POST_DELETED", 'posts', 'success');
            $this->response->redirect('index.php?action=posts');
        }

    }


    public function handlePost(): void
    {
        $data = $this->request->getAllGet();

        $postId = (int) $this->request->get('id');

        // recupérer l'article complet
        $postManager = new PostManager($this->db, $this->stringUtil);
        $post = $this->postManager->read($postId);

        $userManager = new UserManager($this->db, $this->stringUtil);
        $user = $userManager->read($post->getUserId());

        $category = $this->categoryManager->read($post->getCategoryId());
        
        $postData = [];
        $postData['post_id'] = $post->getId();
        $postData['post_title'] = $post->getTitle();
        $postData['post_content'] = $post->getContent();
        $postData['post_publication_date'] = $post->getPublicationDate();
        $postData['post_update_date'] = $post->getUpdateDate();
        $postData['post_category'] = $category->getName();
        $postData['post_user'] = $user->getUsername() ?? $user->getFullName();
        $postData['post_featured_image_path'] = $post->getFeaturedImagePath();
        
        // $commentController = new CommentController($this->db, $this->stringUtil, $this->request, $this->validationService, $this->sessionManager, $this->errorHandler, $this->translationService, $this->response, $this->userController);
        // Contient les objets comments
        $comments = $this->commentController->getCommentsByPostId($postId);

        $commentsData = [];
        foreach( $comments as $comment ){
            /*  Ce code permet d'afficher uniquement 
            *  les commentaires vérifié.
            */
            $userManager = new UserManager($this->db, $this->stringUtil);
            $user = $userManager->read( $comment->getUserId() );

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

        // Cette variable est nécessaire pour afficher la liste des catégories dans la page frontend/post.php
        $categoriesData = $this->getCategoryList();

        // Cette variable est nécessaire pour afficher le dernier news
        $lastPostData = $this->getLastPosts();
        
        $errorHandler = $this->errorHandler;
        require("./views/frontend/post.php");
    }


    private function togglePostStatus(int $postId, bool $newStatus) : void
    {
        $data = $this->request->getAllPost();

        // recupérer le post qui correspond à cet id
        // changer le status de la méthode
        $post = $this->postManager->read($postId);
        $post->setStatus($newStatus); 

        $this->postManager->update($post);
    }


    public function handleBlogPage() : void 
    {
        $data = $this->request->getAllPost();                
        $postManager = new PostManager($this->db, $this->stringUtil);

        $pageValue = $this->request->get('page');
        $page = isset($pageValue) ? intval($pageValue) : 1;
        $page = intval($this->request->get('page') ?? 1);

        $postsPerPage = 6;

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

        // Cette variable est nécessaire pour afficher la liste des catégories
        $categoriesData = $this->getCategoryList();

        // cette variable permet d'afficher les dernier articles
        $lastPostData = $this->getLastPosts();
    
        require("./views/frontend/blog.php");
    }


    public function getCategoryList(): array
    {
        $categories = $this->categoryManager->getAll();

        $categoriesData = [];
        foreach( $categories as $category )
        {
            if( $category->getName() != 'Default' ){
                $categoryData['name'] = $category->getName();
                $categoriesData[] = $categoryData;
            }
        }
        return $categoriesData;
    }

    public function getLastPosts()
    {
        $lastPost = $this->postManager->lastPost();

        $lastPostData['id'] = $lastPost->getId();
        $lastPostData['title'] = $lastPost->getTitle();
        $lastPostData['content'] = $lastPost->getContent();

        return $lastPostData;
    }


    
    public function addMessage(string $messageCode, string $domain, string $type){
        $message = $this->translationService->get($messageCode,$domain);
        $this->errorHandler->addFlashMessage($message, $type);
    }
    
}



