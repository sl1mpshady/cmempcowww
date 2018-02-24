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
<script src="media/js/jquery-ui.js"></script>
<link href="media/css/jquery-ui.css" rel="stylesheet" />
<title><?php if(isset($_GET['name'])){echo 'asasas';}?></title>
<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<script src="media/js/jquery.highlight.js"></script>
<script>
	$(document).ready(function(e) {
        var list = $('#list').dataTable({
			"scrollY":        "280px",
			"scrollCollapse": false,
			"paging":         false,
			//searchHighlight: true,
			"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
				$(nRow).removeClass('active_');
				if (iDisplayIndex == 0) {
					$(nRow).addClass('active_');
					$('.btn[data-id="rowOpt"]').attr('disabled',false);
					$('#-clear').attr('disabled',false);
					($('#category1').val() > 0) ? $('#save').attr('disabled',false) : $('#save').attr('disabled',true);
				}
			}
		});
    });
</script>
<script src="media/js/dataTables.searchHighlight.min.js"></script>
<link href="media/css/dataTables.searchHighlight.css" rel="stylesheet" />
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/import_product.js"></script>
</head>
<span id="addProduct" style="display:none;visibility:0"><?php echo $row_rsAccount['addProduct'];?></span>
<span id="editProduct" style="display:none;visibility:0"><?php echo $row_rsAccount['editProduct'];?></span>
<span id="deleteProduct" style="display:none;visibility:0"><?php echo $row_rsAccount['deleteProduct'];?></span>
<script>
	$(document).ready(function () {
		$('#modules').addClass('active');
		$('#modules_menu').show();
		$('#import-products').addClass('selected');
		$('#buttons .btn').attr('disabled',true);	
		shortcut.add('ctrl+p',function() {
			$('#print').click();
		});
	});	
</script>
<style>
	
	.dataTables_scrollHead {
		background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);

	}
	.dataTables_scroll {
		  box-shadow: 0px 0px 2px gray;
	}
	.btn {
		  background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
		  padding: 2px 5px;
		  margin-left:5px;
		  font-size:12px;
	}
	.form-control {
		height:28px;
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
		height: auto;
	}
	#list_filter,.dataTables_info,.dataTables_empty {
		display:none;
	}
	#buttons .btn {
		float: left;
		font-size: 16px;
		padding: 3px 12px 3px 12px;
		margin-right: 3px;
		width: 109px;
		width: auto;
		font-weight: bold;
	}
	#key {
		width: 100%;
		float: inherit;
		font-size: 12px;
	}
	.progress {
		height: 20px;
		margin-bottom: 20px;
		overflow: hidden;
		background-color: #f7f7f7;
		background-image: -moz-linear-gradient(top, #f5f5f5, #f9f9f9);
		background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#f5f5f5), to(#f9f9f9));
		background-image: -webkit-linear-gradient(top, #f5f5f5, #f9f9f9);
		background-image: -o-linear-gradient(top, #f5f5f5, #f9f9f9);
		background-image: linear-gradient(to bottom, #f5f5f5, #f9f9f9);
		background-repeat: repeat-x;
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff5f5f5', endColorstr='#fff9f9f9', GradientType=0);
		-webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
		-moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
		box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
	}
	.progress.active .bar {
		-webkit-animation: progress-bar-stripes 2s linear infinite;
		-moz-animation: progress-bar-stripes 2s linear infinite;
		-ms-animation: progress-bar-stripes 2s linear infinite;
		-o-animation: progress-bar-stripes 2s linear infinite;
		animation: progress-bar-stripes 2s linear infinite;
	}
	.progress-striped .bar {
		background-color: #149bdf !important;
		background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent)) !important;
		background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
		background-image: -moz-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
		background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
		background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
		-webkit-background-size: 40px 40px;
		-moz-background-size: 40px 40px;
		-o-background-size: 40px 40px;
		background-size: 40px 40px;
	}
	.progress .bar {
		float: left;
		width: 0;
		height: 100%;
		font-size: 12px;
		color: #ffffff;
		text-align: center;
		text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
		background-color: #0e90d2;
		background-image: -moz-linear-gradient(top, #149bdf, #0480be);
		background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#149bdf), to(#0480be));
		background-image: -webkit-linear-gradient(top, #149bdf, #0480be);
		background-image: -o-linear-gradient(top, #149bdf, #0480be);
		background-image: linear-gradient(to bottom, #149bdf, #0480be);
		background-repeat: repeat-x;
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff149bdf', endColorstr='#ff0480be', GradientType=0);
		-webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
		-moz-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
		box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		-webkit-transition: width 0.6s ease;
		-moz-transition: width 0.6s ease;
		-o-transition: width 0.6s ease;
		transition: width 0.6s ease;
	}
	.modal-body .btn {
		background-image: linear-gradient(to bottom, #ffffff, lightgray 213%);
		border: 1px solid lightgray;
		font-weight: bold;
		font-size: 14px;
		padding: 0.75em 1.5em !important;
	}
	@media print {
		#payment2 td:nth-child(1), td:nth-child(3){
		width: 130px;
  		text-align: right;
		}
		#payment2 td:nth-child(2),td:nth-child(4){
			text-align:right;
		}
	}
</style>
<body>
<div style="display:none" id="parent">modules</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Import Products</div>
    <div id="box" class="bg-success" style="display:<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'block;' : 'none;')?> padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<?php
			if($_GET['success']=='add'){
				echo 'Product has been added successfuly.';
			}
			else if($_GET['success']=='delete'){
				echo 'Product has been deleted successfully.';
			}
			else if($_GET['success']=='edit'){
				echo 'Product has been updated successfully.';
			}
		?>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div>
    </div>
    <div>
    	<table>
        	<tr>
            	<td width="20%">Category:</td>
                <td>Choose an Excel file to import:</td>
            </tr>
            <tr>
            	<td>
                	<select id="category1" class="form-control" style="height:34px;width:95%">
                    	<option value="0">Select Category</option>
					<?php 
						do {
							echo '<option data-measurement="'.$row_rsCategory['meas'].'" value="'.$row_rsCategory['catID'].'">'.$row_rsCategory['catName'].'</option>';
						}while($row_rsCategory = mysql_fetch_array($rsCategory));
					?>
                    </select>
                    <input type="hidden" style="display:none;opacity:0" id="measurement" />
                    <input type="hidden" style="display:none;opacity:0" id="subProd" />
                </td>
                <td>
                	<input type="text" class="form-control" id="file-name" style="width:96%;float:left;height:auto">
                    <span class="im_upload btn" style="overflow:hidden;cursor:pointer">
                    	<form id="fileForm" enctype="multipart/form-data">
                    		<input type="file" name="file" id="file" style="overflow:hidden;float: left;height: 27px;opacity:0;cursor:pointer" class="nohistory image-placeholder image-placeholder-show">
                        </form>
                    </span>
                </td>
            </tr>
        </table>
        <br />
        <style>
			#list td:nth-child(3){
				text-align:center;
			}
			#list td:nth-child(4),#list td:nth-child(5){
				text-align:right;
			}
		</style>
        <table id="list" width="100%">
        	<thead>
            	<th style="text-align:center" width="16%">Product ID</th>
                <th style="text-align:center">Item Description</th>
                <th style="text-align:center" width="8%">Status</th>
                <th style="text-align:center" width="13%">Reorder Qty</th>
                <th style="text-align:center" width="15%">Unit Price</th>
            </thead>
        </table>
        <div id="buttons" style="width:72%;float:left;margin-top:1%">
            <a class="btn" id="-remove" data-id="rowOpt">Remove<span id="key">F1</span></a>
            <a class="btn" data-modal-id="prodID_popup" data-id="rowOpt">Product ID<span id="key">F2</span></a>
            <a class="btn" data-modal-id="desc_popup" data-id="rowOpt">Description<span id="key">F3</span></a>
            <a class="btn" data-modal-id="status_popup" data-id="rowOpt">Status<span id="key">F4</span></a>
            <a class="btn" data-modal-id="reorder_popup" data-id="rowOpt">Reorder Qty<span id="key">F5</span></a>
            <a class="btn" data-modal-id="price_popup" data-id="rowOpt">Unit Price<span id="key">F6</span></a>
            <a class="btn" id="save">Save<span id="key">F7</span></a>
            <a class="btn" id="-clear">Clear<span id="key">F8</span></a>
        </div>
        <div style="float:right;width:28%;height:54px;margin-top:10px;background-color:#222;padding-left:10px;padding-right:5px;padding-top:5px;padding-bottom:5px">
    	
        
        <div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Total Product</span>
            <span style="float:right;font-size: 22px !important;font-family: -webkit-body;color: #00cc00;" id="sum-qty">0</span>
        </div>
    </div>
    </div>
    <div id="prodID_popup" class="modal-box" style="width:25%">
    	<header>
          <h3>Edit Product ID</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
           <div style="text-align: left;text-shadow: 1px 0px black;" id="title">Enter Product ID:</div>
           <div id="tend-div">
           		<input id="prodID" type="text" class="form-control" style="font-family:-webkit-body;font-size: 60px !important;padding:0;color:black;"/>
           </div>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="prodID_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
                <a href="#" id="prodIDcancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
            </div>
        </div>
    </div>
    <div id="desc_popup" class="modal-box" style="width:25%">
    	<header>
          <h3>Edit Description</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
           <div style="text-align: left;text-shadow: 1px 0px black;" id="title">Enter Description:</div>
           <div id="tend-div">
           		<input id="desc" type="text" class="form-control" style="font-family:-webkit-body;font-size: 60px !important;padding:0;color:black;"/>
           </div>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="desc_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
                <a href="#" id="desccancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
            </div>
        </div>
    </div>
    <div id="status_popup" class="modal-box" style="width:25%">
    	<header>
          <h3>Edit Status</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
           <div style="text-align: left;text-shadow: 1px 0px black;" id="title">Select Status:</div>
           <div id="tend-div">
           		<select id="status" class="form-control" style="font-family: -webkit-body; padding: 0px; color: black; font-size: 25px !important;height:100%">
                	<option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
           </div>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="status_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
                <a href="#" id="statuscancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
            </div>
        </div>
    </div>
    <div id="reorder_popup" class="modal-box" style="width:25%">
    	<header>
          <h3>Edit Reorder  Quantity</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
           <div style="text-align: left;text-shadow: 1px 0px black;" id="title">Enter Reorder Quantity:</div>
           <div id="tend-div">
           		<input id="reorder" type="text" class="form-control" style="font-family:-webkit-body;font-size: 60px !important;padding:0;color:black;"/>
           </div>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="reorder_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
                <a href="#" id="reordercancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
            </div>
        </div>
    </div>
    <div id="price_popup" class="modal-box" style="width:25%">
    	<header>
          <h3>Edit Price</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
           <div style="text-align: left;text-shadow: 1px 0px black;" id="title">Enter Price:</div>
           <div id="tend-div">
           		<input id="price" type="text" class="form-control" style="font-family:-webkit-body;font-size: 60px !important;padding:0;color:black;"/>
           </div>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="price_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
                <a href="#" id="pricecancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
            </div>
        </div>
    </div>
    
</div>
<div id="print" style="display:none;margin:0px;width:100%;font-size:20px;float:none;text-align:center" class="visible-print-block" >
	<div>
        <div id="store-name">Sales & Inventory Management System</div>
        <div id="store-addr">Commercial Center, Mindanao State University, Marawi City</div>
        <div id="store-contact"> +639 1037 27802</div>
    </div>
	<div style="margin-top:15px;font-weight:400">PRODUCT LIST</div>
    <table width="100%" border="0" style="border-bottom:2px solid black;border-top:2px solid black;">
    	<td style="text-align:left" width="21%">Category</td>
        <td style="text-align:left" width="20%">Product ID</td>
        <td style="text-align:center">Item Description</td>
        <td style="text-align:right" width="13%">Unit Price</td>
    </table>
    <style>
		#receipt-list td:nth-child(1),#receipt-list td:nth-child(2),#receipt-list td:nth-child(3){
			text-align:left;
		}
		#receipt-list td:nth-child(1){
			width:21%;
		}
		#receipt-list td:nth-child(2){
			width:20%;
		}
		#receipt-list td:nth-child(4){
			width:13%;
			text-align:right
		}#receipt-list td:nth-child(5),#receipt-list td:nth-child(6),#receipt-list td:nth-child(7){
			width:13%;
			text-align:right
		}
		#receipt-list td {
			border-bottom: 1px solid black;
			border-spacing: 0;
			border-collapse: collapse;
		}
		#payment2 td, #product td:nth-child(5), #product td:nth-child(4){
			text-align:right
		}
		#account td{
			text-align:center;
			text-transform:uppercase;
		}
	</style>
    <table id="receipt-list" width="100%" border="0">
    <tbody>
    </tbody>
    </table>
    <div style="margin-top:15px;font-weight:400;border-bottom:2px solid black">SUMMARY</div>
    <div style="margin:10px" id="payment2">
    	<table width="100%" border="0">
          <tr>
            <td style="width:15%">Total Quantity:</td>
            <td id="totQty"></td>
            <td>&nbsp;</td>
            <td>Total Price:</td>
            <td id="totCost"></td>
          </tr>
          <tr>
          	<td>No. of Products:</td>
            <td id="tot-num"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
		<table width="100%" border="0" id="account" style="margin-top:20px">
          <tr>
            <td id="custName3">&nbsp;Date Printed:<span style="margin-right:5px" id="datetime"></span></td>
            <td width="20%">&nbsp;</td>
            <td width="40%" style="border-bottom:1px solid black" id="accName1"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>VERIFIED BY</td>
          </tr>
        </table>

    </div>
</div>

<script>
	$(function(){
	var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('button[data-modal-id]').click(function(e) {
		e.preventDefault();
		var check = false;
		if($('#list tr').hasClass('active_')){
			//alert($('#list .active_ td').eq(0).text());
			$('#oldID').html($('#list .active_ td').eq(0).text());
			$.ajax({
				url: 'fetch/get-product.php',
				data: {prodID:$('#list .active_ td').eq(0).text(),type:true},
				dataType:'json',
				success: function(s){
					console.log(s);
					$('#name').val(s[0]);
					$('#category option[value="'+s[1]+'"]').attr('selected','selected');
					$('#id').val(s[2]);
					$('#stock').val(s[3]);
					$('#price').val(s[4]);
					$('#reorder').val(s[6]);
					s[5]=='Active' ? $('#status').prop('checked','checked') : '';
				}
			});
			check = true;
		}
		
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			//$(".js-modalbox").fadeIn(500);
			var modalBox = $(this).attr('data-modal-id');
			$('#'+modalBox).fadeIn($(this).data());
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 150;
			$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
			$('#'+modalBox).css("top", view_top);
		}
		//$(this+' input').focus();
	});  
	
  
  
$(".js-modal-close, .modal-overlay").click(function() {
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
});
 
 
});
// JavaScript Document
</script>
<script>
$(document).ready(function() {
	$('.print_info').click(function(e) {
		if(this.id!="save_loading" && this.id!="import_loading")
        	$(this).fadeOut(1500);
    });
	$('#delete').click(function() {
		if($('#list tr').hasClass('active_')){
			var id = $('#list .active_ td').eq(0).text();
			bootbox.dialog({
				message: "Are you sure you want to delete "+$('#list .active_ td').eq(1).text()+".",
				buttons: {
					main: {
						label: 'Ok',
						className: "btn",
						callback: function() {
							$.ajax({
								url: 'save/save.php',
								data: {prodID:id,update:true,delete:true,loc:'product'},
								dataType: 'json',
								success: function(s){
									console.log(s);
								}
							});
							//alert($('#edit select').prop('selected', true).val());
							window.location.assign('products.php?success=delete');
						}
					},
					cancel: {
						label: 'Cancel',
						className: "btn"
					}
				}
			});	
		}
	});
	$('#stock').keypress(function(e) {
	  if ($.isNumeric(String.fromCharCode(e.keyCode))){
		  if(new Number($(this).val() + String.fromCharCode(e.keyCode)) <= 0)
			  return false;
		  else
			  return true;
	  }
	  return false;
  });
  $('#price').autoNumeric('init',{'vMin':0});
  $('#stock').keyup(function() {
	  $(this).val(function(index, value) {
		  return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		});
   });
   $('#reorder').autoNumeric('init',{'vMin':0,'mDec':0});
   $('#name').keypress(function(e){     
		var str = String.fromCharCode(e.keyCode);
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test(str) && str!=' ') 
		  return false;
		else
			return true;
	});
	$("#id").keypress(function(e){     
		var str = String.fromCharCode(e.keyCode);
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test(str)) 
		  return false;
		else
			return true;
	});
	$("#name").keyup(function() {
	  str = $(this).val();
	  force = false;
	  str=force ? str.toLowerCase() : str;  
	  $(this).val(function(index, value) {
		  return str.replace(/(\b)([a-zA-Z])/g,function(firstLetter){return   firstLetter.toUpperCase();});
		});
	});
	$('#submit').click(function() {
		var check = true;
		$('#edit input[type=text],#edit select').each(function() {
			if(this.value == '' || (this.type=='select-one' && this.value =='0')){
				check = false;
				$(this).parent().removeClass('has-success');
				$(this).parent().addClass('has-error');
			}
			else{
				$(this).parent().removeClass('has-error');
				$(this).parent().addClass('has-success');
			}
		});
		if(!check){
			$('.modal-body').css('padding-top','0');
			$('#id1').hide();
			$('#box1').show();
		}
		else{
			$('.close').click();
			if($('#oldID').html()!=$('#id').val())
				$.ajax({
					url: 'check/check.php',
					dataType: 'json',
					data: {prodID:$.trim($('#id').val())},
					success: function(s){
						console.log(s);
						if(s[0]=='true'){
							$('.modal-body').css('padding-top','0');
							$('#box1').hide();
							$('#id1').show();
							$('#id').parent().addClass('has-error');
						}
						else {
							$.ajax({
								url: 'save/save.php',
								data: {prodID:$.trim($('#id').val()),prodCategory:$('#category').val(),prodName:$('#name').val(),prodStock:$('#stock').val().replace(/,/g, ""),prodPrice:$('#price').val().replace(/,/g, ""),prodStatus:$('input[type=checkbox]').prop('checked'),update:true,oldID:$('#oldID').html(),prodReorderQty:$('#reorder').val().replace(/,/g, "")},
								dataType: 'json',
								success: function(s){
									console.log(s);
								}
							});
							window.location.assign('products.php?success=edit');
							
						}
					}
				});
			else{
				$.ajax({
					url: 'save/save.php',
					data: {prodID:$.trim($('#id').val()),prodCategory:$('#edit select').val(),prodName:$('#name').val(),prodStock:$('#stock').val().replace(/,/g, ""),prodPrice:$('#price').val().replace(/,/g, ""),prodStatus:$('input[type=checkbox]').prop('checked'),update:true,oldID:$.trim($('#id').val()),prodReorderQty:$('#reorder').val().replace(/,/g, "")},
					dataType: 'json',
					success: function(s){
						console.log(s);
					}
				});
				//alert($('#edit select').prop('selected', true).val());
				window.location.assign('products.php?success=edit');
			}
		}
	});
	$('#edit-popup .close').click(function() {
		$('.modal-body').css('padding-top','2em');
	});
	
});
</script>
<div class="print_info" id="import_error" style="display:none"><h6>Warning! File Error</h6><p>Error <strong><span id="rowcol"></span></strong>.<br /><span id="suggestion"></span></p></div>
<div class="print_info" id="import_loading" style="display:none">
	<h6>Loading Data<br />...</h6>
	<p>
    	<div class="progress progress-striped active">
          <div class="bar" style="width: 0%">0%/div>
        </div>
    </p>
</div>
<div class="print_info" id="save_loading" style="display:none">
	<h6>Saving Data<br />...</h6>
	<p>
    	<div>Please dont close this window.</div>
    	<div class="progress progress-striped active">
          <div class="bar" style="width: 0%">0%/div>
        </div>
    </p>
</div>
</body>
</html>
<?php
mysql_free_result($rsProduct);
}
else
header("Location: index.php");
?>
