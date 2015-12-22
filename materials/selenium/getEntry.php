<?php
$year = intval($_GET['y']);
$month = intval($_GET['m']);
$recipient = strval($_GET['r']);
$party = strval($_GET['p']);
$type = strval($_GET['t']);
/*$addQuery1 = (!empty($recipient) ? mysql_real_escape_string($recipient) : false);*/

$con = mysqli_connect('mysql.r-datacollection.com','pp1','Sspw-09_','feringo');
if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

mysqli_select_db($con,"ajax_demo");
$sql="SELECT * FROM contributions WHERE year = '".$year."' AND month = '".$month."' ";

if(!empty($recipient))
  {
$sql = $sql . "AND recipient = '".$recipient."' ";
  }


$result = mysqli_query($con,$sql);

echo "<table border='1'>
<tr>
<th>year</th>
<th>name</th>
<th>party</th>
<th>contributor</th>
<th>state</th>
<th>amount</th>
</tr>";

while($row = mysqli_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['year'] . "</td>";
  echo "<td>" . $row['recipient'] . "</td>";
  echo "<td>" . $row['party'] . "</td>";
  echo "<td>" . $row['contributor'] . "</td>";
  echo "<td>" . $row['state'] . "</td>";
  echo "<td>" . $row['amount'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysqli_close($con);
?> 