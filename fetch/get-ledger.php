<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);

if(isset($_GET['from']) && isset($_GET['to']) && isset($_GET['custID'])){
	$query=mysql_query("SELECT saleDateTime,saleNetAmount,customerBal,getAccName(saleAssign),saleID, saleType FROM sales_order WHERE saleCustomer='".$_GET['custID']."' AND DATEDIFF(saleDateTime, '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',saleDateTime) >= 0");
	while($fetch=mysql_fetch_array($query)){
		 $date=date_create($fetch[0]);
		 $fetch[0] = date_format($date,"M d, o h:i A");
		 $type = '';
		 if($fetch[5]=='Panel')
			$type = "Panel/SO";
		 else
			$type = "Sales Order";
		 $data[]=array($fetch[0],$type.' <a href="view-sales_order.php?i='.$fetch[4].'">'.$fetch[4].'</a>', number_format($fetch[1],2),number_format($fetch[2],2),$fetch[3]);
	}
	/*$query=mysql_query("SELECT saleDateTime,saleNetAmount,customerBal,getAccName(saleAssign),saleID FROM sales_order WHERE saleCustomer='".$_GET['custID']."' AND salePanel>0 AND DATEDIFF(saleDateTime, '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',saleDateTime) >= 0");
	while($fetch=mysql_fetch_array($query)){
		 $date=date_create($fetch[0]);
		 $fetch[0] = date_format($date,"M d, o h:i A");
		 $data[]=array($fetch[0],'Panel/SO <a href="view-sales_order.php?i='.$fetch[4].'">'.$fetch[4].'</a>', number_format($fetch[1],2),number_format($fetch[2],2),$fetch[3]);
	}*/
	$query=mysql_query("SELECT getgetDedDate(dedID),deduction,netCredit,getAccName(getgetDedAcc(dedID)),dedID FROM pd_customer_list WHERE custID='".$_GET['custID']."' AND DATEDIFF(getgetDedDate(dedID), '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',getgetDedDate(dedID)) >= 0");
	
	while($fetch=mysql_fetch_array($query)){
		 $date=date_create($fetch[0]);
		 $fetch[0] = date_format($date,"M d, o h:i A");
		 $data[]=array($fetch[0],'Deduction <a href="view-deduction.php?i='.$fetch[4].'">'.$fetch[4].'</a>', number_format($fetch[1],2),number_format($fetch[2],2),$fetch[3]);
	}
	$query=mysql_query("SELECT retDateTime, retCost,NULL, getAccName(retAssign),retID FROM _return WHERE retType='SO' AND getSaleCustID(retSubject)='".$_GET['custID']."' AND DATEDIFF(retDateTime, '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',retDateTime) >= 0");
	while($fetch=mysql_fetch_array($query)){
		 $date=date_create($fetch[0]);
		 $fetch[0] = date_format($date,"M d, o h:i A");
		 $data[]=array($fetch[0],'Return <a href="view-return.php?i='.$fetch[4].'">'.$fetch[4].'</a>', number_format($fetch[1],2),number_format($fetch[2],2),$fetch[3]);
	}
	$query=mysql_query("SELECT refDateTime, refCost,NULL, getAccName(refAssign),retID FROM refund WHERE custID='".$_GET['custID']."' AND DATEDIFF(refDateTime, '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',refDateTime) >= 0");
	while($fetch=mysql_fetch_array($query)){
		 $date=date_create($fetch[0]);
		 $fetch[0] = date_format($date,"M d, o h:i A");
		 $data[]=array($fetch[0],'Refund of Return <a href="view-return.php?i='.$fetch[4].'">'.$fetch[4].'</a>', number_format($fetch[1],2),number_format($fetch[2],2),$fetch[3]);
	}
	$query=mysql_query("SELECT repDateTime, repCost,NULL, getAccName(repAssign),retID FROM Replacement WHERE custID='".$_GET['custID']."' AND DATEDIFF(repDateTime, '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',repDateTime) >= 0");
	while($fetch=mysql_fetch_array($query)){
		 $date=date_create($fetch[0]);
		 $fetch[0] = date_format($date,"M d, o h:i A");
		 $data[]=array($fetch[0],'Replacement of Return <a href="view-return.php?i='.$fetch[4].'">'.$fetch[4].'</a>', number_format($fetch[1],2),number_format($fetch[2],2),$fetch[3]);
	}
	
}
mysql_free_result($query);
echo json_encode($data);
?>

