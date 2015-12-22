<?php
foreach (getallheaders() as $name => $value) {
    echo "$name: $value\r\n";
}
echo "\r\n";

echo $_POST["name"] . "\r\n";
echo $_POST["age"] . "\r\n";
echo $_FILES[0] . "\r\n";
echo $_PILES[1] . "\r\n";
?>
    