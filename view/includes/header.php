<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/app/init.php';

/**
 * @var string $docRoot
 * @var Auth $auth
 * @var Database $db
 */
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- main JS functions -->

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="undefined" crossorigin="anonymous">
    <link rel="stylesheet" href="/view/css/main.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <title>MaskifyHours</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">MaskifyHours</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home <span class="sr-only"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="_blank" href="/view/phpdoc/">PHPDoc</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="_blank" href="/view/report/">UnitTests</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-lang="langpicker">
                        Talen
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <button class="dropdown-item" onclick="loadTranslation('nl')">
                            <img
                                    src="https://flagcdn.com/nl.svg"
                                    width="30"
                                    alt="nl"><span data-lang="lang-nl">Nederlands</span>
                        </button>
                        <button class="dropdown-item" onclick="loadTranslation('en')">
                            <img
                                    src="https://flagcdn.com/gb.svg"
                                    width="30"
                                    alt="en">
                                    <span data-lang="lang-en">Engels</span>
                        </button>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/view/install/">Installer</a>
                </li>
                <?php if(!isset($_SESSION['employee'])) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="/view/contact.php">Contact Us!</a>
                </li>
                <?php endif;
if(isset($_SESSION['manager']) && $_SESSION['manager'] == "true") : ?>

                <li class="nav-item">
                      <a class="nav-link" href="/view/manager/solutions.php" data-lang="manageapp">Beheer App</a>
                </li>
<?php endif;?>
            </ul>
        </div>
        <div id="account">
            <ul class="navbar-nav ml-auto">
                <?php if(isset($_SESSION['manager']) && $_SESSION['manager'] == "true")
                    if( explode("/",$_SERVER['PHP_SELF'])[2] == "employee"): ?>
                <li class="nav-item my-1 my-lg-0 mx-0 mx-lg-2""><a href="/view/manager/"><button class="btn btn-secondary btn-w mr-1 mr-lg-0"><i class="bi bi-arrow-bar-right mx-1"></i>Manager</button></a></li>
                    <?php else : ?>
                <li class="nav-item my-1 my-lg-0 mx-0 mx-lg-2""><a href="/view/employee/"><button class="btn btn-secondary btn-w"><i class="bi bi-arrow-bar-right mx-1"></i>Employee</button></a></li>
                    <?php endif;
                if(isset($_SESSION['employee'])): ?>
                <li class="nav-item"><button class="btn btn-secondary btn-w" onclick="logout()">Logout</button></li>
                <?php endif; ?>
            </ul>
        </div>
        </ul>
      </nav>