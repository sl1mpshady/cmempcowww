<?php
 $data=array();
 include('../class/class.php');
 if(isset($_GET['catID'])){
	//isset($_GET['type']) ? $product = new Product($_GET['prodID'],true) : $product = new Product($_GET['prodID']);
	$category = new Category($_GET['catID']);
	$data = array($category->catName,$category->catDiscount,$category->catStatus,$category->catMeasurement,$category->catSubProduct);
 }
else{
	require_once('../Connections/connection.php');	
	mysql_connect($db_hostname,$db_username,$db_password);
	mysql_select_db($db_database);
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