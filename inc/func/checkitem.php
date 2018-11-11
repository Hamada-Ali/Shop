<?php

// Function To Count Members Or Items And Return Numbers Of Them

function check_item($column, $table, $active = false) {

    // Make $con variable global to able to use it from everywhere
    global $con;

    if ($active == false) {
        $stmt = $con->prepare("SELECT COUNT($column) FROM $table");
        $stmt->execute();
        $fetch = $stmt->fetchColumn();
        return $fetch;
    } elseif ($active == true) {

        $stmt = $con->prepare("SELECT COUNT($column) FROM $table WHERE TrueStatus = 0");
        $stmt->execute();
        $fetch = $stmt->fetchColumn();
        return $fetch;

    } else {

        $stmt = $con->prepare("SELECT COUNT($column) FROM $table");
        $stmt->execute();
        $fetch = $stmt->fetchColumn();
        return $fetch;
    }

}