<?php 

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Http\SessionManager;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Models\UserManager;

class Authenticator
{
    private Database $db;
    private SessionManager $sessionManager;
    private UserManager $userManager;
    private TranslationService $translationService;
    private ErrorHandler $errorHandler;
    private HttpResponse $response;

    public function __construct(
        Database $db, 
        SessionManager $sessionManager, 
        UserManager $userManager,
        TranslationService $translationService,
        ErrorHandler $errorHandler,
        HttpResponse $response
        )
    {
        $this->db = $db;
        $this->sessionManager = $sessionManager;
        $this->userManager = $userManager;
        $this->translationService = $translationService;
        $this->errorHandler = $errorHandler;
        $this->response = $response;
    }

    private function isAuditedAccount(): bool {
        $user = $this->userManager->read($this->sessionManager->get('user_id'));
        return (bool) $user->getAuditedAccount(); 
    }

    public function ensureAuditedUserAuthentication() : void
    {
        if(!$this->isAuthenticated() || !$this->isAuditedAccount())
        {
            $errorMessage = $this->translationService->get('PROTECTED_PAGE','login');
            $this->errorHandler->addFlashMessage($errorMessage, "danger");

            $this->response->redirect('index.php?action=login');
            return;   
        }
    }

    public function isAuthenticated(): bool 
    {
        // logique pour vérifier si un utilisateur est authentifié
        return (bool) $this->sessionManager->get('user_id');
    }

    public function isAdmin(): bool
    {
        // logique si l'utilisateur est un administrateur
        return $this->sessionManager->get('user_role') === 'admin';
    }

    public function getUserId(): ?int {
        return $this->sessionManager->get('user_id');
    }

    public function ensureAdmin() :void
    {
        if(!$this->isAdmin() || !$this->isAuditedAccount())
        {
            $errorMessage = $this->translationService->get('ACCESS_DENIED','login');
            $this->errorHandler->addFlashMessage($errorMessage, "danger");
            $this->response->redirect('index.php?action=login');

            return;
        }
    }

}


