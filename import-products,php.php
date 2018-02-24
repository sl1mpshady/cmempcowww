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
mysql_select_db($database_connSIMS, $connSIMS);
$query_rsProduct = "SELECT prodID, prodDesc, prodOHQuantity,prodReorderQty, prodPrice, prodStatus, getCatName(prodCategory) as category FROM product WHERE prodDelete='False'";
$rsProduct = mysql_query($query_rsProduct, $connSIMS) or die(mysql_error());
//$row_rsProduct = mysql_fetch_assoc($rsProduct);
$totalRows_rsProduct = mysql_num_rows($rsProduct);
$query_rsCategory = "SELECT catID, catName FROM category";
$rsCategory = mysql_query($query_rsCategory, $connSIMS) or die(mysql_error());
$row_rsCategory = mysql_fetch_array($rsCategory);
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
<script src="media/js/jquery.highlight.js"></script>
<script src="media/js/dataTables.searchHighlight.min.js"></script>
<link href="media/css/dataTables.searchHighlight.css" rel="stylesheet" />
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>
<span id="addProduct" style="display:none;visibility:0"><?php echo $row_rsAccount['addProduct'];?></span>
<span id="editProduct" style="display:none;visibility:0"><?php echo $row_rsAccount['editProduct'];?></span>
<span id="deleteProduct" style="display:none;visibility:0"><?php echo $row_rsAccount['deleteProduct'];?></span>
<script>
	$(document).ready(function () {
		$('#home').addClass('active');
		$('#home_menu').show();
		$('#product_menu').addClass('selected');
		
		$('#list').dataTable({
			"scrollY":        "300px",
			"scrollCollapse": false,
			"paging":         false,
			//searchHighlight: true,
			"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
				$(nRow).removeClass('active_');
				if (iDisplayIndex == 0) {
					$(nRow).addClass('active_');
				}
			}
		});
		
		if($('#addProduct').html()!='True'){
			$('#add').remove();
		}
		else 
			shortcut.add('ctrl+a',function() {
				window.location.assign('add-product.php');
			});
		if($('#editProduct').html()!='True')
			$('#edit').remove();
		else 
			shortcut.add('ctrl+e',function() {
				$('#edit').click();
			});
		if($('#deleteProduct').html()!='True')
			$('#delete').remove();	
		else 
			shortcut.add('ctrl+d',function() {
				$('#delete').click();
			});
		$('#edit').attr('data-modal-id',"edit-popup");
		$('#print').attr('data-modal-id',"print_popup");
		$('#add').click(function() {
				window.location.assign('add-product.php');
			});
		$(document).on("click","#list tr", function(){
			if ( $(this).hasClass('active_') ) {
				$(this).removeClass('active_');
			}
			else {
				$('#list tr.active_').removeClass('active_');
				$(this).addClass('active_');
			}
		});
		$(document).on("click", function(event){
			if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("a.btn")){
				 $('#list tr.active_').removeClass('active_');
			}
		});
		shortcut.add('ctrl+p',function() {
			$('#print').click();
		});
		
		$('#cat_button').click(function() {
			$.ajax({
			   url: 'fetch/get-product.php?',
			   type: 'get',
			   data: {'catID':$('#category1').val()},
			   dataType: 'json',
			   success: function(s){
				  console.log(s);
				  for(var i = 0; i < s.length-1; i++) {
					  $('#receipt-list').find('tbody').append('<tr><td>'+s[i][0]+'</td><td>'+s[i][1]+'</td><td>'+s[i][2]+'</td><td>'+s[i][4]+'</td></tr>');
				  }
				  $('#totQty').html(s[s.length-1][0]);
				  $('#totCost').html(s[s.length-1][1]);
				  $('#datetime').html(s[s.length-1][2]);
				  $('#accName1').html($('#accname').html());
				  $('#tot-num').html((s.length-1));
				  window.print() ;
			   }
		  });
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
<div style="display:none" id="parent">home</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Product</div>
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
    <div style="border:1px solid lightgray;border-radius:4px">
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list"></span> Product List</div>
        <div style="padding:5px">
        	

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
<script src="media/js/product-popup.js"></script>
</body>
</html>
<?php
mysql_free_result($rsProduct);
}
else
header("Location: index.php");
?>
