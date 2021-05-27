<html>
<head>
    <title>Testing</title>
</head>
<body>
    <pre>
    <?php
    require_once "app/init.php";
    $db = new Database;
    $result = $db->table('employees')->innerjoin('departmentmemberlist','EmployeeID')->where('EmployeeID','=',1)->get();
    var_dump($result);
    ?>
    </pre>
</body>
</html>