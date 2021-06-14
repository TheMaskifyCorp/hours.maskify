<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/app/init.php';
/**
 * @var string $docRoot
 * @var Auth $auth
 * @var Database $db
 */

if (!file_exists($_SERVER['DOCUMENT_ROOT']."/.env")) header("Location: /view/install/index.php");
if (!isset($_SESSION['employee'],$_SESSION['manager'])) header("Location: /view/index.php");

require_once "$docRoot/view/includes/header.php";
?>

<div class="body-wrapper">
    <div class="grid-wrapper">
        <div class="grid-item1"></div>
        <div class="grid-item2">
            <div id="employeedata">
            </div>
            <div id="specificEmployee">
            </div>
            <span></span>
        </div>
        <div class="grid-item3">
            <div id="hourContainer" class="employeehours-grid-wrapper employeehours-default-grid">
                <div class="eh-datepicker"><h3 data-lang="validation">Waiting for validation</h3>
                </div>
                <span class="eh-head1" data-lang="date">Datum</span><span class="eh-head2" data-lang="timeinmins">Tijd in minuten</span><span class="eh-head3">Status</span>
                <div id='notAccorded' class="eh-hours-grid"></div>
            </div>
        </div>
        <div class="grid-item4">

            <form id="newEmployeeForm" action="/app/csvtojson.php" method="post" enctype="multipart/form-data">
                Upload gegevens nieuwe medewerker:
                <input type="file" name="newEmployeeFile" id="newEmployeeFile" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                <input type="submit" value="Upload CSV" name="submit">
            </form>
            <!-- Button trigger modal -->
        </div>
        <div class="grid-item5">
            <div id="hourContainer" class="employeehours-grid-wrapper employeehours-default-grid">
                <div class="eh-datepicker">
                    <h3 data-lang="hours this month">Hours this month</h3>
                </div>
                <span class="eh-head1">Date</span><span class="eh-head2">Time in minutes</span><span class="eh-head3">Status</span>
                <div id="employeehours" class="eh-hours-grid">
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once "$docRoot/view/includes/footer.php"; ?>
<script src="/view/js/manager.js"></script>
<script src="/view/js/uploadEmployee.js"></script>