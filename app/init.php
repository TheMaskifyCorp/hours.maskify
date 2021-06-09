<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] ."/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);

try {
    $dotenv->load();
} catch (Exception $e) {
    if(!$_SERVER['REQUEST_URI'] == "/view/install/index.php")
       header("Location: /view/install/index.php");
}
$jwt = "";
if (isset($_SESSION['employee'])) {
    $token = array(
        'eid' => ($_SESSION['employee']),
        'manager' => ($_SESSION['manager']),
        'iat' => time()
    );
    $jwt = Firebase\JWT\JWT::encode($token, $_ENV['JWTSECRET']);
    setcookie("jwt", $jwt);
}
$hash = new Hash;
$db = new Database();
$auth = new Auth($db, $hash);
$docRoot = $_SERVER['DOCUMENT_ROOT'];