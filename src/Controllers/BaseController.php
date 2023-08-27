<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;


use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Service\MailService;
use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;
use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Http\SessionManager;
use Portfolio\Ntimbablog\Helpers\StringUtil;
use Portfolio\Ntimbablog\Service\Authenticator;

abstract class BaseController {
    protected ErrorHandler $errorHandler;
    protected MailService $mailService;
    protected TranslationService $translationService;
    protected ValidationService $validationService;
    protected Request $request;
    protected Database $db;
    protected HttpResponse $response;
    protected SessionManager $sessionManager;
    protected StringUtil $stringUtil;
    protected Authenticator $authenticator;

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
    ) {
        $this->errorHandler = $errorHandler;
        $this->mailService = $mailService;
        $this->translationService = $translationService;
        $this->validationService = $validationService;
        $this->request = $request;
        $this->db = $db;
        $this->response = $response;
        $this->sessionManager = $sessionManager;
        $this->stringUtil = $stringUtil;
        $this->authenticator = $authenticator;
    }

}


