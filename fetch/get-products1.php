<?php
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$data=array();
$type = $_GET['type'];
$value = $_GET['value'];
if($type == 'measID')
	$type = "measID LIKE '%$value%' OR getMeasName(measID)"; 

($value!=NULL && $value!='') ? $query = mysql_query("SELECT getMeasName(measID), prodID, prodDesc, prodStatus, prodMaximumQty, prodReorderQty, prodPrice FROM product WHERE ".$type." LIKE '%$value%' ORDER BY prodID ASC") : $query = mysql_query("SELECT getMeasName(measID), prodID, prodDesc, prodStatus, prodMaximumQty, prodReorderQty, prodPrice FROM product ORDER BY prodID ASC");
while($po=mysql_fetch_array($query))
	$data[]=array($po[0],$po[1],$po[2],$po[3],$po[4],$po[5],$po[6]);
mysql_free_result($query);
echo json_encode($data);
 ?>