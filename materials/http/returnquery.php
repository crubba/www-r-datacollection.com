<?php 
echo "<pre>\n" ; 
$getter = $_GET; 
$getter[] = "";
$poster = $_POST;
$poster[]="";
$MAX[] = max(array_map('strlen', $getter));
$MAX[] = max(array_map('strlen', $poster));
$MAX = max($MAX);

echo "parameters submitted via GET: (name) : (value)\n" ;
    foreach ( $_GET as $key => $value){
        echo str_pad($key,$MAX+1," ") . " : " . $value . "\n" ; 
        }
echo "\n\nparameters submitted via POST: (name) : (value)\n" ; 
    foreach ( $_POST as $key => $value){
        echo str_pad($key,$MAX+1," ") . " : " . $value . "\n" ; 
        }
echo "\n</pre>\n" ; 
?>