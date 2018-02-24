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
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>
<span id="addCategory" style="display:none;visibility:0"><?php echo $row_rsAccount['addCategory'];?></span>
<span id="editCategory" style="display:none;visibility:0"><?php echo $row_rsAccount['editCategory'];?></span>
<span id="deleteCategory" style="display:none;visibility:0"><?php echo $row_rsAccount['deleteCategory'];?></span>
<script>
	$(document).ready(function () {
		$('#home').addClass('active');
		$('#home_menu').show();
		$('#measurements_menu').addClass('selected');
		
		$('#list').dataTable({
			"scrollY":        "300px",
			"scrollCollapse": false,
			"paging":         false,
			searchHighlight: true,
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_processing/category_server_processing.php"
		});
		if($('#addCategory').html()!='True')
			$('#add').remove();
		if($('#editCategory').html()!='True')
			$('#edit').remove();
		if($('#deleteCategory').html()!='True')
			$('#delete').remove();
		$('#edit').attr('data-modal-id',"edit-popup");
		$('#add').attr('data-modal-id',"add-popup");
		$('#add').html('<span class="glyphicon glyphicon-plus-sign" style="color:navy;margin-right:3px"></span>Add Category');
		$(document).on("click","#list tr", function(){
			if ( $(this).hasClass('active_') ) {
				$(this).removeClass('active_');
			}
			else {
				$('#list tr.active_').removeClass('active_');
				$(this).addClass('active_');
			}
			if($('#list .active_ td').eq(0).text() == 'No data available in table')
				$(this).removeClass('active_');
		});
		$(document).on("click", function(event){
			if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("a.btn") && !$(event.target).parent().andSelf().is("#list_filter button") && !$(event.target).parent().andSelf().is(".modal-body table")){
				 $('#list tr.active_').removeClass('active_');
			}
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
	td>label>span {
		margin-right:25px;
		font-weight:normal;
	}
</style>
<body>
<div style="display:none" id="parent">home</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Measurements</div>
    <div id="box" class="bg-success" style="display:<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'block;' : 'none;')?> padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<?php
			if($_GET['success']=='add'){
				echo 'Category has been added successfuly.';
			}
			else if($_GET['success']=='delete'){
				echo 'Category has been deleted successfully.';
			}
			else if($_GET['success']=='edit'){
				echo 'Category has been updates successfully.';
			}
		?>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="border:1px solid lightgray;border-radius:4px">
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list"></span> Category List</div>
        <div style="padding:5px">
        	<style>
				#list td:nth-child(6) {
					text-align:right;	
				}
				th, #list td:nth-child(3),#list td:nth-child(4),#list td:nth-child(5) {
					text-align:center !important;
				}
				#list td:nth-child(1) {
					display:none;
				}
			</style>
        	<table width="100%" id="list">
              	<thead>
                	<th style="display:none !important">CatID</th>
                	<th>Category Name</th>
                    <th width="6%" style="text-align:center">Status</th>
                    <th width="10%">Measurement</th>
                    <th width="10%">Sub Product</th>
                    <th width="10%">Discount</th>
                </thead>
                
            </table>

        </div>
    </div>
    <div id="add-popup" class="modal-box" style="width:50%">
    	<header>
          <h3>Add Category</h3>
        </header>
        <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Please fill all the boxes in red.
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div id="id1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Product ID/Barcode has already exist. Please refer to the product list.
            <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
        	<style>
				#edit .form-control {
					width:78%;
					margin-left:20px;
					font-size:14px !important;
					height:auto !important;
					text-align:left !important;
				}
				#edit td:nth-child(1){
					text-align:right;
					font-weight:bold;
					width:34%
				}
				#edit td:nth-child(2){
					padding:5px;
				}
				#edit .btn {
					font-size: 14px;
  					padding: 0.75em 1.5em;
				}
			</style>
        	<table width="100%" id="edit">
              <tr>
                <td><span class='required'>*</span> Category Name :</td>
                <td><input type="text" class="form-control" id="name" style="text-transform:capitalize"/></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Discount : </td>
                <td><input type="text" class="form-control" id="percent"/></td>
              </tr>
              <tr>
              	<td>Measurement : </td>
                <td><label style="margin-left:40px" class="radio"><span><input type="radio" name="measurement" id="measurement1" value="Whole Number" checked="checked">Whole Number </span><span><input type="radio" name="measurement" id="measurement2" value="Decimal">Decimal</span></label></td>
              </tr>
              <tr>
              	<td>Sub-product : </td>
                <td><label style="margin-left:40px" class="radio"><span><input type="radio" name="subProd" id="subProd1" value="True">True </span><span><input type="radio" name="subProd" id="subProd2" value="False" checked="">False</span></label></td>
              </tr>
              <tr>
                <td>Status :</td>
                <td><input type="checkbox" id="status"  style="margin-left:20px" /> Active</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Submit</button><button id="cancel" class="js-modal-close btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Cancel</button></td>
              </tr>
            </table>
        </div>
    </div>
    <div id="edit-popup" class="modal-box" style="width:50%">
    	<header>
          <h3>Edit Category</h3>
        </header>
        <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Please fill all the boxes in red.
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div id="id1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Category name has already exist. Please refer to the category list.
            <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
        	<style>
				#edit .form-control {
					width:78%;
					margin-left:20px;
					font-size:14px !important;
					height:auto !important;
					text-align:left !important;
				}
				#edit td:nth-child(1){
					text-align:right;
					font-weight:bold;
					width:34%
				}
				#edit td:nth-child(2){
					padding:5px;
				}
				#edit .btn {
					font-size: 14px;
  					padding: 0.75em 1.5em;
				}
			</style>
        	<table width="100%" id="edit">
              <tr>
                <td><span class='required'>*</span> Category Name :</td>
                <td><input type="text" class="form-control" id="name" style="text-transform:capitalize"/><span id="catID" style="display:none"></span></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Discount : </td>
                <td><input type="text" class="form-control" id="percent"/></td>
              </tr>
              <tr>
              	<td>Measurement : </td>
                <td><label style="margin-left:40px" class="radio"><span><input type="radio" name="measurement" id="measurement1" value="Whole Number" checked="checked">Whole Number </span><span><input type="radio" name="measurement" id="measurement2" value="Decimal">Decimal</span></label></td>
              </tr>
              <tr>
              	<td>Sub-product : </td>
                <td><label style="margin-left:40px" class="radio"><span><input type="radio" name="subProd" id="subProd1" value="True">True </span><span><input type="radio" name="subProd" id="subProd2" value="False" checked="">False</span></label></td>
              </tr>
              <tr>
                <td>Status :</td>
                <td><input type="checkbox" id="status"  style="margin-left:20px" /> Active</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><button id="submit-edit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Submit</button><button id="cancel" class="js-modal-close btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Cancel</button></td>
              </tr>
            </table>
        </div>
    </div>
</div>
<div id="print" style="display:none;margin:0px;width:675px;float:none;text-align:center" class="visible-print-block" >
	<div>
        <div id="store-name">Sales & Inventory Management System</div>
        <div id="store-addr">Commercial Center, Mindanao State University, Marawi City</div>
        <div id="store-contact"> +639 1037 27802</div>
    </div>
	<div style="margin-top:15px;font-weight:400">CATEGORY LIST</div>
    <table width="100%" border="0" style="border-bottom:2px solid black;border-top:2px solid black;font-size:13px">
    	<td style="text-align:left">Category Name</td>
        <td style="text-align:center" width="10%">Status</td>
        <td style="text-align:right" width="15%">Stock</td>
        <td style="text-align:right" width="15%">Products</td>
        <td style="text-align: right;" width="15%">Discount</td>
    </table>
    <style>
		#receipt-list td:nth-child(1){
			text-align:left;
		}
		#receipt-list td:nth-child(2){
			width:10%;
			text-align:center;
		}
		#receipt-list td:nth-child(3),#receipt-list td:nth-child(4),#receipt-list td:nth-child(5){
			width:15%;
			text-align:right
		}
		#receipt-list td {
			border-bottom: 1px solid black;
			border-spacing: 0;
			border-collapse: collapse;
		}
		#account td{
			text-align:center;
			text-transform:uppercase;
		}
	</style>
    <table id="receipt-list" width="100%" border="0" style="font-size:13px">
    <tbody>
    </tbody>
    </table>
    <div style="margin-top:15px;font-weight:400;border-bottom:2px solid black">SUMMARY</div>
    <div style="margin:10px;font-size:13px" id="payment2">
    	<table width="100%" border="0">
          <tr>
            <td style="text-align:left">Total Quantity:</td>
            <td id="totQty" style="text-align:left"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
          	<td style="text-align:left">No. of Products:</td>
            <td id="tot-num" style="text-align:left"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
		<table width="100%" border="0" id="account" style="margin-top:20px">
          <tr>
            <td id="custName3" style="text-align:left">&nbsp;Date Printed:<span style="margin-right:5px" id="datetime"></span></td>
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
		var modalBox = $(this).attr('data-modal-id');
		var check=false;
		if(modalBox == 'edit-popup' && $('#list tr').hasClass('active_')){
			$('#catID').html($('#list .active_ td').eq(0).text());
			$.ajax({
				url: 'fetch/get-category.php',
				data: {catID:$('#list .active_ td').eq(0).text()},
				dataType:'json',
				success: function(s){
					console.log(s);
					$('#edit-popup #name').val(s[0]);
					$('#edit-popup #percent').val(s[1]);
					s[2]=='Active' ? $('#edit-popup #status').prop('checked','checked') : '';
					$('#edit-popup input[value="'+s[3]+'"]').prop('checked',true);
					$('#edit-popup input[value="'+s[4]+'"]').prop('checked',true);
				}
			});
			check = true;
		}
		else if(modalBox == 'add-popup'){
			check = true;
			$('#'+modalBox+' input[value="Whole Number"]').prop('checked',true);
			$('#'+modalBox+' input[value="False"]').prop('checked',true);
		}
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			//$(".js-modalbox").fadeIn(500);
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
								data: {catID:id,update:true,delete:true},
								dataType: 'json',
								success: function(s){
									console.log(s);
								}
							});
							//alert($('#edit select').prop('selected', true).val());
							window.location.assign('category.php?success=delete');
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
	$('#percent').priceFormat({
		  prefix: '',
		  thousandsSeparator: '',
		  allowNegative: false
	});
	$('#percent').keypress(function(e) {
		if($(this).val().length > 4)
		  return false;
	});
   	$('#name').keypress(function(e){     
		var str = String.fromCharCode(e.keyCode);
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test(str) && str!=' ') 
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
	$('#submit,#submit-edit').click(function() {
		var check = true;
		if($('#edit-popup').is(':visible'))
			modalBox = 'edit-popup';
		else
			modalBox = 'add-popup';
		$('#'+modalBox+' input[type=text]').each(function() {
			if(this.value == ''){
				//alert(this.value);
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
			$('#'+modalBox+' .modal-body').css('padding-top','0');
			$('#'+modalBox+' #id1').hide();
			$('#'+modalBox+' #box').show();
			//alert(false);
		}
		else{
			$.ajax({
				url: 'check/check.php',
				dataType: 'json',
				data: {catName:$.trim($('#'+modalBox+' #name').val()),catID:$('#catID').html()},
				success: function(s){
					console.log(s);
					if(s[0]==true){
						$('.modal-body').css('padding-top','0');
						$('#'+modalBox+' #box').hide();
						$('#'+modalBox+' #id1').show();
						$('#id').parent().addClass('has-error');
					}
					else {
						$.ajax({
							url: 'save/save.php',
							data: {catID:modalBox=='edit-popup' ? $.trim($('#catID').html()) : '',catName:$('#'+modalBox+' #name').val(), catDiscount:$('#'+modalBox+' #percent').val(), catStatus:$('#'+modalBox+' #status').prop('checked'),catMeasurement:$('#'+modalBox+' input[name=measurement]:checked').val(),catSubProd:$('#'+modalBox+' input[name=subProd]:checked').val(),type:modalBox=='edit-popup' ? 'UPDATE' : 'INSERT'},
							dataType: 'json',
							success: function(s){
								console.log(s);
							}
						});
						var x = modalBox=='edit-popup' ? 'edit' : 'add';
						window.location.assign('category.php?success='+x);
						
					}
				}
			});
		}
	});
	$('#edit-popup .close').click(function() {
		$('.modal-body').css('padding-top','2em');
	});
	$('.close').click(function() {
		$('#'+modalBox+' .modal-body').css('padding-top','2em');	
	});
	shortcut.add('Ctrl+p',function() {
		$('#print').click();
		});
	$('#print').click(function() {
		$.ajax({
		   url: 'fetch/get-category.php?',
		   type: 'get',
		   dataType: 'json',
		   success: function(s){
			  console.log(s);
			  for(var i = 0; i < s.length-1; i++) {
				  $('#receipt-list').find('tbody').append('<tr><td>'+s[i][1]+'</td><td>'+s[i][2]+'</td><td>'+s[i][3]+'</td><td>'+s[i][4]+'</td><td>'+s[i][5]+'</td></tr>');
			  }
			  $('#totQty').html(s[s.length-1][0]);
			  $('#datetime').html(s[s.length-1][2]);
			  $('#accName1').html($('#accname').html());
			  $('#tot-num').html(s[s.length-1][1]);
			  window.print() ;
		   }
	  });	
	});
});
</script>
<script src="media/js/product-popup.js"></script>
</body>
</html>
<?php
}else
header("Location: index.php");
?>
