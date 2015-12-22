<?php
$string  = file_get_contents("../html/quotes/all_quotes.json");
$json_a  = json_decode($string,true);
$index   = date(i) * ( (date(H) % 6) + 1 ) ;

/* 
echo $index . "\n<br>";
echo $index ; 
echo  $json_a[$index][quote];
echo  $json_a[$index][author];
echo  $json_a[$index][context];
echo  $json_a[$index][source];
echo  $json_a[$index][date];
echo date(H) % 6 ;
*/
?>
<div 
    id="<?php echo $index ; ?>" 
    date="<?php echo  $json_a[$index][date]; ?>"
    >
  <h1> <?php echo  $json_a[$index][author];?> </h1>
  <p><i>'<?php echo  $json_a[$index][quote]; ?>'</i></p>
  <p><b>Source: </b><?php echo  $json_a[$index][source]; ?></p>
  <p><b>Context: </b><?php echo  $json_a[$index][context]; ?></p>
  <p><b>Date: </b><?php echo  $json_a[$index][date]; ?></p>
</div>

