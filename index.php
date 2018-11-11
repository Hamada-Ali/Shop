<?php
ob_start();
session_start();
$title="Home";
include 'init.php';
if (isset($_SESSION['user'])){

    $items = get_all_from('*', "items", "WHERE approve = 1", "ID", "DESC");

    echo "<div class='container'>";
    echo "<h1 class='text-center'>Home Page</h1>";
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

} else {
    header("location: login.php");
    exit();
}
include $tmp . 'footer.php';
ob_end_flush();
