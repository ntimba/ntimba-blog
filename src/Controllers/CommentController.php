<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Helpers\StringUtil;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Http\SessionManager;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Models\CommentManager;
use Portfolio\Ntimbablog\Models\Comment;
use Portfolio\Ntimbablog\Models\PostManager;
use Portfolio\Ntimbablog\Models\UserManager;
use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;

class CommentController
{
    private Database $db;
    private StringUtil $stringUtil;
    private Request $request;
    private ValidationService $validationService;
    private SessionManager $sessionManager;
    private ErrorHandler $errorHandler;
    private TranslationService $translationService;
    private HttpResponse $response;
    private UserController $userController;


    public function __construct(Database $db, StringUtil $stringUtil, Request $request, ValidationService $validationService, SessionManager $sessionManager, ErrorHandler $errorHandler, TranslationService $translationService, HttpResponse $response, UserController $userController)
    {
        $this->db = $db;
        $this->stringUtil = $stringUtil;
        $this->request = $request;
        $this->validationService = $validationService;
        $this->sessionManager = $sessionManager;
        $this->errorHandler = $errorHandler;
        $this->translationService = $translationService;
        $this->response = $response;
        $this->userController = $userController;
    }
    
    public function getCommentsByPostId(int $postId) : array | bool
    {
        $commentManager = new CommentManager($this->db, $this->stringUtil);
        $comments = $commentManager->getPostComments($postId);
        
        return $comments;
    }

    public function modifyComment() : void
    {
        $this->userController->handleAdminPage();

        $data = $this->request->getAllPost();
 
        if( !isset( $data['comment_ids'] ) ){
            $errorMessage = $this->translationService->get('CHOOSE_A_COMMENT','comments');
            $this->errorHandler->addFlashMessage($errorMessage, "warning");
            $this->response->redirect('index.php?action=comments');
            return;
        }

        
        if( $this->request->post('action') === 'approve' ) {
            
            foreach( $data['comment_ids'] as $comment_id ){
                $commentManager = new CommentManager($this->db, $this->stringUtil);
                $comment_id = (int) $comment_id;

                $comment = $commentManager->getComment($comment_id);
                $comment->setStatus(true);
                $commentManager->updateComment($comment);
            }


            $successMessage = $this->translationService->get('COMMENT_APPROVED','comments');
            $this->errorHandler->addFlashMessage($successMessage, "success");
            $this->response->redirect('index.php?action=comments');
            
        } elseif( $this->request->post('action') === 'disapprove' ) {

            // désapprouver le commentaire
            foreach( $data['comment_ids'] as $comment_id ){
                $commentManager = new CommentManager($this->db, $this->stringUtil);
                $comment_id = (int) $comment_id;

                $comment = $commentManager->getComment($comment_id);
                $comment->setStatus(false);
                $commentManager->updateComment($comment);
            }

            $successMessage = $this->translationService->get('COMMENT_DISAPPROVED','comments');
            $this->errorHandler->addFlashMessage($successMessage, "success");
            $this->response->redirect('index.php?action=comments');
            
        } elseif( $this->request->post('action') === 'delete' ){

            foreach( $data['comment_ids'] as $comment_id ){
                $commentManager = new CommentManager($this->db, $this->stringUtil);
                $comment_id = (int) $comment_id;

                $commentManager->deleteComment($comment_id);
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
    
    public function addComment() : void
    {
        $this->userController->handleAdminPage();

        $data = $this->request->getAllPost();
        if($this->validationService->addCommentValidateField($data) ){
            
            $commentManager = new CommentManager($this->db, $this->stringUtil);
            $comment = new Comment();
            
            $postId = (int) $this->request->post('post_id');
            $content = $this->request->post('comment_content');
            $userId = $this->sessionManager->get('user_id');
            $status = false;
            $clientIp = $this->request->getClientIp();

            $comment->setPostId($postId);
            $comment->setContent($content);
            $comment->setUserId($userId);
            $comment->setStatus($status);
            $comment->setIpAddress($clientIp);

            $commentManager->addComment($comment);

            $successMessage = $this->translationService->get('COMMENT_SUCCESS','comments');
            $this->errorHandler->addFlashMessage($successMessage, "success");
            $this->response->redirect('index.php?action=post&id='. $data['post_id']);

        }else{
            $errorMessage = $this->translationService->get('FILL_COMMENT_FORM','comments');
            $this->errorHandler->addFlashMessage($errorMessage, "warning");
            $this->response->redirect('index.php?action=post&id='. $data['post_id']);
        }
        
        $commentManager = new CommentManager($this->db, $this->stringUtil);
    }

    public function handleComments() :void
    {
        $this->userController->handleAdminPage();

        $commentManager = new CommentManager($this->db, $this->stringUtil);
        $comments = $commentManager->getAllComments();

        $commentsData = [];
        foreach( $comments as $comment ){

            $userManager = new UserManager($this->db, $this->stringUtil);
            $user = $userManager->getUser( $comment->getUserid() );

            $postManager = new PostManager($this->db, $this->stringUtil);
            $post = $postManager->getPost($comment->getPostid());
            
            $commentData['comment_id'] = $comment->getId();
            $commentData['comment_content'] = $comment->getContent();
            $commentData['comment_date'] = $comment->getCommentedDate();
            $commentData['comment_post_title'] = $post->getTitle();
            $commentData['comment_status'] = $comment->getStatus();
            $commentData['comment_user'] = $user->getUsername() ?? $user->getFullName();
            $commentData['comment_user_image'] = $user->getProfilePicture();
            $commentData['comment_user_ipaddress'] = $comment->getIpAddress();

            $commentsData[] = $commentData;
        }
        
        $errorHandler = $this->errorHandler;
        require("./views/backend/comments.php");
    }
}
    



