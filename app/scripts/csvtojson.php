<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
/**
 * @var string $docRoot
 *
 */
$uploadDirectory = "$docRoot/app/uploads/";

// Store all errors
$errors = [];

// Available file extensions
$fileExtensions = ['csv'];
try {
    if (!empty($_FILES['newEmp'] ?? null)) {

        $fileName = $_FILES['newEmp']['name'];
        $fileTmpName = $_FILES['newEmp']['tmp_name'];
        $fileType = $_FILES['newEmp']['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $uploadPath = $uploadDirectory . basename($fileName);

        if (isset($fileName)) {
            if (!in_array($fileExtension, $fileExtensions)) {
                $errors[] = "Only CSV is supported";
            }
            if (empty($errors)) {
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                if ($didUpload) {
                    echo file_get_contents($uploadPath);
                    unlink($uploadPath);
                } else {
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