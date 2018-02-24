<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$type = $_GET['type'];
$value = $_GET['value'];
if($value!=NULL || $value!=''){
	if($type=='purcID')
		$query = mysql_query("SELECT purcID, purcCost, purcCharges, suppName, DATE_FORMAT(purcDateTime,'%b %d, %Y %h:%i %p'), getAccName(purcAssign) FROM purchase WHERE DATEDIFF(purcDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(purcDateTime,'".$_GET['to']."')<=0 AND purcID LIKE '%$value%'");
	else if($type=='supplier')
		$query = mysql_query("SELECT purcID, purcCost, purcCharges, suppName, DATE_FORMAT(purcDateTime,'%b %d, %Y %h:%i %p'), getAccName(purcAssign) FROM purchase WHERE DATEDIFF(purcDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(purcDateTime,'".$_GET['to']."')<=0 AND suppName LIKE '%$value%'");
	else if($type=='assign')
		$query = mysql_query("SELECT purcID, purcCost, purcCharges, suppName, DATE_FORMAT(purcDateTime,'%b %d, %Y %h:%i %p'), getAccName(purcAssign) FROM purchase WHERE DATEDIFF(purcDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(purcDateTime,'".$_GET['to']."')<=0 AND getAccName(purcAssign) LIKE '%$value%'");
}
else
	$query = mysql_query("SELECT purcID, purcCost, purcCharges, suppName, DATE_FORMAT(purcDateTime,'%b %d, %Y %h:%i %p'), getAccName(purcAssign) FROM purchase WHERE DATEDIFF(purcDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(purcDateTime,'".$_GET['to']."')<=0");
while($po=mysql_fetch_array($query))
	$data[]=array($po[0],number_format($po[1],2),number_format($po[2],2),$po[3],$po[4],$po[5]);
mysql_free_result($query);
echo json_encode($data);

 ?>