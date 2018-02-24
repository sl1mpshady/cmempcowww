<?php
 $data=array();
 require_once('../Connections/connection.php');	
 mysql_connect($db_hostname,$db_username,$db_password);
 mysql_select_db($db_database);
 if(isset($_GET['from']) && isset($_GET['to'])){
	 if($_GET['customer'] != '')
	 $query = "SELECT saleID, saleNetAmount, saleGrossAmount, getCustName(saleCustomer) as Customer, saleDateTime, saleType FROM sales_order WHERE saleCustomer='".$_GET['customer']."' AND DATEDIFF(saleDateTime, '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',saleDateTime) >= 0" ;
	 else
	  $query = "SELECT saleID, saleNetAmount, saleGrossAmount, getCustName(saleCustomer) as Customer, saleDateTime, saleType FROM sales_order WHERE DATEDIFF(saleDateTime, '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',saleDateTime) >= 0" ;
	 $query=mysql_query($query);
	 $gross=$net=$cash=$credit=$totcash=0;
	 while($so=mysql_fetch_array($query)){
		$data[]=array($so[0],number_format($so[1],2),number_format($so[2],2),$so[3],$so[4],$so[5]);
		$gross += $so[2];
		$net += $so[1];
		if($so[5]=='Cash'){
			$cash += 1;
			$totcash += $so[1];
		}
		else if($so[5]=='Credit')
			$credit += 1;
	 }
	 $data[]=array(number_format($net,2),number_format($gross,2),number_format($cash),number_format($credit),number_format($totcash,2));
	 mysql_free_result($query);
 }
else if(isset($_GET['saleID']) && isset($_GET['payment'])){
	$query = "SELECT saleGrossAmount,saleNetAmount,saleGrossAmount-saleNetAmount,saleDateTime,saleDueDate FROM sales_order WHERE saleID='".$_GET['saleID']."'";
	$query= mysql_query($query);
	$fetch = mysql_fetch_array($query);
	$data[]=array($fetch[0],$fetch[1],$fetch[2],$fetch[3],$fetch[4]);
	$query= mysql_query("SELECT saleBalance FROM payment_so_list WHERE payID='".$_GET['payment']."' AND saleID='".$_GET['saleID']."'");
	$fetch = mysql_fetch_array($query);
	$data[]=array($fetch[0]);
	mysql_free_result($query);
}
 else if(isset($_GET['saleID']) && isset($_GET['products'])){
	 $saleID = $_GET['saleID'];
	 $qty = 0;
	 $query=mysql_query("SELECT *, getProdCategory(prodID) as Cat,getProdName(prodID) as prodName FROM so_product_list where saleID='$saleID'");
		while($product=mysql_fetch_array($query)){
			 $data[]=array($product["Cat"],$product["prodName"],number_format($product["prodQty"],0),number_format($product["grossPrice"]/$product["prodQty"],2),number_format($product["grossPrice"],2),number_format($product["netPrice"],2),number_format($product["grossPrice"]-$product["netPrice"],2));
			 $qty += $product["prodQty"];
		}
	mysql_free_result($query);
	$query=mysql_query("SELECT getAccName(saleAssign) FROM sales_order WHERE saleID='$saleID' LIMIT 0,1");
	$acc=mysql_fetch_array($query);
	$data[] = array(number_format($qty,0),$acc[0]);
		
	mysql_free_result($query);
 }
 else if(isset($_GET['saleID'])){
	 if(isset($_GET['check'])){
		if($_GET['check']==true)
			$query = "SELECT saleID,DATEDIFF(NOW(),saleDateTime),saleType,saleNetAmount FROM sales_order WHERE saleID='".$_GET['saleID']."'";
		else
	 		$query = "SELECT saleID,DATEDIFF(NOW(),saleDateTime),saleType,saleNetAmount FROM sales_order WHERE saleCustomer='".$_GET['check']."' AND saleID='".$_GET['saleID']."'";
		$query= mysql_query($query);
		if(mysql_num_rows($query) == 0)
			return false;
		else{
			$fetch = mysql_fetch_array($query);
			$data1=array($fetch[1],$fetch[2],$fetch[3]);
			mysql_free_result($query);
			$data2 = array();
			$query = mysql_query("SELECT retID,retDateTime,retQty FROM _return WHERE retType='SO' AND retSubject='".$_GET['saleID']."'");
			while($fetch=mysql_fetch_array($query)){
				 $data2[]=array($fetch[0],$fetch[1],number_format($fetch[2],0));
			}
			$data[] = array($data1,$data2);
			mysql_free_result($query);
			}
	 }
	 $query = "SELECT unit,prodID, getProdName(prodID), netPrice/prodQty, prodQty,grossPrice, netPrice,cost,getMeasurement(unit) FROM so_product_list WHERE saleID='".$_GET['saleID']."'";
	 $query=mysql_query($query);
	 while($so=mysql_fetch_array($query)){
	 	$data[]=array($so[0],$so[1],$so[2],number_format($so[3],2),number_format($so[4],2),number_format($so[5],2),number_format($so[6],2),number_format($so[6]-$so[7],2),$so[7],$so[8]);
	 }	
	 mysql_free_result($query);
	 $query = "SELECT saleNetAmount, saleGrossAmount,saleDateTime,saleBalance, saleDueDate, saleCustomer, getCustName(saleCustomer), saleType,saleEntrusted FROM sales_order WHERE saleID='".$_GET['saleID']."'";
	 $query=mysql_query($query);
	 $so=mysql_fetch_array($query);
	 $date=date_create($so[2]);
	 $so[2] = date_format($date,"M d, o h:i A");
	 $date=date_create($so[4]);
	 $so[4] = ($so[4]=='0000-00-00') ? "N/A" : date_format($date,"M d, o");
	 $data[] = array(number_format($so[0],2),number_format($so[1],2),number_format($so[1]-$so[0],2),$so[2],number_format($so[3],2),$so[4],$so[5],$so[6],$so[7],number_format($so[0]-$so[3],2),$so[8]);
	 mysql_free_result($query);
	 if(isset($_GET['account'])){
	 	$query = "SELECT getAccName(saleAssign) FROM sales_order WHERE saleID='".$_GET['saleID']."'";
	    $query=mysql_query($query);
		$so=mysql_fetch_array($query);
		$data[] = array($so[0]);
		mysql_free_result($query);
	 }
 }
 else if(isset($_GET['custID']) && isset($_GET["term"])){
	 $term=$_GET["term"];
	 $custID = $_GET["custID"];
	 $query=mysql_query("SELECT saleID FROM sales_order WHERE saleCustomer='$custID' AND saleID like '%".$term."%' order by saleID LIMIT 0,6");
		while($so=mysql_fetch_array($query)){
		   $data[]=array(
			'value'=> $so['saleID'],
			'label'=> $so['saleID']
		   );
		}
	mysql_free_result($query);
	 
 }
 else {
 	$term=$_GET["term"];
   	$query=mysql_query("SELECT saleID FROM sales_order WHERE saleID like '%".$term."%' order by saleID LIMIT 0,5");
	  while($so=mysql_fetch_array($query)){
		 $data[]=array(
		  'value'=> $so['saleID'],
		  'label'=> $so['saleID']
		 );
	  }
	mysql_free_result($query);
 }
 echo json_encode($data);
?>