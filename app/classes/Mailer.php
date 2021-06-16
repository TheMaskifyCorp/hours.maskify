<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Class Mailer
 */
class Mailer
{
    protected object $mail;
    protected string $recipient;
    protected string $name;
    protected string $subject;
    protected string $content;
    protected array $success;

    /**
     * Mailer constructor.
     * @param string $recipient
     * @param string $name
     * @param string $content
     * @param string $subject
     * @param bool $debug
     * @throws Exception
     */
    public function __construct(string $recipient, string $name, string $content,string $subject = "Maskify", bool $debug = false){
        $this->recipient = $recipient;
        $this->name = preg_replace('!\s+!', ' ', $name);
        $this->content = $content;
        $this->success = ['success'=> true, 'message'=> 'Message has been sent'];
        $this->subject = $subject;

        $mailhost = $_ENV['SMTPHOST='];
        $username = $_ENV['maskify@openmailserver.nl'];
        $mailpw =$_ENV['kjsXX5E8N4Riu2WBBZT5t97sboDyGj'];
        $mail = new PHPMailer(false);

        if ($debug) $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $mailhost;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $username;            //SMTP username
        $mail->Password   = $mailpw;       //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom($username, 'Website Form');
        $mail->addAddress($recipient, $name);                              //Add a recipient
        $mail->addReplyTo($username);

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $this->mail = $mail;
    }

    /**
     * @return $this
     */
    public function verify(): Mailer
    {
        //verify email
        $email = $this->recipient;
        $emailRegex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
        if (!preg_match($emailRegex,$email) )
            $this->success = ['success'=> false, 'message'=> 'E-mail not valid'];
        //verify name
        $name = $this->name;
        $regex = '/^[A-z\s]$/';
        if (!preg_match($emailRegex,$email) )
            $this->success = ['success'=> false, 'message'=> 'Name can only contain letters and whitespace'];
        return $this;
    }

    /**
     * @return array
     */
    public function send(): array
    {
        if (!$this->success['success'])
            $response = $this->success;
        try{
            $this->mail->send();
            $response = $this->success;
        } catch (Exception $e) {
            $response = ['success'=> false, 'message'=> "Message could not be sent. Please try again"];
        }
        return $response;
    }
}