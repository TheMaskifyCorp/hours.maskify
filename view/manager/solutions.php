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
        <div class="grid-item1">
        </div>
        <div class="grid-item2">
            <h3 data-lang="removesearches">Verwijder zoektermen</h3>
            <div id="unusedSearches">
            </div>
        </div>
        <div class="grid-item3">
            <form id="new404ImageForm" method="post" enctype="multipart/form-data">
                Upload PNG bestand voor 404-pagina:
                <input type="file" name="new404ImageFile" id="new404ImageFile" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                <input type="submit" value="Upload PNG" name="submit">
            </form>
        </div>
        <div class="grid-item4">
        </div>
        <div class="grid-item5">
        </div>
    </div>
</div>
<?php
require_once "$docRoot/view/includes/footer.php";
?>
<script src="/view/js/manage-faq.js"></script>
<script src="/view/js/upload404Image.js"></script>
