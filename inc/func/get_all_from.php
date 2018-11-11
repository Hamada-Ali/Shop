<?php

function get_all_from($column ,$table_name, $condition, $order_by, $order_type = "DESC") {

    global $con;

    $stmt = $con->prepare("SELECT $column FROM $table_name $condition ORDER BY $order_by $order_type");
    $stmt->execute();
    $fetch = $stmt->fetchAll();
    return $fetch;
}
