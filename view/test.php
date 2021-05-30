<pre>
<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
/**
 * @var Auth $auth
 * @var Database $db
 */
?><pre><?php
    $result = $db->table('employeehours')->delete(["EmployeeHoursID","=","ffdba82c-50f0-3574-9855-784eb6fd7f1b"]);
    var_dump($result);
    ?> </pre>


