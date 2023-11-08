<?php
// Src/Lib/EmailService.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;

use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Http\HttpResponse;


class MailService {

    private Request $request;

    public function __construct(Request $request )
    {
        $this->request = $request;
    }

    public function sendEmail(string $to, string $subject, string $message, string $headers) : bool
    {
        if(mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

    public function prepareEmail(string $fullName, string $email, string $replyTo, string $subject, string $messageContent, string $emailBody)
    {
        $subject = $subject;
        $message = $this->getEmailBody( $fullName, $messageContent, $emailBody);
        $domainName = $this->request->getDomainName();
        $headers = 'From: webmaster@' . $domainName . "\r\n" .
                   'Reply-To: webmaster@' . $domainName . "\r\n" .
                   'X-Mailer: PHP/' . phpversion() . "\r\n".
                   'Content-type:text/html;charset=UTF-8';
                           
        return $this->sendEmail($email, $subject, $message, $headers);
    }

    private function getEmailBody(string $fullName, string $messageContent, string $emailBody) : string
    {
        // Start output buffering
        ob_start();

        // Include the email template
        require($emailBody);

        // Get the contents of the buffer
        $message = ob_get_contents();

        // End output buffering and clean the buffer
        ob_end_clean();

        return $message;
    }
   
}




