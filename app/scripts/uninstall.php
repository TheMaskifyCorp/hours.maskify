<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/init.php';
/**
 * @var string $docRoot
 * @var Auth $auth
 * @var Database $db
 */
try {
    $auth->signout();
    unlink($docRoot . '/.env');
    header("Location: /view/install/index.php");
}catch(Exception $e){
    http_send_status(400);
}