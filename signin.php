<?php

require_once 'app/init.php';

?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Index</title>
    </head>
    <body>
        <form action="signin.php" method="post">
            <fieldset>
                <legend>Sign in</legend>
                <label>
                    Username
                    <input type="text" name="username">
                </label>
                <label>
                    Password
                    <input type="password" name="password">
                </label>
            </fieldset>
            <input type="submit" value="Sign in">
        </form>
    </body>
</html>