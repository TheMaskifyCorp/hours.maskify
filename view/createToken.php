<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
$jwt = "";
if (isset ($_POST['id']) ) {
    $id = $_POST['id'];
    $emp = $db->table('employees')->where(['EmployeeID','=',$id])->first();
    $manager = $emp->FunctionTypeID == 2;
    $token = array (
    'eid' => $id,
    'manager' => $manager,
    'iat' => time()
    );
    $jwt =  Firebase\JWT\JWT::encode($token, $_ENV['JWTSECRET']);
}
?>
<form action="createToken.php" method="post">
    <input type="text" id="id" name="id"><br>
    <input type="submit" value="Submit">
</form>

<?php echo $jwt;