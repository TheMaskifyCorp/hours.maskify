<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/init.php';
/**
 * @var object $auth
 */
$auth->signout();
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    <meta charset="utf-8">
    <meta name="description" content="Maskify Hour Registration System">
    <meta name="Maskify" content="GoodShit!">
    <title>Maskify POC</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--    <link rel="stylesheet" href="css/timepicker.css">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    <!--    <link rel="stylesheet" href="css/styles.css"/>-->
</head>
<body>
<div class="container">
    <div class="modal fade" id="signout" tabindex="-1" role="dialog" aria-labelledby="signin" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="Sign out succes" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary" role="alert">
                        Signed out
                    </div>
                    <a href="/view/index.php">
                    <button type="button" class="btn btn-primary">Back to login</button>
                    </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="application/javascript">
    $(document).ready(function(){
        $('#signout').modal('show');
    });
</script>
</body>
</html>
