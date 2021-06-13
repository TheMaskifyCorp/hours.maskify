<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/app/init.php';
/**
 * @var string $docRoot
 * @var Auth $auth
 * @var Database $db
 */

if (!file_exists($docRoot."/.env")) header("Location: /view/install/index.php");
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
            <h3>Log in</h3>
            <div class="SignIn">
                <form id="signin" action="" method="POST">
                    <div id="name-group" class="form-group ">
                        <label for="username">Email</label>
                        <input type="text" class="form-control" name="username" placeholder="Gebruiker">
                    </div>
                    <div id="password-group" class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="wachtwoord">
                    </div>
                    <button type="submit" class="mt-1 btn btn-success">Submit <span class="bi bi-chevron-double-right"></span></button>
                </form>
            </div>
        </div>
        <div id='faq-div' class="grid-item3">
            <h3>Veelgestelde vragen</h3>
            <form id='faqForm' class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Zoek artikelen" aria-label="Search" name="faqSearch">
            </form>
        </div>
        <div class="grid-item4">
        </div>
        <div class="grid-item5">5</div>
        <div class="grid-item6">6</div>
    </div>
</div>

<?php require_once "$docRoot/view/includes/footer.php";
?>
<script src="/view/js/signin.js"></script>
<script src="/view/js/useSearch.js"></script>
<script src="/view/translate/translate.js"></script>

