<?php

// English Language

function lang($phrase) {

    static $lang = array(

        // navbar links
        '@brand'        => 'Home',
        '@categories'   => 'Categories',
        '@items'        => 'Items',
        '@members'      => 'Members',
        '@comments'     => 'Comments',
        '@statistics'   => 'Statistics',
        '@logs'         => 'Logs',
        '@edit'         => 'Edit Profile',
        '@setting'      => 'Settings',
        '@logout'       => 'Logout',

    );

    return $lang[$phrase];

}