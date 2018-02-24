<?php
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');

$PHPJasperXML = new PHPJasperXML();
mysql_connect($server,$user,$pass);
mysql_select_db($db);
$from = "'".$_GET['from']."'";
$to = "'".$_GET['to']."'";
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
//$PHPJasperXML->debugsql=true;
if($type=='custName')
		$type = 'getCustName(custID)';
$PHPJasperXML->arrayParameter = array("to"=>$to,"from"=>$from,type=>$type,type1=>$type1,value=>$value,"assign"=>$_GET['assign']);
($value!=NULL || $value!='') ? $PHPJasperXML->load_xml_file("over-all_ledger_1.jrxml") : $PHPJasperXML->load_xml_file("over-all_ledger.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");
?>