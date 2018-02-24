<?php require_once('Connections/connSIMS.php'); ?>
<?php
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


if (!isset($_SESSION)) {
  session_start();
}

if(isset($_SESSION['MM_Username'])){
   $query = "DELETE FROM so_temp_list WHERE accID='".$_SESSION['MM_AccID']."' AND sessionID='".session_id()."'";
   mysql_select_db($database_connSIMS, $connSIMS);
   mysql_query($query, $connSIMS) or die(mysql_error());
$query_rsProduct = "SELECT p.prodID,p.prodDesc,p.prodStatus,p.prodOHQuantity, getProductRemaining(p.prodID) as sold,p.prodPrice FROM product p";
$rsProduct = mysql_query($query_rsProduct, $connSIMS) or die(mysql_error());
$row_rsProduct = mysql_fetch_assoc($rsProduct);
$totalRows_rsProduct = mysql_num_rows($rsProduct);
$query_rsGeneral = "SELECT printReceipt,askEntrusted,panel FROM general_ LIMIT 0,1";
$rsGeneral = mysql_query($query_rsGeneral, $connSIMS) or die(mysql_error());
$row_rsGeneral = mysql_fetch_assoc($rsGeneral);
$totalRows_rsGeneral = mysql_num_rows($rsGeneral);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SIMS</title>
<?php include_once('menu.php'); ?>
<script>

$(document).ready(function () {
	$('#sell').addClass('active');
	$('#sell_menu').show();
  var product = $('#product').dataTable( {
			"scrollY":        "200px",
			"scrollCollapse": false,
			"paging":         false,
			"order": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "fetch/server-side.php",
			"fnRowCallback": function (nRow, aData, iDisplayIndex) {
				$(nRow).removeClass('selected');
				if (iDisplayIndex == 0) {
					$(nRow).addClass('selected');
				}
			}
	});
  $('#product tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
      $(this).removeClass('selected');
    }
    else {
      product.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });
  $('#type').change(function() {
   	  if($(this).val() == 'cash'){
		$('#tend').val($('#total').html().replace(/,/g, "")).prop('disabled',false).focus();
		$('#title').html('Amount Tendered:');
	  }
	  else{
		  if($(this).val()=='panel'){
			  
			  $('#title').html('Amount To Give:');
			  $.ajax({
          url: 'fetch/get-general.php',
          dataType:'json',
          success: function(s){
            console.log(s);
            //alert(new String((new Number($('#total').html().replace(/,/g, ""))-(new Number($('#total').html().replace(/,/g, ""))*new Number(new Number(s[0][19])*0.01))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#tend').val(new String(new Number($('#total').html().replace(/,/g, ""))-(new Number($('#total').html().replace(/,/g, ""))*new Number(new Number(s[0][19])*0.01)).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          },
          error: function(ss){
            console.log(ss);
            alert('Error: '.ss);
          }
          });
			  
		  }
	  }
   });
});
</script>
<script src="media/js/jquery-ui.js"></script>
<link href="media/css/jquery-ui.css" rel="stylesheet" />
<script src="media/js/shortcut.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/popup.js"></script>

<script src="media/js/sales.js"></script>
<link href="media/css/sales.css" rel="stylesheet" />

<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>

<script src="media/js/sales.dataTable.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/style.css" rel="stylesheet" />

<span id="printReceipt" style="display:none;visibility:0"><?php echo $row_rsGeneral['printReceipt'];?></span>
<span id="askEntrusted" style="display:none;visibility:0"><?php echo $row_rsGeneral['askEntrusted'];?></span>
<input type="hidden" value="<?php echo $row_rsGeneral['panel'];?>" id="panel1">
<input type="hidden" id="entrusted_person_name" />
<span id="SALEIDPRINT" style="display:none;visibility:0"></span>
<style>
/*.dataTables_scrollHeadInner {
	width:100% !important;
}*/
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
#lookup .dataTables_scrollHeadInner th,#product td:nth-child(4) {
	text-align:center;
}
/*#lookup .dataTables_scrollHeadInner th:nth-child(1), #product td:nth-child(1){
	width:15% !important;
}*/
#product td:nth-child(6),#product td:nth-child(5){ 
  text-align:right;
}

#product td:nth-child(7){ 
  display:none;
}

#product td:nth-child(5),#customer11 td:nth-child(3),#customer11 td:nth-child(4){
	text-align:right;
}
#lookup .btn, #customer_pop .btn {
	background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
	padding: 4px 5px;
	margin-left: 5px;
	font-size: 13px;
	font-family: 'Ubuntu';
  	font-weight: normal;
}

</style>

<div style="display:none;opacity:0">
  <input type="hidden" id="data-measurement">
  <input type="hidden" id="data-conversion">
  <input type="hidden" id="data-unit">
  <input type="hidden" id="data-price">
  <input type="hidden" id="sum-tend">
</div>
<div style="padding:20px;padding-top:10px" id="body" class="hidden-print">
	<div style="display:none" id="parent">sell</div>
	<div style="width:50%;float: left">
        <table width="100%" id="input">
          <tr>
            <td><input type="text" id="id" class="form-control" placeholder="Product ID" /></td>
            <td><input type="text" id="price" disabled="disabled" class="form-control" placeholder="Unit Price" style="text-align:right"/></td>
            <td><input type="text" id="qty" class="form-control" placeholder="Quantity" style="text-align:right"/><!--<input type="hidden" id="discountP" />-->
            <span id="qtyy" style="display:none"></span><span id="qtyType" style="display:none"></span></td>
          </tr>
          <tr>
            <td colspan="3"><input type="text" disabled="disabled" id="desc" class="form-control" placeholder="Item Desription" /></td>
          </tr>
        </table>
    </div>
    <div style="width:50%;float: left">
    	<div style="background:#222;margin-left: 30px;color: white;padding-left: 5px;padding-right: 5px;height: 90px;border-radius:4px">
        	<div style="font-size: 22px; font-family: -webkit-body; text-transform: uppercase;position: absolute;font-weight:bold">Net Amount</div>
            <div id="total">0.00</div>
        </div>
    </div>
    <div id="list">
    	<div id="head">
        	<table width="100%" id="table_head">
              <tr>
                <td width="4%">Unit</td>  
                <td width="12%">Product ID</td>
                <td width="35%">Item Description</td>
                <td width="10%">Unit Price</td>
                <td width="8%">Quantity</td>
                <td style="border:0" width="10%">Net Price</td>
                <td style="display:none" >Quantity</td>
              </tr>
            </table>
        </div>
        <div id="list1" style="height:311px;overflow:auto">
        	<table width="100%" id="list2" style="font-family:calibri;font-size:16px">
            </table>
        </div>
    </div> <!--list-->
	<div style="width:60%;float:left;margin-top:10px">
    	<div style="padding:5px;color:inherit">
        	<table width="100%" id="cust_info">
              <tr>
                <td width="10%">Customer</td>
                <td width="15%"><input type="text" style="text-align:center" class="form-control" placeholder="Customer ID" id="custID"/></td>
                <td><button class="btn" style="padding: 0.5em 1.5em;" id="customer_but" data-modal-id="customer_pop">...</button></td>
                <td width="40%"><input type="text" class="form-control" placeholder="Customer Name" id="name" disabled="disabled"/></td>
                <td><input type="text" class="form-control" placeholder="Credit" style="text-align:right" id="credit" disabled="disabled"/></td>
                <td><input type="text" class="form-control" placeholder="Credit Limit" style="text-align:right" id="limit" disabled="disabled"/></td>
              </tr>
            </table>
        </div>
        <div id="buttons">
        	<a class="btn" id="add_item">Add Item<span id="key">F1</span></a>
            <a class="btn" data-modal-id="lookup" id="look">Products<span id="key">F2</span></a>
            <a class="btn" data-modal-id="edit_qty_popup">Edit Qty<span id="key">F3</span></a>
            <!--<a class="btn" data-modal-id="entrusted_popup">Edit %<span id="key">F4</span></a>-->
            <a class="btn" data-modal-id="payment_popup">Payment<span id="key">F5</span></a>
            <a class="btn" id="delete">Remove<span id="key">F6</span></a>
            <a class="btn" id="refresh">Clear<span id="key">F7</span></a>
            <a class="btn" id="save">Save<span id="key">F8</span></a>
        </div>
    </div>
    <div style="float:left;width:40%;margin-top:10px;background-color:#222;padding-left:10px;padding-right:5px;padding-top:5px;padding-bottom:5px">
    	<div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Product :</span>
            <span style="float:right;font-size: 20px !important;font-family: -webkit-body;color: whitesmoke;" id="sum-prodName"></span>
        </div>
        
        <div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Price * Quantity :</span>
            <span style="float:right;font-size: 22px !important;font-family: -webkit-body;color: whitesmoke;" id="sum-pricexqty"></span>
        </div>
        <div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Total :</span>
            <span style="float:right;font-size: 22px !important;font-family: -webkit-body;color: #00cc00;" id="sum-prodTotal"></span>
        </div>
    </div>
    <div id="percent_popup" class="modal-box" style="width:20%;display:none !important">
    	<header>
          <h3>Discount</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
        	<span style="  text-shadow: 1px 0px black;">Discount:</span>
         	<div><input type="text" id="percent" class="form-control" /></div>
            <div style="margin-top:15px;text-align:center;"><a href="#" id="percent_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a></div>
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
           <table width="100%">
             <tr>
             	<td style="width:42%;text-shadow: 1px 0px black;">Payment Type:</td>
                <td><select id="type" class="form-control" style="width:88%"><option value="cash">Cash</option><option value="credit">Credit</option><option value="panel">Panel</option></select></td>
             </tr>
           </table>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="pay_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
                <a href="#" id="paycancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
            </div>
        </div>
    </div>
    <div id="pay_details" style="display:none">
    	<span id="type_det"></span>
        <span id="value"></span>
        <span id="pay_stats"></span>
    </div>
    <div id="edit_qty_popup" class="modal-box" style="width:20%">
    	<header>
          <h3>Edit Quantity</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
        	<span style="  text-shadow: 1px 0px black;">Quantity</span>
         	<div><input type="text" id="qty_edit" class="form-control" /></div>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="qty_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
                <a href="#" id="qtycancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
            </div>
        </div>
    </div>
    <div id="edit_disc_popup" class="modal-box" style="width:20%">
    	<header>
          <h3>Edit Discount</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
        	<span style="  text-shadow: 1px 0px black;">Percentage</span>
         	<div><input type="text" id="percent_edit" class="form-control" /></div>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="percent-edit_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
                <a href="#" id="percent-cancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
            </div>
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
    <div id="lookup" class="modal-box" style="width:65%;">
    	<header>
          <h3>Product Lookup</h3>
        </header>
        <div class="modal-body">
        	<table id="product" width="100%">
                <thead>
                	<tr>
                        <th width="8%">Unit</th>
                        <th width="15%" style="width:15% !important">Product ID</th>
                        <th>Item Description</th>
                        <th width="5%" style="width:5% !important">Status</th>
                        <th width="13%" style="width:13% !important">Stock</th>
                        <th width="13%" style="width:13% !important">Price</th>
                        <th style="display:none">&nbsp;</th>
                        <!--<th width="15%" style="width:15% !important">Product ID</th>
                        <th>Item Description</th>
                        <th width="5%" style="width:5% !important">Status</th>
                        <th width="10%" style="width:10% !important">Stock</th>-->
                	</tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            
        </div>
    </div>
    <!--<div id="lookup" class="modal-box" style="width:75%;">
      <header>
        <h3>Product Lookup</h3>
      </header>
      <style>
        #product td:nth-child(1){
          padding-right:0; 
        }
        #product td:nth-child(2){
          padding-right:15px; 
        }
        #product td:nth-child(3){
          padding-left:3px; 
        }
      </style>
      <div class="modal-body">
        <table id="product1" width="100%">
              <thead>
                <tr>
                      <th width="8%">Unit</td>
                      <th width="15%">Product ID</th>
                      <th>Item Description</th>
                      <th width="5%" style="width:5% !important">Status</th>
                      <th width="10%" style="width:10% !important">Stock</th>
                      <th width="10%" style="width:10% !important">Price</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
          
      </div>
    </div>-->
    <div id="entrusted_popup" class="modal-box" style="width:40%;">
    	<header>
          <h3>Entrusted Person</h3>
        </header>
        <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
            <span id="blank">Please enter a name for entrusted.</span>
            <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
        	<span style="  text-shadow: 1px 0px black;">This Sales Order Is Entrusted To:</span>
         	<div><input type="text" id="entrusted_person" class="form-control alpha" /></div>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="entrusted_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
            </div>
        </div>
    </div>
</div>
<div class="print_info" id="print_info" style="display:none"><h6>Sales Order Saved Successfully</h6><p>Press <kbd>F7</kbd> or Clear Button to create new. Press <kbd>Ctrl+P</kbd> to reprint the Sales Order Confirmation.</p></div>
<div class="print_info" id="expire_info" style="display:none"><h6>Warning!</h6><p>Customer's account will expire on <strong><span id="days"></span></strong> day/s.</p></div>
<div class="print_info" id="customer_info" style="display:none"><h6>Warning!</h6><p>Please select a customer first before clicking this button.</p></div>
<div class="print_info" id="count_info" style="display:none"><h6>Warning!</h6><p>Please select at least one product to checkout before clicking this button.</p></div>
<div class="print_info" id="payment_info" style="display:none"><h6>Warning!</h6><p>Please settle the payments first by clicking Payment Button or by pressing <kbd>F5</kbd> from the keyboard before clicking this button.</p></div>
<div class="print_info" id="select_uom" style="display:none;width:initial">
  <h3>Select Measurement For <br><span id="selectprodID" style="font-weight:bold"></span></h3>
  <p id="choices">
    
  </p>
  <p><a style="float:right;font-size:10px;color:#337ab7;text-decoration:underline" href="#" id="closeParent">Cancel</a></p>
</div>
</body>
</html>
<?php
mysql_free_result($rsProduct);
mysql_free_result($rsGeneral);
 }else
	header("Location: index.php");
	?>
