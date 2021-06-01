<pre>
<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
/**
 * @var Auth $auth
 * @var Database $db
 */
?><pre><?php

    var_dump($db->table('employees')->where( [['EmployeeID','=',1],['FirstName','=','Sven']],['FunctionTypeID','>',0])->get() );

    ?> </pre>


