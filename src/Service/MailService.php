<?php
// Src/Lib/EmailService.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;

use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Service\EnvironmentService;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class MailService {

    private Request $request;
    private EnvironmentService $environmentService;

    public function __construct(Request $request, EnvironmentService $environmentService )
    {
        $this->request = $request;
        $this->environmentService = $environmentService;
    }

    public function sendEmail(string $to, string $subject, string $htmlMessage, string $replyTo, string $fromName, string $domainName): bool
    {
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();

            $mailHost = $this->environmentService->getMailHost();
            $mailUsername = $this->environmentService->getMailUsername();
            $mailPassword = $this->environmentService->getMailPassword();
            $mailPort = $this->environmentService->getMailPort();
                        
            $mail->isSMTP();
            $mail->Host = $mailHost;
            $mail->SMTPAuth = true;
            $mail->Username = $mailUsername;
            $mail->Password = $mailPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $mailPort;

            $mail->setFrom($mailUsername);
            $mail->addAddress($to);
            $mail->addReplyTo($replyTo, $fromName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlMessage;

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Erreur PHPMailer : ' . $e->getMessage();
            return false;
        }
        
    }

    public function prepareEmail(string $fullName, string $email, string $replyTo, string $subject, string $messageContent, string $emailBodyTemplate): bool {
        $domainName = $this->request->getDomainName();
        $fromName = 'Ntimba Blog'; 
        $message = $this->getEmailBody($fullName, $messageContent, $emailBodyTemplate);

        return $this->sendEmail($email, $subject, $message, $replyTo, $fromName, $domainName);
    }
    
    private function getEmailBody(string $fullName, string $messageContent, string $emailBodyTemplate): string
    {
        ob_start();
        require($emailBodyTemplate); 

        $message = ob_get_contents();
        ob_end_clean();

        return $message;
    }
}




