<?php 
// get IP
    if ( !isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            $client_ip = $_SERVER['REMOTE_ADDR'];
    } else { $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR']; }
// set Cookie , MD5 of IP with duration 0f 1 minute
    setcookie("id", md5($client_ip) , time()+60*60*24*30, "/", ".r-datacollection.com" );
// check if Cookie is ok
    if( $_COOKIE['id'] === md5($client_ip) ){
        echo "Ah, nice to meet you again. " ; 
        } else { echo "Hallo, who are you?" ; }
?>
