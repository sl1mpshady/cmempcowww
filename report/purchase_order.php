<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');



$purcID = $_GET['purcID'];
//mysql_connect($server,$user,$pass);
mysql_connect($server,$user,$pass);
mysql_select_db($db);
$query=mysql_query("SELECT count(*) as count,sum(prodQuantity) as totalQ, sum(prodCost) as totC, getPurcAssign(purcID) as account,DATE_FORMAT(getPurcDate(purcID),'%m/%d/%Y %h:%i %p') as date,DATE_FORMAT(getPurcInvoiceDate(purcID),'%m/%d/%Y') as InvoiceDate, getPurcInvoiceNum(purcID) as InvoiceNum, DATE_FORMAT(NOW(),'%m/%d/%Y %h:%i %p') as NOW, getPurcSupplyAddress(purcID) as sA, getPurcSuppName(purcID) as Sn FROM po_product_list WHERE purcID='$purcID'");
$fetch = mysql_fetch_array($query);

$PHPJasperXML = new PHPJasperXML();
//$PHPJasperXML->debugsql=true;
$PHPJasperXML->arrayParameter=array("purchaseID"=>$_GET['purcID'],"suppName"=>$fetch['Sn'],"suppAddress"=>$fetch['sA'],"purcDateOfPurchase"=>$_GET['dP'],'count'=>$fetch['count'],'totalQ'=>$fetch['totalQ'],'totC'=>$fetch['totC'],'account'=>strtoupper($fetch['account']),'purcDateTime'=>$fetch['date'],'NOW'=>$fetch['NOW'],"InvoiceNo"=>$fetch['InvoiceNum'],"InvoiceDate"=>$fetch['InvoiceDate']);
//$PHPJasperXML->arrayParameter=array("param"=>$_GET['purcID']);
$PHPJasperXML->load_xml_file("purchase_order.jrxml");
mysql_free_result($query);
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>