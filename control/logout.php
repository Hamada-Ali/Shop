<?php
// Start The Session
session_start();
// Delete The Data From Session
session_unset();
// Destroy The Session
session_destroy();
// Redirect To Login Page
header("location: index.php");
// Exit To don't Show A Error Massage If I Write a Wrong Path
exit();

