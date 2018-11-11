<?php

// Arabic Language



function lang($phrase) {

    static $lang = array(

        'sayHello' => "مرحبا",
        "user" => "بالمدير"

    );

    return $lang[$phrase];
}