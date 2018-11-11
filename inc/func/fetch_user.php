<?php

/*
 * This Function Used To Fetch Username From Database and echo it
 * it's better from session method because this function can echo the new name after edit on it
 *  Function fetch_user v1.0
 */

    function fetchuser() {
        // Prepare Statement
        global $con;
        $stmt = $con->prepare("SELECT username FROM users WHERE UserID = ?");
        // Use Id To Help You In Selecting The Current User That He Logged To The Site
        $id = $_SESSION['ID'];
        $stmt->execute(array($id));
        // Fetch Data (username)
        $fetch = $stmt->fetch();
        // Echo It in navbar
        echo $fetch['username'];
    }