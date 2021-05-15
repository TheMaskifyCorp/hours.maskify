<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/maskify/app/init.php";
/**
 * @var Database $db
 */
$values = [
    "FirstName"=> "Sven2",
    "LastName" => "Muste",
    "Email"=> "sven2.muste@maskify.nl",
    "PhoneNumber" => "+31612345678",
    "Street"=> "lagelandenlaan",
    "HouseNumber" => "4",
    "City"=> "Groningen",
    "FunctionTypeID"=> "3"];

$newEmp = Employee::createNewEmployee($values);
var_dump($newEmp);