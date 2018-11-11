<?php

/*
 * This Function Used To Check If User Or Item Or Anything else Exist In Database Or Not
 * Function Description :
 * 1- $column describe the item that will be selected from database
 * 2- $table describe the table that will be selected from database
 * 3- $value describe the value that will be compare with column value to give us some information
 * Function check() v1.0
 */

    function check($column, $table, $value) {
        // use global to make $con variable readable everywhere
        global $con;
        // prepare statement
        $stmt = $con->prepare("SELECT $column FROM $table WHERE $column = ?");
        $stmt->execute(array($value));
        $count = $stmt->rowCount();
        return $count;
    }

