<?php

header("content-type:image/jpg");
header('Content-Disposition: inline; filename="'.$_GET[i].'.jpg"');
$host = 'localhost';
$user = 'root';
$pass = '';

mysql_connect($host, $user, $pass);

mysql_select_db('sims2');
$ext = $_GET[i];

$select_image="select custPicture from customer where custID='$ext'";

$var=mysql_query($select_image);
$image = '';
if($row=mysql_fetch_array($var))
   $image = $row[0];
echo $image;

?>