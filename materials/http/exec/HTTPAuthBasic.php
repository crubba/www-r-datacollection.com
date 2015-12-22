<?php
if (!isset($_GET['AUTH'])){

        header('WWW-Authenticate: Basic realm="rdatacollection"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
}
@list($nutzer,$passwd)=explode(':',base64_decode(substr($_GET['AUTH'],6)));


if($nutzer!='rdata' || $passwd!='collection'){

        header('WWW-Authenticate: Basic realm="rdatacollection"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
}
echo 'Du bist eingeloggt.' . "    \n";
echo $_GET['AUTH'] . "\n";
?>