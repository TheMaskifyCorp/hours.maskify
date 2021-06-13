<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";

$post =  json_decode(file_get_contents("php://input"), true);
if (!empty($post)) {
    $subject = (isset($post['subject'])) ? $post['subject'] : null;
    if (isset($post['name'], $post['email'], $post['content'])) {
        try {
            $mail = new Mailer($post['email'], $post['name'], $post['content'],$subject);
            $response = $mail->verify()->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $response = ['success' => false, "message" => "Something went wrong"];
        }
    }else {
        $response = ['success' => false, "message" => "Missing Post Data"];
    }
}else{
    $response = ['success' => false, "message" => "No post data recieved"];
}
echo json_encode($response);
