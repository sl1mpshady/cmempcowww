<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
if(isset($_GET['from']) && isset($_GET['to'])){
	$query=mysql_query("SELECT prodID,prodDesc, getProdSales(prodID,'".$_GET['from']."','".$_GET['to']."'), getProdCosts(prodID,'".$_GET['from']."','".$_GET['to']."') FROM product");
	
	$query1=mysql_query("SELECT getOthers('".$_GET['from']."','".$_GET['to']."')");
	$fetch1 = mysql_fetch_array($query1);
	while($fetch=mysql_fetch_array($query)){
		$data[] = array($fetch[0]." (".$fetch[1].")",number_format($fetch[2],2),number_format($fetch[3],2),number_format($fetch[2]-$fetch[3],2),number_format($fetch1[0],2));
	}	
}
mysql_free_result($query);
mysql_free_result($query1);
echo json_encode($data);
?>