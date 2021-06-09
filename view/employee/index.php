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
        <div class="grid-item3">
            <div id="hourContainer" class="employeehours-grid-wrapper employeehours-default-grid">
                <input type="text" id="dateRangeHours" class="eh-datepicker">
                <span class="eh-head1">Date</span><span class="eh-head2">Time in minutes</span><span class="eh-head3">Status</span>
                <div id="employeehours" class="eh-hours-grid">
                </div>
            </div>
        </div>
        <div class="grid-item4">4</div>
        <div class="grid-item5">5</div>
        <div class="grid-item6">6</div>
    </div>
</div>
<?php require_once "$docRoot/view/includes/footer.php"; ?>
<script src="/view/js/employee.js"></script>