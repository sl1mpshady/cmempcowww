<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$type = $_GET['type'];
$value = $_GET['value'];
$query = '';
if($value!=NULL || $value!=''){
	if($type=='saleID')
		$query = mysql_query("SELECT saleID, saleNetAmount,getCustName(saleCustomer),DATE_FORMAT(saleDateTime,'%b %d, %Y %h:%i %p'),getAccName(saleAssign),saleType FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND saleID LIKE '%".$value."%'");
	else if($type=='customer')
		$query = mysql_query("SELECT saleID, saleNetAmount,getCustName(saleCustomer),DATE_FORMAT(saleDateTime,'%b %d, %Y %h:%i %p'),getAccName(saleAssign),saleType FROM sales_order WHERE  DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND getCustName(saleCustomer) LIKE '%".$value."%'");
	else if($type=='assign')
		$query = mysql_query("SELECT saleID, saleNetAmount,getCustName(saleCustomer),DATE_FORMAT(saleDateTime,'%b %d, %Y %h:%i %p'),getAccName(saleAssign),saleType FROM sales_order WHERE  DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND getAccName(saleAssign) LIKE '%".$value."%'");
}
else
	$query = mysql_query("SELECT saleID, saleNetAmount,getCustName(saleCustomer),DATE_FORMAT(saleDateTime,'%b %d, %Y %h:%i %p'),getAccName(saleAssign),saleType FROM sales_order WHERE  DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0");
while($so=mysql_fetch_array($query))
	$data[]=array($so[0],number_format($so[1],2),$so[2],$so[3],$so[4],$so[5]);
mysql_free_result($query);
echo json_encode($data);

 ?>