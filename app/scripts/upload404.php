<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
/**
 * @var string $docRoot
 *
 */

// Store all errors
$errors = [];

// Available file extensions
$fileExtensions = ['png'];
try {
    if (!empty($_FILES['newEmp'] ?? null)) {

        $fileName = $_FILES['newEmp']['name'];
        $fileTmpName = $_FILES['newEmp']['tmp_name'];
        $fileType = $_FILES['newEmp']['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $uploadPath = "$docRoot/view/images/404.png";

        if (isset($fileName)) {
            if (!in_array($fileExtension, $fileExtensions)) {
                $errors[] = "Only PNG is supported";
            }
            if (empty($errors)) {
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                if (!$didUpload) {
                    echo "An error occurred while uploading. Try again.";
                }
            } else {
                foreach ($errors as $error) {
                    echo $error . "The following error occured: " . "\n";
                }
            }
        }
    }
}catch(Exception $e){
    echo $e->getMessage();
}