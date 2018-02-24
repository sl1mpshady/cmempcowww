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
<title></title>

<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>

<script>
$(document).ready(function () {
	//$('.print_info').fadeIn(1000).fadeOut(2500);
	$('#setup').addClass('active');
	$('#setup_menu').show();
	$('#general_menu').addClass('selected');		
    $('#salesPanel,#salesTax').autoNumeric('init',{'vMin':0,'mDec':2,'vMax':99.99});
    $('#salesReturn').autoNumeric('init',{'vMin':0,'mDec':0});
});	
</script>
<style>
textarea {
    resize: none;
}
.btn {
    background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
    padding: 2px 5px;
    margin-left:5px;
    font-size:12px;
}
label>span {
	margin-right:22px;
}
.form-control {
	width: 90%;
    margin-left: 20px;
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
  border: 0 !important;
}
  
  fieldset fieldset {
	float:left;
	margin:19px;
}
td:nth-child(1){
	text-align:right;
	/*font-weight: bold;*/
  	width: 40%;
}
label {
	font-weight:normal;
}
td:nth-child(2) {
  padding: 5px;
}
#receipt-box td:nth-child(1){
	text-align:left;
	font-weight: normal;
  	width: 17%;
}
#sales-table td:nth-child(1){
 	width:50%;
}
.breadcrumb {
  padding: 12px 15px;
  margin-bottom: 20px;
  list-style: none;
  border-radius: 4px;
  color: #0088cc;
  text-decoration: none;
  background:none !important;
  cursor:pointer;
}
.breadcrumb>.active {
  color: #777;
  background-color: transparent;
}
.image-placeholder {
  color: #555;
  position: relative!important;
}
.image-placeholder-show {
  display: inline-block;
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
  height:auto;
}
.image-placeholder input[type="file"] {
  position: absolute;
  top: 0;
  right: 0;
  float: left;
  margin: 0;
  opacity: 0;
  width: 100%;
  height: 107px;
  filter: alpha(opacity=0);
  border: 0;
  cursor: pointer;
  cursor: hand;
  font-size: 13px;
  z-index: 100;
  cursor:pointer;
}
input[type="file"]{
	cursor:pointer
}
#submit{
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
#receipt-box .form-control {
	margin:0;
	width:100%;
}
#receipt-box td td:nth-child(1) {
	width:42%;
	text-align:right;
	padding-right:25px;
}
#receipt-box td td:nth-child(2) {
	text-align:right;
}
#receipt-prev td:nth-child(1){
	text-align:left;
	font-weight:normal;
}
#receipt-prev td:nth-child(2){
	text-align:right;
}
</style>
<body>
<div style="display:none" id="parent">setup</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;margin-bottom:25px" id="header2">
    	General 
        <span style="position:absolute;font-size:15px;">
            <ul class="breadcrumb">
                <li  class="active" id="company">Company</li>
                <li id="sales">Sales Order</li>
                <li id="officers">Officers</li>
                <li id="receipt">Receipt Text</li>
            </ul>
        </span>
    </div>
    <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<span id="blank">Business name can not be blanked.</span>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div id="sales-error" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<span id="blank">Please fill in the red boxes below.</span>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="text-align:-webkit-center">
        <fieldset style="width:50%;padding-bottom:10px" id="company-box">
            <legend>[ Company Details ]</legend>
            <div>Enter the basic information about your company</div>
            <table width="100%">
                <tr>
                    <td><span class='required'>*</span> Business Name:</td>
                    <td><input type="text" class="form-control" id="busName" /></td>
                </tr>
                <tr>
                    <td>Registered Number:</td>
                    <td><input type="text" class="form-control" id="regNo" /></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><textarea rows="3" id="address" class="form-control"></textarea></td>
                </tr>
                <tr>
                    <td>Contact Details:</td>
                    <td><textarea rows="2" id="contact" class="form-control"></textarea></td>
                </tr>
                <tr>
                    <td>Logo Image File:</td>
                    <td><input type="text" class="form-control" id="image-loc" style="width:80%;float:left"/>
                    <span class="im_upload btn">
                    <input type="file" id="photo" style="overflow:hidden;float: left;height: 27px;opacity:0" class="nohistory image-placeholder image-placeholder-show">
                    </span></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width:50%;display:none" id="sales-box">
        	<legend>[ Sales Order ]</legend>
            <table width="100%" id="sales-table">
            	<tr>
                	<td>Sales Tax or VAT (%):</td>
                    <td><input type="text" class="form-control" id="salesTax" style="width:20%" value="00.00" /></td>
                </tr>
                <tr>
                	<td>Day(s) of Return:</td>
                    <td><input type="text" class="form-control" id="salesReturn" style="width:20%" value="0" /></td>
                </tr>
                <tr>
                	<td>Panel Price Discount (%):</td>
                    <td><input type="text" class="form-control" id="salesPanel" style="width:20%" value="0" /></td>
                </tr>
                <tr>
                	<td>Print Receipt:</td>
                    <td style="">
                    	<label style="margin-left:40px" class="radio"><span><input type="radio" name="printOpt" id="printOpt1" value="manual" checked>Manual </span><span><input type="radio" name="printOpt" id="printOpt2" value="automatic" checked>Automatic</span></label>
                    	
                    </td>
                </tr>
                <tr>
                	<td>Print Duplicate Copies of Receipt for Cash:</td>
                    <td><label style="margin-left:40px" class="radio"><span><input type="radio" name="cashDupl" id="cashDupl1" value="true">True </span><span><input type="radio" name="cashDupl" id="cashDupl2" value="false" checked>False</span></label></td>
                </tr>
                <tr>
                	<td>Print Duplicate Copies of Receipt for Credit:</td>
                    <td><label style="margin-left:40px" class="radio"><span><input type="radio" name="creditDupl" id="creditDupl1" value="true" checked>True </span><span><input type="radio" name="creditDupl" id="creditDupl2" value="false">False</span></label></td>
                </tr>
                <tr>
                	<td>Ask Who's Entrusted for Credit:</td>
                    <td><label style="margin-left:40px" class="radio"><span><input type="radio" name="entrusted" id="entrusted1" value="true" checked>True </span><span><input type="radio" name="entrusted" id="entrusted2" value="false">False</span></label></td>
                </tr>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width:50%;display:none" id="officers-box">
        	<legend>[ CMEMPCO Officers ]</legend>
            <div>Enter the names of the officers necessary for reports</div>
            <table width="100%">
                <tr>
                    <td>President:</td>
                    <td><input type="text" class="form-control" id="president" /></td>
                </tr>
                <tr>
                    <td>Operation Manager:</td>
                    <td><input type="text" class="form-control" id="operationManager" /></td>
                </tr>
                <tr>
                    <td>Manager:</td>
                    <td><input type="text" class="form-control" id="manager" /></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width:50%;display:none;padding-bottom:20px" id="receipt-box">
        	<legend>[ Comments ]</legend>
            <table width="100%" id="receipt-table1">
            	<table>
            	<tr>
                	<td>Note Comment:</td>
                    <td>&nbsp;</td>
                    <td><textarea rows="3" id="note" class="form-control" style="max-height:323px"></textarea></td>
                </tr>
                <tr>
                	<td>Alignment:</td>
                    <td>
                    	<select id="noteAlignment" class="form-control">
                        	<option value="left">Left</option>
                            <option value="center">Center</option>
                            <option value="right">Right</option>
                        </select>
                    </td>
                    <td>
                    	<table>
                    	<tr>
                        	<td>Font Style:</td>
                            <td>
                            	<select id="noteFontStyle" class="form-control">
                                    <option value="normal">Normal</option>
                                    <option value="bold">Bold</option>
                                    <option value="italic">Italic</option>
                                </select>
                            </td>
                        </tr>
                        </table>
                    </td>
                </tr>
             </table>
             <div style="border-bottom:1px solid gainsboro;margin: 18px 0px;"></div>
             <table  width="100%" id="receipt-table2">
                <tr>
                	<td>Foot Comment:</td>
                    <td>&nbsp;</td>
                    <td><textarea rows="3" id="foot" class="form-control" style="max-height:323px"></textarea></td>
                </tr>
                <tr>
                	<td>Alignment:</td>
                    <td>
                    	<select id="footAlignment" class="form-control">
                        	<option value="left">Left</option>
                            <option value="center">Center</option>
                            <option value="right">Right</option>
                        </select>
                    </td>
                    <td>
                    	<table>
                    	<tr>
                        	<td>Font Style:</td>
                            <td>
                            	<select id="footFontStyle" class="form-control">
                                    <option value="normal">Normal</option>
                                    <option value="bold">Bold</option>
                                    <option value="italic">Italic</option>
                                </select>
                            </td>
                        </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div><button class="btn" data-modal-id="preview">Preview Receipt</button></div>
        </fieldset>
        <div style="margin-top:10px"><button class="btn" style="font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);" id="submit">Submit</button></div>
    </div>
    <div id="preview" class="modal-box" style="width:85mm">
    	<div class="modal-body" style="padding: 5mm 5mm;">
        	<div style="text-align:center" id="preview_logo"></div>
        	<div style="text-align:center" id="preview_busName"></div>
            <div style="text-align:center" id="preview_address"></div>
            <div style="text-align:center" id="preview_regNum"></div>
            <div style="text-align:center" id="preview_contact"></div>
            <br />
            <div style="text-align:center">Sales Order No.</div>
            <br />
            <div>Served By: <span id="server"></span></div>
            <div>Served To: <span id="server"></span></div>
            <div id="preview_entrusted">Entrusted To: <span id="server"></span></div>
            <br />
            <div style="border-bottom:1px dashed black"></div>
            <table width="100%">
            	<tr>
                	<td style="text-align:left">Item</td>
                    <td style="text-align:right" width="30%">Total</td>
                </tr>
            </table>
            <div style="border-bottom:1px dashed black"></div><br />
            <div>Total of n item(s)</div>
            <table id="receipt-prev">
            	<tr>
                	<td>Vatable Sales</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>VAT <span id="preview_vat"></span>%</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>Total Sale</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>Cash</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td>Change</td>
                	<td>&nbsp;</td>
                </tr>
            </table>
            <div id="datetime">Date & Time:</div>
            <br />
            <div id="preview_note"></div>
            <br />
            <div id="preview_foot"></div>
        </div>
    </div>
</div>
</body>
<div class="print_info" id="success_info" style="display:none"><h6>General Settings Updated Successfully</h6></div>
<script>
	$(function(){
	var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('button[data-modal-id]').click(function(e) {
		e.preventDefault();
		check = true;
		if(check){
			$.ajax({
				url: 'fetch/get-general.php',
				dataType:'json',
				success: function(s){
					console.log(s);
					if(s[0][0]!='false'){
						if(s[0]!=null)
							$('#preview_busName').show().html(s[0][0]);
						else
							$('#preview_busName').hide();
						if(s[0][1]!=null)
							$('#preview_regNum').show().html(s[0][1]);
						else
							$('#preview_regNum').hide();
						if(s[0][2]!=null)
							$('#preview_address').show().html(s[0][2]);
						else
							$('#preview_address').hide();
						if(s[0][3]!=null)
							$('#preview_contact').show().html(s[0][3]);
						else
							$('#preview_contact').hide();
						$('#preview_vat').html(s[0][4]);
						if(s[0][5]=='true')
							$('#preview_entrusted').show();
						else
							$('#preview_entrusted').hide();
					}
				}
			});
			$('#preview_note').css({'text-align':$('#noteAlignment').val(),'font-weight':$('#noteFontStyle').val(),'font-style':$('#noteFontStyle').val()}).html($('#note').val());
			$('#preview_foot').css({'text-align':$('#footAlignment').val(),'font-weight':$('#footFontStyle').val(),'font-style':$('#footFontStyle').val()}).html($('#foot').val());
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(1000, 0.7).css('height',$(window).height());	
			//$(".js-modalbox").fadeIn(500);
			var modalBox = $(this).attr('data-modal-id');
			$('#'+modalBox).fadeIn($(this).data());
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 10;
			$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
			$('#'+modalBox).css("top", view_top);
			$(".js-modal-close, .modal-overlay").click(function() {
					$(".modal-box, .modal-overlay").fadeOut(1000, function() {
						$(".modal-overlay").remove();
					});
			});
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
$(document).ready(function(e) {
	$.ajax({
	  url: 'fetch/get-general.php',
	  dataType:'json',
	  success: function(s){
		  console.log(s);
		  $('#busName').val(s[0][0]);
		  $('#regNo').val(s[0][1]);
		  $('#address').html(s[0][2]);
		  $('#contact').html(s[0][3]);
	  }
	});
	$('#submit').click(function() {
		if($('#company-box').is(':visible')){
			if($('#busName').val()==''){
				$('#busName').parent('td').removeClass('has-success');
				$('#busName').parent('td').addClass('has-error');
				$('#header2').css('margin-bottom','0px');
				$('#box').show();
			}
			else{
				$('#box').hide();
				$('#busName').parent('td').removeClass('has-error');
				$('#busName').parent('td').addClass('has-success');
				$.ajax({
					url: 'save/save.php',
					data: {'general':true,busName:$('#busName').val(),regNum:$('#regNo').val(),address:$('#address').val(),contact:$('#contact').val()},
					dataType:'json',
					error: function(s){
						console.log(s);
						$('#success_info').fadeIn(1000).fadeOut(1500);
					}
				});
			}
		}
		else if($('#sales-box').is(':visible')){
			var check_sales = true;
			if($('#salesTax').val()==''){
				$('#salesTax').parent('td').removeClass('has-success');
				$('#salesTax').parent('td').addClass('has-error');
				check_sales = false;
			}
			else {
				$('#salesTax').parent('td').removeClass('has-error');
				$('#salesTax').parent('td').addClass('has-success');
				
			}
			if($('#salesReturn').val()==''){
				$('#salesReturn').parent('td').removeClass('has-success');
				$('#salesReturn').parent('td').addClass('has-error');
				check_sales = false;
			}
			else {
				$('#salesReturn').parent('td').removeClass('has-error');
				$('#salesReturn').parent('td').addClass('has-success');
				
			}
			if(check_sales){
				$.ajax({
					url: 'save/save.php',
					data: {'general':true,printReceipt:$('input[name=printOpt]:checked').val(),salesTax:$('#salesTax').val(),cashDuplicate:$('input[name=cashDupl]:checked').val(),creditDuplicate:$('input[name=creditDupl]:checked').val(),entrusted:$('input[name=entrusted]:checked').val(),salesReturn:$('#salesReturn').val().replace(/,/g, ""),salesPanel:$('#salesPanel').val()},
					dataType:'json',
					error: function(s){
						console.log(s);
						$('#success_info').fadeIn(1000).fadeOut(1500);
					}
				});
			}		
			else {
				$('#header2').css('margin-bottom','0px');
				$('#sales-error').show();
			}
		}
		else if($('#receipt-box').is(':visible')){
			$.ajax({
				url: 'save/save.php',
				data: {'general':true,note:$('#note').val(),noteAlignment:$('#noteAlignment').val(),noteFontStyle:$('#noteFontStyle').val(),foot:$('#foot').val(),footAlignment:$('#footAlignment').val(),footFontStyle:$('#footFontStyle').val()},
				dataType:'json',
				error: function(s){
					console.log(s);
					$('#success_info').fadeIn(1000).fadeOut(1500);
				}
			});
		}
        else {
            $.ajax({
				url: 'save/save.php',
				data: {'general':true,president:$('#president').val(),operationManager:$('#operationManager').val(),manager:$('#manager').val()},
				dataType:'json',
				error: function(s){
					console.log(s);
					$('#success_info').fadeIn(1000).fadeOut(1500);
				}
			});
        }
	});
	function Hide(){
		$('fieldset').hide();
	}
    $('.breadcrumb li').click(function() {
		$('.breadcrumb li').removeClass('active');
		$(this).addClass('active');
		Hide();
		$('table').removeClass('has-error');
		$('table').removeClass('has-success');
		$('#'+$(this).attr('id')+'-box').show();
		var id = $(this).attr('id');
		$.ajax({
			url: 'fetch/get-general.php',
			dataType:'json',
			success: function(s){
				console.log(s);
				$('#busName').val(s[0][0]);
				$('#regNo').val(s[0][1]);
				$('#address').val(s[0][2]);
				$('#contact').val(s[0][3]);
				$('#salesTax').val(s[0][4]);
				if(s[0][5]=='true')
					$('#entrusted1').prop('checked',true);
				else
					$('#entrusted2').prop('checked',true);
				if(s[0][6]=='manual')
					$('#printOpt1').prop('checked',true);
				else
					$('#printOpt2').prop('checked',true);
				if(s[0][7]=='true')
					$('#cashDupl1').prop('checked',true);
				else
					$('#cashDupl2').prop('checked',true);
				if(s[0][8]=='true')
					$('#creditDupl1').prop('checked',true);
				else
					$('#creditDupl2').prop('checked',true);
				$('#salesReturn').val(s[0][15]);
				$('#note').val(s[0][9]);
				$('#foot').val(s[0][12]);
                $('#president').val(s[0][18]);
                $('#manager').val(s[0][16]);
                $('#operationManager').val(s[0][17]);
                $('#salesPanel').val(s[0][19]);
				if(id=='receipt'){
					$('#noteAlignment').children('option').each(function(index, element) {
						if($(this).val()==s[0][10]){
							$(this).prop('selected',true);
							return;
						}
					});
					$('#noteFontStyle').children('option').each(function(index, element) {
						if($(this).val()==s[0][11]){
							$(this).prop('selected',true);
							return;
						}
					});
					$('#footAlignment').children('option').each(function(index, element) {
						if($(this).val()==s[0][13]){
							$(this).prop('selected',true);
							return;
						}
					});
					$('#footFontStyle').children('option').each(function(index, element) {
						if($(this).val()==s[0][14]){
							$(this).prop('selected',true);
							return;
						}
					});
				}
			}
		});
		
	});
	$("#photo").on('change', function () {
		var imgPath = $(this)[0].value;
		var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
		if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
			if (typeof (FileReader) != "undefined") {
				var reader = new FileReader();
				reader.onload = function (e) {
					var x = new String($("#photo").val());
					$('#image-loc').val(x.slice(12));
				}
				reader.readAsDataURL($(this)[0].files[0]);
			} else {
				alert("This browser does not support FileReader.");
			}
		} else {
			alert("Pls select only images");
		}
	});
});
</script>
</html>
<?php
}
else
header("Location: index.php");
?>
