<?php 

namespace App\Gateways;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

interface MailGateway {
    public function send(string $subject, string $body);
}

class PHPMailerGateway implements MailGateway {
    private $mailer;

    public function __construct(PHPMailer $mailer) {
        $this->mailer = $mailer;
    }

    public function send(string $subject, string $body) {
        try { 
            // Configurações do servidor
            $this->mailer->SMTPDebug = 2;                                 
            $this->mailer->isSMTP();                                      
            $this->mailer->Host = $_ENV['SMTP_HOST'];
            $this->mailer->SMTPAuth = true;                               
            $this->mailer->Username = $_ENV['SMTP_USER'];                 
            $this->mailer->Password = $_ENV['SMTP_PASS'];                             
            $this->mailer->SMTPSecure = $_ENV['SMTP_SECURE'];                            
            $this->mailer->Port = $_ENV['SMTP_PORT'];                                   

            // Recipientes
            $this->mailer->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_NAME']);
            $this->mailer->addAddress($_ENV['MAIL_TO'], $_ENV['MAIL_TO_NAME']);     

            // Conteúdo
            $this->mailer->isHTML(true);                                  
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->AltBody = strip_tags($body);

            $this->mailer->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: {$this->mailer->ErrorInfo}");
        }
    }
}
