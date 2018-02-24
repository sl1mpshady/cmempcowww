<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');

$from1 = date_create($_GET['from']); 
$to1 = date_create($_GET['to']);

$from1 = date_format($from1,"m/d/Y");
$to1 = date_format($to1,"m/d/Y");
$from = "'".$_GET['from']."'";
$to = "'".$_GET['to']."'";
//mysql_connect($server,$user,$pass);

$PHPJasperXML = new PHPJasperXML();
//$PHPJasperXML->debugsql=true;
$PHPJasperXML->arrayParameter= array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"prodID"=>"'".$_GET['prodID']."'");
//$PHPJasperXML->arrayParameter=array("param"=>$_GET['purcID']);
$PHPJasperXML->load_xml_file("stock_logs.jrxml");

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>