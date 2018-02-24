<?php
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$data=array();
$type = $_GET['type'];
$value = $_GET['value'];
if($value!=NULL || $value!=''){
	if($type == 'action')
		$query = mysql_query("SELECT subjectID,getAccUName(accID),actionDone,actionDetails,DATE_FORMAT(transDate,'%b %d, %Y %h:%i %p'),pcName FROM transaction_logs WHERE actionDone LIKE '%$value%' AND DATEDIFF(transDate,'".$_GET['from']."')>=0 AND DATEDIFF(transDate,'".$_GET['to']."')<=0");
	else
		$query = mysql_query("SELECT subjectID,getAccUName(accID),actionDone,actionDetails,DATE_FORMAT(transDate,'%b %d, %Y %h:%i %p'),pcName FROM transaction_logs WHERE getAccUName(accID) LIKE '%$value%' AND DATEDIFF(transDate,'".$_GET['from']."')>=0 AND DATEDIFF(transDate,'".$_GET['to']."')<=0");
}
else
	$query = mysql_query("SELECT subjectID,getAccUName(accID),actionDone,actionDetails,DATE_FORMAT(transDate,'%b %d, %Y %h:%i %p'),pcName FROM transaction_logs WHERE DATEDIFF(transDate,'".$_GET['from']."')>=0 AND DATEDIFF(transDate,'".$_GET['to']."')<=0");
while($po=mysql_fetch_array($query)){
	$data[]=array($po[0],$po[1],$po[2],$po[3],$po[4],$po[5]);
}
mysql_free_result($query);
echo json_encode($data);

 ?>