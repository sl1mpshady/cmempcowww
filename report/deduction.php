<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');



$dedID = $_GET['i'];
//mysql_connect($server,$user,$pass);
mysql_connect("localhost","root","");
mysql_select_db("sims2");
$query = mysql_query("SELECT dedID, DATE_FORMAT(dedDateTime,'%m/%d/%y %h:%i %p'), getAccName(dedAssign) FROM deduction WHERE dedID=".$_GET['i']);
$fetch = mysql_fetch_array($query);

$PHPJasperXML = new PHPJasperXML();
//$PHPJasperXML->debugsql=true;

$PHPJasperXML->arrayParameter=array("dedID"=>$_GET['i'],"dedDateTime"=>$fetch[1],"assign"=>strtoupper($fetch[2]));
$PHPJasperXML->load_xml_file("deduction.jrxml");
mysql_free_result($query);
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>