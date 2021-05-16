<?php
session_start();
$app = __DIR__;
require_once "$app/../vendor/autoload.php";

$hash = new Hash;
$db = new Database(true);
$auth = new Auth($db, $hash);
