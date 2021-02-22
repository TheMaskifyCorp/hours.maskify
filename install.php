<?php
require_once 'app/init.php';
/**
 * @var object $auth
 * @var object $db
 */
$ddl = "app/sql/DDL.sql";
$dml = "app/sql/DML.sql";
$install = new Installer($db);
$install->installSQL($ddl)->installSQL($dml)->insertManagers()->createEmployees(50)->insertRandomHours()->insertDuplicateEntries();
