<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
if(isset($_POST['retID'])){
	if(isset($_POST['info'])){
		$query=mysql_query("SELECT *,getCustName(retCustomer) as customer,getSaleNetAmount(retSaleID) as net,getAccName(retAssign) acc FROM _return WHERE retID='".$_POST['retID']."' LIMIT 0,1");
		$fetch=mysql_fetch_array($query);
		$data[] = array($fetch['retCustomer'],$fetch['customer'],$fetch['retDateTime'],$fetch['retID'],$fetch['retSaleID'],$fetch['retQty'],$fetch['retGood'],$fetch['retBad'],$fetch['net'],$fetch['retCost'],number_format($fetch['net'],2),$fetch['acc']);
		mysql_free_result($query);
	}
	$query=mysql_query("SELECT getProdCategory(prodID),prodID,getProdName(prodID),prodStatus,prodQty,prodCost,prodQtyReturn,prodCost FROM _return_product_list WHERE retID='".$_POST['retID']."'");
	while($fetch=mysql_fetch_array($query)){
		 $data[]=array($fetch[0],$fetch[1],$fetch[2],$fetch[3],number_format($fetch[4],0),number_format($fetch[5]/$fetch[4],2),number_format($fetch[6],0),number_format($fetch[7],2));
	}
	mysql_free_result($query);
}
echo json_encode($data);
?>