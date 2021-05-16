<?php
session_start();
$app = __DIR__;

if (file_exists("$app/conf/DBCONF.php")) {
    require_once "$app/conf/DBCONF.php";
    require_once "$app/classes/Database.php";
    require_once "$app/classes/DummyData.php";
    require_once "$app/classes/Employee.php";
    require_once "$app/classes/UUID.php";
    require_once "$app/classes/Auth.php";
    require_once "$app/classes/Hash.php";
    $hash = new Hash;
    $db = new Database(true);
    $auth = new Auth($db, $hash);
}

require_once "$app/classes/Installer.php";
