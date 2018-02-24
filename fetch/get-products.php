<?php
$data = array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
if(isset($_GET['aom'])){
	$prodID = $_GET['prodID'];
	if(isset($_GET['so'])){
		$query = mysql_query("SELECT prodPrice,prodDesc,prodOHQuantity,getMeasurement(measID), measID FROM product WHERE prodID='".$prodID."' LIMIT 0,1");
		$product = mysql_fetch_array($query);
		$data[] = array($product[0],$product[1],$product[2],$product[3],$product[4]);
		mysql_free_result($query);	
	}
	$query = mysql_query("SELECT prodDesc,getMeasName(measID) as meas, measID, getMeasurement(measID) as measurement FROM product WHERE prodID='".$prodID."' LIMIT 0,1");
	$product = mysql_fetch_array($query);
	$data[] = array($product['prodDesc'],$product['meas'],$product['measID'],$product['measurement']);
	mysql_free_result($query);
	$query = mysql_query("SELECT *,getMeasurementName(measID) as measName, getMeasurement(measID) as measurement, measID FROM alternate_of_measure WHERE prodID='".$prodID."'");
	if(mysql_num_rows($query) == 0)
		$data[] = array(false);
	else
		while($product=mysql_fetch_array($query))
			$data[] = array($product['measID'],$product['measName'],$product['conversion'],$product['measurement']);
	mysql_free_result($query);
}

else if(isset($_GET['replenishment'])){
	//SELECT prodID as prodID, prodDesc prodDesc, measID as unit, prodOHQuantity as ohb,getDailyConsumption(prodID) as daily,getROP(prodID)  as rop,prodMaximumQty-getROP(prodID) as orderQty, DATE_FORMAT(NOW(),'%m/%d/%y %h:%i %p') as NOW,DATE_FORMAT(NOW(),'%M %d, %Y') as asOf FROM product WHERE  prodOHQuantity<=getROP(prodID)
	$query = mysql_query("SELECT prodID, prodDesc, measID, prodOHQuantity,getDailyConsumption(prodID),getROP(prodID) ,prodMaximumQty-getROP(prodID) FROM product");
	while($product = mysql_fetch_array($query)){
		$data[] = array($product[0],$product[1],$product[2],number_format($product[3],2),number_format($product[4],2),number_format($product[5],2),number_format($product[6],2));
	}
	mysql_free_result($query);
}
echo json_encode($data);
?>