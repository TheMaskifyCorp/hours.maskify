<?php
if (isset($_SESSION['employee'])):
?>
<script>
    const emp = <?php echo $_SESSION['employee']; ?>;
    const manager = <?php echo ($_SESSION['manager'])? "true": "false" ; ?>;
</script>
<?php endif;