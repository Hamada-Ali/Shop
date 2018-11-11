<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css">
    <link rel="stylesheet" href="<?php echo $css; ?>frstyle.css">
    <?php if (isset($bk)) {
        echo "<style>body {background-color: #e3fdfd;}</style>";
    } ?>
    <title><?php getTitle() ?></title>
</head>
<body>
<div class="container upper-nav">
    <?php
    if (isset($_SESSION['user'])) {

        echo "<div class='dropdown'>";
            echo "<img class='img-circle img-dropdown' alt='img' src='img2.png'>";
            echo "<button class='btn btn-default dropdown-toggle' data-toggle='dropdown'> $_SESSION[user] <span class='caret'></span> </button>";
            echo "<ul class='dropdown-menu'>";
                echo "<li><a href='profile.php'><i class='fa fa-user'></i> Profile</a></li>";
                echo "<li><a href='newad.php'><i class='fa fa-tag'></i> New Item</a></li>";
                echo "<li><a href='logout.php'><i class='fa fa-sign-out'></i> Logout</a></li>";
            echo "</ul>";
        echo "</div>";

    } else {
    ?>
    <a href="login.php" class="pull-right">Login / Sign-up</a>
    <?php  }?>
</div>
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Home</a>
        </div>
        <div class="collapse navbar-collapse navbar-right" id="app-nav">
            <ul class="nav navbar-nav">
                <?php

                $allCats = get_all_from("*", "categories", "WHERE main_cat = 0", "ID");


                foreach ($allCats as $cat) {

                    echo "<li><a href='categories.php?name=" . str_replace(' ', '-', $cat['name']) . "&id=$cat[ID]' class='hovering'>" . $cat['name'] . "</a></li>";

                }

                ?>
            </ul>

        </div>
    </div>
</nav>