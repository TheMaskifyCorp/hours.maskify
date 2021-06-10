<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/init.php';
/**
 * @var object $auth
 */

$errors = array();
$data = array();
$post =  json_decode(file_get_contents("php://input"), true);

if(!empty($post['username']) && !empty($post['password'])){
    if ( ! $auth->signin([
        'username' => $post['username'],
        'password' => $post['password']
    ])) $errors['credentials'] = "Credentials are not correct.";
} else {
    if (empty($post['username'])) $errors['username'] = "Name is required.";
    if (empty($post['password'])) $errors['password'] = "Password is required.";
}
if ( ! empty($errors)) {

    // if there are items in our errors array, return those errors
    $data['success'] = false;
    $data['errors'] = $errors;
} else if($auth->signin([
        'username' => $post['username'],
        'password' => $post['password']
    ]))
{
    $data['success'] = true;
    $data['message'] = 'User logged in.';
}
echo json_encode($data);