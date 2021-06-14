<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/init.php";
/**
 * @var Database $db
 */

unset($_SESSION['employee']);
unset($_SESSION['manager']);
$app = $_SERVER['DOCUMENT_ROOT']."/app";
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

$ddl = "$app/sql/DDL.sql";
$dml = "$app/sql/DML.sql";
$managers = "$app/sql/managers.sql";
$install = new Installer($db);

$response= $install->installSQL($ddl)
    ->installSQL($dml)
    ->installSQL($managers)
    ->createEmployees(50)
    ->insertRandomHours()
    ->insertDuplicateEntries()
    ->createRandomSickLeave()
    ->createRandomHolidays()
    ->returnStatus();

var_dump($response);
