<?php
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$data=array();
$type = $_GET['type'];
$value = $_GET['value'];
if($value!=NULL || $value!=''){
	$query = mysql_query("SELECT getAccUName(accID),getAccName(accID),DATE_FORMAT(loginTime,'%b %d, %Y %h:%i %p'),DATE_FORMAT(logoutTime,'%b %d, %Y %h:%i %p'),logPC FROM user_logs WHERE getAccUName(accID)='$value' AND IFNULL(DATEDIFF(loginTime,'".$_GET['from']."'),0)>=0 AND IFNULL(DATEDIFF(loginTime,'".$_GET['to']."'),0)<=0 AND IFNULL(DATEDIFF(logoutTime,'".$_GET['from']."'),0)>=0 AND IFNULL(DATEDIFF(logoutTime,'".$_GET['to']."'),0)<=0");
}
else
	$query = mysql_query("SELECT getAccUName(accID),getAccName(accID),DATE_FORMAT(loginTime,'%b %d, %Y %h:%i %p'),DATE_FORMAT(logoutTime,'%b %d, %Y %h:%i %p'),logPC FROM user_logs WHERE IFNULL(DATEDIFF(loginTime,'".$_GET['from']."'),0)>=0 AND IFNULL(DATEDIFF(loginTime,'".$_GET['to']."'),0)<=0 AND IFNULL(DATEDIFF(logoutTime,'".$_GET['from']."'),0)>=0 AND IFNULL(DATEDIFF(logoutTime,'".$_GET['to']."'),0)<=0");
while($po=mysql_fetch_array($query)){
	$data[]=array($po[0],$po[1],$po[2],$po[3],$po[4]);
}
mysql_free_result($query);
echo json_encode($data);

 ?>