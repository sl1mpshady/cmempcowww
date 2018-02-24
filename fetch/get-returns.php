<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
if(isset($_GET['from']) && isset($_GET['to'])){
	$query=mysql_query("SELECT retID,retSaleID,getCustName(retCustomer),retCost,retDateTime,getAccName(retAssign),NOW() FROM _return WHERE DATEDIFF(retDateTime, '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',retDateTime) >= 0");
	$count = $paid = $date = 0;
	while($fetch=mysql_fetch_array($query)){
		 $data[]=array($fetch[0],$fetch[1],$fetch[2],number_format($fetch[3],2),$fetch[4],$fetch[5]);
		 $count +=1;
		 $paid +=$fetch[3];
		 $date = $fetch[6];
	}
	$data[]=array(number_format($count,0),number_format($paid,2),$date);
	
}
else if(isset($_GET['retID'])){
	$netAmount = 0;
	$query=mysql_query("SELECT prodID,getProdName(prodID),prodQty,prodCost/prodQtyReturn,prodStatus,prodQtyReturn,prodCost,unit FROM _return_product_list WHERE retID='".$_GET['retID']."'");
	while($fetch=mysql_fetch_array($query)){
		 $fetch[4] = $fetch[4]=='Bad' ? 'Damage' : 'Good';
		 $data[]=array($fetch[0],$fetch[1],number_format($fetch[2],2),$fetch[7],number_format($fetch[3],2),$fetch[4],number_format($fetch[5],0),number_format($fetch[6],2));
		 $netAmount += $fetch[3]*($fetch[2]-$fetch[5]);
	}
	
	mysql_free_result($query);
	$query=mysql_query("SELECT retType, retSubject, getSubjectDate(retSubject,retType), getSubjectAmount(retSubject,retType), getSubjectAssign(retSubject,retType), getSubjectNote(retSubject,retType), retCost, retDateTime, getAccName(retAssign), retQty, retNote FROM _return WHERE retID='".$_GET['retID']."' LIMIT 0,1");
	$fetch=mysql_fetch_array($query);
	$date=date_create($fetch[2]);
	$fetch[2] = date_format($date,"M d, o h:i A");
	$date=date_create($fetch[7]);
	$fetch[7] = date_format($date,"M d, o h:i A");
	if(number_format(substr($fetch[9],strpos($fetch[9],".")+1)) == 0)
		$fetch[9] = number_format($fetch[9],0);
	else
		$fetch[9] = number_format($fetch[9],2);
	$data[]=array($fetch[0], $fetch[1], $fetch[2], number_format($fetch[3],2), $fetch[4], $fetch[5], number_format($fetch[6],2), $fetch[7],$fetch[8], $fetch[9],$fetch[10]);
	mysql_free_result($query);
}
echo json_encode($data);
?>