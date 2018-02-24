<?php
if (!isset($_SESSION)) {
  session_start();
}

require('../class/class.php');
$data = array();
if(isset($_POST['new'])){
	$return = new _Return;
	$retID = $return->new_($_POST['retType'],$_POST['retSubject'],$_POST['retCost'],$_POST['retQty'],$_POST['retGood'],$_POST['retBad'],$_POST['accID'],$_POST['note'],$_POST['type1']);
	
	//$type = $return->type1($retID,$_POST['retCost'],$_POST['retQty'],$_POST['accID'],$_POST['type1']);
	$data[] = array($retID);
	echo json_encode($data);
}
else if(isset($_POST['retID'])) {
	$retID = $_POST['retID'];
	$retSubject = $_POST['retSubject'];
	$retType = $_POST['retType'];
	$type = $_POST['type1'];
	$return = new _Return;
	$return->products($retID,$_POST['prodID'],$_POST['prodCost'],$_POST['prodQty'],$_POST['prodQtyReturn'],$_POST['prodStatus'],$retSubject,$retType,$_POST['unit'],$type);
	/*$query = new Database;
	$query1 = $query->connect("SELECT saleBalance FROM sales_order WHERE saleID='$saleID' LIMIT 0,1");
	$fetch = mysql_fetch_array($query1);
	if($fetch[0]<0)
		$query->connect("UPDATE sales_order SET saleBalance=0 WHERE saleID='$saleID'");
	$query->__close();*/
}
?>