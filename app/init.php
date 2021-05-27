<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] ."/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
try {
    $dotenv->load();
} catch (Exception $e) {
    header("Location: install/index.php");
}

$hash = new Hash;
$db = new Database();
$auth = new Auth($db, $hash);
