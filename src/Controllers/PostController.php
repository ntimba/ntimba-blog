<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Helpers\LayoutHelper;
use Portfolio\Ntimbablog\Helpers\StringUtil;
use Portfolio\Ntimbablog\Helpers\Paginator;
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
use Portfolio\Ntimbablog\Models\PageManager;
use Portfolio\Ntimbablog\Models\User;
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
    private $commentManager;
    private $user;
    private $userManager;
    // private $footerMenu;

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
        Authenticator $authenticator,
        LayoutHelper $layoutHelper
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
            $authenticator,
            $layoutHelper
        );
        $this->postManager = new PostManager($db, $stringUtil);
        $this->post = new Post($stringUtil);

        $this->userManager = new UserManager($db);
        $this->user = new User();

        $this->commentManager = new CommentManager($db, $stringUtil);

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
            $authenticator, 
            $layoutHelper
        );

    }

    /**
     * This method handles the creation of an article.
     */
    public function create(): void 
    {
        $this->authenticator->ensureAdmin();

        $this->category->setName('Default');
        $this->category->setSlug('default');

        if(!$this->categoryManager->getCategoryId('Default')){
            $this->categoryManager->create($this->category);
        }

        $data = $this->request->getAllPost(); 
        if( $this->request->file('featured_image', '') ){
            $data['featured_image'] = $this->request->file('featured_image');
        }

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
            if(isset($data['featured_image']) && $data['featured_image']['size'] > 0) {
                try {
                    $documentRoot = $this->request->getDocumentRoot();
                    $featuredImage = $this->fileManager->importFile($data['featured_image'],  $documentRoot .'/assets/uploads/');
                    $fileName = basename($featuredImage);
                    $this->post->setFeaturedImagePath('/assets/uploads/'.$fileName);
                } catch (\Exception $e) {
                    $warningMessage = "Format de l'image non valide. Veuillez télécharger une image valide.";
                    $this->errorHandler->addFlashMessage($warningMessage, "warning");
                    
                    $this->response->redirect('index.php?action=add_post');
                    return;
                }
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
        $this->authenticator->ensureAdmin();
    }
    
    /**
     * This method handles the modification of an article.
     */
    public function update(): void 
    {
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

        if( $this->request->file('image', '') ){
            $postData['image'] = $this->request->file('image');
        }
        if(isset($postData['image']) && $postData['image']['size'] > 0)
        {
            $documentRoot = $this->request->getDocumentRoot();

            $featuredImage = $this->fileManager->importFile($postData['image'], './assets/uploads/');
            $fileName = basename($featuredImage);
            $this->post->setFeaturedImagePath($featuredImage);
        }

        if( $this->postManager->update($this->post) ){
            $this->errorHandler->addMessage("POST_UPDATED", 'posts', 'success');
            $this->response->redirect('index.php?action=posts');
            return;
        }else{

            $this->errorHandler->addMessage("CANT_UPDATE_POST", 'posts', 'warning');
            $this->response->redirect('index.php?action=posts');
            return;
        }
    }
    
    /**
     * This method handles the deletion of an article.
     */
    public function delete(int $id): void {
        $this->authenticator->ensureAdmin();

        $this->postManager->delete($id);
    }

    /**
      * This method handles the display of articles on the admin side.
      */
    public function handlePosts() : void
    {
        $this->authenticator->ensureAdmin();
        
        $data = $this->request->getAllPost();                
        $postManager = new PostManager($this->db, $this->stringUtil);

        $totalItems = $this->postManager->getTotalPostsCount();
        $itemsPerPage = 6;
        $currentPage = intval($this->request->get('page')) ?? 1;
        $linkParam = 'posts';

        $fetchUsersCallback = function($offset, $limit){
            return $this->postManager->getPostsByPage($offset, $limit);
        };

        $paginator = new Paginator($this->request, $totalItems, $itemsPerPage, $currentPage,$linkParam , $fetchUsersCallback);

        $posts = $paginator->getItemsForCurrentPage(); 
        
        $postsData = [];
        foreach( $posts as $post ){

            $categoryManager = new CategoryManager($this->db, $this->stringUtil);
            $category = $this->categoryManager->read($post->getCategoryId());
            
            $postData['post_id'] = $post->getId();
            $postData['title'] = $post->getTitle();
            $postData['slug'] = $post->getSlug();
            $postData['content'] = $post->getContent();
            $postData['publication_date'] = $this->stringUtil->getForamtedDate($post->getPublicationDate());
            $postData['update_date'] = $this->stringUtil->getForamtedDate('');
            $postData['featured_image_path'] = $post->getFeaturedImagePath();
            $postData['status'] = $post->getStatus();
            $postData['category_name'] = $category->getName();
            $postData['user_id'] = $post->getUserId();

            $postsData[] = $postData; 
        }

        $paginationLinks = $paginator->getPaginationLinks($currentPage, $paginator->getTotalPages());
        $errorHandler = $this->errorHandler;
        require("./views/backend/posts.php");
    }
    
    
    /**
      * This method handles user actions and directs to the appropriate page.
      */
    public function postModify() : void
    {
        $this->authenticator->ensureAdmin();
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
                $postId = (int) $data['post_ids'][0];
                $post = $this->postManager->read($postId);
                
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

                $footerMenu = $this->layoutHelper->footerHelper();
                
                $errorHandler = $this->errorHandler;
                require("./views/backend/editpost.php");
            }else{
                $this->errorHandler->addMessage("CHOOSE_ONLY_ONE_POST", 'posts', 'warning');
                $this->response->redirect('index.php?action=posts');
                return;
            }
        }elseif( $data['action'] === 'delete' ){
            if( count($data['post_ids']) > 0 ){
                foreach( $data['post_ids'] as $id ){

                    $id = (int) $id;
                    $this->commentManager->deleteByPostId($id);
                    $this->delete($id);
                }
            }
            $this->errorHandler->addMessage("POST_DELETED", 'posts', 'success');
            $this->response->redirect('index.php?action=posts');
        }

    }


    /**
      * This method handles the display of an article and comments on the client side.
      */
    public function handlePost(): void
    {
        $data = $this->request->getAllGet();
        $postId = (int) $this->request->get('id');

        $postManager = new PostManager($this->db, $this->stringUtil);        
        if( $this->postManager->read($postId) ){
            $post = $this->postManager->read($postId);
        }else{
            $this->response->redirect('index.php?action=blog');
        }

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
        $postData['post_status'] = $post->getStatus();
        
        $comments = $this->commentController->getCommentsByPostId($postId);

        $commentsData = [];
        foreach( $comments as $comment ){
            $userManager = new UserManager($this->db, $this->stringUtil);
            $user = $userManager->read( $comment->getUserId() );

            if( $comment->getStatus() ){
                $commentData['comment_id'] = $comment->getId();
                $commentData['comment_content'] = $comment->getContent();
                $commentData['comment_date'] = $this->stringUtil->getForamtedDate($comment->getCommentedDate());
                $commentData['comment_post_id'] = $comment->getPostId();
                $commentData['comment_user'] = $user->getUsername() ?? $user->getFullName();
                $commentData['comment_user_image'] = $user->getProfilePicture();
                $commentData['comment_status'] = $comment->getStatus();
                $commentData['comment_ipAddress'] = $comment->getIpAddress();
    
                $commentsData[] = $commentData; 
            } 
        }

        $categoriesData = $this->getCategoryList();

        $adminData = $this->getAdminData();
        
        $lastPostData = $this->getLastPosts();

        $footerMenu = $this->layoutHelper->footerHelper();
                
        $errorHandler = $this->errorHandler;
        require("./views/frontend/post.php");
    }


    private function togglePostStatus(int $postId, bool $newStatus) : void
    {
        $data = $this->request->getAllPost();
        $post = $this->postManager->read($postId);
        $post->setStatus($newStatus); 

        $this->postManager->update($post);
    }


    /**
     * This method handles the display of all articles on the client side (Blog).
     */
    public function handleBlogPage() : void 
    {
        $data = $this->request->getAllPost();                
        $postManager = new PostManager($this->db, $this->stringUtil);

        $totalItems = $this->postManager->getTotalPostsCount();
        $itemsPerPage = 6;
        $currentPage = intval($this->request->get('page')) ?? 1;
        $linkParam = 'blog';

        $fetchUsersCallback = function($offset, $limit){
            return $this->postManager->getPostsByPage($offset, $limit);
        };

        $paginator = new Paginator($this->request, $totalItems, $itemsPerPage, $currentPage,$linkParam , $fetchUsersCallback);

        $posts = $paginator->getItemsForCurrentPage(); 

        $postsData = [];
        foreach( $posts as $post )
        {
            $categoryData = [];
            if( $post->getStatus() ){
                $postData['post_id'] = $post->getId();
                $postData['post_title'] = $post->getTitle();
                $postData['post_date'] = $this->stringUtil->getForamtedDate($post->getPublicationDate());
                $postData['post_content'] = $this->stringUtil->displayFirst150Characters( $post->getContent() );
                $postData['post_category'] = $this->categoryManager->getCategoryNameById($post->getCategoryId());
                $postData['post_image'] = $post->getFeaturedImagePath();
                $postData['post_status'] = $post->getStatus();

                $postsData[] = $postData;
            }     
        }

        $adminData = $this->getAdminData();
        $categoriesData = $this->getCategoryList();
        $lastPostData = $this->getLastPosts();
            
        $paginationLinks = $paginator->getPaginationLinks($currentPage, $paginator->getTotalPages());
        $errorHandler = $this->errorHandler;
        require("./views/frontend/blog.php");
    }


    /**
     * This method retrieves the information of the admin.
     */
    public function getAdminData(): array
    {
        $allUsers = $this->userManager->getAllUsers();
        $adminData = [];
        foreach( $allUsers as $user  ){
            if( $user->getRole() === 'admin' ){
                $adminData['image'] = $user->getProfilePicture();
                $adminData['firstname'] = $user->getFirstname();
                $adminData['biography'] = $user->getBiography();
            }
        }
        return $adminData;
    }


    /**
     * This method returns the list of existing categories.
     */
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

    /**
     * This method returns the list of the latest added articles.
     */
    public function getLastPosts() : array|string
    {
        $lastPost = $this->postManager->lastPost();

        if( $lastPost ){
            if( $lastPost->getStatus() ){
                $lastPostData['post_id'] = $lastPost->getId();
                $lastPostData['post_title'] = $lastPost->getTitle();
                $lastPostData['post_date'] = $this->stringUtil->getForamtedDate($lastPost->getPublicationDate());
                $lastPostData['post_content'] = $this->stringUtil->displayFirst150Characters( $lastPost->getContent() );
                $lastPostData['post_image'] = $lastPost->getFeaturedImagePath();
                $lastPostData['post_category'] = $this->categoryManager->getCategoryNameById($lastPost->getCategoryId());
                $lastPostData[] = $lastPostData;
            }
        }else{

        }
        return $lastPostData ?? [];
    }
    
    public function addMessage(string $messageCode, string $domain, string $type): void
    {
        $message = $this->translationService->get($messageCode,$domain);
        $this->errorHandler->addFlashMessage($message, $type);
    }
    
}



