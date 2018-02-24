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

mysql_select_db($database_connSIMS, $connSIMS);
$query_rsCategory = "SELECT catID, catName FROM category WHERE category.catStatus ='Active'";
$rsCategory = mysql_query($query_rsCategory, $connSIMS) or die(mysql_error());
$row_rsCategory = mysql_fetch_assoc($rsCategory);
$totalRows_rsCategory = mysql_num_rows($rsCategory);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>StoreSys</title>
<?php include_once('menu.php'); ?>
<script src="media/js/jquery-ui.js"></script>
<link href="media/css/jquery-ui.css" rel="stylesheet" />
<title><?php if(isset($_GET['name'])){echo 'asasas';}?></title>

<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
</head>
<script>
	$(document).ready(function () {
		$('#modules').addClass('active');
		$('#modules_menu').show();
		$('#add-product').addClass('selected');
	});
</script>
<body>
<div style="display:none" id="parent">modules</div>
<div style="padding:25px">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;margin-bottom:"<?php echo isset($_GET['success']) ? '0px' : '25px'?>"" id="header2">Product List</div>
    <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Please fill all the boxes in red.
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div id="id1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Product ID/Barcode has already exist. Please refer to the product list.
  		<button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="border:1px solid lightgray;border-radius:4px">
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-plus-sign"></span> Add Product</div>
        <div style="padding:20px">
        	<style>
				.form-control {
					width:50%;
					margin-left:20px;
				}
				td:nth-child(1){
					text-align:right;
					font-weight:bold;
					width:40%
				}
				td:nth-child(2){
					padding:5px;
				}
        .form-control1 {
					padding: 3px 7px;
					font-size: 14px;
					line-height: 1.42857143;
					color: #555;
					background-color: #fff;
					background-image: none;
					border: 1px solid #ccc;
					border-radius: 4px;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
					-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
					transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
				}
				#list_filter {
					display:none;
				}
			</style>
        	<table width="100%">
              <tr>
                <td><span class='required'>*</span> Product Description :</td>
                <td><input type="text" class="form-control" id="name" style="text-transform:capitalize"/></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Category :</td>
                <td><select id="category" class="form-control" >
                	<option value="0">Select Category</option>
					<?php 
						do {
							echo '<option value="'.$row_rsCategory['catID'].'">'.$row_rsCategory['catName'].'</option>';
						}while($row_rsCategory = mysql_fetch_array($rsCategory));
					?>
                    </select>
                </td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Product ID/Barcode :</td>
                <td><input type="text" class="form-control" id="id" /></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Stock(#UNITS) :</td>
                <td><input type="text" class="form-control" id="stock" /></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Price : </td>
                <td><input type="text" class="form-control" id="price" /></td>
              </tr>
              <tr>
                <td>Status :</td>
                <td><input type="checkbox" id="status"  style="margin-left:20px"/> Active</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Submit</button></td>
              </tr>
            </table>

        </div>
    </div>
</div>
<script>
</script>
</body>
</html>
<?php

?>
