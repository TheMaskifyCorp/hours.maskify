<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
/**
 * @var string $docRoot
 * @var Auth $auth
 * @var Database $db
 */

$jwt = "";
if (isset ($_POST['id']) ) {
    $id = $_POST['id'];
    $emp = $db->table('employees')->where(['EmployeeID','=',$id])->first();
    $manager = $emp->FunctionTypeID > 1;
    $token = array (
    'eid' => $id,
    'manager' => $manager,
    'iat' => time()
    );
    $jwt =  Firebase\JWT\JWT::encode($token, $_ENV['JWTSECRET']);
}
require_once $docRoot."/view/includes/header.php";
?>
<form action="createToken.php" method="post">
    <input type="text" id="id" name="id"><br>
    <input type="submit" value="Submit">
</form>

<?php
if(isset($jwt)) :
    ?>
    <form>
        <div class="input-group">
            <input type="text" class="form-control"
                   value="<?php echo  $jwt; ?>" placeholder="Some path" id="copy-input">
            <span class="input-group-btn">
    </span>
        </div>
    </form>
<?php endif;
require_once $docRoot."/view/includes/footer.php"; ?>

