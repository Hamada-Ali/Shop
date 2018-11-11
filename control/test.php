<?php

$action = '';

if (isset($_GET['action'])) {

    $action = $_GET['action'];

} else {

    $action = 'manage';


}

if ($action == "manage") {

    echo "Welcome To manage Page";
    echo " <a href='?action=add'>adding page</a>";


} elseif ($action == "edit"){

    echo "Edit Page ^_^";

}
elseif ($action == "add"){

    echo "adding Page ^_^";

}
else {

    //header('HTTP/1.1 404 NOT FOUND');

    echo "Sorry Page Not Found";

}