<?php
$db = mysqli_connect("localhost", "username", "password", "selgros");
if(!$db)
{
  exit("connection error: ".mysqli_connect_error());
}
mysqli_query($db, 'set names utf8');
mysqli_set_charset($db, 'utf8');
?>
