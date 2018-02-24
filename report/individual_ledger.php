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
mysql_connect($server,$user,$pass);
mysql_select_db($db);
$query=mysql_query("SELECT getAccName(".$_GET['accID'].")");
$fetch = mysql_fetch_array($query);
$PHPJasperXML = new PHPJasperXML();
//$veri = $fetch[0];
//echo $fetch[0];
//$PHPJasperXML->debugsql=true;
$PHPJasperXML->arrayParameter= array("from"=>$from,"to"=>$to,"from1"=>$from1,"to1"=>$to1,"custID"=>"'".$_GET['custID']."'","custID1"=>$_GET['custID'],"verifier"=>strtoupper($fetch[0]));
//$PHPJasperXML->arrayParameter=array("param"=>$_GET['purcID']);
$PHPJasperXML->load_xml_file("individual_ledger1.jrxml");
mysql_free_result($query);
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>