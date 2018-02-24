<?php
 require_once('../Connections/connection.php');	
 mysql_connect($db_hostname,$db_username,$db_password);
 mysql_select_db($db_database);
 $data=array();
 if(isset($_GET['custID'])){
 	include('../class/class.php');
	if($_GET['sql'] == 'sale'){
		$customer = new Customer($_GET['custID'],"SELECT concat(custFName,' ',custMName,' ',custLName) as name, custCredit, custLimit, DATEDIFF(custExpire,NOW()) as exp FROM customer WHERE custID='".$_GET['custID']."' AND custDelete!='Perma'",$_GET['sql']);
		$data = array($customer->name,$customer->credit,$customer->limit,$customer->expDate);
 	}
	else if($_GET['sql'] == 'payment'){
		$customer = new Customer($_GET['custID'],"SELECT concat(custFName,' ',custMName,' ',custLName) as name, custCredit, getLastPay('".$_GET['custID']."') as last FROM customer WHERE custID='".$_GET['custID']."' AND custDelete!='Perma'",$_GET['sql']);
		$data = array($customer->name,$customer->credit,$customer->last_payment);
		if($customer->name!=false){mysql_connect("localhost","root","");
	 	mysql_select_db("sims");
		$query = mysql_query("SELECT saleID, saleNetAmount, saleGrossAmount, saleBalance, saleDateTime, saleDueDate FROM sales_order WHERE saleCustomer='".$_GET['custID']."' AND saleBalance>0 AND saleType='Credit'");
		while($fetch=mysql_fetch_array($query)){
			 $data[]=array($fetch[0],number_format($fetch[1],2),number_format($fetch[2],2),number_format($fetch[3],2),$fetch[4],$fetch[5]);
		}}
	}
	else if($_GET['sql'] == 'return'){
		$customer = new Customer($_GET['custID'],"SELECT concat(custFName,' ',custMName,' ',custLName) as name, custCredit, getLastReturn('".$_GET['custID']."') as last FROM customer WHERE custID='".$_GET['custID']."' AND custDelete!='Perma'",$_GET['sql']);
		$data = array($customer->name,$customer->credit,$customer->last_return);
	}
 }
 else if(isset($_GET["term"])){
	 $term=$_GET["term"];
	 $query=mysql_query("SELECT custID FROM customer WHERE custDelete='False' AND custID like '%".$term."%' order by custID LIMIT 0,6");
		while($customer=mysql_fetch_array($query)){
			 $data[]=array(
						'value'=> $customer['custID'],
						'label'=> $customer['custID']
							);
		}
	mysql_free_result($query);
 }
 else if(isset($_GET['date'])){
    $query=mysql_query("SELECT custID, custFName, custMName, custLName, custRegDate, getCustLastTrans(custID), custCredit, custLimit FROM customer  WHERE custDelete!='Perma' order by custID");
	$count = $credit = $limit = 0;
	while($fetch = mysql_fetch_array($query))
	{
		$fetch[5] = $fetch[5]==NULL ? '' : $fetch[5];	
		$data[] = array ($fetch['custID'],$fetch['custFName'].' '.$fetch['custMName'].' '.$fetch['custLName'],$fetch[4],$fetch[5],number_format($fetch['custCredit'],2),number_format($fetch['custLimit'],2));
		$count +=1;
		$credit += $fetch[6];
		$limit +=$fetch[7];
	}
	mysql_free_result($query);
	$query=mysql_query("SELECT NOW()");
	$fetch = mysql_fetch_array($query);
	$data[] = array(number_format($count,0),number_format($credit,2),number_format($limit,2),$fetch[0]);
	mysql_free_result($query);
 }
 else {
    $query=mysql_query("SELECT custID, concat(custFName,' ',custLName) as name, custCredit, custLimit FROM customer order by custID");
	while($fetch = mysql_fetch_array($query))
	{
		$data[] = array ($fetch['custID'],$fetch['name'],number_format($fetch['custCredit'],2),number_format($fetch['custLimit'],2));
	}
	mysql_free_result($query);
 }
 echo json_encode($data);
?>