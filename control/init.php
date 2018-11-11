<?php

include "connect.php"; // The Connect File

// Routes

$tmp = "inc/tmp/"; // Template Directory For HTML Elements
$css = "layout/css/"; // css Directory
$js = "layout/js/"; // javascript Directory
$langs = "inc/langs/"; // Languages Directory
$func = "inc/func/"; //Functions Directory

// include important files
include $langs . "en.php"; // English Language
include  $func .  "get_all_from.php"; // Get All Columns From Tables
include  $func .  "title.php"; // Title Function
include $tmp . "header.php"; // Header Section
include $func . "redirect.php"; // redirect user to the home page when he try enter to prevented pages or pages not founded
include  $func . "fetch_user.php"; // print the Current Username
include  $func . "check.php"; // check the columns from database
include  $func . "checkitem.php"; // check numbers of columns from database
include  $func . "getlatest.php"; // check numbers of columns from database

// include navbar for files not contain noNavBar variable
if (!isset($noNavBar)) {include $tmp . "navbar.php";}






