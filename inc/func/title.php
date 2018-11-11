<?php


  // Title Function Used To Print Title Dynamically In Title Tag For Each Page


function getTitle() {

    global $title;

    if(isset($title)) {

        echo $title;

    } else {

        echo "Page";

    }

}

