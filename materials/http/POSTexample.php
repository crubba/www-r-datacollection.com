<?php
    // parameter check
    $parerror = FALSE ; 
    if (isset($_POST["name"]) ){
    }else{
        echo "Please specify your name!\n";
    }
    if (isset($_POST["age"]) ){
    }else{
        echo "Please specify your age!\n";
        $parerror = TRUE; 
    }
// if parameters are missing, stop 
if($parerror == TRUE ) exit() ; 

// input processing 
if ($_POST["return"]==FALSE){
    echo 'Hello ' . htmlspecialchars($_POST["name"]) . "!\n";
    if (intval($_POST["age"]) < 13 && intval($_POST["age"]) >= 0) {
        echo "... you are so young, have an ...
                     _
                   ,' `,.
                   >-.(__)
                  (_,-' |
                    `.  |
                      `.| hjw
                        `
        ";
    } elseif ($_POST["age"] > 120) {
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
        echo "You are " . $_POST["age"] . " years old.\n";
    }
} else {
include '../ReturnHTTP.php';
}
?>