<?php
include_once('class/tcpdf/tcpdf.php');
include_once("class/PHPJasperXML.inc.php");
include_once ('setting.php');

$PHPJasperXML = new PHPJasperXML();
mysql_connect($server,$user,$pass);
mysql_select_db($db);
$type = $_GET['type'];
$value = $_GET['value'];
$type = ($type=='uname') ? 'username' : 'name';
$query = mysql_query("SELECT getAccCashier('$type','$value'), getAccBasic('$type','$value'), getAccIntermediate('$type','$value'), getAccAdvance('$type','$value')");
//echo "SELECT getAccCashier('$type','$value'), getAccBasic('$type','$value'), getAccIntermediate('$type','$value'), getAccAdvance('$type','$value')";
$ac = mysql_fetch_array($query);
if($value!=NULL || $value!=''){
	$type = ($type=='username') ? 'accUsername' : 'accName';
	$PHPJasperXML->arrayParameter = array("CS"=>$ac[0],"BS"=>$ac[1],"IM"=>$ac[2],"AV"=>$ac[3],"type"=>$type,"value"=>$value);
	$PHPJasperXML->load_xml_file("account-list_1.jrxml");
}
else {
	$PHPJasperXML->arrayParameter = array("CS"=>$ac[0],"BS"=>$ac[1],"IM"=>$ac[2],"AV"=>$ac[3]);
	$PHPJasperXML->load_xml_file("account-list.jrxml");
}

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");
?>