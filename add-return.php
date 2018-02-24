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
<title></title>

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
<span id="acceptReturn" style="display:none;visibility:0"><?php echo $row_rsAccount['acceptReturn'];?></span>
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
.modal-footer {
    padding: 6px !important;
}
.modal {
	top:20%;
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
		
		$('#modules').addClass('active');
		$('#modules_menu').show();
		$('#add-return').addClass('selected');
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
		$('a[data-modal-id="qty_popup"]').attr('disabled',true);
		
		
		
		$('#list_filter,#history_filter').remove();
		
		$(document).on("click","#list tr", function(){
			if($('#return-stat').html()=='Saved')
				return;
			if ( $(this).hasClass('active_') && !$(event.target).parents().andSelf().is("input")) {
				$(this).removeClass('active_');
				$('a[data-modal-id="qty_popup"]').attr('disabled',true);
			}
			else {
				$('#list tr.active_').removeClass('active_');
				$(this).addClass('active_');
				$('a[data-modal-id="qty_popup"]').attr('disabled',false);
			}
			if($('#list .active_ td').eq(0).text() == 'No data available in table'){
				$(this).removeClass('active_');$('#net,#bal').html('0.00');
			}
			else{
				if($(event.target).parents().andSelf().is("input")){
					if($(this).find('input').prop('checked')){
						$('#list tr.active_').removeClass('active_');
						$(this).addClass('active_');
						$(function(){
							var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
							var modalBox = 'qty_popup';
							$("body").append(appendthis);
							$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
							$('#'+modalBox).fadeIn($(this).data());
							var view_width = $(window).width();
							var view_top = $(window).scrollTop() + 150;
							$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
							$('#'+modalBox).css("top", view_top);
							
							if($('#list .active_ td').eq(9).text()=='Whole Number'){
								var x = $('#list .active_ td').eq(5).text();
								x = x.substr(0,x.indexOf('.'))
								$('#tend').val(x.replace(/,/g, ""));
							}
							else	
								$('#tend').val($('#list .active_ td').eq(5).text().replace(/,/g, ""));
							$('#tend').focus();
							$('#tend').keypress(function(e) {
								if ($.isNumeric(String.fromCharCode(e.keyCode))){
									if($(this).val().indexOf(".")>=0){
										x = $(this).val();
										if((x.substr($(this).val().indexOf(".")+1)+String.fromCharCode(e.keyCode)).length<=2 && new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number(new String($('#list .active_ td').eq(5).text()).replace(/,/g, "")))
											return true;
										else
											return false;
									}
									if(new Number($(this).val() + String.fromCharCode(e.keyCode)) <= 0)
										return false;
									else{
										if(new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number(new String($('#list .active_ td').eq(5).text()).replace(/,/g, "")))
											return true;
										return false;
									}
								}
								else if(String.fromCharCode(e.keyCode)=='.' && $('#list .active_ td').eq(9).text()=='Decimal'){
									if($(this).val() == '')
										return false;
									else if($(this).val().indexOf(".")>=0)
										return false;
									else{
										if(new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number(new String($('#list .active_ td').eq(5).text()).replace(/,/g, "")))
											return true;
										return false;
									}
								}
								return false;
							});
						});
						
					}
					else{
						if($('#list .active_ td').eq(7).text() == 'Good')
							$('#sum-good').html(new String(new Number(new Number($('#sum-good').html().replace(/,/g, ""))-new Number($('#list .active_ td').eq(7).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						else if($('#list .active_ td').eq(7).text() == 'Damage')
							$('#sum-bad').html(new String(new Number(new Number($('#sum-bad').html().replace(/,/g, ""))-new Number($('#list .active_ td').eq(7).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						x = (new Number($('#list .active_ td').eq(4).text().replace(/,/g, ""))*new Number($('#list .active_ td').eq(7).text().replace(/,/g, "")));
						y = (new Number($('#list .active_ td').eq(4).text().replace(/,/g, "")) *new Number($('#tend').val().replace(/,/g, ""))); 
						total = new Number($('#total').html().replace(/,/g, ""))-x;
						$('#total').html(new String(new Number(total).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						$('#sum-qty').html(new String(new Number(new Number($('#sum-qty').html().replace(/,/g, ""))-new Number($('#list .active_ td').eq(6).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						$('#list .active_ td').eq(7).html('0.00');
						$(this).removeClass('active_');
					}
				}
			}
		});
		shortcut.add('f1',function() {
			$('a[data-modal-id="qty_popup"]').click();
			});
		shortcut.add('f2',function() {
			$('#save').click();
			});
		$(document).on("click", function(event){
			if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("a.btn") && !$(event.target).parents().andSelf().is("#history_wrapper") && !$(event.target).parent().andSelf().is(".modal-overlay") && !$('#qty_popup').is(':visible')){
				 $('#list tr.active_').removeClass('active_');
				 $('a[data-modal-id="qty_popup"]').attr('disabled',true);
				 $('#net,#bal').html('0.00');
			}
		});
		var customer = $('#customer11').dataTable( {
				"scrollY":        "200px",
				"scrollCollapse": false,
				"paging":         false
			} );
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
				$('#custID').val($('#customer11 .selected td').eq(0).text());
				$('#custName').val($('#customer11 .selected td').eq(1).text());
				$('.js-modal-close').click();
				$('#saleID').prop('disabled',false).focus();
			}
		});
		$('#typeValue').autocomplete({
			  source:'fetch/get-sales_orders.php',
			  minLength:1,
			  autoFocus:true,	
			  select: function( event, ui ) {
				  $.ajax({
					url: 'fetch/get-sales_orders.php',
					 type: 'get',
					 data: {'saleID':$(ui)[0].item.value,account:true,check:true},
					 dataType: 'json',
					 success: function(s){
						console.log(s);
						if(new Number(s[0][0][0])>0 && new Number(s[0][0][0]) > new Number($('#salesReturn').val())){
							$('#date_error').fadeIn(1000).fadeOut(5500);
							$(this).val('').focus();
							return;
						}
						else{
							$('#net2').val(s[0][0][2]);
							var oTable = $('#list').dataTable();
							oTable.fnClearTable();
							var oTable1 = $('#history').dataTable();
							oTable1.fnClearTable();
							var x = new Number();
							for(var i = 0; i < s[0][1].length; i++){
								oTable1.fnAddData([
								'<a href="view-return.php?'+s[0][1][i][0]+'">'+s[0][1][i][0]+'</a>',
								s[0][1][i][1],
								s[0][1][i][2]
								]);
							}
							for(var i = 1; i < s.length-2; i++) {
								
								if(new Number(s[i][4]) > 0){
								oTable.fnAddData([
								'<input type="checkbox" data-check-id='+s[i][1]+'>',
								s[i][1],
								s[i][2],
								s[i][0],
								s[i][3],
								s[i][4],
								s[i][5],
								0.00,'Good',s[i][9]
								]);
								x +=new Number(s[i][4].replace(/,/g, ""));
								}
							} // End For
							$('#saleID').focusout();
							$('#qty').html(new String(x).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
							$('#accName').html(s[s.length-1][0]);
						}
					 },error: function(ss){
							 	$('#returnType1,#returnType2').html('Sales Order');
								$('#no_product').fadeIn(1000).fadeOut(6500);
							 }
				});
			}
		}).on("keydown", function(e) {
			  if(e.keyCode==9)
			  	return
			   var oTable = $('#list').dataTable();
			   oTable.fnClearTable();
			   var oTable = $('#history').dataTable();
			   oTable.fnClearTable();
		});
		function change1(x,y){
				$('#label').html(x);
				$('#typeValue').val('').attr('placeholder',y).focus();
				$('#qty').html('0');
				$('#accName').html('Account Name');
				$('#total').html('0.00');
				var oTable = $('#list').dataTable();
				oTable.fnClearTable();
				var oTable = $('#history').dataTable();
				oTable.fnClearTable();
				if(x=='SO No.:'){
					$('#type1').show();
					$('#netPP').html('Net Price');
					$('#typeValue').autocomplete({
					  source:'fetch/get-sales_orders.php',
					  minLength:1,
					  autoFocus:true,	
					  select: function( event, ui ) {
						  $.ajax({
							url: 'fetch/get-sales_orders.php',
							 type: 'get',
							 data: {'saleID':$(ui)[0].item.value,account:true,check:true},
							 dataType: 'json',
							 success: function(s){
								console.log(s);
								if(new Number(s[0][0][0]) < new Number($('#salesReturn').val())){
									$('#date_error').fadeIn(1000).fadeOut(5500);
									$(this).val('').focus();
								}
								else{
									$('#net2').val(s[0][0][2]);
									var oTable = $('#list').dataTable();
									oTable.fnClearTable();
									var oTable1 = $('#history').dataTable();
									oTable1.fnClearTable();
									var x = new Number();
									for(var i = 0; i < s[0][1].length; i++){
										oTable1.fnAddData([
										'<a href="view-return.php?'+s[0][1][i][0]+'">'+s[0][1][i][0]+'</a>',
										s[0][1][i][1],
										s[0][1][i][2]
										]);
									}
									for(var i = 1; i < s.length-2; i++) {
										if(new Number(s[i][3]) > 0){
										oTable.fnAddData([
										'<input type="checkbox" data-check-id='+s[i][1]+'>',
										s[i][1],
										s[i][2],
										s[i][0],
										s[i][3],
										s[i][4],
										s[i][5],
										0.00,'Good',s[i][9]
										]);
										x +=new Number(s[i][4].replace(/,/g, ""));
										}
									} // End For
									$('#saleID').focusout();
									$('#qty').html(new String(x).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
									$('#accName').html(s[s.length-1][0]);
								}
							 },error: function(ss){
							 	$('#returnType1,#returnType2').html('Sales Order');
								$('#no_product').fadeIn(1000).fadeOut(6500);
							 }
						});
					}
				}).on("keydown", function(e) {
					  if(e.keyCode==9)
			  			 return
					   var oTable = $('#list').dataTable();
					   oTable.fnClearTable();
					   var oTable = $('#history').dataTable();
					   oTable.fnClearTable();
				});
				}
				else{
					$('#type1').hide();
					$('#netPP').html('Net Cost');
					$('#typeValue').autocomplete({
					  source:'fetch/get-purchase_orders.php',
					  minLength:1,
					  autoFocus:true,	
					  select: function( event, ui ) {
						  $.ajax({
							url: 'fetch/get-purchase_orders.php',
							 type: 'get',
							 data: {'purcID':$(ui)[0].item.value,account:true,check:true},
							 dataType: 'json',
							 success: function(s){
								console.log(s);
								var oTable = $('#list').dataTable();
								oTable.fnClearTable();
								var oTable1 = $('#history').dataTable();
								oTable1.fnClearTable();
								var x = new Number();
								for(var i = 0; i < s[0][1].length; i++){
									oTable1.fnAddData([
									'<a href="view-return.php?'+s[0][1][i][0]+'">'+s[0][1][i][0]+'</a>',
									s[0][1][i][1],
									s[0][1][i][2]
									]);
								}
								for(var i = 1; i < s.length-2; i++) {
									if(new Number(s[i][3]) > 0){
									oTable.fnAddData([
										'<input type="checkbox" data-check-id='+s[i][0]+'>',
										s[i][0],
										s[i][1],
										s[i][6],
										s[i][2],
										s[i][3],
										s[i][4],
										0.00,'Good',s[i][7]
									]);
									x +=new Number(s[i][3].replace(/,/g, ""));
									}
								} // End For
								$('#saleID').focusout();
								$('#qty').html(new String(x).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
								$('#accName').html(s[s.length-1][0]);
							},
							error: function(ss){
								$('#returnType1,#returnType2').html('Purchase Order');
								$('#no_product').fadeIn(1000).fadeOut(6500);
							}
						});
					}
				}).on("keydown", function(e) {
					   if(e.keyCode==9)
			  			return
					   var oTable = $('#list').dataTable();
					   oTable.fnClearTable();
					   var oTable = $('#history').dataTable();
					   oTable.fnClearTable();
				});
				}
			}
		$('#type').change(function(e) {
			change1('SO No.:','Sales Order Number');
            if($(this).val()!='toInventory')
				change1('PO No.:','Purchase Order Number');
			
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
#qty_popup .btn {
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
  width:auto;
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
#list td:nth-child(8),#list td:nth-child(5),#list td:nth-child(6),#list td:nth-child(7),#history td:nth-child(2),#history td:nth-child(3){
	text-align:right;
}
#list td:nth-child(4){
	text-align:center;
}
#list td:nth-child(10){
	display:none;
}
p {
  margin: 0 0 0px;
}
</style>
<body>
<div style="display:none" id="parent">modules</div>
<input type="hidden" id="salesReturn" value="<?php echo $row_rsGeneral1['salesReturn'];?>" />
<div style="padding:25px" class="hidden-print">
	<div style="float:left;width:50%">
    	<style>
			.form-control {
				height:auto;
			}
		</style>
        <fieldset>
        <legend>[ Information ]</legend>
    	<table width="100%" border="0" id="cust_info">
          <tr>
            <td width="15%">Return Type:</td>
            <td width="20%">
            	<select contenteditable="true" style="text-align:center;background:transparent" class="form-control ui-autocomplete-input" id="type" autocomplete="off">
                <option value="toInventory">To Inventory</option>
                <option value="toSupplier">To Supplier</option>
                </select>
            </td>
            <td style="text-align:right"><span style="margin-right:5px" id="label">SO No.:</span></td>
            <td style="text-align:right">
            	<input type="hidden" id="net2" />
				<input type="hidden" id="retID">
            	<input type="text" style="text-align:center;background: transparent;width:98%" class="form-control ui-autocomplete-input" placeholder="Sales Order Number" id="typeValue" autocomplete="off">
            </td>
			<td>
				<select id="type1" style="text-align:center;background:transparent" class="form-control">
					<option value="RP">Replace</option>
                	<option value="RF">Refund</option>
				</select>
			</td>
          </tr>
          <tr>
            <td style="text-align:right"><span style="margin-right:5px">Note/Memo:</span></td>
            <td colspan="5">
            	<input type="text" style="text-align:left;background: transparent;" class="form-control ui-autocomplete-input" placeholder="Note" id="note" autocomplete="off">
            </td>
          </tr>
        </table>
        </fieldset>
        <table width="100%" border="0" id="details" style="background:#222;margin-top:10px;font-family:calibri">
          <tr>
            <td width="25%" style="color:whitesmoke;font-weight:bold">Total Quantity:</td>
            <td width=""><div style="text-align:right;width:100%;padding-right:10px;color: #00cc00;font-family:-webkit-body" id="qty">0</div></td>
            <td width="25%" style="color:whitesmoke;font-weight:bold">Assigned to:</td>
            <td width="25%"><div style="text-align:right;width:100%;color: #00cc00;font-family:-webkit-body" id="accName">Account Name</div></td>
          </tr>
        </table>
    </div>
    <div style="float: right;width: 49%;background:#222;height: 139px;border-radius: 5px;overflow:hidden">
    	<div style="text-align: center;font-size: 23px;color: white;border-bottom: 2px solid;font-family:-webkit-body">
        	Total Net Amount
        </div>
        <div id="total" style="font-family:-webkit-body;color: #00cc00;text-align: right;/* padding: 10px; */font-size: 93px;height: auto !important;overflow: hidden;">0.00</div>
        <span id="return-stat" style="display:none"></span>
    </div>
    <div style="width:70%;float:left;margin-top:10px">
    	<table width="100%" id="list">
            <thead>
                <th width="1%"><input type="checkbox" id="checkAll" /></th>
                <th width="13%">Product ID</th>
                <th>Item Description</th>
				<th width="3%">Unit</th>
                <th width="12%">Price</th>
                <th width="8%">Qty</th>
                <th width="12%" id="netPP">Net Price</th>
                <th width="8%">Return</th>
                <th width="6%">Status</th>
				<th style="display:none">qtyType</th>
            </thead>
        </table>
    </div>
    <div style="width:29%;float:right;margin-top:10px">
    	<div style="text-align:center;font-weight:bold;margin-bottom:5px">Return History</div>
    	<table width="100%" id="history">
            <thead>
            	<th>Return ID</th>
                <th width="45%">Date & Time</th>
                <th>Quantity</th>
            </thead>
        </table>
    </div>
    <div id="buttons" style="width:30%;float:left;margin-top:3%">
    	<a class="btn" data-modal-id="qty_popup">Edit Quantity<span id="key">F1</span></a>
        <a class="btn" id="save">Save<span id="key">F2</span></a>
        <a class="btn" id="clear">Clear<span id="key">F5</span></a>
    </div>
    <div style="width: 29%;float:left;margin-top:10px;background-color: #222;padding: 5px;color: whitesmoke;font-size: 11.49px;font-weight:bold;height: 85px;">
    	<span>Note: </span>
        <p style="color:#BA1E1E" style="margin-bottom:0px !important"> To select all products to return, check the topmost checkbox</p>
        <p style="color:#BA1E1E" style="margin-bottom:0px !important"> To remove a product from the list, click its box on the left</p>
        <span style="color:whitesmoke">Status:</span>
        <p style="color:#BA1E1E" style="margin-bottom:0px !important"> Select Items and Press "G" as Good or "D" as Damage</p>
    </div>
    <div style="float:right;width:40%;height:85px;margin-top:10px;background-color:#222;padding-left:10px;padding-right:5px;padding-top:5px;padding-bottom:5px">
    	
        
        <div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Total Quantity</span>
            <span style="float:right;font-size: 22px !important;font-family: -webkit-body;color: #00cc00;" id="sum-qty">0.00</span>
        </div>
        <div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Good</span>
            <span style="float:right;font-size: 22px !important;font-family: -webkit-body;color: #00cc00;" id="sum-good">0.00</span>
        </div>
        <div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Damage</span>
            <span style="float:right;font-size: 22px !important;font-family: -webkit-body;color: #00cc00;" id="sum-bad">0.00</span>
        </div>
    </div>
    <div id="qty_popup" class="modal-box" style="width:25%">
    	<header>
          <h3>Return Quantity</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
           <div style="text-align: left;text-shadow: 1px 0px black;" id="title">Quantity to return:</div>
           <div id="tend-div"><input id="tend" type="text" class="form-control" style="font-family:-webkit-body;font-size: 60px !important;padding:0;color:black;"/></div>
           
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="qty_button" class="btn" style="text-transform:uppercase">Submit</a>
            </div>
        </div>
    </div>
	<div id="print_popup" class="modal-box" style="width:90%;top:10px;">
		<header>
		<h3>Print Preview<span title="Close" class="glyphicon glyphicon-remove-sign js-modal-close" aria-hidden="true" style="float:right;font-size: 17px;cursor: pointer;"></span></h3>
		</header>
		<div class="modal-body" style="text-align:center;padding:0px;display:initial;height:initial">
		<iframe id="report" src="" frameborder="0" style="width:100%;height:100%"></iframe>
		</div>
    </div>   
</div>

</script>


<script src="media/js/add-return.js"></script>
<script>
	$(function(){
	var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('.btn[data-modal-id]').click(function(e) {

		if($('#ret-stat').html()!='Save'){
		
		e.preventDefault();
		check = true;

		var modalBox = $(this).attr('data-modal-id');
		if(modalBox=='qty_popup'){
			if(!$('#list tr').hasClass('active_'))
				check = false;
		}
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			$('#'+modalBox).fadeIn($(this).data());
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 150;
			$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
			$('#'+modalBox).css("top", view_top);
			if(modalBox=='qty_popup'){
				if($('#list .active_ td').eq(9).text()=='Whole Number'){
					var x = $('#list .active_ td').eq(5).text();
					x = x.substr(0,x.indexOf('.'))
					$('#tend').val(x.replace(/,/g, ""));
				}
				else	
					$('#tend').val($('#list .active_ td').eq(5).text().replace(/,/g, ""));
				$('#tend').focus();
				$('#tend').keypress(function(e) {
					if ($.isNumeric(String.fromCharCode(e.keyCode))){
						if($(this).val().indexOf(".")>=0){
							x = $(this).val();
							if((x.substr($(this).val().indexOf(".")+1)+String.fromCharCode(e.keyCode)).length<=2 && new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number(new String($('#list .active_ td').eq(5).text()).replace(/,/g, "")))
								return true;
							else
								return false;
						}
						if(new Number($(this).val() + String.fromCharCode(e.keyCode)) <= 0)
							return false;
						else{
							if(new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number(new String($('#list .active_ td').eq(5).text()).replace(/,/g, ""))){
								return true;
							}
							return false;
						}
					}
					else if(String.fromCharCode(e.keyCode)=='.' && $('#list .active_ td').eq(9).text()=='Decimal'){
						if($(this).val() == '')
							return false;
						else if($(this).val().indexOf(".")>=0)
							return false;
						else{
							if(new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number(new String($('#list .active_ td').eq(5).text()).replace(/,/g, ""))){
								return true;
							}
							return false;
						}
					}
					return false;
				});
			}
			$('#list .active_ td').eq(0).find('input').prop('checked',true);
		}
		}
	});  
	
	$(".js-modal-close, .modal-overlay").click(function() {
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	});
	$('#qty_button').click(function() {
		var check = true;
		if(new Number($('#tend').val().replace(/,/g, "")) > 0){
			if($('#list .active_ td').eq(8).text() == 'Good')
				$('#sum-good').html(new String(new Number(new Number($('#sum-good').html().replace(/,/g, ""))+new Number($('#tend').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			else if($('#list .active_ td').eq(8).text() == 'Damage')
				$('#sum-bad').html(new String(new Number(new Number($('#sum-bad').html().replace(/,/g, ""))+new Number($('#tend').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			$(".js-modal-close").click();
			x = (new Number($('#list .active_ td').eq(4).text().replace(/,/g, ""))*new Number($('#list .active_ td').eq(7).text().replace(/,/g, "")));
			y = (new Number($('#list .active_ td').eq(4).text().replace(/,/g, "")) *new Number($('#tend').val().replace(/,/g, ""))); 
			total = new Number($('#total').html().replace(/,/g, ""))-x;
			$('#total').html(new String(new Number(total+y).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			$('#list .active_ td').eq(7).html(new String(new Number($('#tend').val().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			$('#sum-qty').html(new String(new Number(new Number($('#sum-qty').html().replace(/,/g, ""))+new Number($('#tend').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		}
		else
			$('#tend').val('0.00');
	});
});
// JavaScript Document
</script>
<div class="print_info" id="save_success" style="display:none"><h6>Return Saved Successfully</h6><p>Press <kbd>F5</kbd> or Clear Button to create new. Press <kbd>Ctrl+P</kbd> to print the Return Confirmation.</p></div>
<div class="print_info" id="find_error" style="display:none"><h6>Sales Order Not Found !<p>Please refer to Sales Order list.</p></h6></div>
<div class="print_info" id="save_error" style="display:none"><h6>Warning !<p>Please select atleast one product to return before clicking <strong>Save</strong> button.</p></h6></div>
<div class="print_info" id="type_error" style="display:none"><h6>Warning !<p>The system only accepts return of products <br /> from a <strong>Cash</strong> type Sales Order.</p></h6></div>
<div class="print_info" id="no_so_product" style="display:none"><h6>Warning !<p><strong>Sales Order</strong> not found.</p></h6></div>
<div class="print_info" id="no_po_product" style="display:none"><h6>Warning !<p><strong>Purchase Order</strong> not found.</p></h6></div>
<div class="print_info" id="date_error" style="display:none"><h6>Warning !<p>The system only accepts return of products <br />within <strong><?php echo $row_rsGeneral1['salesReturn']; ?> Day(s)</strong> after Sales Order has been placed.</p></h6></div>
<div class="print_info" id="no_product" style="display:none;    padding: 10px 23px;"><h6>Warning !<p>This <strong><span id="returnType1"></span></strong> has no product to return. <br />It maybe because all products of this <strong><span id="returnType2"></span></strong> had already returned.</p></h6></div>

</body>
</html>
<?php
}else
header("Location: index.php");
?>
