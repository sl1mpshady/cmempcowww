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
   $query = "DELETE FROM pd_temp_list WHERE accID='".$_SESSION['MM_AccID']."' AND sessionID='".session_id()."'";
   mysql_select_db($database_connSIMS, $connSIMS);
   mysql_query($query, $connSIMS) or die(mysql_error());
   mysql_select_db($database_connSIMS, $connSIMS);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SIMS</title>
<?php include_once('menu.php'); ?>
<script src="media/js/jquery-ui.js"></script>
<link href="media/css/jquery-ui.css" rel="stylesheet" />
<script src="media/js/shortcut.js"></script>
<script src="media/js/add-deduction-popup.js"></script>
<script src="media/js/add-deduction.js"></script>
<link href="media/css/add-deduction.css" rel="stylesheet" />

<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/sales.dataTable.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/style.css" rel="stylesheet" />

<span id="printReceipt" style="display:none;visibility:0"><?php echo $row_rsGeneral['printReceipt'];?></span>
<span id="askEntrusted" style="display:none;visibility:0"><?php echo $row_rsGeneral['askEntrusted'];?></span>
<input type="hidden" id="entrusted_person_name" />
<span id="SALEIDPRINT" style="display:none;visibility:0"></span>
<style>

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
#lookup .btn, #customer_pop .btn {
	background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
	padding: 4px 5px;
	margin-left: 5px;
	font-size: 13px;
	font-family: 'Ubuntu';
  	font-weight: normal;
}
#product td:nth-child(6){
	display:none;
}
</style>
<script>

$(document).ready(function () {
	$('#modules').addClass('active');
	$('#modules_menu').show();
	$('#add-payroll_deduction').addClass('selected');
});
</script>

<div style="padding:20px;padding-top:10px" id="body" class="hidden-print">
	<div style="display:none" id="parent">modules</div>
	<div style="width:50%;float: left">
        <table width="100%" id="cust_info">
          <tr>
            <td><input type="text" id="custID" class="form-control" placeholder="Customer ID" /></td>
            <td><input type="text" id="credit" disabled="disabled" class="form-control" placeholder="Credit" style="text-align:right"/></td>
            <td><input type="text" id="deduction" class="form-control" placeholder="Deduction" style="text-align:right"/>
            <span id="qtyy" style="display:none"></span></td>
          </tr>
          <tr>
            <td colspan="3"><input type="text" disabled="disabled" id="name" class="form-control" placeholder="Complete Name" /></td>
          </tr>
        </table>
    </div>
    <div style="width:50%;float: left">
    	<div style="background:#222;margin-left: 30px;color: white;padding-left: 5px;padding-right: 5px;height: 90px;border-radius:4px">
        	<div style="font-size: 22px; font-family: -webkit-body; text-transform: uppercase;position: absolute;font-weight:bold">Total Deduction</div>
            <div id="total">0.00</div>
        </div>
    </div>
    <div id="list" style="height:auto;">
    	<div id="head">
        	<table width="100%" id="table_head">
              <tr>
                <td width="10%">Customer ID</td>
                <td>Name</td>
                <td width="10%">Gross Credit</td>
                <td width="10%">Deduction</td>
                <td width="10%">Net Credit</td>
              </tr>
            </table>
        </div>
        <div id="list1" style="height:333px;overflow:auto">
        	<table width="100%" id="list2" style="font-family:calibri;font-size:16px">
            </table>
        </div>
    </div> <!--list-->
	<div style="width:60%;float:left;margin-top:23px">
        <div id="buttons">
        	<a class="btn" id="add_customer">Add Customer<span id="key">F1</span></a>
            <a class="btn" data-modal-id="customer_pop" id="customer_but">Customer<span id="key">F2</span></a>
            <a class="btn" data-modal-id="edit-deduction_popup">Edit Deduction<span id="key">F3</span></a>
            <a class="btn" id="import">Read File<span id="key">F4</span></a>
            <a class="btn" id="delete">Remove<span id="key">F5</span></a>
            <a class="btn" id="refresh">Clear<span id="key">F6</span></a>
            <a class="btn" id="save">Save<span id="key">F7</span></a>
        </div>
    </div>
    <form id="fileForm" enctype="multipart/form-data" style="display:none">
      <input type="file" name="file" id="file" style="overflow:hidden;float: left;height: 27px;opacity:0;cursor:pointer" class="nohistory image-placeholder image-placeholder-show">
    </form>
    <div style="float:left;width:40%;margin-top:10px;background-color:#222;padding-left:10px;padding-right:5px;padding-top:5px;padding-bottom:5px">
    	<div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Customers</span>
            <span style="float:right;font-size: 22px !important;font-family: -webkit-body;color: #00cc00;" id="sum-customer">0</span>
        </div>
        
        <div class="summary">
        	<span style="float:left;font-size: 22px;font-family: -webkit-body;color: white;">Total Customers Credit</span>
            <span style="float:right;font-size: 22px !important;font-family: -webkit-body;color: #00cc00;" id="sum-credit">0.00</span>
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
                <td><select id="type" class="form-control" style="width:88%"><option value="cash">Cash</option><option value="credit">Credit</option></select></td>
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
        <span id="deduction_stats"></span>
    </div>
    <div id="edit-deduction_popup" class="modal-box" style="width:20%">
    	<header>
          <h3>Edit Deduction</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
        	<span style="  text-shadow: 1px 0px black;">Deduction</span>
         	<div><input type="text" id="deduction_edit" class="form-control" /></div>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="deduction_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
                <a href="#" id="deductioncancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
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
    <div id="lookup" class="modal-box" style="width:75%;">
    	<header>
          <h3>Product Lookup</h3>
        </header>
        <div class="modal-body">
        	<table id="product" width="100%">
                <thead>
                	<tr>
                        <th width="15%" style="width:15% !important">Product ID</th>
                        <th>Item Description</th>
                        <th width="5%" style="width:5% !important">Status</th>
                        <th width="10%" style="width:10% !important">Stock</th>
                        <th width="10%" style="width:10% !important">Price</th>
                        <th style="display:none">&nbsp;</th>
                	</tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            
        </div>
    </div>
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
<div id="print_popup" class="modal-box" style="width:90%;top:10px;">
  <header>
    <h3>Print Preview<span title="Close" class="glyphicon glyphicon-remove-sign js-modal-close" aria-hidden="true" style="float:right;font-size: 17px;cursor: pointer;"></span></h3>
  </header>
  <div class="modal-body" style="text-align:center;padding:0px;display:initial;height:initial">
    <iframe id="report" src="" frameborder="0" style="width:100%;height:100%"></iframe>
  </div>
</div>

<div class="visible-print" style="text-align:center;width:150%">
</div>
<div class="print_info" id="print_info" style="display:none"><h6>Credit Deduction Saved Successfully</h6><p>Press <kbd>F6</kbd> or Clear Button to create new. Press <kbd>Ctrl+P</kbd> to reprint the Sales Order Confirmation.</p></div>
<div class="print_info" id="expire_info" style="display:none"><h6>Warning!</h6><p>Customer's account will expire on <strong><span id="days"></span></strong> day/s.</p></div>
<div class="print_info" id="save_info" style="display:none"><h6>Please wait while the system is saving the transaction.</h6><p>Please don't do anything while this message appear on screen.</p></div>
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
 }else
	header("Location: index.php");
	?>
