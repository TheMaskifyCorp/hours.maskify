<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
$values = [
    "HolidaysAccorded" => 1,
    "AccordedByManager" => 2
];
$employeeID = [
        "EmployeeID","=",1
    ];
$startDate =  [
        "HolidayStartDate","=","2021-06-16"
    ];
?><pre><?php
$response = $db->table('holidays')->update($values, $employeeID, $startDate);
var_dump($response);
    ?> </pre>