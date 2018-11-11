<?php
session_start();
if (isset($_SESSION['user'])) {
    $title = "Categories";
    include "init.php";
    echo "<div class='container'>";
    echo "<h1 class='text-center'>" . str_replace('-', ' ', $_GET['name']) . "</h1>";
    $items = showitems('cat_id', $_GET['id']);
    foreach ($items as $item) {

        echo "<div class='col-xs-12 col-sm-6 col-md-4 col-lg-3'>";
        echo "<div class='thumbnail item-box'>";
        echo "<span class='price'>$" . $item['price'] . "</span>";
        echo "<img src='img2.png' alt='Image Here' class='img-responsive'/>";
        echo "<div class='caption'>";
        echo "<h3><a href='showitems.php?id=$item[ID]'>" . $item['name'] . "</a></h3>";
        echo "<p>" . $item['description'] . "<p>";
        echo "<span class='pull-right item-date'>" . $item['date'] . "</span>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

    }

    echo "</div>";
    include $tmp . "footer.php";
} else {

    header("location: login.php");
    exit();

}