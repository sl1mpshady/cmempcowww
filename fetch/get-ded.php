<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
if(isset($_GET['dedID'])){
	$query = mysql_query("SELECT custID, getEmpName(custID), grossCredit, deduction, netCredit FROM pd_customer_list WHERE dedID=".$_GET['dedID']);
	while($ded=mysql_fetch_array($query)){
		
		$ded[1] = '<a href="customer-view.php?i='.$ded[0].'">'.$ded[1]."</a>";
		$ded[0] = '<a href="customer-view.php?i='.$ded[0].'">'.$ded[0]."</a>";
		$data[]=array($ded[0],$ded[1],number_format($ded[2],2),number_format($ded[3],2),number_format($ded[4],2));	
	}
	mysql_free_result($query);
	$query = mysql_query("SELECT dedID, dedTotalDeduction, dedDateTime, getAccName(dedAssign) FROM deduction WHERE dedID=".$_GET['dedID']);
	$ded=mysql_fetch_array($query);
	$date=date_create($ded[2]);
	$ded[2] = date_format($date,"M d, o h:i A");
	$data[]=array($ded[0],number_format($ded[1],2),$ded[2],$ded[3]);
	mysql_free_result($query);
}
else {
	$type = $_GET['type'];
	$value = $_GET['value'];
	if($type=='dedID')
		$query = mysql_query("SELECT dedID, dedCustomers, dedTotalDeduction,DATE_FORMAT(dedDateTime,'%b %d, %Y %h:%i %p'),getAccName(dedAssign) FROM deduction WHERE DATEDIFF(dedDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(dedDateTime,'".$_GET['to']."')<=0 AND dedID LIKE '%$value%'");
	else if($type=='assign')
		$query = mysql_query("SELECT dedID, dedCustomers, dedTotalDeduction,DATE_FORMAT(dedDateTime,'%b %d, %Y %h:%i %p'),getAccName(dedAssign) FROM deduction WHERE DATEDIFF(dedDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(dedDateTime,'".$_GET['to']."')<=0 AND getAccName(dedAssign) LIKE '%$value%'");
	else
		$query = mysql_query("SELECT dedID, dedCustomers, dedTotalDeduction,DATE_FORMAT(dedDateTime,'%b %d, %Y %h:%i %p'),getAccName(dedAssign) FROM deduction WHERE DATEDIFF(dedDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(dedDateTime,'".$_GET['to']."')<=0");
	while($po=mysql_fetch_array($query)){
		$data[]=array($po[0],number_format($po[1],0),number_format($po[2],2),$po[3],$po[4]);
	}
	mysql_free_result($query);
}

echo json_encode($data);

 ?>