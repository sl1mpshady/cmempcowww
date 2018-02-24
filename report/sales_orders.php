<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');


mysql_connect($server,$user,$pass);
mysql_select_db($db);
$from1 = date_create($_GET['from']); 
$to1 = date_create($_GET['to']);

$from1 = date_format($from1,"m/d/Y");
$to1 = date_format($to1,"m/d/Y");
$from = "'".$_GET['from']."'";
$to = "'".$_GET['to']."'";
$type = $_GET['type'];
$value = $_GET['value'];

class Query {
	public $count, $sum, $query;
	public function query1($query1){
		$query = mysql_query($query1);
		$so = mysql_fetch_array($query);
		$this->count = $so[0];
		$this->sum = $so[1];
		mysql_free_result($query);
	}
}
$Cash = new Query;
$Credit = new Query;
$Panel = new Query;
$PHPJasperXML = new PHPJasperXML();
//echo $Cash." ".$totCash." \n ".$Credit." ".$totCredit;
//$PHPJasperXML->debugsql=true;
//$PHPJasperXML->arrayParameter=array("purchaseID"=>$_GET['purcID'],"suppName"=>$fetch['Sn'],"suppAddress"=>$fetch['sA'],"purcDateOfPurchase"=>$_GET['dP'],'count'=>$fetch['count'],'totalQ'=>$fetch['totalQ'],'totC'=>$fetch['totC'],'account'=>strtoupper($fetch['account']),'purcDateTime'=>$fetch['date'],'NOW'=>$fetch['NOW'],"InvoiceNo"=>$fetch['InvoiceNum'],"InvoiceDate"=>$fetch['InvoiceDate']);
if($value!=NULL || $value!=''){
	if($type=='saleID'){
		$Cash->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND saleID LIKE '%$value%' AND saleType='Cash'");
		$Credit->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND saleID LIKE '%$value%' AND saleType='Credit'");
		$Panel->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND saleID LIKE '%$value%' AND saleType='Panel'");
		$PHPJasperXML->arrayParameter=array("saleID"=>$value,"from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"totCash"=>$Cash->sum,"totCredit"=>$Credit->sum,"Cash"=>$Cash->count,"Credit"=>$Credit->count,"Panel"=>$Panel->count);
		$PHPJasperXML->load_xml_file("sales_order.jrxml");	
	}
	else if ($type=='customer'){
		$Cash->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND getCustName(saleCustomer) LIKE '%$value%' AND saleType='Cash'");
		$Credit->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND getCustName(saleCustomer) LIKE '%$value%' AND saleType='Credit'");
		$Panel->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND getCustName(saleCustomer) LIKE '%$value%' AND saleType='Panel'");
		$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"customer"=>$value,"totCash"=>$Cash->sum,"totCredit"=>$Credit->sum,"Cash"=>$Cash->count,"Credit"=>$Credit->count,"Panel"=>$Panel->count);
		$PHPJasperXML->load_xml_file("sales_order1.jrxml");	
	}
	else if ($type=='assign'){
		$Cash->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND getAccName(saleAssign) LIKE '%$value%' AND saleType='Cash'");
		$Credit->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND getAccName(saleAssign) LIKE '%$value%' AND saleType='Credit'");
		$Panel->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND getAccName(saleAssign) LIKE '%$value%' AND saleType='Panel'");
		$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"assign"=>$value,"totCash"=>$Cash->sum,"totCredit"=>$Credit->sum,"Cash"=>$Cash->count,"Credit"=>$Credit->count,"Panel"=>$Panel->count);
		$PHPJasperXML->load_xml_file("sales_order2.jrxml");	
	}
	else if ($type=='custID'){
		$Cash->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND saleCustomer='$value' AND saleType='Cash'");
		$Credit->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND saleCustomer='$value' AND saleType='Credit'");
		$Panel->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND saleCustomer='$value' AND saleType='Panel'");
		$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"custID"=>$value,"totCash"=>$Cash->sum,"totCredit"=>$Credit->sum,"Cash"=>$Cash->count,"Credit"=>$Credit->count,"Panel"=>$Panel->count);
		$PHPJasperXML->load_xml_file("sales_order3.jrxml");	
	}
}
else {
	$Cash->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND saleType='Cash'");
	$Credit->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND saleType='Credit'");
	$Panel->query1("SELECT COUNT(saleID),IFNULL(SUM(saleNetAmount),'0.00') FROM sales_order WHERE DATEDIFF(saleDateTime,'".$_GET['from']."')>=0 AND DATEDIFF(saleDateTime,'".$_GET['to']."')<=0 AND saleType='Panel'");
	$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"totCash"=>$Cash->sum,"totCredit"=>$Credit->sum,"Cash"=>$Cash->count,"Credit"=>$Credit->count,"Panel"=>$Panel->count);
	$PHPJasperXML->load_xml_file("sales_orders.jrxml");
}

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>