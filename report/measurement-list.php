<?php
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');

$PHPJasperXML = new PHPJasperXML();
mysql_connect($server,$user,$pass);
mysql_select_db($db);
$value = trim($_GET['value']);
if($value!=NULL || $value!=''){
	$PHPJasperXML->arrayParameter = array("value"=>$value);
	$PHPJasperXML->load_xml_file("measurement-list_1.jrxml");
}
else {
	$PHPJasperXML->arrayParameter = array();
	$PHPJasperXML->load_xml_file("measurement-list.jrxml");
}

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");
?>