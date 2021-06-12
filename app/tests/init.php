<?php
session_start();
$docRoot = "/opt/lampp/htdocs/contracts/hours.maskify";
require_once $docRoot ."/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable($docRoot);

    $dotenv->load();

    $hash = new Hash;
    $db = new Database();
    $auth = new Auth($db, $hash);

