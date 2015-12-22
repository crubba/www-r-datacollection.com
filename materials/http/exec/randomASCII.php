<?php 
$asciisource = 'http://www.chris.com/ascii/index.php?art=cartoons/simpsons' ;
// get asciis 
$file = 'EasterEgg.txt';
$content = file_get_contents($file);
// split 
$array = explode("\r\n\r\n", $content);
// choose one and print 
$index = array_rand($array);
echo '<pre>';
print_r($array[$index]);
echo "\r\n\r\n\r\n\r\n source:   " . $asciisource ;
echo '</pre>';
?>