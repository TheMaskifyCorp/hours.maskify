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


//$docRoot = "/home/jeroen/PhpstormProjects/maskify/semester3/hours.maskify";
//require_once $docRoot . "/vendor/autoload.php";
//$_ENV["HOSTNAME"] = 'localhost';
//$_ENV["DATABASE"] = "maskify_hours";
//$_ENV["USERNAME"] = "root";
//$_ENV["PASSWORD"] = "rootpassword";
//$hash = new Hash;
//$db = new Database();
//$auth = new Auth($db, $hash);
//$_ENV['DB_SOCKET'] = '/opt/lampp/var/mysql/mysql';