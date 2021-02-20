<?php
require_once 'app/init.php';
/**
 * @var object $auth
 * @var object $db
 */
$ddl = file_get_contents("app/sql/DDL.sql");
$dml = file_get_contents("app/sql/DML.sql");
$install = new Installer($db);
$install->install($ddl)->dummydata($dml)->insertManagers()->createEmployees(50)->insertRandomHours();
