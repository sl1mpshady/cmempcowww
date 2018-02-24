<?php
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');

$PHPJasperXML = new PHPJasperXML();
mysql_connect($server,$user,$pass);
mysql_select_db($db);
$type = $_GET['type'];
$value = $_GET['value'];

$ac = mysql_fetch_array($query);
$PHPJasperXML->arrayParameter = array("type"=>$type,"value"=>$value);
($value!=NULL && $value!='') ? $PHPJasperXML->load_xml_file("customer-list_1.jrxml") : $PHPJasperXML->load_xml_file("customer-list.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");
?>