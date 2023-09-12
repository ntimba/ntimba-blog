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


    // public function handlePost(): void
    // {
    //     $data = $this->request->getAllGet();

    //     $postId = (int) $this->request->get('id');

    //     // recupérer l'article complet
    //     $postManager = new PostManager($this->db, $this->stringUtil);
    //     $post = $this->postManager->read($postId);

    //     $userManager = new UserManager($this->db, $this->stringUtil);
    //     $user = $userManager->getUser($post->getUserId());

    //     $category = $this->categoryManager->read($post->getCategoryId());
        
    //     $postData = [];
    //     $postData['post_id'] = $post->getId();
    //     $postData['post_title'] = $post->getTitle();
    //     $postData['post_content'] = $post->getContent();
    //     $postData['post_publication_date'] = $post->getPublicationDate();
    //     $postData['post_update_date'] = $post->getUpdateDate();
    //     $postData['post_category'] = $category->getName();
    //     $postData['post_user'] = $user->getUsername() ?? $user->getFullName();
    //     $postData['post_featured_image_path'] = $post->getFeaturedImagePath();
        
    //     $commentController = new CommentController($this->db, $this->stringUtil, $this->request, $this->validationService, $this->sessionManager, $this->errorHandler, $this->translationService, $this->response, $this->userController);
    //     // Contient les objets comments
    //     $comments = $commentController->getCommentsByPostId($postId);

    //     $commentsData = [];
    //     foreach( $comments as $comment ){
    //         /*  Ce code permet d'afficher uniquement 
    //         *  les commentaires vérifié.
    //         */
    //         $userManager = new UserManager($this->db, $this->stringUtil);
    //         $user = $userManager->getUser( $comment->getUserId() );

    //         if( $comment->getStatus() ){
    //             $commentData['comment_id'] = $comment->getId();
    //             $commentData['comment_content'] = $comment->getContent();
    //             $commentData['comment_date'] = $comment->getCommentedDate();
    //             $commentData['comment_post_id'] = $comment->getPostId();
    //             $commentData['comment_user'] = $user->getUsername() ?? $user->getFullName();
    //             $commentData['comment_user_image'] = $user->getProfilePicture();
    //             $commentData['comment_status'] = $comment->getStatus();
    //             $commentData['comment_ipAddress'] = $comment->getIpAddress();
    
    //             $commentsData[] = $commentData; 
    //         } 
    //     }
        
    //     $errorHandler = $this->errorHandler;
    //     require("./views/frontend/post.php");
    // }





    private function togglePostStatus(int $postId, bool $newStatus) : void
    {
        $data = $this->request->getAllPost();

        // recupérer le post qui correspond à cet id
        // changer le status de la méthode
        $post = $this->postManager->read($postId);
        $post->setStatus($newStatus); 

        $this->postManager->update($post);
    }

  
    // public function handleAddPost() : void
    // {
    //     $this->authenticator->ensureAdmin();


    //     $data = $this->request->getAllPost();


    //     // Afficher la catégorie des articles 
        
    //     if( $this->request->file('featured_image', '') ){
    //         $data['featured_image'] = $this->request->file('featured_image');
    //     }

    //     // valider les données du formulaire
    //     if($this->validationService->validatePostData($data)){
            
    //         $postManager = new PostManager($this->db, $this->stringUtil);
    //         $post = new Post($this->stringUtil);
            
    //         // l'identitiant de la personne qui enregistre l'article
    //         $userId = $this->sessionManager->get('user_id');

    //         // le status de l'article 
    //         $status = 0;
    //         if( $data['action'] == 'publish_post' ){
    //             $status = 1;
    //         }

    //         $post->setTitle($data['title']);
    //         $post->setSlug($data['slug']);
    //         $post->setContent($data['content']);
    //         $post->setStatus($status);

    //         // Le champ catégorie ne doit pas être vide
    //         if( $data['id_category'] == 'none' ){
    //             $warningMessage = $this->translationService->get('CHOOSE_A_CATEGORY','posts');
    //             $this->errorHandler->addFlashMessage($warningMessage, "warning");
                
    //             $this->response->redirect('index.php?action=add_post');
    //             return;
    //         }

    //         // Si la la catégorie existe on ajoute

    //         $categoryId = (int) $data['id_category'];

    //         $post->setCategoryId( $categoryId );

    //         $post->setUserId($userId);

    //         // traiter l'import de limage
    //         if(isset($data['featured_image']) && $data['featured_image']['size'] > 0)
    //         {
    //             $fileManager = new FilesManager($this->response);
    //             $documentRoot = $this->request->getDocumentRoot();
    //             $featuredImage = $fileManager->importFile($data['featured_image'],  $documentRoot .'/assets/uploads/');
    //             $post->setFeaturedImagePath($featuredImage);
    //         }

    //         // Si le slug ou le nom du fichier est trouvable, 
    //         // il a les vacances jusqu'au 25 september 
    //         $postId = $postManager->getPostId( $data['title'] );
    //         if( !$postManager->getPostId( $data['title'] ) ){
    //             $postManager->createPost($post);

    //             $successMessage = $this->translationService->get('POST_ADDED_SUCCESS','posts');
    //             $this->errorHandler->addFlashMessage($successMessage, "success");
                
    //             $this->response->redirect('index.php?action=add_post');
    //             return;
    //         }else{

    //             $warningMessage = $this->translationService->get('POST_EXIST','posts');
    //             $this->errorHandler->addFlashMessage($warningMessage, "warning");
                    
    //             $this->response->redirect('index.php?action=add_post');
    //             return;
    //         }
            
    //     }

    //     // Lister toute les catégories
    //     $categoryManager = new CategoryManager($this->db, $this->stringUtil);
    //     $categories = $categoryManager->getCategories();

    //     $categoriesData = [];
    //     foreach( $categories as $category )
    //     {
    //         $categoryData = [];
    
    //         $categoryData['category_id'] = $category->getId();
    //         $categoryData['category_name'] = $category->getName();
    //         $categoryData['category_slug'] = $category->getSlug();
    //         $categoryData['category_total_posts'] = '300 Articles';
    //         if($category->getIdParent())
    //         {
    //             $parent = $categoryManager->getCategory($category->getIdParent());
    //             $categoryData['category_parent_name'] = $parent->getName();
    //         }else{
    //             $categoryData['category_parent_name'] = '-';
    //         }

    //         $categoriesData[] = $categoryData;
    //     }

    //     $errorHandler = $this->errorHandler;
    //     require("./views/backend/formpost.php");
    // }
    
    // public function handleEditPost() : void
    // {
    //     $this->authenticator->ensureAdmin();

    //     $postData = $_GET;
    //     $id = isset($postData['id']) ? $postData['id'] : null;
    // }
    
    // public function handleDeletePost() : void {
    //     $this->authenticator->ensureAdmin();

    //     $postData = $_GET;
    //     $id = isset($postData['id']) ? $postData['id'] : null;
    // }
    



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

    public function addMessage(string $messageCode, string $domain, string $type){
        $message = $this->translationService->get($messageCode,$domain);
        $this->errorHandler->addFlashMessage($message, $type);
    }
    
}



