<?php
 $data=array();
 if(isset($_GET['accID'])){
 	include('../class/class.php');
	$account = new Account($_GET['accID']);
	$col = array('accID','accUsername','accPassword','accName','addedBy','regDateTime','addProduct','editProduct','deleteProduct','addPurchase','editPurchase','deletePurchase','addCustomer','editCustomer','deleteCustomer','addCategory','editCategory','deleteCategory','addAccount','editAccount','deleteAccount','acceptReturn','acceptPayment','accStatus');
	$var = '';
	for($i=0;$i<count($col);$i++){
		$data[] = array($account->$col[$i]);
	}
	//$var = substr_replace( $var, "", -3 );
	//$data = array($var);
 }else {
	require_once('../Connections/connection.php');	
	mysql_connect($db_hostname,$db_username,$db_password);
	mysql_select_db($db_database);
    $query=mysql_query("SELECT accUsername,accName,regDateTime,getAccName(addedBy) FROM account WHERE accStatus='Active' order by accID");
	$count = $credit = $limit = 0;
	while($fetch = mysql_fetch_array($query))
	{	
		$data[] = array ($fetch[0],$fetch[1],$fetch[2],$fetch[3]);
		$count +=1;
	}
	mysql_free_result($query);
	$query=mysql_query("SELECT NOW()");
	$fetch = mysql_fetch_array($query);
	$data[] = array(number_format($count,0),$fetch[0]);
	mysql_free_result($query);
 }
 echo json_encode($data);
?>