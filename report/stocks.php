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
$query=mysql_query("SELECT COUNT(prodID),SUM(prodTQuantity), SUM(prodSQuantity), SUM(prodOHQuantity) FROM product ORDER BY  prodID desc");
$fetch = mysql_fetch_array($query);

$PHPJasperXML = new PHPJasperXML();
//$PHPJasperXML->debugsql=true;
$PHPJasperXML->arrayParameter= array("count"=>number_format($fetch[0],0),"sumT"=>number_format($fetch[1],2),"sumS"=>number_format($fetch[2],6),"sumO"=>number_format($fetch[3],2));
//$PHPJasperXML->arrayParameter=array("param"=>$_GET['purcID']);
$PHPJasperXML->load_xml_file("stocks.jrxml");
mysql_free_result($query);
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>