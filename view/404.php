<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
/**
 * @var string $docRoot
 */
require_once $docRoot.'/view/includes/header.php';
?>
<h3>Sorry, the page could not be found.</h3>
<h4>Perhaps you made a typo?</h4>
<img src="/view/images/404.png" class='w-100' alt="notfound">
<?php
require_once $docRoot.'/view/includes/footer.php';