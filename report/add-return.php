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
$query = mysql_query("SELECT retID,retType,retSubject,retCost,retGood,retBad,getAccName(retAssign),DATE_FORMAT(retDateTime ,'%m/%d/%y %h:%i %p') FROM _return WHERE retID='".$_GET['retID']."'");
$rt = mysql_fetch_array($query);
$PHPJasperXML = new PHPJasperXML();

//$PHPJasperXML->debugsql=true;
if($rt[1]=='SO'){
	$query1 = mysql_query("SELECT getCustName(saleCustomer), saleNetAmount FROM sales_order WHERE saleID='".$rt[2]."'");
	$so = mysql_fetch_array($query1);
	$PHPJasperXML->arrayParameter= array("retID"=>$rt[0],"retNo"=>$rt[0],"accName"=>strtoupper($rt[6]),"retType"=>"Sales Order","retDateTime"=>$rt[7],"so"=>$rt[2],"custName"=>strtoupper($so[0]),"soNet"=>$rt[3]+$so[1],"retCost"=>$rt[3],"soRemaining"=>$so[1],"good"=>$rt[4],"bad"=>$rt[5]);
	$PHPJasperXML->load_xml_file("add-return.jrxml");
	mysql_free_result($query1);
}
else {
	$query1 = mysql_query("SELECT LAST(operationmanager) FROM general_");
	$gen = mysql_fetch_array($query1);
	$query2 = mysql_query("SELECT purcCost FROM purchase WHERE purcID=".$rt[2]);
	$po = mysql_fetch_array($query2);
	$PHPJasperXML->arrayParameter = array("retID"=>$rt[0],"retNo"=>$rt[0],"accName"=>$rt[6],"retType"=>"Purchase Order","retDateTime"=>$rt[7],"po"=>$rt[2],"opManager"=>strtoupper($gen[0]),"poNet"=>$rt[3]+$po[0],"retCost"=>$rt[3],"poRemaining"=>$po[0],"good"=>$rt[4],"bad"=>$rt[5]);
	$PHPJasperXML->load_xml_file("add-return_1.jrxml");
	mysql_free_result($query1);
	mysql_free_result($query2);
}
mysql_free_result($query);

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>