<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
$values = [
"HolidaysAccorded" => 1
];
$where = [
    [
        "EmployeeID","=",5
    ],
    [
        "HolidayStartDate" => "2020-09-21"
    ]
];
echo $db->table('holidays')->update($values, $where);
