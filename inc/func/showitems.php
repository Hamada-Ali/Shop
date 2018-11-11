<?php

/*
 * showitem() function v1.0
 * This Function Used To Fetch The All Data For Items Table
 */

function showitems($where, $val, $sql = NULL) {
    global $con;

    if ($sql === NULL) {

        $sql = " AND approve = 1 ";

    } else {

        $sql = NULL;

    }

    $stmt = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY ID ASC");
    $stmt->execute(array($val));
    $fetch = $stmt->fetchAll();

    return $fetch;
}