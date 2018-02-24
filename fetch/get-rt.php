<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$type = $_GET['type'];
$value = $_GET['value'];
if($type=='retID')
	$query = mysql_query("SELECT retID, retType,retSubject,retCost, DATE_FORMAT(retDateTime,'%b %d, %Y %h:%i %p'),getAccName(retAssign) FROM _return WHERE DATEDIFF(retDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(retDateTime,'".$_GET['to']."')<=0 AND retID LIKE '%$value%'");
else if($type=='SO')
	$query = mysql_query("SELECT retID, retType,retSubject,retCost, DATE_FORMAT(retDateTime,'%b %d, %Y %h:%i %p'),getAccName(retAssign) FROM _return WHERE DATEDIFF(retDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(retDateTime,'".$_GET['to']."')<=0 AND retType='SO'");
else if($type=='PO')
	$query = mysql_query("SELECT retID, retType,retSubject,retCost, DATE_FORMAT(retDateTime,'%b %d, %Y %h:%i %p'),getAccName(retAssign) FROM _return WHERE DATEDIFF(retDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(retDateTime,'".$_GET['to']."')<=0 AND retType='PO'");
else if($type=='assign')
	$query = $query = mysql_query("SELECT retID, retType,retSubject,retCost, DATE_FORMAT(retDateTime,'%b %d, %Y %h:%i %p'),getAccName(retAssign) FROM _return WHERE DATEDIFF(retDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(retDateTime,'".$_GET['to']."')<=0 AND getAccName(retAssign) LIKE '%$value%'");
else
	$query = mysql_query("SELECT retID, retType,retSubject,retCost, DATE_FORMAT(retDateTime,'%b %d, %Y %h:%i %p'),getAccName(retAssign) FROM _return WHERE DATEDIFF(retDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(retDateTime,'".$_GET['to']."')<=0");
while($po=mysql_fetch_array($query)){
	if($po[1]=='SO'){
		$po[1]='Sales Order';
		$po[2]='<a href="view-sales_order.php?i='.$po[2].'">'.$po[2].'</a>';
	}
	else{
		$po[1]='Purchase Order';
		$po[2]= '<a href="view-purchase_order.php?i='.$po[2].'">'.$po[2].'</a>';
	}
	$data[]=array($po[0],$po[1],$po[2],number_format($po[3],2),$po[4],$po[5]);
}
mysql_free_result($query);
echo json_encode($data);

 ?>