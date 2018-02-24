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
mysql_connect($server,$user,$pass);
mysql_select_db($db);

$query1=mysql_query("SELECT getOthers('".$_GET['from']."','".$_GET['to']."')");
$fetch1 = mysql_fetch_array($query1);
$PHPJasperXML->arrayParameter=array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"others"=>$fetch1[0]);
$PHPJasperXML->load_xml_file("sales_summary.jrxml");

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I"); 
?>