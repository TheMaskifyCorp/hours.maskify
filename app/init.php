<?php

session_start();
$app = __DIR__;

$hash = new Hash;
$db = new Database();
$auth = new Auth($db, $hash);
