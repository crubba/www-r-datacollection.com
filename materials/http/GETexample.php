<?php
    // parameter check
    $parerror = FALSE ; 
    if (isset($_GET["name"]) ){
    }else{
        echo "Please specify your name!\n";
    }
    if (isset($_GET["age"]) ){
    }else{
        echo "Please specify your age!\n";
        $parerror = TRUE; 
    }
// if parameters are missing, stop 
if($parerror == TRUE ) exit() ; 

// input processing 
if ($_GET["return"]==FALSE){
    echo 'Hello ' . htmlspecialchars($_GET["name"]) . "!\n";
    if (intval($_GET["age"]) < 13 && intval($_GET["age"]) >= 0) {
        echo "... you are so young, have an ...
                     _
                   ,' `,.
                   >-.(__)
                  (_,-' |
                    `.  |
                      `.| hjw
                        `
        ";
    } elseif ($_GET["age"] > 120) {
        echo "... uh, impressive age ...\n
              ._
                    |
                    |
                    |L___,
                  .' '.  T
                 :  *  :_|
                  '._.'   L
    ";
    } else {
        echo "You are " . $_GET["age"] . " years old.\n";
    }
} else {
include 'ReturnHTTP.php';
}
?>