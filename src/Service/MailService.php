<?php
// Src/Lib/EmailService.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Service;


class MailService {

    public function sendEmail(string $to, string $subject, string $message, string $headers) : bool
    {
        if(mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }


    public function prepareConfirmationEmail(string $fullName, string $email, string $confirmationLink) : bool
    {
        $subject = "Confirmation d'inscription au blog de ntimba.com.";
        $message = $this->getConfirmationEmailBody($fullName, $confirmationLink);
        $headers = 'From: webmaster@' . $_SERVER['HTTP_HOST'] . "\r\n" .
                   'Reply-To: webmaster@' . $_SERVER['HTTP_HOST'] . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        return $this->sendEmail($email, $subject, $message, $headers);
    }

    private function getConfirmationEmailBody(string $fullName, string $confirmationLink) : string
    {
        // Start output buffering
        ob_start();

        // Include the email template
        require('Views/emails/confirmaccount.php');

        // Get the contents of the buffer
        $message = ob_get_contents();

        // End output buffering and clean the buffer
        ob_end_clean();

        return $message;
    }
}
