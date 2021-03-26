<?php

require_once 'app/init.php';
/**
 * @var object $auth
 */

$errors = array();
$data = array();


if(!empty($_POST['username']) && !empty($_POST['password'])){
    if ( ! $auth->signin([
        'username' => $_POST['username'],
        'password' => $_POST['password']
    ])) $errors['credentials'] = "Credentials are not correct.";
} else {
    if (empty($_POST['username'])) $errors['username'] = "Name is required.";
    if (empty($_POST['password'])) $errors['password'] = "Password is required.";
}
if ( ! empty($errors)) {

    // if there are items in our errors array, return those errors
    $data['success'] = false;
    $data['errors'] = $errors;
} else if($auth->signin([
        'username' => $_POST['username'],
        'password' => $_POST['password']
    ]))
{
    $data['success'] = true;
    $data['message'] = 'Success!';
}
echo json_encode($data);