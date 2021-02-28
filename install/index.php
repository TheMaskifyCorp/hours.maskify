<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    <meta charset="utf-8">
    <meta name="description" content="Maskify POC">
    <meta name="Maskify" content="GoodShit!">
    <title>Maskify POC</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>

<body>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Maskify DB Installer</h1>
        <p class="lead">On this page you can fill in the credentials for your database. </p>
        <hr class="my-4">
        <p></p>
        <p class="lead">
        <p>Then, automagically, we'll run the DDL, DML and add random data to the database.<br> Queries are much more fun when there's actual data available, don't you think?</p>
        <p>
            <a class="btn btn-primary btn-lg" href="https://maskify.nl" role="button">Go to Maskify.nl</a>
        </p>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <form id="theForm" action="" method="post" autocomplete="off">
                <div class="form-group">
                    <label for="hostname">Hostname:</label>
                    <div class="input-group">
                        <div class="input-group-prepend w-15">
                            <span class="input-group-text w-100" id="basic-addon2"><i class="mx-auto fas fa-server"></i></span>
                        </div>
                        <input name="hostname" id="hostname" class="form-control" placeholder="Have you tried localhost?" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="database">Database name:</label>
                    <div class="input-group">
                        <div class="input-group-prepend w-15">
                            <span class="input-group-text w-100" id="basic-addon2"><i class="mx-auto fas fa-database"></i></span>
                        </div>
                        <input name="database" id="database" class="form-control" placeholder="Can't really help you here..." required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <div class="input-group">
                        <div class="input-group-prepend w-15">
                            <span class="input-group-text w-100" id="basic-addon2"><i class="mx-auto fas fa-user"></i></span>
                        </div>
                        <input name="username" id="username" class="form-control" placeholder="Could be root, could be anything" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="input-group">
                        <div class="input-group-prepend w-15">
                            <span class="input-group-text w-100" id="basic-addon2"><i class="mx-auto fas fa-key"></i></span>
                        </div>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Much secret, wow!">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="checkbox" name="dummydata" id="dummydata" autocomplete="off" checked>
                        <label class="ml-3" for="dummydata">Install database with Dummydata</label>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary float-right" value="Install database">
            </form>
        </div>
        <div id="callbackTarget" class="col-sm-12 col-md-6">
            <div class="small text-right text-muted mt-3">Installer messages:</div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="../app/js/install.js"></script>
</body>
</html>