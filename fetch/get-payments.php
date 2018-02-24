<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
if(isset($_GET['SO'])){
	$query=mysql_query("SELECT getPayDate(payID) as date,saleBalance, getPayAssign(payID), payReceive FROM payment_so_list WHERE saleID='".$_GET['saleID']."'");
	while($fetch=mysql_fetch_array($query)){
		 $data[]=array($fetch[0],number_format($fetch[1],2),$fetch[2],number_format($fetch[3],2));
	}
}
else if(isset($_GET['payID'])){
	$query=mysql_query("SELECT saleID,payReceive,saleBalance FROM payment_so_list WHERE payID='".$_GET['payID']."'");
	while($fetch=mysql_fetch_array($query)){
		 $data[]=array($fetch[0],$fetch[1],$fetch[2]);
	}
	$query=mysql_query("SELECT custID,getCustName(custID),payDateTime,payReceived,getAccName(payAssign) FROM payment WHERE payID='".$_GET['payID']."'");
	$fetch=mysql_fetch_array($query);
	$data[]=array($fetch[0],$fetch[1],$fetch[2],$fetch[3],$fetch[4]);
}
else if(isset($_GET['from']) && isset($_GET['to'])){
	$query=mysql_query("SELECT payID, payReceived,getCustName(custID),payDateTime,getAccName(payAssign),NOW() FROM payment WHERE DATEDIFF(payDateTime, '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',payDateTime) >= 0");
	$count = $paid = $date = 0;
	while($fetch=mysql_fetch_array($query)){
		 $data[]=array($fetch[0],number_format($fetch[1],2),$fetch[2],$fetch[3],$fetch[4]);
		 $count +=1;
		 $paid +=$fetch[1];
		 $date = $fetch[5];
	}
	$data[]=array(number_format($count,0),number_format($paid,2),$date);
	
}
else{
	$query=mysql_query("SELECT getPayDate(payID) as date,saleBalance, payReceive FROM payment_so_list WHERE saleID='".$_GET['saleID']."'");
	while($fetch=mysql_fetch_array($query)){
		 $data[]=array($fetch[0],$fetch[1],$fetch[2]);
	}
}
mysql_free_result($query);
echo json_encode($data);
?>