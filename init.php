<?php

include "control/connect.php"; // The Connect File

if (isset($_SESSION['user'])) {
    $usersession = $_SESSION['user'];
}
// Routes

$tmp = "inc/tmp/"; // Template Directory For HTML Elements
$css = "layout/css/"; // css Directory
$js = "layout/js/"; // javascript Directory
$langs = "inc/langs/"; // Languages Directory
$func = "inc/func/"; //Functions Directory

// include important files
include $langs . "en.php"; // English Language
include $func . "title.php"; // Title Function
include $func . "getcat.php"; // Get All Categories Names
include $func . "get_all_from.php"; // Get All From Any Table
include $func . "showitems.php"; // Get All Categories Names
include $func . "check_reg_status.php"; // Check The Register Status Of The User
include $tmp . "header.php"; // Header Section
include $func . "redirect.php"; // redirect user to the home page when he try enter to prevented pages or pages not founded
include $func . "fetch_user.php"; // print the Current Username
include $func . "check.php"; // check the columns from database
include $func . "checkitem.php"; // check numbers of columns from database
include $func . "getlatest.php"; // check numbers of columns from database










