<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
if(isset($_GET['from']) && isset($_GET['to']) && isset($_GET['prodID'])){
	$query=mysql_query("SELECT getPurcDate(purcID),prodQuantity,prodCost,purcID,getPurcAssign(purcID),stock FROM po_product_list WHERE prodID='".$_GET['prodID']."' AND DATEDIFF(getPurcDate(purcID), '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',getPurcDate(purcID)) >= 0");
	while($fetch=mysql_fetch_array($query)){
		 $date=date_create($fetch[0]);
		 $fetch[0] = date_format($date,"M d, o h:i A");
		 $data[]=array($fetch[0],'( IN )  Purchase Order <a href="view-purchase_order.php?i='.$fetch[3].'">'.$fetch[3].'</a>', number_format($fetch[1],2),number_format($fetch[2],2),number_format($fetch[5],2),number_format($fetch[5]+$fetch[1],2),$fetch[4]);
	}
	$query=mysql_query("SELECT getSaleDate(saleID),prodQty,netPrice,saleID,getAccName(getSaleAssign(saleID)),stock FROM so_product_list WHERE prodID='".$_GET['prodID']."' AND DATEDIFF(getSaleDate(saleID), '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',getSaleDate(saleID)) >= 0");
	while($fetch=mysql_fetch_array($query)){
		 $date=date_create($fetch[0]);
		 $fetch[0] = date_format($date,"M d, o h:i A");
		 $data[]=array($fetch[0],'( OUT )  Sales Order <a href="view-sales_order.php?i='.$fetch[3].'">'.$fetch[3].'</a>', number_format($fetch[1],2),number_format($fetch[2],2),number_format($fetch[5],2),number_format($fetch[5]-$fetch[1],2),$fetch[4]);
	}
}
mysql_free_result($query);
echo json_encode($data);
?>