<?php
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');

$PHPJasperXML = new PHPJasperXML();
mysql_connect($server,$user,$pass);
mysql_select_db($db);
$type = $_GET['type'];
$value = $_GET['value'];
if($type == 'measID')
	$type = "measID LIKE '%$value%' OR getMeasName(measID)"; 
$PHPJasperXML->arrayParameter = array("type"=>$type,"value"=>$value);
($value!=NULL && $value!='') ? $PHPJasperXML->load_xml_file("product-list_1.jrxml") : $PHPJasperXML->load_xml_file("product-list.jrxml");

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");
?>