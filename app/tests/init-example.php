<?php
session_start();
$docRoot = "/opt/lampp/htdocs/contracts/hours.maskify";
require_once $docRoot ."/vendor/autoload.php";
$_ENV['DB_SOCKET'] =  '/opt/lampp/var/mysql/mysql.sock';
$dotenv = Dotenv\Dotenv::createImmutable($docRoot);


$dotenv->load();

$hash = new Hash;
$db = new Database();
$auth = new Auth($db, $hash);