<?php
$app = "../app";
require_once "$app/init.php";
$hostname = $_POST['hostname'];
$database = $_POST['database'];
$username = $_POST['username'];
$password = $_POST['password'];

if(gethostbyname($hostname.".")==$hostname.".") {
    echo json_encode(array("Hostname <strong>$hostname</strong> not resolvable" => "Warning"));
} else {
    $db = new Database($hostname, $database, $username, $password);

    $ddl = "$app/sql/DDL.sql";
    $dml = "$app/sql/DML.sql";
    $managers = "$app/sql/managers.sql";
    $install = new Installer($db);
    if (isset($_POST['dummydata'])) {
        echo $install->installSQL($ddl)->installSQL($dml)->installSQL($managers)->insertRandomHours()->insertDuplicateEntries()->returnStatus();
    } else {
        echo $install->installSQL($ddl)->installSQL($dml)->returnStatus();
    }
};