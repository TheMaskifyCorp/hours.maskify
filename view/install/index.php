<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/init.php';
/**
 * @var string $docRoot
 * @var Auth $auth
 * @var Database $db
 */

require_once "$docRoot/view/includes/header.php";
?>
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
            <a class="btn btn-primary btn-lg deleteconf" href="/app/scripts/uninstall.php" role="button">Delete existing configuration</a>
        </p>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <form id="theForm" action="" method="post" autocomplete="off">
                <div class="form-group dbsettings">
                    <label for="hostname">Hostname:</label>
                    <div class="input-group">
                        <div class="input-group-prepend w-15">
                            <span class="input-group-text w-100" id="basic-addon2"><i class="mx-auto bi bi-hdd-network"></i></span>
                        </div>
                        <input name="hostname" id="hostname" class="form-control" placeholder="Have you tried localhost?" required>
                    </div>
                </div>
                <div class="form-group dbsettings">
                    <label for="database">Database name:</label>
                    <div class="input-group">
                        <div class="input-group-prepend w-15">
                            <span class="input-group-text w-100" id="basic-addon2"><i class="mx-auto bi bi-book-half"></i></i></span>
                        </div>
                        <input name="database" id="database" class="form-control" placeholder="Can't really help you here..." required>
                    </div>
                </div>
                <div class="form-group dbsettings">
                    <label for="username">Username:</label>
                    <div class="input-group">
                        <div class="input-group-prepend w-15">
                            <span class="input-group-text w-100" id="basic-addon2"><i class="mx-auto bi bi-file-earmark-person"></i></span>
                        </div>
                        <input name="username" id="username" class="form-control" placeholder="Could be root, could be anything" required>
                    </div>
                </div>
                <div class="form-group dbsettings">
                    <label for="password">Password:</label>
                    <div class="input-group">
                        <div class="input-group-prepend w-15">
                            <span class="input-group-text w-100" id="basic-addon2"><i class="mx-auto bi bi-key"></i></span>
                        </div>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Much secret, wow!">
                    </div>
                </div>
                <div class="form-group dbsettings">
                    <div class="input-group mb-3">
                        <label for="dummydata">Options:</label>
                        <div class="input-group">
                            <div class="input-group-prepend w-15">
                                <span class="input-group-text w-100 h-100" id="basic-addon2"><input name="dummydata" type="checkbox" aria-label="Checkbox for following text input" checked></span>
                            </div>
                            <input type="text" class="form-control fake-disabled" aria-label="Text input with checkbox" placeholder="Install using DummyData" disabled>
                        </div>
                    </div>
                </div>
                <input type="submit" id='submitinstall' class="btn btn-primary float-right" value="Install database">
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<?php

if(file_exists($_SERVER['DOCUMENT_ROOT']."/.env")){
    ?>
    <script>
        $(document).ready(function(){
            $('.dbsettings').remove();
        })
    </script>
    <?php } else { ?>
    <script>
        $(document).ready(function(){
            $('.deleteconf').remove();
        })
    </script>
<?php
    };
require_once "$docRoot/view/includes/footer.php";
?>
<script src="/view/js/install.js"></script>