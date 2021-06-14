<?php
/**
 * @var string $docRoot
 */
if (isset($_SESSION['employee'])):
?>
<script>
    const emp = <?php echo $_SESSION['employee']; ?>;
    const manager = <?php echo ($_SESSION['manager'])? "true": "false" ; ?>;
<?php if(($_SESSION['manager']) && file_exists($docRoot."/.env")):
    $emp = new Employee($_SESSION['employee']);?>
    const department = <?php echo $emp->getDepartment();?>;
<?php endif;
?>
</script>
<?php endif;