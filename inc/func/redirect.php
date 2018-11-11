<?php

 /*
  * Redirect Function used To Redirect The Page To Another Page After A Few Seconds
  * Function Description :
  * $details For The Massage That You Want To Show It.
  * $alert_type For Bootstrap Alert Colors
  * $url url That You Will Be Redirected To it
  * $container Adding Container Or Not
  * $seconds Seconds Delay Before Redirection
  * Redirect Function v2.0
  */

   function redirect($details, $alert_type = NULL, $url = NULL , $container = NULL , $info = NULL, $second = 2) {

       if ($alert_type !== "success" && $alert_type !== "danger" && $alert_type !== "warning" && $alert_type !== "info") {

           $alert_type = "info";

       }

       if ($url === NULL || $url == '') {

           $url = "index.php";
           $user_url = strstr($url, '.' , 'TRUE');

       } elseif (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

           $url = $_SERVER['HTTP_REFERER'];
           $user_url = strstr(basename($url), '.' , 'TRUE');

       } else {

           $url = "index.php";
           $user_url = strstr($url, '.' , 'TRUE');

       }

        if ($container === TRUE) {

            if ($info == FALSE) {


                echo "<div class='container'>";
                echo "<div class='alert alert-$alert_type text-center'>$details</div>";
                echo "</div>";

            } else {

                // Directing Time : $second  Seconds To $user_url

                echo "<div class='container'>";
                echo "<div class='alert alert-$alert_type text-center'>$details</div>";
                echo "<div class='alert alert-$alert_type text-center'>Directing Time  $second Seconds To $user_url Page</div>";
                echo "</div>";

            }
            }
        elseif
            ($container === FALSE){

           if ($info == FALSE) {

               echo "<div class='alert alert-$alert_type text-center'>$details</div>";
           } // complete else

           echo "<div class='alert alert-$alert_type text-center'>$details</div>";
            echo "<div class='alert alert-$alert_type text-center'>Directing Time : $second Seconds To $user_url Page</div>";

        } else {

                echo "<div class='container'>";
                echo "<div class='alert alert-$alert_type text-center'>$details</div>";
                echo "<div class='alert alert-$alert_type text-center'>Directing Time : $second Seconds To $user_url Page</div>";
                echo "</div>";

            }


        header("refresh:$second;$url");

       exit();
    }