<?php
include('../master/dbconnect.php');

$selgrosID = $_GET['selgrosID'];
$price = $_GET['price'];

if (isset($_GET['offerprice'])) {
    $offerprice = $_GET['offerprice'];
} else {
    $offerprice = "NULL";
}


$sql = "INSERT INTO `preise`(`id`, `selgrosID`, `preis`, `angebotspreis`, `added`) VALUES (NULL,$selgrosID,$price,$offerprice,NULL)";
mysqli_query($db, $sql);
?>