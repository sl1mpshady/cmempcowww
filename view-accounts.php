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
<title></title>

<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/sales-view.dataTable"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>
<span id="addAccount" style="display:none;visibility:0"><?php echo $row_rsAccount['addAccount'];?></span>
<span id="editAccount" style="display:none;visibility:0"><?php echo $row_rsAccount['editAccount'];?></span>
<span id="deleteAccount" style="display:none;visibility:0"><?php echo $row_rsAccount['deleteAccount'];?></span>
<script>
	$(document).ready(function () {
		//$('.print_info').fadeIn(1000).fadeOut(2500);
		$('#admin').addClass('active');
		$('#admin_menu').show();
		$('#view-accounts').addClass('selected');
		$(document).ajaxStart(function(){
			$(this).css('cursor','wait');
		});
		$(document).ajaxSuccess(function(){
			//$(this).css('cursor','auto');
		});
		$('#list').dataTable({
			"scrollY":        "250px",
			"scrollCollapse": false,
			"paging":         false,
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_processing/account_server_processing.php",
			"createdRow": function ( row, data, index ) {
				//$('td', row).eq(0).html('<a href="view-purchase_order.php?i='+data[0]+'">'+data[0]+'</a>');
			}
		});
		$('#tools').append('<button class="btn" id="add"><span class="glyphicon glyphicon-plus-sign" style="color:navy;margin-right:3px"></span>Add Account</button><button class="btn" id="edit" data-modal-id="edit-popup"><span class="glyphicon glyphicon-pencil" style="color:navy;margin-right:3px"></span>Edit</button><button class="btn" id="delete"><span class="glyphicon glyphicon-trash" style="color:red;margin-right:3px"></span>Delete</button><button class="btn" id="print"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button>');
		if($('#addAccount').html()!='True')
			$('#add').remove();
		if($('#editAccount').html()!='True')
			$('#edit').remove();
		if($('#deleteAccount').html()!='True')
			$('#delete').remove();	
		if($('#addAccount').html()!='True' && $('#editAccount').html()!='True' && $('#deleteAccount').html()!='True')
			window.location.assign('home.php');
		$('#add').click(function() {
			window.location.assign('add-account.php');
			});
		shortcut.add('ctrl+p',function() {
			$('#print').click();
			});
		$('#print').click(function() {
			$.ajax({
				 url: 'fetch/get-account.php?',
				 type: 'get',
				 data: {'date':true},
				 dataType: 'json',
				 success: function(s){
					console.log(s);
					for(var i = 0; i < s.length-1; i++) {
						$('#receipt-list').find('tbody').append('<tr><td>'+new String(new Number(i)+1).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td>'+s[i][0]+'</td><td>'+s[i][1]+'</td><td>'+s[i][2]+'</td><td>'+s[i][3]+'</td></tr>');
					}
					$('#totAcc').html(s[s.length-1][0]);
					$('#datetime').html(s[s.length-1][1]);
					$('#accName1').html($('#accname').html());
					window.print() ;
				 }
			});
			});
		$('#edit').attr('data-modal-id',"edit_popup");
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
		
	});	
</script>
<style>
	#list a {
	  color: #337ab7 !important;
	}
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
	.modal-body input {
	  text-align: center;
	  font-size: 31px !important;
	  height: auto;
	  border:0;
	}
	.modal-body input:focus {
	  border:none !important;
	  box-shadow:none !important;
	  background-color:none !important;
	  border-color:0 !important;
	}
	.modal-body .form-control {
	  overflow: hidden !important;
	  height: inherit !important;
	}
	.modal-body input[type=date]{
	  margin-left: -20px;
	  padding-left: 4px;
	  height: 75px;
	  margin-top: -15px;
	  width: 326px;
	}
	.modal-body div .form-control {
	  height:60px !important;
	}
	#print_popup .btn {
	  font-size: 14px;
	  padding: 0.75em 1.5em;
	  /* margin-left: 20px; */
	  font-weight: bold;
	}
	@media print {
		#payment2 td:nth-child(1){
		width: 130px;
  		text-align: right;
		}
		#payment2 td:nth-child(2){
			text-align:right;
		}
		
		.modal-overlay {
			display:none;
		}
	}
</style>
<body>
<div style="display:none" id="parent">modules</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Accounts</div>
    <div id="box" class="bg-success" style="display:<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'block;' : 'none;')?> padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<?php
			if($_GET['success']=='add'){
				echo 'Account has been added successfuly.';
			}
			else if($_GET['success']=='delete'){
				echo 'Account has been deleted successfully.';
			}
			else if($_GET['success']=='edit'){
				echo 'Account has been updated successfully.';
			}
		?>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="border:1px solid lightgray;border-radius:4px">
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list"></span> Account Lists</div>
        <div style="padding:10px">
        	<style>
				#list td:nth-child(2),#list td:nth-child(5),#list td:nth-child(6) {
					text-align:left;	
				}
				
				#list td:nth-child(4) {
					text-align:center;
				}
			</style>
        	<table width="100%" id="list">
              	<thead>
                	<th width="1%">#</th>
                    <th width="15%">Username</th>
                    <th >Account Name</th>
                    <th width="14%">Registered</th>
                    <th >Added By</th>
                </thead>
                
            </table>
        </div>
    </div>
    <div id="edit_popup" class="modal-box" style="width:100%">
    	<header>
        <h3>Edit Account</h3>
        </header>
        <div id="box2" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Please fill all the boxes in red.
            <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div id="id2" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Username already exist. Please user other username.
            <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div id="pass2" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Password did not match.
            <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div id="name2" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Account name already exist. Please user other name.
            <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" style="text-align:center;padding:15px;">
        	<div style="padding:20px;height:350px">
				<style>
					.modal-box #list td:nth-child(4), .modal-box  #list td:nth-child(5),.modal-box #list td:nth-child(6) {
						text-align:right;	
					}
					.modal-box th,.modal-box  #list td:nth-child(3) {
						text-align:center !important;
					}
					.modal-box .form-control {
						width:90%;
						margin-left:20px;
					}
					.modal-box .input td:nth-child(1){
						text-align:left;
						font-weight:bold;
						width:32%
					}
					.input input:focus {
						border: 1px solid #ccc !important;
  						text-align: left !important;
					}
					.modal-box td:nth-child(2),.modal-box td:nth-child(3),.modal-box td:nth-child(4){
						padding:5px;
					}
					.modal-box .head-title1 {
						background-color: rgb(150, 150, 150);
						margin-left: -20px;
						font-family: calibri;
						width: 35%;
						padding-left: 15px;
						font-weight: bold;
						color: white;
						box-shadow: 2px 2px 5px 1px gray;
						text-align: center;
						margin-bottom:15px;
					}
					 .modal-box  .has-error .form-control {
					  border-color: #a94442;
					  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					  box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					  }
					 .modal-box .form-control .succ{
						border-color: #3c763d;
						-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
						box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					  }
					  .modal-box .btn {
							background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
							padding: 2px 5px;
							margin-left:5px;
							font-size:12px;
					  }
					  .modal-box .form-control {
						  height:28px;
					  }
					  fieldset {
						margin-top: 1em !important;
						border-radius: 4px 4px 0 0;
						-moz-border-radius: 4px 4px 0 0;
						-webkit-border-radius: 4px 4px 0 0;
						 border: 1px solid #ccc;
						padding: 1.5em;
						background: white;
						text-shadow: 1px 1px 2px #fff inset;
						-moz-box-shadow: 1px 1px 2px #fff inset;
						-webkit-box-shadow: 1px 1px 2px #fff inset;
						box-shadow: 1px 1px 2px #fff inset;
						margin-top: -1em !important;
						
					  }
					  fieldset legend {
						font-weight: bold;
						color: #444;
						padding: 0px 10px;
						border-radius: 2px;
						-moz-border-radius: 2px;
						-webkit-border-radius: 2px;
						border: 1px solid #aaa;
						background-color: #fff;
						-moz-box-shadow: 3px 3px 15px #bbb;
						-webkit-box-shadow: 3px 3px 15px #bbb;
						box-shadow: 3px 3px 15px #bbb;
						max-width: 100%;
						width: auto;
						font-size:90%;
						margin-bottom:0px !important;
						text-align: left;
					  }
					  fieldset div {
						  font-size: 11px;
					  }
					  fieldset div input {
						  width: 12px;
						vertical-align: sub;
						margin-right: 3px;
					  }
					  fieldset fieldset {
						  float:left;
						  margin:19px;
					  }
					 
				</style>
				<div style="width:45%;float:left">		
				  <div class="head-title1">Account Information</div>
				  <table width="100%" border="0" class="input">
				  <tr>
					<td width="50%"><span class='required'>*</span> Username :</td>
					<td><input type="text" id="accUsername" class="form-control" style="height:28px !important;font-size:14px !important;border: 1px solid #ccc;text-align: left;cursor:auto" disabled="disabled"  /></td>
				  </tr>
				  <tr>
					<td><span class='required'>*</span> Account Name :</td>
					<td><input type="text" id="accName" class="form-control" style="height:28px !important;font-size:14px !important;border: 1px solid #ccc;text-align: left;" /></td>
				  </tr>
                  <tr>
                  	<td colspan="2"><div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 47%, gainsboro 229%);border-bottom: 1px solid lightgray;font-weight: 100;font-size: 13px;padding: 15px;border-top: 1px solid rgb(250, 250, 250);width: 96%;">Leave <strong>password</strong> blank if you dont want to update password.</div></td>
                  </tr>
                  <tr>
					<td> Current Password :</td>
					<td><input type="text" id="password" class="form-control" style="height:28px !important;font-size:14px !important;border: 1px solid #ccc;text-align: left;cursor:auto" disabled="disabled"/></td>
				  </tr>
				  <tr>
					<td> Password :</td>
					<td><input type="password" id="password1" class="form-control" style="height:28px !important;font-size:14px !important;border: 1px solid #ccc;text-align: left;"  /></td>
				  </tr>
				  <tr>
					 <td> Confirm Password :</td>
					<td><input type="password" id="password2" class="form-control" style="height:28px !important;font-size:14px !important;border: 1px solid #ccc;text-align: left;"  /></td>
				  </tr>
				  <tr>
					 <td>&nbsp;</td>
					<td><button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);font-size: 14px;padding: 12px 20px;">Submit</button><button id="cancel" class="btn js-modal-close" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);font-size: 14px;padding: 12px 20px;">Cancel</button></td>
				  </tr>
				  </table>
				</div>
				<div style="width:48%;float:right">
				  <div class="head-title1" style="margin-bottom:10px">Account Previligers</div>
				  <fieldset style="  margin-top: 11px !important;">
					  <legend>Global privileges <input type="checkbox" id="checkall" class="checkall_box" title="Check All"> <label for="checkall">Check All</label> </legend>
					  <fieldset style="padding:11px">
						  <legend>Product</legend>
						  <div><input type="checkbox" id="product-add" data-id="check" /><label for="product-add">ADD</label></div>
						  <div><input type="checkbox" id="product-edit" data-id="check" /><label for="product-edit">EDIT</label></div>
						  <div><input type="checkbox" id="product-delete" data-id="check" /><label for="product-delete">DELETE</label></div>
					  </fieldset>
					  <fieldset style="padding:11px">
						  <legend>Purchase</legend>
						  <div><input type="checkbox" id="purchase-add" data-id="check" /><label for="purchase-add">ADD</label></div>
						  <div><input type="checkbox" id="purchase-edit" data-id="check" /><label for="purchase-edit">EDIT</label></div>
						  <div><input type="checkbox" id="purchase-delete" data-id="check" /><label for="purchase-delete">DELETE</label></div>
					  </fieldset>
					  <fieldset style="padding:11px">
						  <legend>Customer</legend>
						  <div><input type="checkbox" id="customer-add" data-id="check" /><label for="customer-add">ADD</label></div>
						  <div><input type="checkbox" id="customer-edit" data-id="check" /><label for="customer-edit">EDIT</label></div>
						  <div><input type="checkbox" id="customer-delete" data-id="check" /><label for="customer-delete">DELETE</label></div>
					  </fieldset>
					  <fieldset style="padding:11px">
						  <legend>Category</legend>
						  <div><input type="checkbox" id="category-add" data-id="check" /><label for="category-add">ADD</label></div>
						  <div><input type="checkbox" id="category-edit" data-id="check" /><label for="category-edit">EDIT</label></div>
						  <div><input type="checkbox" id="category-delete" data-id="check" /><label for="category-delete">DELETE</label></div>
					  </fieldset>
					  <fieldset style="padding:11px">
						  <legend>Administration</legend>
						  <div style="float:left;margin-right:10px;width:109px"><input type="checkbox" id="account-add" data-id="check" /><label for="account-add">ADD ACCOUNT</label></div>
						  <div style="float:left;margin-right:10px"><input type="checkbox" id="account-edit" data-id="check" /><label for="account-edit">EDIT ACCOUNT</label></div>
						  <div style="float:left;margin-right:10px"><input type="checkbox" id="account-delete" data-id="check" /><label for="account-delete">DELETE ACCOUNT</label></div>
						  <div style="float:left;margin-right:10px"><input type="checkbox" id="accept-return" data-id="check" /><label for="accept-return">ACCEPT RETURN</label></div>
						  <div style="float:left;margin-right:10px"><input type="checkbox" id="accept-payment" data-id="check" /><label for="accept-payment">ACCEPT PAYMENT</label></div>
					  </fieldset>
				  </fieldset>
				</div>
			</div>
        </div>
    </div>
</div>
<div id="print" style="display:none;margin:0px;width:675px;float:none;text-align:center" class="visible-print-block" >
	<div>
        <div id="store-name">Sales & Inventory Management System</div>
        <div id="store-addr">Commercial Center, Mindanao State University, Marawi City</div>
        <div id="store-contact"> +639 1037 27802</div>
    </div>
	<div style="margin-top:15px;font-weight:100">ACCOUNT LIST</div>
    <table width="100%" border="0" style="border-bottom:2px solid black;border-top:2px solid black;font-size:13px">
    	<td style="text-align:left" width="5%">#</td>
    	<td style="text-align:left" width="15%">Username</td>
        <td style="text-align:left">Account Name</td>
        <td style="text-align:center" width="25%">Registration</td>
        <td style="text-align:left">Added By</td>
    </table>
    <style>
		#receipt-list td:nth-child(1),#receipt-list td:nth-child(2){
			text-align:left;
		}
		#receipt-list td:nth-child(3){
			text-align:left;
		}
		#receipt-list td:nth-child(1){
			width:5%;
		}
		#receipt-list td:nth-child(2){
			width:15%;
			padding-right:4px;
		}
		
		#receipt-list td:nth-child(4){
			width:25%;
			text-align:center
		}
		#receipt-list td:nth-child(6),#receipt-list td:nth-child(7){
			width:10%;
			text-align:right
		}
		#receipt-list td:nth-child(5) {
			text-align:left;
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
    <table id="receipt-list" width="100%" border="0" style="font-size:13px">
    <tbody>
    </tbody>
    </table>
    <div style="margin-top:15px;font-weight:400;border-bottom:2px solid black">SUMMARY</div>
    <div style="margin:10px;font-size:13px" id="payment2">
    	<table width="100%" border="0">
          <tr>
          	<td style="text-align:left">Total Number of Account:</td>
            <td id="totAcc" style="text-align:left"></td>
          	<td width="50%">&nbsp;</td>
            <td>&nbsp;</td>
            <td id="totNet"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td id="totGross">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
          	<td>&nbsp;</td>
            <td id="totCash"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td id="cash"></td>
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
			check = true;
			$('#checkall').prop('checked',false);
			$.ajax({
				url: 'fetch/get-account.php',
				data: {accID:$('#list .active_ td').eq(0).text()},
				dataType:'json',
				success: function(s){
					console.log(s);
					$('#accUsername').val(s[1]);
					$('#accName').val(s[3]);
					$('#password').val(s[2]);
					var i=6;
					$('input[data-id=check]').each(function(index, element) {
						(s[i]=='True') ? $(this).prop('checked',true) : $(this).prop('checked',false);
						 i+=1;
					});
					
				}
			});
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
								data: {accID:id,update:true,delete:true,loc:'account'},
								dataType: 'json',
								success: function(s){
									console.log(s);
								}
							});
							//alert($('#edit select').prop('selected', true).val());
							window.location.assign('view-accounts.php?success=delete');
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
	$('#checkall').click(function() {
		if($(this).prop('checked') == true)
			$('input[data-id=check]').each(function(index, element) {
				$(this).prop('checked',true);
			});
		else
			$('input[data-id=check]').each(function(index, element) {
				$(this).prop('checked',false);
			});
	});
	function clear(){
		$('.modal-body').css('padding-top','15px');
		$('#id2').hide();
		$('#box2').hide();
		$('#name2').hide();
	}
	$('#submit').click(function() {
		clear();
		var check = true;
		$('.modal-body input[type=text]').each(function(index, element) {
            if($(this).val()==''){
				$(this).parent().removeClass('has-success');
				$(this).parent().addClass('has-error');
				check = false;
			}
			else{
				$(this).parent().removeClass('has-error');
				$(this).parent().addClass('has-success');
			}
				
        });
		if(!check){
			clear();
			$('.modal-body').css('padding-top','0');
			$('#box2').show();
		}
		else{
			if($('#password1').val() != $('#password2').val()){
				clear();
				$('.modal-body').css('padding-top','0');
				$('#pass2').show();
				check = false;
			}
			else
				check = true;
		}
		
		if(check){
		  $.ajax({
			  url: 'check/check.php',
			  dataType: 'json',
			  data: {accName:$('#accName').val(),accUsername:$('#accUsername').val()},
			  success: function(s){
				  console.log(s);
				  if(s[0]==true){
					  clear();
					  $('.modal-body').css('padding-top','0');
					  $('#name2').show();
				  }
				  else{
					  $.ajax({
						  url: 'save/save.php',
						  data: {accUsername:$('#accUsername').val(),accName:$('#accName').val(),accPassword:$('#password1').val(),addProduct:$('#product-add').prop('checked'),editProduct:$('#product-edit').prop('checked'),deleteProduct:$('#product-delete').prop('checked'),addPurchase:$('#purchase-add').prop('checked'),editPurchase:$('#purchase-edit').prop('checked'),deletePurchase:$('#purchase-delete').prop('checked'),addCustomer:$('#customer-add').prop('checked'),editCustomer:$('#customer-edit').prop('checked'),deleteCustomer:$('#customer-delete').prop('checked'),addCategory:$('#category-add').prop('checked'),editCategory:$('#category-edit').prop('checked'),deleteCategory:$('#category-delete').prop('checked'),addAccount:$('#account-add').prop('checked'),editAccount:$('#account-edit').prop('checked'),deleteAccount:$('#account-delete').prop('checked'),acceptReturn:$('#accept-return').prop('checked'),acceptPayment:$('#accept-payment').prop('checked'),accID:$('#accID').html(),account:true,update:true,edit:true},
						  dataType: 'json',
						  success: function(s){
							  console.log(s);
						  },
						  error: function(f){
							  window.location.assign('view-accounts.php?success=edit');
						  }
					  });
				  }
			  }
		  });
		}
	});
});
</script>
<script src="media/js/product-popup.js"></script>
<div class="print_info" style="display:none"><h6>Hint!</h6><p>When searching for a Purchase Order by date, enter the date in format YYYY-MM-DD or by time in format HH:MM in 24H format</p></div>
</body>
</html>
<?php
}
else
header("Location: index.php");
?>
