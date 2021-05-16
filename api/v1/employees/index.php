<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/maskify/app/init.php";
/**
 * @var Database $db
 */
/*
$values = [
"FirstName" => "TESTNAAM",
"LastName" => "ACHTERNAAM",
"Email" => "VOORNAAM+ACHTERNAAM@MASKIFY.NL",
"Street" => "straat",
"HouseNumber" => "1",
"City" => "middelburg",
"PostalCode" => "1234ab",
"PhoneNumber" => "0612345678",
"DateOfBirth" => "2020-01-01",
"FunctionTypeID" => 1,
"DocumentNumberID" => 123456

    ];
$result = $db->table('employees')->insert($values);*/

$gemma = new Employee(3);
var_dump($gemma);