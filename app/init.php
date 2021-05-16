<?php
session_start();
$app = __DIR__;
require_once "$app/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
try {
    $dotenv->load();
} catch (Exception $e) {
    header("Location: install/index.php");
}

$hash = new Hash;
$db = new Database();
$auth = new Auth($db, $hash);
