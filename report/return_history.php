<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');


$PHPJasperXML = new PHPJasperXML();

//$PHPJasperXML->debugsql=true;
$PHPJasperXML->arrayParameter= array("retSubject"=>$_GET['i']);
if($_GET['type']=='SO')
	$PHPJasperXML->load_xml_file("return-history_1.jrxml");
else 
	$PHPJasperXML->load_xml_file("return-history_2.jrxml");

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>