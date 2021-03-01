<?php
if(isset($_POST['hostname'])) {
    if (gethostbyname($hostname . ".") == $hostname . ".") {
        echo json_encode(array("Hostname <strong>$hostname</strong> not resolvable" => "Warning"));
        exit();
    } else {
        $hostname = $_POST['hostname'];
        $database = $_POST['database'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $UUID = UUID::createRandomUUID('c416205f-49fa-4e90-91f7-e39a1fa0c4c0');

        $dbconf = "
<?php

//database config file

//rename this file to DBCONF.php, and enter database credentials
//IMPORTANT: verify DBCONF.php is in .gitignore
class DBCONF{
    const HOSTNAME = '$hostname';
    const DBNAME = '$database';
    const USER = '$username';
    const PASSWORD = '$password';
    // NAMESPACE should be a valid UUID. You can use the default one, or
    // generate one here: https://www.uuidgenerator.net/
    const NAMESPACE = '$UUID';
}
";
        file_put_contents('../app/conf/DBCONF.php', $dbconf);
    }
};
$app = "../app";
require_once "$app/init.php";

$ddl = "$app/sql/DDL.sql";
$dml = "$app/sql/DML.sql";
$managers = "$app/sql/managers.sql";
$install = new Installer($db);
if (isset($_POST['dummydata'])) {
    echo $install->installSQL($ddl)->installSQL($dml)->installSQL($managers)->createEmployees(50)->insertRandomHours()->insertDuplicateEntries()->returnStatus();
} else {
    echo $install->installSQL($ddl)->installSQL($dml)->returnStatus();
};