<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');


//mysql_connect($server,$user,$pass);
mysql_connect($server,$user,$pass);
mysql_select_db($db);
$query=mysql_query("SELECT retType, retSubject, getAccName(retAssign), DATE_FORMAT(retDateTime,'%m/%d/%y %h:%i %p'), retType1 FROM _return WHERE retID=".$_GET[i]);
$fetch = mysql_fetch_array($query);
$acc = strtoupper($fetch[2]);
$type = $fetch[0];
$id=$fetch[1];
$date = $fetch[3];
$type1 = $fetch[4];
($type1 == 'RF') ? $type1 = 'REFUND' : $type1 = 'REPLACEMENT';
mysql_free_result($query);
$PHPJasperXML = new PHPJasperXML();

//$PHPJasperXML->debugsql=true;
if($type=='PO'){
	$query=mysql_query("SELECT suppName,suppAddress FROM purchase WHERE purcID=".$id);
	$fetch = mysql_fetch_array($query);
	$PHPJasperXML->arrayParameter= array("retID"=>$_GET['i'],"account"=>$acc,"suppName"=>$fetch[0],"suppAddress"=>$fetch[1],"subject"=>$id,"retDateTime"=>$date);
	$PHPJasperXML->load_xml_file("return.jrxml");
}
else {
	$query=mysql_query("SELECT getCustName(saleCustomer) FROM sales_order WHERE saleID=".$id);
	$fetch = mysql_fetch_array($query);
	$PHPJasperXML->arrayParameter= array("retID"=>$_GET['i'],"account"=>$acc,"custName"=>strtoupper($fetch[0]),"subject"=>$id,"retDateTime"=>$date,"retType1"=>$type1);
	$PHPJasperXML->load_xml_file("return_1.jrxml");
}
mysql_free_result($query);
//$PHPJasperXML->arrayParameter=array("param"=>$_GET['purcID']);


$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>