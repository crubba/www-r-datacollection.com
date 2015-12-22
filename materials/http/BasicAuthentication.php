<?php
include 'exec/getPHPAuth.php' ; 
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'OK, basic authentication canceled.';
    exit;
} else {
    echo "Hallo {$_SERVER['PHP_AUTH_USER']}.\n";
    echo "Your Password is: {$_SERVER['PHP_AUTH_PW']}";
    if ($_SERVER['PHP_AUTH_USER']     =='peterpinski' || 
        $_SERVER['PHP_AUTH_USER']   =='djnubbel' || 
        $_SERVER['PHP_AUTH_USER']     =='jonasmichaelis' || 
        $_SERVER['PHP_AUTH_USER']     =='easteregg' ||
        $_SERVER['PHP_AUTH_USER']     =='cr' 
                ){
    echo "
   ___  _              
  / _ )(_)__  ___ ____  
 / _  / / _ \/ _ `/ _ \ 
/____/_/_//_/\_, /\___/ 
            /___/       
" ; 
    }
    if (    $_SERVER['PHP_AUTH_PW']     =='123' ||
            $_SERVER['PHP_AUTH_PW']     =='asdf' ||
            $_SERVER['PHP_AUTH_PW']     =='qwertl' ||
            $_SERVER['PHP_AUTH_PW']     =='1234' ||
            $_SERVER['PHP_AUTH_PW']     =='test' ||
            $_SERVER['PHP_AUTH_PW']     =='tset' ||
            $_SERVER['PHP_AUTH_PW']     =='boring' 
    ) {
    echo "
___.                .__                
\_ |__   ___________|__| ____    ____  
 | __ \ /  _ \_  __ \  |/    \  / ___\ 
 | \_\ (  <_> )  | \/  |   |  \/ /_/  >
 |___  /\____/|__|  |__|___|  /\___  / 
     \/                     \//_____/  
     " ;
    }
}
// include '../ReturnHTTP.php' ;
?>
