<?php

/*
 * Function For Fetch All Categories Names
 */


function getcat() {

    global $con;

    $stmt = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
    $stmt->execute();
    $fetch = $stmt->fetchAll();
    return $fetch;
}