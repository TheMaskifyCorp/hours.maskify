<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/app/init.php';
/**
 * @var string $docRoot
 * @var Auth $auth
 * @var Database $db
 */

if (!file_exists($_SERVER['DOCUMENT_ROOT']."/.env")) header("Location: /install/index.php");
if (isset($_SESSION['employee'],$_SESSION['manager']))
{
    if ($_SESSION['manager']) header("Location: /view/manager/index.php");
    header("Location: /view/employee/index.php");
}
require_once "$docRoot/view/includes/header.php";
?>
<div class="body-wrapper">
    <div class="grid-wrapper">
        <div class="grid-item1">1</div>
        <div class="grid-item2">
            <div class="SignIn">
                <form id="signin" action="/view/login/signin.php" method="POST">
                    <div id="name-group" class="form-group">
                        <label for="username">Email</label>
                        <input type="text" class="form-control" name="username" placeholder="Gebruiker">
                    </div>
                    <div id="password-group" class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="password">
                    </div>
                    <button type="submit" class="btn btn-success">Submit <span class="fa fa-arrow-right"></span></button
                </form>
            </div>
        </div>
        <div class="grid-item3">
        </div>
        <div class="grid-item4">4</div>
        <div class="grid-item5">5</div>
        <div class="grid-item6">6</div>
    </div>
</div>
<script src="/view/js/signin.js"></script>
<?php require_once "$docRoot/view/includes/footer.php";