<?php
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

if($value!=NULL || $value!=''){
	$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"value"=>$value);
	$PHPJasperXML->load_xml_file("account_logs_1.jrxml");	
}
else {
	$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1);
	$PHPJasperXML->load_xml_file("account_logs.jrxml");
}

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I"); 
?>