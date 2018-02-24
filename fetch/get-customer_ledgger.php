<?php
$data=array();
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$type = $_GET['type'];
$value = $_GET['value'];
$type1 = $_GET['type2'];
if($type1!='all'){
	if($type1=='regular')
		$type1 = "AND custAccType = 'Regular'";
	else
		$type1 = "AND (custAccType = 'Casual Skilled' OR custAccType = 'Casual Non Skilled')";
} else 
$type1 = '';
if(isset($_GET['to'])){
	if($value!=NULL){
		if($type=='custID')
			$query = "SELECT custID, custFname, custMName, custLName, custSection, custDept, getCustCreditLedger(custID,'".$_GET['to']."') FROM customer WHERE custID LIKE '%".$value."%' AND custDelete='False' AND getCustCreditLedger(custID,'".$_GET['to']."')>0 ".$type1." ORDER BY custID ASC";
		else if($type=='custName')
			$query = "SELECT custID, custFname, custMName, custLName, custSection, custDept, getCustCreditLedger(custID,'".$_GET['to']."') FROM customer WHERE getCustName(custID) LIKE '%".$value."%' AND custDelete='False' AND getCustCreditLedger(custID,'".$_GET['to']."')>0 ".$type1." ORDER BY custID ASC";
		else if($type=='custSection')	
			$query = "SELECT custID, custFname, custMName, custLName, custSection, custDept, getCustCreditLedger(custID,'".$_GET['to']."') FROM customer WHERE custSection LIKE '%".$value."%' AND custDelete='False' AND getCustCreditLedger(custID,'".$_GET['to']."')>0 ".$type1." ORDER BY custID ASC";
		else if($type=='custDept')
			$query = "SELECT custID, custFname, custMName, custLName, custSection, custDept, getCustCreditLedger(custID,'".$_GET['to']."') FROM customer WHERE custDept LIKE '%".$value."%' AND custDelete='False' AND getCustCreditLedger(custID,'".$_GET['to']."')>0 ".$type1." ORDER BY custID ASC";
	}
	else {
		$query = "SELECT custID, custFName, custMName, custLName, custSection, custDept, getCustCreditLedger(custID,'".$_GET['to']."') FROM customer WHERE custDelete='False' AND getCustCreditLedger(custID,'".$_GET['to']."')>0 ".$type1." ORDER BY custID asc" ;
	}
	$query=mysql_query($query);
	while($so=mysql_fetch_array($query)){
		$data[]=array($so[0],strtoupper($so[3].", ".$so[1]." ".$so[2][0]."."),$so[4],$so[5],number_format($so[6],2));
	}
	mysql_free_result($query);
}
echo json_encode($data);
?>