<?php
include 'exec/getallheaders.php';
echo $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['PHP_SELF'] . " " . $_SERVER['SERVER_PROTOCOL'] . "\r\n" ; 
foreach (getallheaders() as $name => $value) {
    echo "$name: $value\r\n";
}
echo "\r\n";
echo  file_get_contents("php://input");
?>