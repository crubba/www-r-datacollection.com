<?php 
list($SERVER['PHP_AUTH_USER'], $SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($SERVER['HTTP_AUTHORIZATION'], 6)));
?>