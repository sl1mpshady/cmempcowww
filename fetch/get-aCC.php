<?php
require_once('../Connections/connection.php');	
require_once('../class/base64.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$data=array();
if(isset($_GET['edit-acc'])){
	$query = mysql_query("SELECT accUsername, accName, accPassword, accType, accStatus FROM account WHERE accID='".$_GET['accID']."' LIMIT 0,1");
	$acc = mysql_fetch_array($query);
	$p = new Password('',$acc[0],$acc[2]);
	$data[]=array($acc[0],$acc[1],$acc[2],$acc[3],$acc[4],strlen($p->decrypt()));
	mysql_free_result($query);
}else {
	$type = $_GET['type'];
	$value = $_GET['value'];
	if($value!=NULL || $value!=''){
		if($type=='uname')
			$query = mysql_query("SELECT accUsername,accName,accStatus,DATE_FORMAT(lastLogin,'%b %d, %Y %h:%i %p'),DATE_FORMAT(getCurrentLogin(accID),'%b %d, %Y %h:%i %p'),accType,accID FROM account WHERE accStatus!='Delete' AND accUsername LIKE '%$value%'");
		else if($type=='name')
			$query = mysql_query("SELECT accUsername,accName,accStatus,DATE_FORMAT(lastLogin,'%b %d, %Y %h:%i %p'),DATE_FORMAT(getCurrentLogin(accID),'%b %d, %Y %h:%i %p'),accType,accID FROM account WHERE accStatus!='Delete' AND accName LIKE '%$value%'");
	}
	else
		$query = mysql_query("SELECT accUsername,accName,accStatus,DATE_FORMAT(lastLogin,'%b %d, %Y %h:%i %p'),DATE_FORMAT(getCurrentLogin(accID),'%b %d, %Y %h:%i %p'),accType,accID FROM account WHERE accStatus!='Delete'");
	while($po=mysql_fetch_array($query)){
		if($po[2]=='Banned')
			$po[2]="Inactive";
		if($po[5]=='CS')
			$po[5]='Cashier';
		else if($po[5]=='BS')
			$po[5]='Basic';
		else if($po[5]=='IM')
			$po[5]='Intermediate';
		else if($po[5]=='AV')
			$po[5]='Advance';
		$data[]=array($po[0],$po[1],$po[2],$po[3],$po[4],$po[5],$po[6]);
	}
	mysql_free_result($query);
}
echo json_encode($data);

 ?>