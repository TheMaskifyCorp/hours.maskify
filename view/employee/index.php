<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/app/init.php';
/**
 * @var string $docRoot
 * @var Auth $auth
 * @var Database $db
 */

if (!file_exists($_SERVER['DOCUMENT_ROOT']."/.env")) header("Location: /install/index.php");
if (!isset($_SESSION['employee'],$_SESSION['manager'])) header("Location: /view/index.php");

require_once "$docRoot/view/includes/header.php";
?>

<div class="body-wrapper">
    <div class="grid-wrapper">
        <div class="grid-item1">1</div>
        <div class="grid-item2">
            <div id="employeedata">

            </div>
        </div>
        <div class="grid-item3">3</div>
        <div class="grid-item4">4</div>
        <div class="grid-item5">5</div>
        <div class="grid-item6">6</div>
    </div>
</div>
<?php require_once "$docRoot/view/includes/footer.php"; ?>
<script src="/view/js/employee.js"></script>