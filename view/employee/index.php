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
        <div id="employeedata" class="grid-item2">
        </div>
        <div class="grid-item3">
            <div id="hourContainer" class="employeehours-grid-wrapper employeehours-default-grid">
                <div id="date-range-group" class="form-group eh-datepicker">
                    <input type="text" class="form-control text-center m-auto eh-datepicker"  name='daterange' id="dateRangeHours">
                </div>
                <span class="eh-head1">Date</span><span class="eh-head2">Time in minutes</span><span class="eh-head3">Status</span>
                <div id="employeehours" class="eh-hours-grid">
                </div>
            </div>
        </div>
        <div class="grid-item5">
            <h3 data-lang="add hours">Add Hours</h3>
            <div>
                <form id="addHours" action="" method="POST">
                    <div id="date-group" class="form-group">
                        <label for="date">Datum</label>
                        <input type="text" class="form-control" name='date' id="dateHoursSelector">
                    </div>
                    <div id="time-group" class="form-group">
                        <label for="time">Tijd in minuten</label>
                        <input type="number" class="form-control" name="time" value="60">
                    </div>
                    <button type="submit" class="mt-1 btn btn-success">Submit <span class="bi bi-chevron-double-right"></span></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once "$docRoot/view/includes/footer.php"; ?>
<script src="/view/js/employee.js"></script>
