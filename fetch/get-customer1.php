<?php
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$data=array();
$type = $_GET['type'];
$value = $_GET['value'];
if($value!=NULL || $value!=''){
	if($type=='custID')
		$query = mysql_query("SELECT custID, getCustName(custID), custDesignation, custSection, custDept, IFNULL(DATE_FORMAT(getCustLastTrans(custID),'%b %d, %Y %h:%i %p'),'--') FROM customer WHERE custID LIKE '%$value%'");
	else if($type=='getCustName(custID)')
		$query = mysql_query("SELECT custID, getCustName(custID), custDesignation, custSection, custDept, IFNULL(DATE_FORMAT(getCustLastTrans(custID),'%b %d, %Y %h:%i %p'),'--') FROM customer WHERE getCustName(custID) LIKE '%$value%'");
	else if($type=='custDesignation')
		$query = mysql_query("SELECT custID, getCustName(custID), custDesignation, custSection, custDept, IFNULL(DATE_FORMAT(getCustLastTrans(custID),'%b %d, %Y %h:%i %p'),'--') FROM customer WHERE custDesignation LIKE '%$value%'");
	else if($type=='custSection')
		$query = mysql_query("SELECT custID, getCustName(custID), custDesignation, custSection, custDept, IFNULL(DATE_FORMAT(getCustLastTrans(custID),'%b %d, %Y %h:%i %p'),'--') FROM customer WHERE custSection LIKE '%$value%'");
	else if($type=='custDepartment')
		$query = mysql_query("SELECT custID, getCustName(custID), custDesignation, custSection, custDept, IFNULL(DATE_FORMAT(getCustLastTrans(custID),'%b %d, %Y %h:%i %p'),'--') FROM customer WHERE custDepartment LIKE '%$value%'");
}
else
	$query = mysql_query("SELECT custID, getCustName(custID), custDesignation, custSection, custDept, IFNULL(DATE_FORMAT(getCustLastTrans(custID),'%b %d, %Y %h:%i %p'),'--') FROM customer");
while($po=mysql_fetch_array($query))
	$data[]=array($po[0],$po[1],$po[2],$po[3],$po[4],$po[5]);

mysql_free_result($query);
echo json_encode($data);

 ?>