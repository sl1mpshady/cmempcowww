<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');


//mysql_connect($server,$user,$pass);
mysql_connect($server,$user,$pass);
mysql_select_db($db);
$query=mysql_query("SELECT operationmanager FROM general LIMIT 0,1");
$fetch = mysql_fetch_array($query);
$opmanager = strtoupper($fetch[0]);
$query=mysql_query("SELECT getAccName(".$_GET['accID'].") LIMIT 0,1");
$fetch = mysql_fetch_array($query);
$acc = strtoupper($fetch[0]);
mysql_free_result($query);
$PHPJasperXML = new PHPJasperXML();

//$PHPJasperXML->debugsql=true;
$PHPJasperXML->arrayParameter= array("preparer"=>$acc,"approver"=>$opmanager);
//$PHPJasperXML->arrayParameter=array("param"=>$_GET['purcID']);
$PHPJasperXML->load_xml_file("replenishment.jrxml");

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>