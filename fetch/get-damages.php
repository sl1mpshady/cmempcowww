<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$query = mysql_query("SELECT DATE_FORMAT(damDateTime,'%b %d, %Y %h:%i %p'), prodID, getProdName(prodID), damType, prodCost, getAccName(damAssign), typeID FROM damages WHERE DATEDIFF(damDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(damDateTime,'".$_GET['to']."')<=0");
while($po=mysql_fetch_array($query)){
	if($po[3]=='SO')
		$po[3]='<a href="view-sales_order.php?i='.$po[6].'">Sales Order '.$po[6].'</a>';
	$data[]=array($po[0],$po[1],$po[2],$po[3],number_format($po[4],2),$po[5]);
}
mysql_free_result($query);
echo json_encode($data);

 ?>