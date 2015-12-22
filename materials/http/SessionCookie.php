<?php
session_set_cookie_params(60*60*24*30,"/","www.r-datacollection.com");
session_start();
// session time out 
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 60*60*24*30)) {
    // last request was more than 60 seconds ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

// session counter 
if (!isset($_SESSION['zaehler'])) {
  $_SESSION['zaehler'] = 0;
} else {
  $_SESSION['zaehler']++;
}
echo $_SESSION['zaehler']+1 . "  \n" ;

$arr = array(   0   => "Three blind mice. Three blind mice.", 
                1   => "See how they run. See how they run.", 
                2   => "They all ran after the farmer's wife,", 
                3   => "Who cut off their tails with a carving knife,", 
                4   => "Did you ever see such a sight in your life,", 
                5   => "As three blind mice?"
            ) ; 
echo $arr[$_SESSION['zaehler']] . "\n<br><br><br>" ; 
echo "\n".'<a href="http://www.r-datacollection.com/materials/http/SessionCookie.php">relaod</a>' . "<br>" ; 
echo "\nSource: http://www.gutenberg.org/cache/epub/26060/pg26060.txt" . "<br>" ; 

if ( $_SESSION['zaehler'] > 4) {session_destroy();}
?>


