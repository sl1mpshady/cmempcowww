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
$query_rsMeasure = "SELECT measID, measDesc FROM measurement";
$rsMeasure = mysql_query($query_rsMeasure, $connSIMS) or die(mysql_error());
$row_rsMeasure = mysql_fetch_assoc($rsMeasure);
$totalRows_rsMeasure = mysql_num_rows($rsMeasure);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>StoreSys</title>
<?php include_once('menu.php');?>
<script src="media/js/jquery-ui.js"></script>
<link href="media/css/jquery-ui.css" rel="stylesheet" />
<title><?php if(isset($_GET['name'])){echo 'asasas';}?></title>

<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
</head>
<span id="addProduct" style="display:none;visibility:0"><?php echo $row_rsAccount['addProduct'];?></span>
<script>
$(document).ready(function () {
	if(['IM','AV'].indexOf($('#accType').val())<0)
		window.location.assign('products.php');
	$('#modules').addClass('active');
	$('#modules_menu').show();
	$('#add-product_menu').addClass('selected');
	$('#add_conversion').css({'color':'#337ab7','cursor':'pointer'});
});
</script>
<style>
#header {
	position:fixed !important;
	width:100% !important;
}
.remove{
	color:#A21C1C; !important;
}
.remove:hover{
	color:#C00 !important;
	cursor:pointer;
}
.close {
	font-size:18px !important;
}
.form-control {
	width:50%;
	margin-left:20px;
}
td:nth-child(1){
	text-align:right;
	font-weight:bold;
	width:40%
}
td:nth-child(2){
	padding:5px;
}
.form-control.has-error  {
	border-color: #a94442;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
}
.form-control.has-success  {
	border-color: #3c763d;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
}
disabled{
	cursor:default;
}
</style>
<body>
<div style="display:none" id="parent">modules</div>
<div style="padding:25px;padding-top: 155px;">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;margin-bottom:25px" id="header2">Add Product</div>
    <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Please fill all the boxes in red. Make sure to input zero(0) above numbers when it requires a number.
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div id="id1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Product ID/Barcode has already exist. Please refer to the product list.
  		<button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="/*border:1px solid lightgray;*/border-radius:4px">
        <div style="padding:0px 20px">
        	<table width="100%">
              <tr>
                <td><span class='required'>*</span> Product Description :</td>
                <td><input type="text" class="form-control" id="name" style="text-transform:capitalize" placeholder="Description"/></td>
              </tr>
              <tr>
              	
                <td>
                <input type="hidden" style="display:none;opacity:0" id="qtyType" />
                <span class='required'>*</span> Unit of Measure :</td>
                <td><select id="measurement1" class="form-control" >
					<?php 
						do {
							echo '<option value="'.$row_rsMeasure['measID'].'">'.$row_rsMeasure['measDesc'].'</option>';
						}while($row_rsMeasure = mysql_fetch_array($rsMeasure));
					?>
                    </select>
                    <input type="hidden" style="display:none;opacity:0" id="measurement" />
                    <input type="hidden" style="display:none;opacity:0" id="subProd" />
                </td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Product ID/Barcode :</td>
                <td><input type="text" class="form-control" id="id" placeholder="Barcode" /></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Safety Stock :</td>
                <td>
                	<input type="text" class="form-control" id="reorder" value="5" style="cursor:auto;width:14%;float:left" placeholder="Reorder Level" />
                    <span style="float:left;margin-left: 12px;font-weight: bold;padding-top: 5px;"><span class='required'>*</span> Maximum Stock :</span>
                    <input type="text" class="form-control" id="maximum" value="20" style="cursor:auto;width:14%;float:left" placeholder="Reorder Level" />
                </td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Lead Time in Days : </td>
                <td><input type="text" class="form-control" id="leadtime" value="5" placeholder="Delivery Leadtime" /></td>
              </tr>
           	  <tr>
                <td><span class='required'>*</span> Stock(#UNITS) :</td>
                <td><input type="text" class="form-control" id="stock" value="0"  /></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Price : </td>
                <td><input type="text" class="form-control" id="price" value="0.00" placeholder="Price" /></td>
              </tr>
              <tr>
                <td>Status :</td>
                <td><input type="checkbox" id="status" checked="checked"  style="margin-left:20px"/> Active</td>
              </tr>
              <tr>
              	<td><a style="font-size:12px;font-weight:600;cursor:pointer;color:black !important;cursor:default" id="add_conversion"><span class="glyphicon glyphicon-plus-sign"></span> Alternate of Measure</a></td>
                <td>&nbsp;</td>
              </tr>
              
              <tr>
                <td>&nbsp;</td>
                <td><button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Submit</button></td>
              </tr>
            </table>

        </div>
    </div>
</div>
<script>
$(document).ready(function() {
	getMaximum = function(e){
		return $('#maximum').val().replace(/,/g, "");
	}
	$('#maximum').autoNumeric('init',{'vMin':1,'mDec':0});
	$('#leadtime').autoNumeric('init',{'vMin':1,'mDec':0});
	$('#reorder').autoNumeric('init',{'vMin':1,'mDec':0,'vMax':getMaximum});
	$('#stock').autoNumeric('init',{'vMin':0,'mDec':0,'vMax':getMaximum});
	$('tr[data-id=conversions] input').autoNumeric('init',{'vMin':1,'mDec':0});
	
	$('#maximum').change(function() {
		x = 0;
		if($('#measurement').val()=='Decimal')
			x = 2;
		$('#reorder,#stock').autoNumeric('destroy');
		$('#reorder').autoNumeric('init',{'vMin':1,'mDec':x, 'vMax':getMaximum});
		$('#stock').autoNumeric('init',{'vMin':0,'mDec':0,'vMax':getMaximum});
	});
	removeParent = function(e) {
		 e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
	}
	AOMchange = function(e){
		$input = $(e).parents('tr[data-id=conversions]').find('input');
		$inputValue = $input.val().replace(/,/g, "").split('.');
		
		if(($('tr[data-id=conversions] option[value="'+$(e).val()+'"]').attr('data-measurement'))=='Whole Number')
			$input.val($inputValue[0].replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		else {
			if(!$inputValue[1])
				new Number($inputValue[0])==0 ? $input.val('0.00') : $input.val($input.val()+'.00');
		}
	}
	numberUP = function(e) {
		x = $(e).val().split('.');
		x = (x[1] || x[1]=='') ? new String(x[0].replace(/,/g, "")).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'.'+x[1]: new String(x[0].replace(/,/g, "")).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return $(e).val(x);
   }
   numberPress = function(e){
    	if ($.isNumeric(String.fromCharCode(event.keyCode))){
			if($(e).val().indexOf(".")>=0){
	
				x = $(e).val();
				if((x.substr($(e).val().indexOf(".")+1)+String.fromCharCode(event.keyCode)).length<=2 && new Number($(e).val().replace(/,/g, "") + String.fromCharCode(event.keyCode)) <= new Number($('#maximum').val().replace(/,/g, "")))
					return true;
				else
					return false;
			}
			if(new Number($(e).val() + String.fromCharCode(event.keyCode)) <= 0)
				return false;
			else{
				if(new Number($(e).val().replace(/,/g, "") + String.fromCharCode(event.keyCode)) <= new Number($('#maximum').val().replace(/,/g, ""))){
					return true;
				}
				return false;
			}
		}
		else if(String.fromCharCode(event.keyCode)=='.' && $('tr[data-id=conversions] option[value="'+$(e).parents('tr[data-id=conversions]').find('select').val()+'"]').attr('data-measurement')=='Decimal'){
			if($(e).val() == '')
				return false;
			else if($(e).val().indexOf(".")>=0)
				return false;
			else{
				if(new Number($(e).val().replace(/,/g, "") + String.fromCharCode(event.keyCode)) <= new Number($('#maximum').val().replace(/,/g, ""))){
					return true;
				}
				return false;
			}
		}
		return false;
   }
	$('#measurement1').change(function(e) {
		$('tr[data-id="conversions"').remove();
        if($(this).val()!='0'){
			$('#add_conversion').css({'color':'#337ab7','cursor':'pointer'});
			$.ajax({
				url: 'fetch/get-measurement.php',
				data: {measID:$('#measurement1').val()},
				dataType:'json',
				success: function(s){
					console.log(s);
					$('#qtyType').val(s[2]);
					stock = $('#stock').val().split('.');
					reorder = $('#reorder').val().split('.');
					maximum = $('#maximum').val().split('.');
					$('#measurement').val(s[2]);
					if(s[2] == 'Whole Number'){
						$('#stock').val(stock[0].replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						$('#reorder').val(reorder[0].replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						$('#maximum').val(maximum[0].replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						
						$('#maximum,#reorder,#stock').autoNumeric('destroy');
						$('#reorder').autoNumeric('init',{'vMin':1,'mDec':0,'vMax':getMaximum});
						$('#stock').autoNumeric('init',{'vMin':0,'mDec':0,'vMax':getMaximum});
						$('#maximum').autoNumeric('init',{'vMin':1,'mDec':0});
					}
					else {
						if(!stock[1])
							$('#stock').val($('#stock').val()+'.00');
						if(!reorder[1])
							$('#reorder').val($('#reorder').val()+'.00');
						if(!maximum[1])
							$('#maximum').val($('#maximum').val()+'.00');
						
						$('#maximum,#reorder,#stock').autoNumeric('destroy');
						$('#reorder').autoNumeric('init',{'vMin':1,'mDec':2,'vMax':getMaximum});
						$('#stock').autoNumeric('init',{'vMin':0,'mDec':2,'vMax':getMaximum});
						$('#maximum').autoNumeric('init',{'vMin':1,'mDec':2});
						
					}
				}
			});
		}
		else  if($(this).val()=='0'){
			$('#add_conversion').css({'color':'black !important','cursor':'default'});
			$('#qtyType').val('Whole Number');
		}
		
    });
	
	$('#add_conversion').click(function(e) {
		if($('#measurement1').val()=='0')
			return;
		var i = $('tbody > tr').length;
		var opt = new String();
		$.ajax({
			url: 'fetch/get-measurement.php',
			data: {all:true,measID:$('#measurement1').val()},
			dataType:'json',
			success: function(s){
				console.log(s);
				for(var x=0; x<s.length; x++)
					opt += '<option value="'+s[x][0]+'" data-measurement="'+s[x][2]+'">'+s[x][1]+'</option>';
	
				$value = (s[0][2]=='Whole Number') ? "0" : "0.00";
				$('tbody > tr').eq(i-3).before('<tr data-id="conversions"><td>Alternate '+new Number(new Number(i-10)+new Number(1))+' :</td><td><select onchange="AOMchange(this)" data-select="alternateMeasurement" class="select form-control" style="background: url(media/images/arrow_down.png) 98% 50% / 14px no-repeat transparent;width:30%;float:left;margin-right:1%">'+opt+'</select><input value="'+$value+'" onkeyup="numberUP(this)" onkeypress="return numberPress(this)" class="form-control" style="width:19%;float:left;margin-left:0px" placeholder="'+$('#measurement1 option[value="'+$('#measurement1').val()+'"]').html()+'(s)'+'"><a class="remove" onClick="removeParent(this)" ><span style="margin-top:9px;margin-left:5px" class="glyphicon glyphicon-remove" aria-hidden="true" style="margin-right: 3px;"></span></a></td></tr>');
			}
		});
		
        
    });
  $('#price').autoNumeric('init',{'vMin':0});
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
	$('#closeParent').click(function() {
		$('.print_info').fadeOut(500);
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
			$('#header2').css('margin-bottom','25px');
			$('#id1').hide();
			$('#box').hide();
			var check = true;
			$('input[type=text],select').each(function() {
				  if($(this).attr('id')=='price' || $(this).attr('id')=='reorder' || $(this).attr('id')=='maximum' || $(this).attr('id')=='leadtime'){
					  if(new Number($(this).val().replace(/\B(?=(\d{3})+(?!\d))/g, ","))==0){
						  check = false;
						  $(this).parent().removeClass('has-success');
						  $(this).parent().addClass('has-error');
					  }
					  else{
						  $(this).parent().removeClass('has-error');
						  $(this).parent().addClass('has-success');
					  }
											  
				  }
				  else{
					  if(this.value == ''){
						  check = false;
						  $(this).parent().removeClass('has-success');
						  $(this).parent().addClass('has-error');
					  }
					  else{
						  $(this).parent().removeClass('has-error');
						  $(this).parent().addClass('has-success');
					  }
				  }
			});
			if($('tr[data-id=conversions]').is(':visible')){
				$('tr[data-id=conversions] input').each(function(index, element) {
                    if(this.value == '' || new Number($(this).val().replace(/\B(?=(\d{3})+(?!\d))/g, ","))==0){
						check = false;
						$(this).parent().removeClass('has-success');
						$(this).parent().addClass('has-error');
					}
					else{
						$(this).parent().removeClass('has-error');
						$(this).parent().addClass('has-success');
					}
                });
			}
			if(!check){
				$('#header2').css('margin-bottom','0');
				$('#id1').hide();
				$('#box').show();
			}
			else{
				$('.close').click();
				var j;
				$.ajax({
					url: 'check/check.php',
					dataType: 'json',
					data: {prodID:$.trim($('#id').val())},
					success: function(s){
						console.log(s);
						j=s[0];
					},
					complete:function(){
						if(j==true){
							$('#header2').css('margin-bottom','0');
							$('#box').hide();
							$('#id1').show();
						}
						else {
							bootbox.dialog({
							  message: "Please make sure all data are correct. Do you want to complete this transaction?",
							  buttons: {
								  main: {
									  label: 'Yes',
									  className: "btn",
									  callback: function() {
									  	var y = ($('input[type=checkbox]').prop('checked'));
										$.ajax({
											url: 'save/save.php',
											dataType: 'json',
											data: {prodID:$.trim($('#id').val()),prodMeasurement:$('#measurement1').val(),prodName:$('#name').val(),prodStock:$('#stock').val().replace(/,/g, ""),prodPrice:$('#price').val().replace(/,/g, ""),prodStatus:y,reorderQty:$('#reorder').val().replace(/,/g, ""),maxQuantity:$('#maximum').val().replace(/,/g, ""),leadtime:$('#leadtime').val().replace(/,/g, "")},
											success: function(s){
												console.log(s);
												if(s[0]==true){
													var conversions = [];
													if($('tr[data-id=conversions]').is(':visible')){
														$('tr[data-id=conversions]').each(function(index, element) {
															measID = $(this).find('select').val();
															conversion = $(this).find('input').val();
															conversions.push({'measID':measID,'conversion':conversion});
															
														});
														$.ajax({
																url: 'save/save.php',
																dataType: 'json',
																type: 'POST',
																data: {'data':JSON.stringify(conversions),'prodID':$.trim($('#id').val())},
																success: function(d){
																	console.log(s);
																	$('#save_success').fadeIn(1000);
																},
																error: function(e){
																	console.log(e);
																	$('#error_msg').html('Error 000ax01: No connection could be made because the target machine actively refused it.');
																	$('#save_error').fadeIn(1000);
																}
															});
													}
													$('#save_success').fadeIn(1000);
												}
												else{
													$('#error_msg').html('Error 000ax01: No connection could be made because the target machine actively refused it.');
													$('#save_error').fadeIn(1000);
											}
											},
											error: function(a){
												console.log(a);
												$('#error_msg').html('Error 000ax01: No connection could be made because the target machine actively refused it.');
												$('#save_error').fadeIn(1000);
											}
										});
									  }
								  },
								  cancel: {
									  label: 'No',
									  className: "btn"
								  }
							  }
						  });
							
						}
					}
				});
			}
		});
	
});
</script>
<div class="print_info" style="display:none" id="save_success">
	<h6>Product Saved Successfully</h6>
	<p>Press <kbd>F5</kbd> or click Add Button to add a new product. Click <kbd>View</kbd> button to view product list.</p>
	<p>
		<button class="btn" onClick="javascript:window.location.assign('add-product.php')" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Add</button>
		<button class="btn" onClick="javascript:window.location.assign('products.php')" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">View</button>
	</p>
</div>
<div class="print_info" style="display:none" id="save_error">
	<h6>Product Saved Unsuccessfully</h6>
	<p>
		<span id="error_msg">
			
		</span>
	</p>
	<p>
		<button class="btn" id="closeParent" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Okay</button>
	</p>
</div>

</body>
</html>
<?php
mysql_free_result($rsMeasure);
}else
header("Location: index.php");
?>
