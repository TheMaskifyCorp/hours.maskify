<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/app/init.php';
/**
 * @var Auth $auth
 * @var Database $db
 */

if (!file_exists($_SERVER['DOCUMENT_ROOT']."/.env")) header("Location: /install/index.php");
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
    <div class="jumbotron">
        <h1 class="display-4">Proof of Concept</h1>
        <p class="lead">This is a very basic proof of concept for the hour-registration functionality for Maskify.</p>
        <hr class="my-4">
        <p>Work in progress</p>
        <p class="lead">
            <?php
            if(!$auth->check()): ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#signin">
                    Sign in!
                </button>
            <?php else: ?>
                <a href="login/signout.php">
                    <button type="button" class="btn btn-primary">
                        Sign out!
                    </button></a>
            <?php endif;?>
        </p>
        <div class="modal fade" id="signin" tabindex="-1" role="dialog" aria-labelledby="signin" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="Sign In">
                        <form id="signin" action="login/signin.php" method="POST">
                            <div id="name-group" class="form-group">
                                <label for="username">Email</label>
                                <input type="text" class="form-control" name="username" placeholder="Gebruiker">
                            </div>
                            <div id="password-group" class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="password">
                            </div>
                            <button type="submit" class="btn btn-success">Submit <span class="fa fa-arrow-right"></span></button
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div id="leftColumn" class="col-sm-12 col-md-6">
        </div>
        <div class="col-sm-12 col-md-6">
            <?php
            if($auth->check()): ?>
                <div class="alert alert-success" role="alert">User is logged in!</div>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">User is not logged in!</div>
            <?php endif;?>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!--    <script src="js/timepicker.js"></script>-->
<script src="js/signin.js"></script>
</body>
</html>