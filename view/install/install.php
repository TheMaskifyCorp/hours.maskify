<?php

$app = $_SERVER['DOCUMENT_ROOT']."/app";
require_once $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";
if(isset($_POST['hostname'])) {
    $filename = $_SERVER['DOCUMENT_ROOT'].'/.env';
    try {
        $fp = fopen($filename, "w+");
    } catch (Exception $e) {
        return array("Warning" => "Could not open .env for writing, please check file permissions");
    }
    $hostname = $_POST['hostname'];
    $database = $_POST['database'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    try{
        $pdo = new PDO("mysql:host=$hostname;dbname=$database",$username,$password);
        unset($pdo);
    }catch(Exception $e){
        http_response_code(404);
        header("Location: /view/install/index.php");
    }
    Installer::createENV($hostname, $database, $username, $password);
}
require_once "$app/init.php";
/**
 * @var Database $db
 */
$ddl = "$app/sql/DDL.sql";
$dml = "$app/sql/DML.sql";
$managers = "$app/sql/managers.sql";
$install = new Installer($db);
if (isset($_POST['dummydata']))
{
    echo $install->installSQL($ddl)
        ->installSQL($dml)
        ->installSQL($managers)
        ->createEmployees(50)
        ->insertRandomHours()
        ->insertDuplicateEntries()
        ->createRandomSickLeave()
        ->createRandomHolidays()
        ->returnStatus();
} else {
    echo $install->installSQL($ddl)
    ->installSQL($dml)
    ->returnStatus();
}