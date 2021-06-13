<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/init.php';
/**
 * @var object $auth
 */
$auth->signout();
$response = ["succes" => true];
echo json_encode($response);
