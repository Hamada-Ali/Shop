<?php

    // Function To Get Latest Users Have Been Registered

    function get_latest($column, $table, $order, $limit) {

        global $con;
        // Fetching Data From Database
        $stmt = $con->prepare("SELECT $column FROM $table ORDER BY  $order DESC LIMIT $limit");
        $stmt->execute();
        $fetch = $stmt->fetchAll();
        return $fetch;
    }