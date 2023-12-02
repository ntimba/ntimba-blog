<?php
// Src/Lib/EmailService.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;

use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Http\HttpResponse;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class MailService {

    private Request $request;
    private $environmentService;

    public function __construct(Request $request )
    {
        $this->request = $request;
        $this->environmentService = new EnvironmentService($this->request);
    }

    public function sendEmail(string $to, string $subject, string $htmlMessage, string $replyTo, string $fromName, string $domainName): bool
    {
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = $this->environmentService->getMailHost();
            $mail->SMTPAuth = true;
            $mail->Username = $this->environmentService->getMailUsername();
            $mail->Password = $this->environmentService->getMailPassword();
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = $this->environmentService->getMailPort();

            $mail->setFrom('webmaster@' . $domainName, $fromName);
            $mail->addAddress($to);
            $mail->addReplyTo($replyTo, $fromName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlMessage;

            $mail->send();
            return true;
        } catch (Exception $e) {

            return false;
        }
        
    }

    public function prepareEmail(string $fullName, string $email, string $replyTo, string $subject, string $messageContent, string $emailBodyTemplate): bool {
        $domainName = $this->request->getDomainName();
        $fromName = 'Nom de l\'expéditeur'; 
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




