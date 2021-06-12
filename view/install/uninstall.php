<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/app/init.php';
/**
 * @var string $docRoot
 * @var Auth $auth
 * @var Database $db
 */
$auth->signout();
unlink($docRoot.'/.env');
header("Location: index.php");