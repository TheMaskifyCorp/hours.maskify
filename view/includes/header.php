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
    <script src="/view/js/main.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="/view/css/main.css"/>
  <?php require_once "$docRoot/view/includes/user.php"; ?>
    <title>MaskifyHours</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">MaskifyHours</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home <span class="sr-only"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
          <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
          </form>
        </ul>
        </div>
          <div id="account">
            <ul class="navbar-nav ml-auto">
                <?php if(isset($_SESSION['employee'])): ?>
                <li class="nav-item"><button class="btn btn-secondary" onclick="logout()">Logout</button></li>
                <?php endif; ?>
        </div>
        </ul>
      </nav>