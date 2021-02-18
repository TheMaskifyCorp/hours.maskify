<?php

require_once 'app/init.php';
/**
 * @var object $auth
 */

if(!empty($_POST))
{
    $email = $_POST['email'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    $created = $auth->create([
        'email' => $email,
        'username' => $username,
        'role' => $role,
        'password' => $password
    ]);
    if ($created)
    {
        header('Location: index.php');
    }

}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>
<body>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Proof of Concept</h1>
        <p class="lead">This is a very basic proof of concept for the hour-registration functionality for Maskify.</p>
        <hr class="my-4">
        <p>Work in progress</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="https://maskify.nl" role="button">Go to Maskify.nl</a>
        </p>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h1>Left column</h1>
            <form action="createuser.php" method="post">
                <fieldset>
                    <legend>Create a user</legend>
                    <label>
                        Email
                        <input type="text" name="email">
                    </label>
                    <label>
                        Username
                        <input type="text" name="username">
                    </label>
                    <label>
                        Role
                        <input type="text" name="role">
                    </label>
                    <label>
                        Password
                        <input type="password" name="password">
                    </label>
                </fieldset>
                <input type="submit" value="create">
            </form>
        </div>
        <div class="col-sm-12 col-md-6">
            <h1>Right column</h1>
            <p>in this column, when logged in, a history of submitted hours can be viewed.</p>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="js/timepicker.js"></script>
<script src="js/script.js"></script>
</body>
</html>
<!doctype html>