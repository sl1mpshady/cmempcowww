<?php
 require_once('../Connections/connection.php');	
 mysql_connect($db_hostname,$db_username,$db_password);
 mysql_select_db($db_database);
 $data=array();
 include('../class/class.php');
 if(isset($_GET['all'])){
	$measID = $_GET['measID'];
	$query = mysql_query("SELECT measID, measDesc,measMeasurement FROM measurement WHERE measID!='$measID'");
	while($fetch = mysql_fetch_array($query))
		$data[] = array ($fetch[0],$fetch[1],$fetch[2]);
	mysql_free_result($query);
 }
 else if(isset($_GET['measID'])){
	//isset($_GET['type']) ? $product = new Product($_GET['prodID'],true) : $product = new Product($_GET['prodID']);
	$measurement = new Measurement($_GET['measID']);
	$data = array($measurement->measID,$measurement->measDesc,$measurement->measMeasurement);
 }
else{
	$query=mysql_query("SELECT catID, catName, catStatus, getCatProductOH(catID) as stock, getCatProduct(catID) as product, catDiscount FROM category WHERE catDelete='False'");
	$qty = $prod = 0;
	while($fetch = mysql_fetch_array($query))
	{
		$data[] = array ($fetch[0],$fetch[1],$fetch[2],number_format($fetch[3],0),number_format($fetch[4],0),number_format($fetch[5],2));
		$qty += $fetch[3];
		$prod += $fetch[4];
	}
	$time = new Database;
	$query = $time->connect('SELECT NOW()');
	$fetch = mysql_fetch_array($query);
	$data[] = array($qty,$prod,$fetch[0]);
	mysql_free_result($query);
 }
 echo json_encode($data);
?>