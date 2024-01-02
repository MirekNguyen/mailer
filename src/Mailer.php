<?php

declare(strict_types=1);

namespace App;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private PHPMailer $mail;
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->SMTPAuth = true;
    }
    public function connect(string $host, string $username, string $password, int $port = 25)
    {
        $this->mail->Host = $host;
        $this->mail->Port = $port;
        $this->mail->Username = $username;
        $this->mail->Password = $password;
    }
    public function send(string $message, string $subject, string $fromEmail, string $toEmail)
    {
        $this->mail->setFrom($fromEmail, 'Mailer');
        $this->mail->addAddress($toEmail);
        $this->mail->isHTML(false);
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;
        $this->mail->send();
    }
}
