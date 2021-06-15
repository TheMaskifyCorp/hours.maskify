<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";

if (empty($_POST)) {
    $response = ['success' => false, "message" => "No post data received"];
}else{
    ///verify token
    $data = array(
        'secret' => $_ENV['HCAPTCHASECRET'],
        'response' => $_POST['h-captcha-response']
    );
    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $captchaResponse = curl_exec($verify);

    $responseData = json_decode($captchaResponse);

    if($responseData->success) {


        /*
         * Send the mail
         * */

        $subject = (isset($_POST['subject'])) ? $_POST['subject'] : "Uw contactformulier";
        if (isset($_POST['name'], $_POST['email'], $_POST['content'])) {
            /*
             * create mail for us
             */
            $content = "Name :".$_POST['name'] ."
                    E-mail : ".$_POST['email']."
                    Message: ".$_POST['content'];
            $contactform = new Mailer("info@maskify.nl","FORM",$content,"New Contactform");
            try {
                $mail = new Mailer($_POST['email'], $_POST['name'], $_POST['content'], $_POST['subject']);
                $response = $mail->verify()->send();
                $secondResponse = $contactform->verify()->send();
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                $response = ['success' => false, "message" => "Something went wrong"];
            }
        }else {
            $response = ['success' => false, "message" => "Missing Post Data"];
        }
    }
    else {
        $response = ['success' => false, "message" => $captchaResponse];
    }
}
echo json_encode( $response );