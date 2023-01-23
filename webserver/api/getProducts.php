<?php
include('../master/dbconnect.php');

$sql = "SELECT `selgrosID` FROM `produkte`";
$back = mysqli_query($db, $sql);

header("Content-Type: application/json;charset=utf-8");

$produkte = array();
while ($row = mysqli_fetch_array($back)) {
  $produkte[] = (int) $row["selgrosID"];
}
print(json_encode($produkte));
 ?>
