<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');


$from1 = date_create($_GET['from']); 
$to1 = date_create($_GET['to']);

$from1 = date_format($from1,"m/d/Y");
$to1 = date_format($to1,"m/d/Y");
$from = "'".$_GET['from']."'";
$to = "'".$_GET['to']."'";
$type = $_GET['type'];
$value = $_GET['value'];
$PHPJasperXML = new PHPJasperXML();
//$PHPJasperXML->debugsql=true;
//$PHPJasperXML->arrayParameter=array("purchaseID"=>$_GET['purcID'],"suppName"=>$fetch['Sn'],"suppAddress"=>$fetch['sA'],"purcDateOfPurchase"=>$_GET['dP'],'count'=>$fetch['count'],'totalQ'=>$fetch['totalQ'],'totC'=>$fetch['totC'],'account'=>strtoupper($fetch['account']),'purcDateTime'=>$fetch['date'],'NOW'=>$fetch['NOW'],"InvoiceNo"=>$fetch['InvoiceNum'],"InvoiceDate"=>$fetch['InvoiceDate']);
if($value!=NULL || $value!=''){
	if($type=='purcID'){
		$PHPJasperXML->arrayParameter=array("purcID"=>$value,"from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1);
		$PHPJasperXML->load_xml_file("purchase_orders2.jrxml");	
	}
	else if ($type=='supplier'){
		$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"supplier"=>$value);
		$PHPJasperXML->load_xml_file("purchase_orders3.jrxml");	
	}
	else if ($type=='assign'){
		$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"assign"=>$value);
		$PHPJasperXML->load_xml_file("purchase_orders4.jrxml");	
	}
}
else {
	$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1);
	$PHPJasperXML->load_xml_file("purchase_orders1.jrxml");
}

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I","PurchaseOrders_".time().".pdf");    //page output method I:standard output  D:Download file
?>