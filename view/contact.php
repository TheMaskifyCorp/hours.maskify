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
<div id="contactpage" class="w-50">
    <form id='contactform' action="/app/scripts/mailer.php" method="post">
        <div class="form-group">
            <label for="name">Uw naam</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Pietje Puk">
        </div>
        <div class="form-group">
            <label for="email">E-mailadres</label>
            <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="info@maskify.nl">
            <small id="emailHelp" class="form-text text-muted">Houden we geheim!</small>
        </div>
        <div class="form-group">
            <label for="content">Bericht:</label>
            <textarea class="form-control" id="content" name="content" rows='5' placeholder="Deel uw gedachten met ons"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Verzend</button>
    </form>
</div>
<?php
require_once "$docRoot/view/includes/footer.php"; ?>
<script src="/view/js/contactform.js"></script>

