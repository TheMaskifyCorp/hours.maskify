<pre>
<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
$installer = new Installer($db);
$installer->insertRandomHours();
?> </pre>