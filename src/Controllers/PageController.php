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
        $this->commentManager = new CommentManager($db, $stringUtil);
        $this->userManager = new UserManager($db);
        $this->pageManager = new PageManager($db, $stringUtil);
        $this->fileManager = new FilesManager($response);
        $this->page = new Page($stringUtil);
        $this->categoryManager = new CategoryManager($db, $stringUtil);

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
        // debug($data);
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

            // importer l'image s'il y en a une 
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
    
    public function update(): void 
    {
        // Afficher un formulaire de mise à jour
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
      // $this->post->setTitle
        if( $this->request->file('featured_image', '') ){
            $pageData['featured_image'] = $this->request->file('featured_image');
        }
            // importer le nouveau image
        if(isset($pageData['featured_image']) && $pageData['featured_image']['size'] > 0)
        {
            $documentRoot = $this->request->getDocumentRoot();
            $featuredImage = $this->fileManager->importFile($pageData['featured_image'],  $documentRoot .'/assets/uploads/');
            $this->page->setFeaturedImagePath($featuredImage);
        }
      
        // Enregistrer l'image dans la base de données
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
    

    
    public function delete(int $id): void {
        // pour supprimer un élément 
        $this->authenticator->ensureAdmin();

        $this->pageManager->delete($id);
    }

    public function handleHomePage() : void
    {        
        $errorHandler = $this->errorHandler;
        require("./views/frontend/home.php");
    }

    public function handlePortfolioPage() : void
    {
        require("./views/frontend/portfolio.php");
    }

    public function handleContactPage() : void
    {
        
        if( $this->validationService->validateContactForm($this->request->getAllPost()) )
        {
            $messageData = $this->request->getAllPost();

            // recupéréer l'adresse mail de l'admin
            $users = $this->userManager->getAllUsers();
            $admin = [];
            foreach( $users as $user ){
                if( $user->getRole() === 'admin' ){
                    $admin['fullname'] = $user->getFullname();
                    $admin['email'] = $user->getEmail();
                }
            };

            $protocol = $this->request->getProtocol();
            $fullName = $messageData['full_name'];
            // $adminName = $admin['fullname'];
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
        
        

        $errorHandler = $this->errorHandler;        
        require("./views/frontend/contact.php");
    }

    public function handleDashboardPage() : void
    {
        $this->authenticator->ensureAdmin();

        // Ce bout de code permet de lister les catégories dans la page dashboard
        $categories = $this->categoryManager->getAll();
        $categoriesData = [];
        foreach($categories as $category){

            $categoryData['name'] = $category->getName();

            $categoriesData[] = $categoryData;
        }

        // Ce bout de code permet de lister les 3 dernier articles publié
        $posts = $this->postManager->getAll();
        $lastPosts = array_slice($posts, -10);

        $lastPostsData = [];
        foreach( $lastPosts as $lastPost  ){
            $category = $this->categoryManager->read($lastPost->getCategoryId());
            if( $this->totalPostComments($lastPost->getId()) > 0 ){
                $lastPostData['total_comments'] = $this->totalPostComments( $lastPost->getId() );
                $lastPostData['title'] = $lastPost->getTitle();
                $lastPostData['publication_date'] = $this->stringUtil->getForamtedDate( $lastPost->getPublicationDate() );
                $lastPostData['category'] = $category->getName();
    
                // trier avant d'arranger dans la variable lastPostsData.
                $lastPostsData[] = $lastPostData;
            }
        }
        // Ce bout de code permet de trier les éléments du tableau
        usort($lastPostsData, function ($a, $b) {
            return $b['total_comments'] - $a['total_comments'];
        });

        // Ce bout de code permet de rajouter le ranking
        $ranking = 1;
        foreach ($lastPostsData as &$lastPostData) {
            $lastPostData['ranking'] = $ranking++;
        }
        
        $today = date('Y-m-d');
        // Afficher uniquement les articles publier aujourd'hui
        $allComments = $this->commentManager->getAllComments();
        $todaysComments = [];
        foreach( $allComments as $comment ){
            $user = $this->userManager->read( $comment->getUserId() );

            $position = strpos($comment->getCommentedDate(), $today);
            if ($position !== false) {
                $commentData['hour'] = $this->stringUtil->getHourFromDateTime( $comment->getCommentedDate() );
                $commentData['content'] = $this->stringUtil->displayFirst150Characters( $comment->getContent() );
                $commentData['publish_by'] = $user->getFullName();
                        
                $todaysComments[] = $commentData;
            }
        }


        $totalPosts = count($this->postManager->getAll());
        $totalPublishedPosts = $this->postManager->getTotalPublishedPosts();

        $totalComments = count($this->commentManager->getAllComments());
        $totalApprovedComments = $this->commentManager->getTotalApprovedComments();
        
        $totalUsers = count($this->userManager->getAllUsers());
        $totalActiveUsers = $this->userManager->getTotalActiveUsers();

        

        $errorHandler = $this->errorHandler;
        require("./views/backend/dashboard.php");
    }

    // retourne le nombre total des de commentaire d'un article
    public function totalPostComments(int $postId) : int | null
    {
        return count( $this->commentManager->getPostComments($postId) );
    }

    public function handleCommentsPage() : void
    {
        $this->authenticator->ensureAdmin();
        require("./views/backend/comments.php");
    }

    public function handlePages() : void
    {
        $this->authenticator->ensureAdmin();

        $pages = $this->pageManager->getAll();

        $pagesData = [];
        foreach( $pages as $page ){
            $pageData['page_id'] = $page->getId();
            $pageData['title'] = $page->getTitle();
            $pageData['slug'] = $page->getSlug();
            $pageData['content'] = $page->getContent();
            $pageData['publication_date'] = $page->getPublicationDate();
            $pageData['update_date'] = $page->getUpdateDate();
            $pageData['featured_image_path'] = $page->getFeaturedImagePath();
            $pageData['status'] = $page->getStatus();
            $pageData['user_id'] = $page->getUserId();

            $pagesData[] = $pageData; 
        }

        $errorHandler = $this->errorHandler;
        require("./views/backend/pages.php");
    }



    public function pageModify() : void
    {
        $this->authenticator->ensureAdmin();
        // recois tout les pare
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
                    // publier les articles
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
                // envoyer à la page qui permet de modifier l'article

                $pageId = (int) $data['page_ids'][0];
                $page = $this->pageManager->read($pageId);
                
                // Afficher les catégories
                $errorHandler = $this->errorHandler;
                require("./views/backend/editpage.php");
            }else{
                $this->errorHandler->addMessage("CHOOSE_ONLY_ONE_POST", 'pages', 'warning');
                $this->response->redirect('index.php?action=pages');
                return;
            }
        }elseif( $data['action'] === 'delete' ){
            if( count($data['page_ids']) > 0 ){
                // supprimer l'article
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

        // recupérer le post qui correspond à cet id
        // changer le status de la méthode
        $page = $this->pageManager->read($pageId);
        $page->setStatus($newStatus); 

        $this->pageManager->update($page);
    }

    
    public function handleDefault() : void
    {
        $this->handleHomePage();
    }

}



