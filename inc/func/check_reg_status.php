<?php

/*
Check The Register Status for The User
*/

function reg_stat($user) {

    global $con;

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ? AND TrueStatus = 0");
    $stmt->execute(array($user));
    $count = $stmt->rowCount();

    return $count;
}