<?php
unlink($_SERVER['DOCUMENT_ROOT'].'/.env');
header("Location: index.php");