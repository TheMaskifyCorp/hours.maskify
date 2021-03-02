<?php
$app = "../app";
require_once("$app/classes/Installer.php");
if(isset($_POST['hostname'])) {
    $filename = '../app/conf/DBCONF.php';
    try {
        $fp = fopen($filename, "w+");
    } catch (Exception $e) {
        return array("Warning" => "Could not open DBCONF.php for writing, please check file permissions");
    }
    $hostname = $_POST['hostname'];
    $database = $_POST['database'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    Installer::createDBCONF($hostname, $database, $username, $password);
}
require_once "$app/init.php";
$ddl = "$app/sql/DDL.sql";
$dml = "$app/sql/DML.sql";
$managers = "$app/sql/managers.sql";
$install = new Installer($db);
if (isset($_POST['dummydata'])) {
    echo $install->installSQL($ddl)->installSQL($dml)->installSQL($managers)->createEmployees(50)->insertRandomHours()->insertDuplicateEntries()->returnStatus();
} else echo $install->installSQL($ddl)->installSQL($dml)->returnStatus();