<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Helpers\StringUtil;
use Portfolio\Ntimbablog\Helpers\LayoutHelper;
use Portfolio\Ntimbablog\Helpers\Paginator;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Http\SessionManager;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Models\CommentManager;
use Portfolio\Ntimbablog\Models\Comment;
use Portfolio\Ntimbablog\Models\PostManager;
use Portfolio\Ntimbablog\Models\UserManager;
use Portfolio\Ntimbablog\Service\Authenticator;
use Portfolio\Ntimbablog\Service\MailService;
use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;

class CommentController extends BaseController
{  
    private $userManager;
    private $postManager;
    private $commentManager;

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

        $this->userManager = new UserManager($db);
        $this->postManager = new PostManager($db, $stringUtil);
        $this->commentManager = new CommentManager($db, $stringUtil);
    }
 
    public function getCommentsByPostId(int $postId) : array | bool
    {
        $commentManager = new CommentManager($this->db, $this->stringUtil);
        $comments = $commentManager->getPostComments($postId);
        
        return $comments;
    }


    /**
     * This method handles the following actions:
     * - Approve a comment
     * - Disapprove a comment
     * - Delete a comment
     */
    public function modifyComment() : void
    {
        $this->authenticator->ensureAdmin();

        $data = $this->request->getAllPost();
        if( !isset( $data['comment_ids'] ) ){
            $errorMessage = $this->translationService->get('CHOOSE_A_COMMENT','comments');
            $this->errorHandler->addFlashMessage($errorMessage, "warning");
            $this->response->redirect('index.php?action=comments');
            return;
        }
        
        if( $this->request->post('action') === 'approve' ) {
            
            foreach( $data['comment_ids'] as $comment_id ){
                $comment_id = (int) $comment_id;

                $comment = $this->commentManager->getComment($comment_id);
                $comment->setStatus(true);
                $this->commentManager->updateComment($comment);
            }
            $successMessage = $this->translationService->get('COMMENT_APPROVED','comments');
            $this->errorHandler->addFlashMessage($successMessage, "success");
            $this->response->redirect('index.php?action=comments');
            
        } elseif( $this->request->post('action') === 'disapprove' ) {
            foreach( $data['comment_ids'] as $comment_id ){
                $comment_id = (int) $comment_id;

                $comment = $this->commentManager->getComment($comment_id);
                $comment->setStatus(false);
                $this->commentManager->updateComment($comment);
            }

            $successMessage = $this->translationService->get('COMMENT_DISAPPROVED','comments');
            $this->errorHandler->addFlashMessage($successMessage, "success");
            $this->response->redirect('index.php?action=comments');
            
        } elseif( $this->request->post('action') === 'delete' ){

            foreach( $data['comment_ids'] as $comment_id ){
                $comment_id = (int) $comment_id;
                $this->commentManager->deleteComment($comment_id);
            }

            $successMessage = $this->translationService->get('COMMENT_DELETED','comments');
            $this->errorHandler->addFlashMessage($successMessage, "success");
            $this->response->redirect('index.php?action=comments');
        }
        else{
            $warningMessage = $this->translationService->get('CHOOSE_AN_ACTION','comments');
            $this->errorHandler->addFlashMessage($warningMessage, "warning");
            $this->response->redirect('index.php?action=comments');

        }     
    }
    
    /** 
     * This method allows the addition of a comment to an article.
     * 
     */
    public function addComment() : void
    {
        $this->authenticator->ensureAuditedUserAuthentication();

        $data = $this->request->getAllPost();
        if($this->validationService->addCommentValidateField($data) ){
            
            $comment = new Comment();

            
            $postId = (int) $this->request->post('post_id');
            $content = $this->request->post('comment_content');
            $userId = $this->sessionManager->get('user_id');
            $status = false;

            $comment->setPostId($postId);
            $comment->setContent($content);
            $comment->setUserId($userId);
            $comment->setStatus($status);

            $this->commentManager->addComment($comment);

            $successMessage = $this->translationService->get('COMMENT_SUCCESS','comments');
            $this->errorHandler->addFlashMessage($successMessage, "success");
            $this->response->redirect('index.php?action=post&id='. $data['post_id']);

        }else{
            $errorMessage = $this->translationService->get('FILL_COMMENT_FORM','comments');
            $this->errorHandler->addFlashMessage($errorMessage, "warning");
            $this->response->redirect('index.php?action=post&id='. $data['post_id']);
        }
                
    }

    /**
     * This method handles the display of comments on the admin side.
     * It uses the Paginator class to display them per page.
     */
    public function handleComments() :void
    {
        $this->authenticator->ensureAdmin();
        $totalItems = $this->commentManager->getTotalCommentsCount();
        $itemsPerPage = 10;
        $currentPage = intval($this->request->get('page')) ?? 1;
        $linkParam = 'comments';
        
        $fetchUsersCallback = function($offset, $limit){
            return $this->commentManager->getCommentsByPage($offset, $limit);
        };
        
        $paginator = new Paginator($this->request, $totalItems, $itemsPerPage, $currentPage,$linkParam , $fetchUsersCallback);

        
        $comments = $paginator->getItemsForCurrentPage();


        $commentsData = [];
        foreach( $comments as $comment ){

            $userManager = new UserManager($this->db, $this->stringUtil);
            $user = $this->userManager->read( $comment->getUserid() );
            $post = $this->postManager->read( $comment->getPostid() );
            
            $commentData['comment_id'] = $comment->getId();
            $commentData['comment_content'] = $comment->getContent();
            $commentData['comment_date'] = $this->stringUtil->getForamtedDate($comment->getCommentedDate());
            $commentData['comment_post_title'] = $post->getTitle();
            $commentData['comment_status'] = $comment->getStatus();
            $commentData['comment_user'] = $user->getUsername() ?? $user->getFullName();
            $commentData['comment_user_image'] = $user->getProfilePicture();

            $commentsData[] = $commentData;
        }

        $paginationLinks = $paginator->getPaginationLinks($currentPage, $paginator->getTotalPages());
        $errorHandler = $this->errorHandler;
        require("./views/backend/comments.php");
    }
}
    



