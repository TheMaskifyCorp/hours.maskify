<?php

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
try {
    $dotenv->load();
} catch (Exception $e) {
    header("Location: install/index.php");
}

$hash = new Hash;
$db = new Database();
$auth = new Auth($db, $hash);
