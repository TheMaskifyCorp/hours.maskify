<?php
require_once 'app/init.php';
$hostname = $_POST['hostname'];
$database = $_POST['database'];
$username = $_POST['username'];
$password = $_POST['password'];

$db = new Database($hostname, $database, $username, $password , false);
/*$db = new Database("localhost","maskify_hours","root","rootpassword");*/
$ddl = "app/sql/DDL.sql";
$dml = "app/sql/DML.sql";
$install = new Installer($db);
echo $install->installSQL($ddl)->installSQL($dml)->createEmployees(50)->insertRandomHours()->insertDuplicateEntries()->returnStatus();
