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
$query = "DELETE FROM payment_so_temp_list WHERE accID='".$_SESSION['MM_AccID']."' AND sessionID='".session_id()."'";
mysql_select_db($database_connSIMS, $connSIMS);
mysql_query($query, $connSIMS) or die(mysql_error());
mysql_select_db($database_connSIMS, $connSIMS);
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
<script src="media/js/sales.dataTable.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>
<span id="acceptPayment" style="display:none;visibility:0"><?php echo $row_rsAccount['acceptPayment'];?></span>
<style>
.dataTables_scrollHeadInner {
	width:100% !important;
}
.input-group .form-control {
	font-size: 14px !important;
	text-align:left;
	font-weight:normal;
	height:28px
}
#lookup .modal-body, #customer_pop .modal-body{
	padding:3px !important;
}
#lookup .btn, #customer_pop .btn {
	padding:3px 6px;
}
#lookup .dataTables_scrollHeadInner th,#product td:nth-child(3) {
	text-align:center;
}
#lookup .dataTables_scrollHeadInner th:nth-child(1), #product td:nth-child(1){
	width:15% !important;
}
#product td:nth-child(4),#product td:nth-child(5),#customer11 td:nth-child(3),#customer11 td:nth-child(4){
	text-align:right;
}
@media print {
	#body, #header {
		display:none;
	}
	#print {
		display:block !important;
		width: 8.5in;
        height: 11in;
	}
	.page-break	{ display: none; }
}
</style>
<script>
	$(document).ready(function () {
		if($('#acceptPayment').html()!='True')
			window.history.back();
		$('#modules').addClass('active');
		$('#modules_menu').show();
		$('#payment').addClass('selected');
		
		$('#list').dataTable({
			"scrollY":        "200px",
			"scrollCollapse": false,
			"paging":         false
		});
		$('#history').dataTable({
			"scrollY":        "175px",
			"scrollCollapse": false,
			"paging":         false
		});
		$('#list_filter,#history_filter').remove();
		
		$(document).on("click","#list tr", function(){
			if ( $(this).hasClass('active_') ) {
				$(this).removeClass('active_');
				$('#net,#bal').html('0.00');
			}
			else {
				$('#list tr.active_').removeClass('active_');
				$(this).addClass('active_');
				$('#net').html($('#list .active_ td').eq(2).text());
				$('#bal').html($('#list .active_ td').eq(4).text());
			}
			if($('#list .active_ td').eq(0).text() == 'No data available in table'){
				$(this).removeClass('active_');$('#net,#bal').html('0.00');
			}
			else{
				if($(event.target).parents().andSelf().is("input")){
					if($(this).find('input').prop('checked')){
						$('#total').html((new Number($('#total').html().replace(/,/g, "")) + new Number($(this).find('td').eq(4).text().replace(/,/g, ""))).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","))
						//alert($(this).find('td').eq(2).text());
					}
					else{
						$('#total').html((new Number($('#total').html().replace(/,/g, "")) - new Number($(this).find('td').eq(4).text().replace(/,/g, ""))).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","))
					}
				}
				 $.ajax({
				   url: 'fetch/get-payments.php',
				   type: 'get',
				   data: {'saleID':$('#list .active_ td').eq(1).text()},
				   dataType: 'json',
				   success: function(s){
					  console.log(s);
					  var oTable = $('#history').dataTable();
					  oTable.fnClearTable();
						for(var i = 0; i < s.length; i++) {
							oTable.fnAddData([
							s[i][0],
							s[i][1],
							s[i][2]
							]);
						} // End For
				   }
			  });
			}
		});
		shortcut.add('f1',function() {
			$('[data-modal-id="payment_popup"]').click();
			});
		shortcut.add('f2',function() {
			$('#save').click();
			});
		$(document).on("click", function(event){
			if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("a.btn") && !$(event.target).parents().andSelf().is("#history_wrapper")){
				 $('#list tr.active_').removeClass('active_');
				 $('#net,#bal').html('0.00');
				 $('#history').dataTable().fnClearTable();
			}
		});
		var customer = $('#customer11').dataTable( {
				"scrollY":        "200px",
				"scrollCollapse": false,
				"paging":         false
			} );
		$('#customer_but').click(function() {
			$.ajax({
				url: 'fetch/get-customer.php',
				dataType: 'json',
				success: function(s){
					console.log(s);
					customer.fnClearTable();
					for(var i = 0; i < s.length; i++) {
						customer.fnAddData([
						s[i][0],
						s[i][1],
						s[i][2],
						s[i][3]
						]);
					} // End For
				},
				error: function(e){
					console.log(e.responseText);
				}
			});
		});
		$('#customer11 tbody').on( 'click', 'tr', function () {
			if ( $(this).hasClass('selected') ) {
				$(this).removeClass('selected');
			}
			else {
				customer.$('tr.selected').removeClass('selected');
				$(this).addClass('selected');
			}
		} );
		$(document).click(function(event) { 
			if(!$(event.target).closest('tbody').length) {
				if($('#lookup').is(':visible')){
					$('#product tr.selected').removeClass('selected');
				}
				else if($('#customer_pop').is(':visible')){
					$('#customer11 tr.selected').removeClass('selected');
				}
			}       
		});
		$('#select_cust').click(function() {
			if(customer.$('tr').hasClass('selected')){
				//alert($('#product .selected td').eq(0).text());
				$('#custID').val($('#customer11 .selected td').eq(0).text());
				 $.ajax({
					  url: 'fetch/get-customer.php?sql=payment',
					   type: 'get',
					   data: {'custID':$('#customer11 .selected td').eq(0).text()},
					   dataType: 'json',
					   success: function(s){
						  console.log(s);
						  $('#custName').val(s[0]);
						  $('#custCred').val(s[1]);
						  $('#last').html(s[2] == false ? 'None' : s[2] );
						  var oTable = $('#list').dataTable();
						  oTable.fnClearTable();
							for(var i = 3; i < s.length; i++) {
								oTable.fnAddData([
								'<input type="checkbox" data-check-id='+s[i][0]+'>',
								s[i][0],
								s[i][1],
								s[i][2],
								s[i][3],
								s[i][4],
								s[i][5]
								]);
							} // End For
					   }
				 });
				$('.js-modal-close').click();
			}
		});
		$('#tend').autoNumeric('init',{'vMin':0});
	});	
</script>
<style>
.dataTables_scrollHead {
	background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);

}
.dataTables_scroll {
	  box-shadow: 0px 0px 2px gray;
}
.dataTables_info {
	display:none !important;
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
[disabled]{
	cursor:auto !important;
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
  border:0 !important;
}
fieldset td {
	padding-bottom:.3em;
}
#details td {
	padding:5px;
}
#payment_popup .btn {
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
#buttons .btn {
  float: left;
  font-size: 16px;
  padding: 3px 12px 3px 12px;
  margin-right: 3px;
  width: 109px;
}
#key {
  width: 100%;
  float: inherit;
  font-size: 12px;
}
.btn {
  background-image: linear-gradient(to bottom, #ffffff, lightgray 213%);
  border: 1px solid lightgray;
  font-weight: bold;
}
.summary {
  float: inherit;
  width: 100%;
  line-height: 25px;
}
#list td:nth-child(1){
	text-align:center
}
#list td:nth-child(3),#list td:nth-child(4),#list td:nth-child(5),#history td:nth-child(2),#history td:nth-child(3){
	text-align:right;
}

</style>
<body>
<div style="display:none" id="parent">modules</div>
<div style="padding:25px" class="hidden-print">
	<div style="float:left;width:50%">
    	<fieldset>
        <legend>[ Customer ]</legend>
    	<table width="100%" border="0" id="cust_info">
          <tr>
            <td width="15%">Customer No.:</td>
            <td width="20%">
            	<input type="text" style="text-align:center;background:transparent" class="form-control ui-autocomplete-input" placeholder="Customer ID" id="custID" autocomplete="off">
            </td>
            <td width="10%"><button class="btn" id="customer_but" data-modal-id="customer_pop" style="padding:0 16px;font-size:18px;height:101%;">...</button></td>
            <td><input type="text" style="text-align:center;background:transparent" class="form-control ui-autocomplete-input" placeholder="Customer Name" id="custName" autocomplete="off" disabled="disabled"></td>
          </tr>
          <tr>
            <td style="text-align:right"><span style="margin-right:5px">Credit:</span></td>
            <td>
            	<input type="text" style="text-align:center;background: transparent;" class="form-control ui-autocomplete-input" placeholder="Credit" id="custCred" autocomplete="off" disabled="disabled">
            </td>
            <td>&nbsp;</td>
            <td>
            	Last Payment:<span id="last" style="margin-left:10px"></span>
            </td>
          </tr>
        </table>
        </fieldset>
        <table width="100%" border="0" id="details" style="background:#222;margin-top:10px;font-family:calibri">
          <tr>
            <td width="25%" style="color:whitesmoke;font-weight:bold">SO Net Amount:</td>
            <td width="25%"><div style="text-align:right;width:100%;padding-right:10px;color: #00cc00;font-family:-webkit-body" id="net">0.00</div></td>
            <td width="25%" style="color:whitesmoke;font-weight:bold">SO Balance:</td>
            <td width="25%"><div style="text-align:right;width:100%;color: #00cc00;font-family:-webkit-body" id="bal">0.00</div></td>
          </tr>
        </table>
    </div>
    <div style="float: right;width: 49%;background:#222;height: 139px;border-radius: 5px;overflow:hidden">
    	<div style="text-align: center;font-size: 23px;color: white;border-bottom: 2px solid;font-family:-webkit-body">
        	Total Outstanding Balance
        </div>
        <div id="total" style="font-family:-webkit-body;color: #00cc00;text-align: right;/* padding: 10px; */font-size: 93px;height: auto !important;overflow: hidden;">0.00</div>
        <span id="pay-stat" style="display:none"></span>
    </div>
    <div style="width:70%;float:left;margin-top:10px">
    	<table width="100%" id="list">
            <thead>
                <th width="1%"><input type="checkbox" id="checkAll" /></th>
                <th width="10%">SO No.</th>
                <th width="18%">Net Amount</th>
                <th width="18%">Gross Amount</th>
                <th width="18%">Balance</th>
                <th width="">Date & Time</th>
                <th width="11%">Due Date</th>
            </thead>
        </table>
    </div>
    <div style="width:29%;float:right;margin-top:10px">
    	<div style="text-align:center;font-weight:bold;margin-bottom:5px">Payment History</div>
    	<table width="100%" id="history">
            <thead>
                <th width="45%">Date</th>
                <th>Balance</th>
                <th>Amount</th>
            </thead>
        </table>
    </div>
    <div id="buttons" style="width:30%;float:left;margin-top:3%">
    	<a class="btn" data-modal-id="payment_popup">Payment<span id="key">F1</span></a>
        <a class="btn" id="save">Save<span id="key">F2</span></a>
        <a class="btn" id="clear">Clear<span id="key">F5</span></a>
    </div>
    <div style="width: 29%;float:left;margin-top:10px;background-color: #222;padding: 5px;color: whitesmoke;font-size:12.5px;font-weight:bold">
    	<span>Note: </span>
        <p style="color:#BA1E1E"> To select all sales order, check the topmost checkbox</p>
        <p style="color:#BA1E1E"> To remove a sales order from the list, click its box on the left</p>
    </div>
    <div style="float:right;width:40%;height:85px;margin-top:10px;background-color:#222;padding-left:10px;padding-right:5px;padding-top:21px;padding-bottom:5px">
    	
        
        <div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Cash Tendered</span>
            <span style="float:right;font-size: 22px !important;font-family: -webkit-body;color: #00cc00;" id="sum-tend">0.00</span>
        </div>
        <div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Change</span>
            <span style="float:right;font-size: 22px !important;font-family: -webkit-body;color: #00cc00;" id="sum-change">0.00</span>
        </div>
    </div>
   <div id="customer_pop" class="modal-box" style="width:65%;">
      <header>
          <h3>Customer</h3>
      </header>
      <div class="modal-body">
          <table id="customer11" width="100%">
              <thead>
                  <tr>
                      <th width="15%" style="width:15% !important">Customer ID</th>
                      <th>Name</th>
                      <th width="15%" style="width:15% !important">Credit</th>
                      <th width="15%" style="width:15% !important">Credit Limit</th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
      </div>
    </div>
    <div id="payment_popup" class="modal-box" style="width:25%">
    	<header>
          <h3>Payment</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
         	<div style="background:#222;color: white;padding-left: 3px;padding-right: 3px;border-radius:4px">
            	<input type="text" id="nett" class="form-control" style="background:transparent;border:0;color: azure;box-shadow: none !important;padding: 0px;overflow: hidden;font-family: -webkit-body;color: #00cc00;text-shadow: 0px 0px 7px #00cc00;font-size: 60px !important;" disabled/>
                
            </div>
           <div style="text-align: left;text-shadow: 1px 0px black;" id="title">Amount Tendered:</div>
           <div id="tend-div"><input id="tend" type="text" class="form-control" style="font-family:-webkit-body;font-size: 60px !important;padding:0;color:black;"/></div>
           <div id="date-div" style="display:none;height:87px;overflow:hidden" class="form-control">
           	<input id="date" type="date" style="font-family:-webkit-body;font-size: 57px !important;padding: 9px;border: 0;margin-left: -46px;height: 90px;margin-top: -9px;color: black;line-height: 80px;" value="<?php echo date('Y-m-d', strtotime("+30 days"));?>"/></div>
           
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="pay_button" class="btn" style="text-transform:uppercase">Submit</a>
                <a href="#" id="paycancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
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
    <table width="100%" border="0">
      <tr>
        <td>Customer #:</td>
        <td width="344px" id="custID2"style="text-align:left"></td>
        <td>Payment #:</td>
        <td id="payID" style="text-align:left"></td>
      </tr>
      <tr>
        <td width="18%">Customer Name:</td>
        <td id="custName2" width="30%"></td>
        <td>Date & Time:</td>
        <td id="datetime" style="text-align:left"></td>
      </tr>
    </table>
	<div style="margin-top:15px;font-weight:400">PAYMENT SALES ORDERS</div>
    <table width="100%" border="0" style="border-bottom:2px solid black;border-top:2px solid black;font-size:13px">
    	<td style="text-align:left" width="13%">SO #</td>
        <td style="text-align:center">Date & Time</td>
        <td style="text-align:right" width="15%">Net Amount</td>
        <td style="text-align:right" width="15%">Cash Amount</td>
        <td style="text-align:right" width="15%">Balance</td>
        <td style="text-align:right" width="15%">Total</td>
    </table>
    <style>
		#receipt-list td:nth-child(1){
			text-align:left;
		}
		#receipt-list td:nth-child(2){
		 	text-align:center;
		}
		#receipt-list td:nth-child(1){
			width:13%;
		}
		#receipt-list td:nth-child(3),#receipt-list td:nth-child(4),#receipt-list td:nth-child(5),#receipt-list td:nth-child(6){
			width:15%;
			text-align:right
		}
		#receipt-list td {
			border-bottom: 1px solid black;
			border-spacing: 0;
			border-collapse: collapse;
		}
		#payment2 td{
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
    <div style="margin-top:15px;font-weight:400;border-bottom:2px solid black">PAYMENTS</div>
    <div style="margin:10px;font-size:13px" id="payment2">
    	<table width="100%" border="0">
          <tr>
            <td style="text-align:left">Total No. of SO:</td>
            <td id="totSo"></td>
            <td>&nbsp;</td>
            <td>Total Outstanding Credit:</td>
            <td id="totOutCred"></td>
          </tr>
          <tr>
            <td style="text-align:left">Total Net Amount:</td>
            <td id="totNet"></td>
            <td width="30%">&nbsp;</td>
            <td>Cash Paid:</td>
            <td id="totPaid"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Total Remaining Credit:</td>
            <td id="totRemCredit">&nbsp;</td>
          </tr>
          
          
        </table>
		<table width="100%" border="0" id="account" style="margin-top:20px">
          <tr>
            <td style="border-bottom:1px solid black" id="custName3"></td>
            <td width="20%">&nbsp;</td>
            <td style="border-bottom:1px solid black" id="accName1"></td>
          </tr>
          <tr>
            <td>CUSTOMER</td>
            <td>&nbsp;</td>
            <td>VERIFIED BY</td>
          </tr>
        </table>

    </div>
    
</div>
</script>
<script src="media/js/product-popup.js"></script>
<script src="media/js/payment.js"></script>
<script>
	$(function(){
	var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('.btn[data-modal-id]').click(function(e) {
		if($('#pay-stat').html()!='Save'){
		e.preventDefault();
		check = true;
		
		//$(".js-modalbox").fadeIn(500);
		var modalBox = $(this).attr('data-modal-id');
		if(modalBox=='payment_popup'){
			$('#list').find('tr').each(function(index, element) {
				if($(this).find('td').eq(0).text()=='No data available in table'){
					check = false;
				}
				return;
			});
			$('#nett').val($('#total').html());
		}
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			$('#'+modalBox).fadeIn($(this).data());
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 150;
			$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
			$('#'+modalBox).css("top", view_top);
			if(modalBox=='payment_popup')
				$('#tend').focus();
			//$(this+' input').focus();
			$(".modal-overlay").click(function() {
				if($('#lookup,#customer_pop,#payment_popup').is(':visible')){
					$(".modal-box, .modal-overlay").fadeOut(100, function() {
						$(".modal-overlay").remove();
					});
				}
			});
		}
		}
	});  
	
	$(".js-modal-close, .modal-overlay").click(function() {
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	});
	$('#pay_button').click(function() {
		var check = true;
		if(new Number($('#tend').val().replace(/,/g, "")) < new Number($('#nett').val().replace(/,/g, ""))){
			check = false;
			bootbox.dialog({
				message: "The amount should be greater than or equal to "+$('#nett').val()+".",
				buttons: {
					main: {
						label: 'Ok',
						className: "btn"
					}
				}
			});	
		}
		if(check){
			$(".js-modal-close").click();
			$('#pay-stat').html('Ready');
		}
	});
});
// JavaScript Document
</script>
<div class="print_info" style="display:none"><h6>Payment Saved Successfully</h6><p>Press F5 or Clear Button to create new. Press Ctrl+P to print the Payment  Invoice.</p></div>

</body>
</html>
<?php
}else
header("Location: index.php");
?>
