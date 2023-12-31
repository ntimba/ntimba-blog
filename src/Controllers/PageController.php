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
use Portfolio\Ntimbablog\Models\CommentManager;
use Portfolio\Ntimbablog\Models\FilesManager;
use Portfolio\Ntimbablog\Models\Page;
use Portfolio\Ntimbablog\Models\PageManager;
use Portfolio\Ntimbablog\Models\PostManager;
use Portfolio\Ntimbablog\Models\UserManager;
use Portfolio\Ntimbablog\Service\Authenticator;
use Portfolio\Ntimbablog\Service\MailService;
use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;

class PageController extends CRUDController
{  

    private $post;
    private $postManager;
    private $comment;
    private $commentManager;
    private $user;
    private $userManager;
    private $pageManager;
    private $page;
    private $fileManager;
    private $categoryManager;

        
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
        $this->commentManager = new CommentManager($db, $stringUtil);
        $this->userManager = new UserManager($db);
        $this->pageManager = new PageManager($db, $stringUtil);
        $this->fileManager = new FilesManager($response);
        $this->page = new Page($stringUtil);
        $this->categoryManager = new CategoryManager($db, $stringUtil);

    }
    

    /**
     * This method handles the creation of a page.
     */
    public function create(): void 
    {
        $this->authenticator->ensureAdmin();

        $data = $this->request->getAllPost();
        if( $this->request->file('featured_image', '') ){
            $data['featured_image'] = $this->request->file('featured_image');
        }

        if($this->validationService->validatePageData($data)){
            if( $this->pageManager->getPageId($data['title']) ){
                $warningMessage = $this->translationService->get('PAGE_TITLE_EXIST','pages');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");

                $this->response->redirect('index.php?action=pages');
                return;
            }

            if( $this->pageManager->slugExists($data['slug']) ){
                $warningMessage = $this->translationService->get('PAGE_SLUG_EXIST','pages');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");
                
                $this->response->redirect('index.php?action=pages');
                return;
            }

            $this->page->setTitle($data['title']);
            $this->page->setContent($data['content']);
            $this->page->setSlug($data['slug']);
            if( $data['action'] === 'publish' ){
                $this->page->setStatus(true);
            }else{
                $this->page->setStatus(false);
            }
            $this->page->setUserId($this->sessionManager->get('user_id'));

            if(isset($data['featured_image']) && $data['featured_image']['size'] > 0)
            {
                $documentRoot = $this->request->getDocumentRoot();
                $featuredImage = $this->fileManager->importFile($data['featured_image'],  $documentRoot .'/assets/uploads/');
                $this->page->setFeaturedImagePath($featuredImage);
            }

            if( $this->pageManager->create($this->page) ){
                $successMessage = $this->translationService->get('PAGE_ADDED','pages');
                $this->errorHandler->addFlashMessage($successMessage, "success");

                $this->response->redirect('index.php?action=pages');
                return;
            }else{
                $warningMessage = $this->translationService->get('PAGE_NOT_ADDED','pages');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");

                $this->response->redirect('index.php?action=pages');
                return;
            }
        }
        
        $errorHandler = $this->errorHandler;
        require("./views/backend/formpage.php");
    }

    public function read(): void
    {

    }
    
    /**
     * This method handles the updating of the page's content.
     */
    public function update(): void 
    {
        $this->authenticator->ensureAdmin();

        $pageData = $this->request->getAllPost();
        $pageId = (int) $this->request->get('id');
        $page = $this->pageManager->read($pageId);
        
        if( !$page ){
            $this->errorHandler->addMessage("PAGE_DOES_NOT_EXIST", 'pages', 'warning');
            $this->response->redirect('index.php?action=pages');
            return;
        }

        $this->page->setId($pageId);
        $this->page->setTitle($pageData['title']);
        $this->page->setContent($pageData['content']);
        $this->page->setSlug($pageData['slug']);

        if( $pageData['action'] === 'publish' ){
            $this->page->setStatus(true);
        }else{
            $this->page->setStatus(false);
        }

        $this->page->setUserId($this->sessionManager->get('user_id'));

        if( $this->request->file('featured_image', '') ){
            $pageData['featured_image'] = $this->request->file('featured_image');
        }

        if(isset($pageData['featured_image']) && $pageData['featured_image']['size'] > 0)
        {
            $documentRoot = $this->request->getDocumentRoot();
            $featuredImage = $this->fileManager->importFile($pageData['featured_image'],  $documentRoot .'/assets/uploads/');
            $this->page->setFeaturedImagePath($featuredImage);
        }
      
        if( $this->pageManager->update($this->page) ){
            $this->errorHandler->addMessage("PAGE_UPDATED", 'pages', 'success');
            $this->response->redirect('index.php?action=pages');
            return;
        }else{

            $this->errorHandler->addMessage("CANT_UPDATE_PAGE", 'pages', 'warning');
            $this->response->redirect('index.php?action=pages');
            return;
        }        
    }
    

    /**
     * This method handles the deletion of a page.
     */
    public function delete(int $id): void {
        $this->authenticator->ensureAdmin();
        $this->pageManager->delete($id);
    }

    /**
     * This method retrieves the admin's information.
     */
    private function getAdminData(): array
    {
        $users = $this->userManager->getAllUsers();
        $admin = [];
        foreach( $users as $user ){
            if( $user->getRole() === 'admin' ){
                $admin['firstname'] = $user->getFirstname();
                $admin['fullname'] = $user->getFullname();
                $admin['email'] = $user->getEmail();
                $admin['biography'] = $user->getBiography();
            }
        };

        return $admin;
    }

    /**
     * This method handles the display of the homepage.
     * It displays the admin's information.
     */
    public function handleHomePage() : void
    {        
        $adminData = $this->getAdminData();

        $footerMenu = $this->layoutHelper->footerHelper();

        $errorHandler = $this->errorHandler;

        require("./views/frontend/home.php");
    }

    /**
     * This method manages the contact page. It allows sending a message to the admin.
     */
    public function handleContactPage() : void
    {   
        if( $this->validationService->validateContactForm($this->request->getAllPost()) )
        {
            $messageData = $this->request->getAllPost();

            $admin = $this->getAdminData();

            $protocol = $this->request->getProtocol();
            $fullName = $messageData['full_name'];
            $email = $admin['email'];
            $replyTo = $messageData['email'];
            $subject = $messageData['subject'];
            $messageContent = $messageData['message'];
            $emailBody = 'Views/emails/visitorscontact.php';
            
            $wasSent = $this->mailService->prepareEmail( $fullName,  $email,  $replyTo,  $subject,  $messageContent,  $emailBody);

            if( $wasSent ){
                $this->errorHandler->addMessage("MESSAGE_SENT", 'contact', 'success');
                $this->response->redirect('index.php?action=contact');
                return;
            }
        }
        
        $footerMenu = $this->layoutHelper->footerHelper();

        $errorHandler = $this->errorHandler;        
        require("./views/frontend/contact.php");
    }

    /**
     * This page manages the Dashboard page.
     * It displays the total number of articles, comments, and users.
     * It also displays the latest comments and the most commented articles.
     */
    public function handleDashboardPage() : void
    {
        $this->authenticator->ensureAdmin();

        $today = date('Y-m-d');
        $categories = $this->categoryManager->getAll();
        $categoriesData = [];
        foreach($categories as $category){

            $categoryData['name'] = $category->getName();

            $categoriesData[] = $categoryData;
        }

        $posts = $this->postManager->getAll();
        $lastPosts = array_slice($posts, -10);

        $lastPostsData = [];
        foreach( $lastPosts as $lastPost  ){
            $category = $this->categoryManager->read($lastPost->getCategoryId());
            if( $this->totalPostComments($lastPost->getId()) > 0 ){
                $lastPostData['total_comments'] = $this->totalPostComments( $lastPost->getId() );
                $lastPostData['title'] = $lastPost->getTitle();
                $lastPostData['image'] = $lastPost->getFeaturedImagePath();
                $lastPostData['publication_date'] = $this->stringUtil->getForamtedDate( $lastPost->getPublicationDate() );
                $lastPostData['category'] = $category->getName();
                $lastPostsData[] = $lastPostData;
            }
        }
        usort($lastPostsData, function ($a, $b) {
            return $b['total_comments'] - $a['total_comments'];
        });
        
        $totalItems = $this->commentManager->getTotalCommentsCount();
        $itemsPerPage = 7;
        $currentPage = intval($this->request->get('page')) ?? 1;
        $linkParam = 'dashboard';
        
        $fetchUsersCallback = function($offset, $limit){
            return $this->commentManager->getCommentsByPage($offset, $limit);
        };
        
        $paginator = new Paginator($this->request, $totalItems, $itemsPerPage, $currentPage,$linkParam , $fetchUsersCallback);


        $comments = $paginator->getItemsForCurrentPage();
        $unapprovedComments = [];
        foreach( $comments as $comment ){
            $user = $this->userManager->read( $comment->getUserId() );

            if( !$comment->getStatus()){
                $commentData['publish_by'] = $user->getFullName() ?? $user->getUsername;
                $commentData['publish_by_image'] = $user->getProfilePicture();
                $commentData['content'] = $this->stringUtil->postExcerpt( $comment->getContent(), 50 );
                $commentData['date'] = $this->stringUtil->getForamtedDate( $comment->getCommentedDate() );

                $unapprovedComments[] = $commentData;
            }

        }

        $totalPosts = count($this->postManager->getAll());
        $totalPublishedPosts = $this->postManager->getTotalPublishedPosts();

        $totalComments = count($this->commentManager->getAllComments());
        $totalApprovedComments = $this->commentManager->getTotalApprovedComments();
        
        $totalUsers = count($this->userManager->getAllUsers());
        $totalActiveUsers = $this->userManager->getTotalActiveUsers();

        $footerMenu = $this->layoutHelper->footerHelper();
        $paginationLinks = $paginator->getPaginationLinks($currentPage, $paginator->getTotalPages());
        $errorHandler = $this->errorHandler;
        require("./views/backend/dashboard.php");
    }

    /**
     * This method returns the total number of comments.
     */
    public function totalPostComments(int $postId) : int | null
    {
        return count( $this->commentManager->getPostComments($postId) );
    }

    /**
     * This method handles the display of comments on the admin side.
     */
    public function handleCommentsPage() : void
    {
        $this->authenticator->ensureAdmin();

        $footerMenu = $this->layoutHelper->footerHelper();
        
        require("./views/backend/comments.php");
    }

    /**
     * This method handles the display of pages on the admin side.
     */
    public function handlePages() : void
    {
        $this->authenticator->ensureAdmin();

        $totalItems = $this->pageManager->getTotalPagesCount();
        $itemsPerPage = 10;
        $currentPage = intval($this->request->get('page')) ?? 1;
        $linkParam = 'pages';
        
        $fetchUsersCallback = function($offset, $limit){
            return $this->pageManager->getPagesByPage($offset, $limit);
        };
        
        $paginator = new Paginator($this->request, $totalItems, $itemsPerPage, $currentPage,$linkParam , $fetchUsersCallback);
        
        $pages = $paginator->getItemsForCurrentPage();

        $pagesData = [];
        foreach( $pages as $page ){
            $pageData['page_id'] = $page->getId();
            $pageData['title'] = $page->getTitle();
            $pageData['slug'] = $page->getSlug();
            $pageData['content'] = $page->getContent();
            $pageData['publication_date'] = $this->stringUtil->getForamtedDate( $page->getPublicationDate());
            $pageData['update_date'] = $this->stringUtil->getForamtedDate('');
            $pageData['featured_image_path'] = $page->getFeaturedImagePath();
            $pageData['status'] = $page->getStatus();
            $pageData['user_id'] = $page->getUserId();

            $pagesData[] = $pageData; 
        }

        $this->sessionManager->set('shared_pages', $pagesData);
        
        $sharedData = $this->sessionManager->get('shared_pages');
        $footerMenu = $this->layoutHelper->footerHelper();

        $paginationLinks = $paginator->getPaginationLinks($currentPage, $paginator->getTotalPages());
        $errorHandler = $this->errorHandler;
        require("./views/backend/pages.php");
    }
    
    
    /**
     * This method handles the display of a page.
     */
    public function handlePage(): void
    {
        $data = $this->request->getAllGet();

        $pageId = (int) $data['id'];

        if( !$this->pageManager->read($pageId) ){
            $this->response->redirect('index.php?action=home');    
        }

        $page = $this->pageManager->read($pageId);

        $pageData = [];
        $pageData['page_title'] = $page->getTitle();
        $pageData['page_featured_image_path'] = $page->getFeaturedImagePath();
        $pageData['page_content'] = $page->getContent();
        $pageData['page_status'] = $page->getStatus();
               
        require("./views/frontend/page.php");
    }


    /**
     * This method handles the actions chosen by the user
     * and redirects to the appropriate page.
     */
    public function pageModify() : void
    {
        $this->authenticator->ensureAdmin();

        $data = $this->request->getAllPost();
        if( !isset($data['action']) ){
            $this->errorHandler->addMessage("CHOOSE_AN_ACTION", 'pages', 'warning');
            $this->response->redirect('index.php?action=pages');
            return;
        }

        if( !isset($data['page_ids']) ){
            $this->errorHandler->addMessage("CHOOSE_A_PAGE", 'pages', 'warning');
            $this->response->redirect('index.php?action=pages');
            return;
        }

        if( $data['action'] === 'publish' ){
            if( count($data['page_ids']) > 0 ){
                foreach( $data['page_ids'] as $id ){
                    $id = (int) $id;
                    $this->togglePageStatus($id, true);
                }
            }

            $this->errorHandler->addMessage("PAGE_PUBLISHED", 'pages', 'success');
            $this->response->redirect('index.php?action=pages');
            
        }elseif( $data['action'] === 'unpublish' ){
            if( count($data['page_ids']) > 0 ){
                foreach( $data['page_ids'] as $id ){
                    $id = (int) $id;
                    $this->togglePageStatus($id, false);
                }
            }

            $this->errorHandler->addMessage("PAGE_UNPUBLISHED", 'pages', 'success');
            $this->response->redirect('index.php?action=pages');

        }elseif( $data['action'] === 'update' ){
            if( count($data['page_ids']) === 1 ){
                $pageId = (int) $data['page_ids'][0];
                $page = $this->pageManager->read($pageId);
                
                $errorHandler = $this->errorHandler;
                require("./views/backend/editpage.php");
            }else{
                $this->errorHandler->addMessage("CHOOSE_ONLY_ONE_POST", 'pages', 'warning');
                $this->response->redirect('index.php?action=pages');
                return;
            }
        }elseif( $data['action'] === 'delete' ){
            if( count($data['page_ids']) > 0 ){
                foreach( $data['page_ids'] as $id ){
                    $id = (int) $id;
                    $this->delete($id);
                }
            }
            $this->errorHandler->addMessage("PAGE_DELETED", 'pages', 'success');
            $this->response->redirect('index.php?action=pages');
        }

    }

    private function togglePageStatus(int $pageId, bool $newStatus) : void
    {
        $data = $this->request->getAllPost();

        $page = $this->pageManager->read($pageId);
        $page->setStatus($newStatus); 
        $this->pageManager->update($page);
    }

    
    public function handleDefault() : void
    {
        $this->handleHomePage();
    }

}



