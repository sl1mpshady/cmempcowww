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
$query = "DELETE FROM po_product_temp_list WHERE accID='".$_SESSION['MM_AccID']."' AND sessionID='".session_id()."'";
mysql_select_db($database_connSIMS, $connSIMS);
mysql_query($query, $connSIMS) or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>StoreSys</title>
<?php include_once('menu.php'); ?>
<script src="media/js/jquery-ui.js"></script>
<link href="media/css/jquery-ui.css" rel="stylesheet" />
<title>SIMS</title>
<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/sales.dataTable.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/add-purchase.js"></script>
<link href="media/css/sales.css" rel="stylesheet" />
<link href="media/css/style1.css" rel="stylesheet" />
<link href="media/css/print.css" rel="stylesheet" media="print" />
</head>
<span id="addPurchase" style="display:none;visibility:0"><?php echo $row_rsAccount['addPurchase'];?></span>
<script>

$(document).ready(function () {
  document.getElementById("date").valueAsDate = new Date();	
  $('#modules').addClass('active');
  $('#modules_menu').show();
  $('#add-purchase_menu').addClass('selected');
  
  
  $(document).on("click","#list tr", function(event){
    if ( $(this).hasClass('active_') ) {
      $(this).removeClass('active_');
      $('#delete,#edit-cost,#edit-freight').attr('disabled',true);
    }
    else {
      $('#list tr.active_').removeClass('active_');
      $(this).addClass('active_');
      $('#delete,#edit-cost,#edit-freight').attr('disabled',false);
    }
    if($(event.target).parents().andSelf().is("#table_head")){
      $('#table_head tr.active_').removeClass('active_');
      $('#delete,#edit-cost,#edit-freight').attr('disabled',true);
    }
  });
  
  $(document).on("click", function(event){
    if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("[data-modal-id='edit_qty_popup']") && !$(event.target).parent().andSelf().is("[data-modal-id='edit_cost_popup']") && !$(event.target).parent().andSelf().is(".bootbox") && !$(event.target).parent().andSelf().is("#delete") && !$(event.target).parent().andSelf().is("[data-modal-id='edit_qty_popup']")  && !$(event.target).parent().andSelf().is(".modal-content") && !$(event.target).parent().andSelf().is("[data-modal-id='edit_freight_popup']") && !$(event.target).parents().andSelf().is(".modal-box") && !$(event.target).parents().andSelf().is(".modal-overlay") && !$(event.target).parents().andSelf().is(".modal-dialog") && !$(event.target).parents().andSelf().is(".modal-backdrop")){
      $('#list tr.active_').removeClass('active_');
    }
    /*if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("a.btn") && !$(event.target).parent().andSelf().is("#qty_edit") && !$(event.target).parent().andSelf().is("#cost_edit") && !$(event.target).parent().andSelf().is(".modal-overlay") && !$(event.target).parent().andSelf().is(".modal-box")){
        $('#list tr.active_').removeClass('active_');
    }*/
  });
});	
</script>
<style>
.modal {
  top:20%
}
.dataTables_scrollHead {
background-image: -webkit-linear-gradient(#ffffff 0%, gainsboro 158%);
background-image: -o-linear-gradient(#ffffff 0%, gainsboro 158%);
background-image: linear-gradient(#ffffff 0%, gainsboro 158%);
}
/*.dataTables_scrollHeadInner {
  width:98.4% !important;
  width:100% !important;
}*/
.dataTables_scroll {
    box-shadow: 0px 0px 2px gray;
}
/*.btn {
    background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
    padding: 2px 5px;
    margin-left:5px;
    font-size:12px;
}*/
.form-control {
  height:28px;
}
.uneditable-input, .uneditable-textarea {
  color: #999999;
  background-color: #fcfcfc;
  border-color: #cccccc;
  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
  -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
}
#buttons .btn {
  float: left;
  font-size: 15px;
  padding: 3px 10px 3px 10px;
  padding: 3px 4px 3px 4px;
  margin-right: 3px;
  width: 109px;
  width: auto !important;
}
#list2 td:nth-child(1), #list2 td:nth-child(2) {
  text-align:left;
}
#list2 td:nth-child(7), #list2 td:nth-child(4), #list2 td:nth-child(5), #list2 td:nth-child(6) {
  text-align:right;
}
#list2 td:nth-child(3){
  width:inherit;
  text-align:left;
}
#list2 td:nth-child(1){
  width:5%;
}
#list2 td:nth-child(2){
  width:15%;
}

#list2 td:nth-child(4),#list2 td:nth-child(6),#list2 td:nth-child(7){
  width:12%;
}
#list2 td:nth-child(5){
  width:8%;
}
.input-group .form-control {
  font-size: 14px !important;
  text-align: left;
  font-weight: normal;
  height: 28px;
}
#lookup .modal-body, #customer_pop .modal-body {
  padding: 3px !important;
}
#lookup .btn{
  background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
  padding: 4px 5px;
  margin-left: 5px;
  font-size: 13px;
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
fieldset .form-control,input[type=date] {
	font-size: 14px !important;
}
.required {
  font-size:inherit !important;
}
</style>
<body>
<div style="display:none" id="parent">modules</div>
<div style="padding:25px" class="hidden-print" id="hi">
	<div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;"><span id="msg"></span>
    <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
  </div>
  <div style="display:none;opacity:0">
    <input type="hidden" id="edit_unit_data">
    <input type="hidden" id="edit_prodID_data">
    <input type="hidden" id="edit_cost_data">
    <input type="hidden" id="edit_qty_data">
    <input type="hidden" id="edit_freight_data">
    <input type="hidden" id="edit_total_data">
  </div>
	<div style="width:47%;float: left">
    <fieldset>
      <legend>[ Supplier ]</legend>
    	<table width="100%" border="0" id="supp_info">
          <tr>
            <td width="15%" style="text-align:right"><span class="required">*</span> Name:</td>
            <td><input type="text" style="text-align:left;background:transparent" class="form-control ui-autocomplete-input" placeholder="Supplier's Name" id="suppName" autocomplete="off"></td>
            <td>
          </tr>
          <tr>
            <td width="15%" style="text-align:right"> <span class="required">*</span>Address:</td>
            <td><input type="text" style="text-align:left;background:transparent" class="form-control ui-autocomplete-input" placeholder="Supplier's Address" id="suppAddress" autocomplete="off"></td>
            <td>
          </tr>
      </table>
    </fieldset>
  </div>
  <div style="width:47%;float: right;margin-bottom:15px">
    <fieldset>
      <legend>[ Others ]</legend>
      <table width="100%" border="0" id="supp_info">
        <tr>
          <td width="20%" style="text-align:right">Note/Memo:</td>
          <td><input type="text" style="text-align:left;background:transparent" class="form-control ui-autocomplete-input" placeholder="Note" id="note" autocomplete="off"></td>
        </tr>
        <tr>
          <td width="20%" style="text-align:right"><span class="required">*</span> Invoice Number:</td>
          <td>
            <input type="text" style="text-align:left;background:transparent;width:30%;text-align:left;float:left" placeholder="Invoice Number" class="form-control ui-autocomplete-input" id="invoicenum" autocomplete="off">
              <span style="float:right;text-align:right"> <span class="required">*</span> Invoice Date: <input type="date" id="date" class="form-control-date" style="width:60%;margin:auto" /></span>
          </td>
        </tr>
      </table>
      </fieldset>
  </div>
  <span id="status" style="display:none"></span>
  <div style="border-radius:4px">
    <div id="list" style="margin-top:0;height:auto">
          <div id="head">
            <input type="hidden" id="meas" />
              <table width="100%" id="table_head">
                <tr>
                  <td width="5%">Unit</td>
                  <td width="15%">Product ID</td>
                  <td width="">Item Description</td>
                  <td width="12%">Unit Cost</td>
                  <td width="8%">Quantity</td>
                  <td width="12%">Freight</td>
                  <td width="12%">Sub Total</td>
                </tr>
              </table>
          </div>
          <div id="list1" style="height:300px;overflow:auto">
              <table width="100%" id="list2" style="font-family:calibri;font-size:16px">
              </table>
          </div>
      </div>
      <div style="width:50%;float: left;margin-top:17px;">
          <table width="100%" id="input">
            <tr>
              <td width="40%">
                <input type="text" id="id" class="form-control" placeholder="Product ID" />
                <input type="hidden" id="sub_prodID" />
                  <input type="hidden" id="sub_prodQty" />
                  <input type="hidden" id="sub_prodName"  />
                  <input type="hidden" id="data-measurement" />
                  <input type="hidden" id="data-conversion" />
                  <input type="hidden" id="data-unit" />
                  
              </td>
              <td><input type="text" id="price" class="form-control" placeholder="Unit Cost" style="text-align:right"/></td>
              <td width="14%"><input type="text" id="qty" class="form-control" placeholder="Quantity" style="text-align:right"/>
              <span id="qtyy" style="display:none"></span></td>
              <td><input type="text" id="freight" class="form-control" placeholder="Freight" style="text-align:right"/></td>
            </tr>
            <tr>
              <td colspan="4"><input type="text" disabled="disabled" id="desc" class="form-control" placeholder="Item Desription" /></td>
            </tr>
          </table>
      </div>
      <div style="width:50%;float: right;margin-top:3px;">
        <div style="width:100%;float:right">
            <span class="input-xlarge uneditable-input">
              <table style="width:75% !important;float:right;border:1px solid #CCC;color: #555;    background-color: #fff;    background-image: none;    border: 1px solid #ccc;    border-radius: 4px;">
                  <td style="width:14%">Total Qty:</td>
                  <td style="text-align:right;width:22%;" id="tot-qty">0.00</td>
                  <td style="width:18%">Total Freight:</td>
                  <td style="width:15%;text-align:right" id="tot-freight">0.00</td>
                  <td>Total:</td>
                  <td style="text-align:right;padding-right:20px;" id="total1">0.00</td>
              </table>
              </span>
          </div>
          <div style="float:right;width:100%;margin-top:3px;" id="buttons">
              <a class="btn" id="add">Add Item<span id="key">F1</span></a>
              <a class="btn" data-modal-id="lookup" id="look">Products<span id="key">F2</span></a>
              <a class="btn" style="display:none" data-modal-id="print_popup">Print</a>
              <a class="btn" id="delete" disabled="disabled">Remove<span id="key">F3</span></a>
              <a class="btn" id="save">Save<span id="key">F4</span></a>
              <a class="btn" data-modal-id="edit_qty_popup" id="edit-qty">Quantity<span id="key">F5</span></a>
              <a class="btn" data-modal-id="edit_cost_popup" disabled="disabled" id="edit-cost">Cost<span id="key">F6</span></a>
              <a class="btn" data-modal-id="edit_freight_popup" disabled="disabled" id="edit-freight">Freight<span id="key">F7</span></a>
              <a class="btn" id="refresh">Clear<span id="key">F8</span></a>
          </div>
      </div>	
	</div>
  <div id="lookup" class="modal-box" style="width:75%;">
    <header>
        <h3>Product Lookup</h3>
      </header>
      <style>
        #product td:nth-child(4){
          text-align:center;
        }
        #product td:nth-child(5),#product td:nth-child(6){
          text-align:right;
        }
        
      </style>
      <div class="modal-body">
        <table id="product">
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
  </div>
  <div id="edit_qty_popup" class="modal-box" style="width:20%">
    <header>
        <h3>Update Quantity</h3>
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
  <div id="edit_freight_popup" class="modal-box" style="width:20%">
    <header>
        <h3>Update Freight</h3>
      </header>
      <div class="modal-body" style="text-align:center;padding:15px;">
        <span style="  text-shadow: 1px 0px black;">Freight</span>
        <div><input type="text" id="freight_edit" class="form-control" /></div>
          <div style="margin-top:15px;text-align:center;">
            <a href="#" id="freight_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
              <a href="#" id="freightcancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
          </div>
      </div>
  </div>
  <div id="edit_cost_popup" class="modal-box" style="width:20%">
    <header>
        <h3>Update Cost</h3>
      </header>
      <div class="modal-body" style="text-align:center;padding:15px;">
        <span style="  text-shadow: 1px 0px black;">Cost</span>
        <div><input type="text" id="cost_edit" class="form-control" /></div>
          <div style="margin-top:15px;text-align:center;">
            <a href="#" id="qty_button1" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
              <a href="#" id="qtycancel_button1" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
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
<script>
$(function(){
	var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('button[data-modal-id]').click(function(e) {
		e.preventDefault();
		if($('#list tr').hasClass('active_')){
			//$('#oldID').html($('#list .active_ td').eq(0).text());
			
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			//$(".js-modalbox").fadeIn(500);
			var modalBox = $(this).attr('data-modal-id');
			if(modalBox == 'lookup'){
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
						s[5]=='Active' ? $('#status').prop('checked','checked') : '';
					}
				});
			}
			$('#'+modalBox+' input').focus();
			$('#'+modalBox).fadeIn($(this).data());
      $('#'+modalBox).fadeIn(function(){
				$('#product').DataTable().columns.adjust().draw();
			});
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
	/*$('#qty,#qty_edit').keypress(function(e) {
	  if ($.isNumeric(String.fromCharCode(e.keyCode))){
		  if(new Number($(this).val() + String.fromCharCode(e.keyCode)) <= 0)
			  return false;
		  else
			  return true;
	  }
	  return false;
  });
  $('#qty,#qty_edit').keyup(function() {
	  $(this).val(function(index, value) {
		  return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		});
   });*/
  $('#qty,#qty_edit').autoNumeric('init',{'vMin':0,'mDec':0});
  $('#price,#cost_edit,#freight').autoNumeric('init',{'vMin':0});
  $('#stock').keyup(function() {
	  $(this).val(function(index, value) {
		  return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		});
   });
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
	
	$('#edit-popup .close').click(function() {
		$('.modal-body').css('padding-top','2em');
	});
});
</script>
<script src="media/js/product-popup.js"></script>
<div id="poID" style="display:none"></div>
</div>
<div class="print_info" style="display:none" id="save_info"><h6>Purchase Order Saved Successfully</h6><p>Press <kbd>F7</kbd> or Clear Button to create new. Press <kbd>Ctrl+P</kbd> to print the Purchase Order`s Confirmation.</p></div>
<div class="print_info" id="count_info" style="display:none"><h6>Warning!</h6><p>Please select at least one product to add to purchase list before clicking this button.</p></div>
<div class="print_info" id="select_uom" style="display:none;width:initial">
  <h3>Select Measurement For <br><span id="selectprodID" style="font-weight:bold"></span></h3>
  <p id="choices">
    
  </p>
  <p><a style="float:right;font-size:10px;color:#337ab7;text-decoration:underline" href="#" id="closeParent">Cancel</a></p>
</div>
</body>
</html>
<?php
}
else
header("Location: index.php");
?>
