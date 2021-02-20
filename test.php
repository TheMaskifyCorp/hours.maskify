<?php
require_once 'app/init.php';
/**
 * @var object $auth
 * @var object $db
 */

var_dump(
    $db->table('Employees')->selection([
    "Employees.FirstName",
    "EmployeeTypes.Description"
])->innerJoin("EmployeeTypes","Employees.FunctionTypeID  = EmployeeTypes.FunctionTypeID")->get()
);