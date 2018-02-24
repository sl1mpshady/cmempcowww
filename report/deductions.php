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
	if($type=='dedID'){
		$PHPJasperXML->arrayParameter=array("dedID"=>$value,"from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1);
		$PHPJasperXML->load_xml_file("deductions3.jrxml");	
	}
	else if ($type=='assign'){
		$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"assign"=>$value);
		$PHPJasperXML->load_xml_file("deductions2.jrxml");	
	}
}
else {
	$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1);
	$PHPJasperXML->load_xml_file("deductions1.jrxml");
}

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I"); 
?>