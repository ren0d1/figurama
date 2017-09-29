<?php

class mailer
{
    private static $mailer = null;
    private $mail;

    private function __construct()
    {
        //PHP Mailer function
        require_once('PHPMailer/PHPMailerAutoload.php');

        $this->mail = new PHPMailer();

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $this->mail->isSMTP();                                      // Set mailer to use SMTP
        $this->mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mail->Username = 'tahoren@gmail.com';                 // SMTP username
        $this->mail->Password = 'r1e2n3a4u5d6';                           // SMTP password
        $this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = 587;                                    // TCP port to connect to

        $this->mail->setFrom('noreply@figurama.com', 'Figurama');
        //$this->mail->addAddress('killbyus1@gmail.com');               // Name is optional

        $this->mail->isHTML(true);                                  // Set email format to HTML

        /*
        $mail->Subject = 'Bienvenue sur Figurama!';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        */
    }

    public static function getInstance() {
        if(is_null(self::$mailer)) {
            self::$mailer = new mailer();
        }
        return self::$mailer;
    }

    public function setDestinataire($dest){
        $this->mail->addAddress($dest);
    }

    public function setSubject($subject){
        $this->mail->Subject = $subject;
    }

    public function setBody($body){
        $this->mail->Body = $body;
        $this->setAltBody(strip_tags($body));
    }

    public function setAltBody($alt){
        $this->mail->AltBody = $alt;
    }

    public function send(){
        if(!$this->mail->send()) {
            return false;
        }else{
            return true;
        }
    }
}