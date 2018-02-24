<?php require_once('Connections/connSIMS.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
if(isset($_SESSION['MM_Username'])){

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>StoreSys</title>
<?php include_once('menu.php'); ?>
<title></title>

<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>

<script>
$(document).ready(function () {
	//$('.print_info').fadeIn(1000).fadeOut(2500);
	$('#setup').addClass('active');
	$('#setup_menu').show();
	$('#general_menu').addClass('selected');		
    $('#salesPanel,#salesTax').autoNumeric('init',{'vMin':0,'mDec':2,'vMax':99.99});
    $('#salesReturn').autoNumeric('init',{'vMin':0,'mDec':0});
});	
</script>
<style>
textarea {
    resize: none;
}
.btn {
    background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
    padding: 2px 5px;
    margin-left:5px;
    font-size:12px;
}
label>span {
	margin-right:22px;
}
.form-control {
	width: 90%;
    margin-left: 20px;
}
fieldset {
  border-radius: 4px 4px 0 0;
  -moz-border-radius: 4px 4px 0 0;
  -webkit-border-radius: 4px 4px 0 0;
  border: #E2D9D9 solid 1px;
  padding: 2px 10px;
  text-shadow: 1px 1px 2px #fff inset;
  -moz-box-shadow: 1px 1px 2px #fff inset;
  -webkit-box-shadow: 1px 1px 2px #fff inset;
  box-shadow: 1px 1px 2px #fff inset;
}
fieldset legend {
  font-weight: bold !important;
  color: #444 !important;
  padding: 5px 10px !important;
  border-radius: 2px !important;
  -moz-border-radius: 2px !important;
  -moz-box-shadow: 3px 3px 15px #bbb !important;
  width: auto !important;
  font-size: 14px;
  margin-bottom: 0 !important;
  border: 0 !important;
}
  
  fieldset fieldset {
	float:left;
	margin:19px;
}
td:nth-child(1){
	text-align:right;
	/*font-weight: bold;*/
  	width: 40%;
}
label {
	font-weight:normal;
}
td:nth-child(2) {
  padding: 5px;
}
#receipt-box td:nth-child(1){
	text-align:left;
	font-weight: normal;
  	width: 17%;
}
#sales-table td:nth-child(1){
 	width:50%;
}
.breadcrumb {
  padding: 12px 15px;
  margin-bottom: 20px;
  list-style: none;
  border-radius: 4px;
  color: #0088cc;
  text-decoration: none;
  background:none !important;
  cursor:pointer;
}
.breadcrumb>.active {
  color: #777;
  background-color: transparent;
}
.image-placeholder {
  color: #555;
  position: relative!important;
}
.image-placeholder-show {
  display: inline-block;
}
.im_upload {
  border-radius: 4px;
  color: white;
  border: 1px solid #cdcdcd;
  background-color: #fff;
  display: inline-block;
  font-weight: bold;
  text-decoration: none;
  width: 33px;
  margin-left: 4px;
  height:auto;
}
.image-placeholder input[type="file"] {
  position: absolute;
  top: 0;
  right: 0;
  float: left;
  margin: 0;
  opacity: 0;
  width: 100%;
  height: 107px;
  filter: alpha(opacity=0);
  border: 0;
  cursor: pointer;
  cursor: hand;
  font-size: 13px;
  z-index: 100;
  cursor:pointer;
}
input[type="file"]{
	cursor:pointer
}
#submit{
  font-size: 14px;
  padding: 0.75em 1.5em;
  background-color: #fff;
  border: 1px solid #bbb;
  color: #333;
  text-decoration: none;
  display: inline;
  border-radius: 4px;
  -webkit-transition: background-color 1s ease;
  -moz-transition: background-color 1s ease;
  transition: background-color 1s ease;
}
#receipt-box .form-control {
	margin:0;
	width:100%;
}
#receipt-box td td:nth-child(1) {
	width:42%;
	text-align:right;
	padding-right:25px;
}
#receipt-box td td:nth-child(2) {
	text-align:right;
}
#receipt-prev td:nth-child(1){
	text-align:left;
	font-weight:normal;
}
#receipt-prev td:nth-child(2){
	text-align:right;
}
</style>
<body>
<div style="display:none" id="parent">setup</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;margin-bottom:25px" id="header2">
    	Change Password
    </div>
    <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<span id="blank">Business name can not be blanked.</span>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div id="sales-error" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<span id="blank">Please fill in the red boxes below.</span>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="text-align:-webkit-center">
        <fieldset style="width:50%;padding-bottom:10px" id="company-box">
            <table width="100%">
                <tr>
                    <td>Username : </td>
                    <td><input type="hidden" name="id"><input type="text" class="form-control" name="username" /></td>
                </tr>
                <tr>
                    <td>Old Password : </td>
                    <td><input type="password" name="oldpassword" class="form-control" id="oldpassword" /></td>
                </tr>
                <tr>
                    <td>New Password : </td>
                    <td><input type="password" name="newpassword" class="form-control" id="newpassword" /></td>
                </tr>
                <tr>
                    <td>Confirm New Password : </td>
                    <td><input type="password" name="confirmnewpassword" class="form-control" id="confirmnewpassword" /></td>
                </tr>
               
            </table>
        </fieldset>
        <div style="margin-top:10px"><button class="btn" style="font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);" id="submit">Submit</button></div>
    </div>
    
</div>
</body>
<div class="print_info" id="success_info" style="display:none"><h6>General Settings Updated Successfully</h6></div>

</html>
<?php
}
else
header("Location: index.php");
?>
