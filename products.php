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
			
		});
	
		$('#conversions').dataTable({
			"scrollY":        "150px",
			"scrollCollapse": false,
			"paging":         false,
		});
		
		if(['IM','AV'].indexOf($('#accType').val())<0){
			$('#add').remove();
			$('#edit').remove();
		}	
		if(['AV'].indexOf($('#accType').val())<0){
			$('#delete').remove();
			
		}
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
		$('select').change(function() {
		 	($(this).val()=='all') ? $('#search').val('').prop('disabled',true) : $('#search').prop('disabled',false);
		});
		$('#go').click(function() {
			var list = $('#list').dataTable();
			$.ajax({
				url: 'fetch/get-products1.php',
				dataType: 'json',
				type:'get',
				data: {type:$('select').val(),value:$('#search').val()},
				success: function(s){
					console.log(s);
					list.fnClearTable();
					for(var i = 0; i < s.length; i++) {
						list.fnAddData([
							
							s[i][1],
							s[i][2],
							s[i][0],
							s[i][3],
							s[i][4],
							s[i][5],
							s[i][6]
						]);
					}
					if(s.length==0)
						$('#noData').fadeIn(1000).fadeOut(1500);
				},
				error: function(e){
					console.log(e.responseText);
				}
			});	
		});
		shortcut.add('enter',function(){
			$('#go').click();
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
	}	/*#conversions td:nth-child(2),#conversions td:nth-child(4),#conversions th:nth-child(2),#conversions th:nth-child(4){
		display:none;
	}*/
	.modal-overlay.js-modal-close {
		display: none;
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
        	<style>
				#list td:nth-child(7),#list td:nth-child(6),#list td:nth-child(5) {
					text-align:right;	
				}
				th, #list td:nth-child(3),#list td:nth-child(4) {
					text-align:center !important;
				}
				.form-control1 {
					padding: 3px 7px;
					font-size: 14px;
					line-height: 1.42857143;
					color: #555;
					background-color: #fff;
					background-image: none;
					border: 1px solid #ccc;
					border-radius: 4px;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
					-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
					transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
				}
				.dataTables_filter {
					display:none;
				}
			</style>
			<table width="100%" style="margin:0px 0px 5px 0px">
				<tr>
					<td width="40%">
						<div style="float:left;width:75%" class="input-group"><span class="input-group-addon" style="background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;"><span class="glyphicon glyphicon-search"></span> </span><input type="text" class="form-control" placeholder="Search" style="font-weight:normal" aria-controls="list" id="search" disabled></div>
						<select class="form-control1" style="margin-left:5px;float:right;height: 28px;width:120px">
							<option value="all">-- All --</option>
							<option value="measID">Measurement</option>
							<option value="prodID">Product ID</option>
							<option value="prodDesc">Description</option>
						</select>
						
					</td>
					<td style="text-align:right">&nbsp;</td>
					<td width="25%" style="text-align:right"><button style="color:navy;font-weight:bolder" class="btn" id="go">{ }</button><button class="btn" id="conv" ><span class="glyphicon glyphicon-th-list" style="color:navy;margin-right:3px"></span>Conversions</button><button class="btn" id="edit" data-modal-id="edit_popup"><span class="glyphicon glyphicon-pencil" style="color:navy;margin-right:3px"></span>Edit</button><button class="btn" id="delete" data-modal-id="delete_popup"><span class="glyphicon glyphicon-trash" style="color:maroon;margin-right:3px"></span>Delete</button><button class="btn" id="print"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button></td>
				</tr>
			</table>
        	<table width="100%" id="list">
              	<thead>
                	
                	<th width="15%">Product ID</th>
                    <th>Item Description</th>
					<th width="7%">Unit</th>
                    <th width="6%" style="text-align:center">Status</th>
                    <th width="11%">Maximum Qty</th>
                    <th width="10%">Reorder Qty</th>
                    <th width="10%">Unit Price</th>
                </thead>
            </table>

        </div>
    </div>
    <div id="edit-popup" class="modal-box" style="width:50%;height:auto !important">
    	<header>
          <h3>Edit Product</h3>
        </header>
        <div id="box1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Please fill all the boxes in red.
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div id="id1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Product ID/Barcode has already exist. Please refer to the product list.
            <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
			<span id="oldID" style="display:none"></span>
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
				#edit .btn, #print_popup .btn {
					font-size: 14px;
  					padding: 0.75em 1.5em;
					/*margin-left: 20px;*/
  					font-weight: bold;
				}
				
			</style>
           
        	
        	<table width="100%" id="edit">
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
                	<input type="text" class="form-control" id="reorder" value="5" style="cursor:auto;width:18.5%;float:left" placeholder="Reorder Level" />
                    <span style="float:left;margin-left: 12px;font-weight: bold;padding-top: 5px;"><span class='required'>*</span> Maximum Stock :</span>
                    <input type="text" class="form-control" id="maximum" value="20" style="cursor:auto;width:18.5%;float:left" placeholder="Reorder Level" />
                </td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Lead Time in Days : </td>
                <td><input type="text" class="form-control" id="leadtime" value="5" placeholder="Delivery Leadtime" /></td>
              </tr>
           	  <tr style="display:none">
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
                <td><button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Submit</button><button id="cancel"" class="btn js-modal-close" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);" class="">Cancel</button></td>
              </tr>
            </table>
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
	<div id="conversions_popup" class="modal-box" style="width:30%;top:50px;height: 285px !important;">
		<header>
		<h3>Product Conversions<span title="Close" class="glyphicon glyphicon-remove-sign js-modal-close" aria-hidden="true" style="float:right;font-size: 17px;cursor: pointer;"></span></h3>
		</header>
		<div class="modal-body" style="text-align:center;height:initial;padding: 0.1em 0.5em;">
			<div style="text-align:left;padding: 7px 0px;">
				Product: <kbd><span id="prodID-c"></span></kbd> <span id="prodDesc-c"></span>
			</div>
			<table id="conversions" width="100%">
				<input type="hidden" id="check1">
				<thead>
					<th>Unit</th>
					<th>Conversion</th>	
				</thead>
				<tbody>
				</tbody>
			</table>
			<input type="hidden" id="check33">
		</div>
    </div>
</div>
<script>
	
	$(function(){
	var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
	$('#conv').click(function(e) {
		if($('#list tr').hasClass('active_')){
			var prodID = $('#list .active_ td').eq(0).text();
			var prodName = $('#list .active_ td').eq(1).text()
			var $conv = $('#conversions').dataTable();
			var check1 = true;
			$.ajax({
				url: 'fetch/get-products.php?aom&so&prodID='+prodID,
				dataType: 'json',
				success: function(s){
					console.log(s);
					$conv.fnClearTable();
					if(s.length>3){
						for(var i = 1; i < s.length; i++) {
							if(i==1)
								s[i][2] = '1.00';
							$conv.fnAddData([
								s[i][1],
								s[i][2]
							]);
						}
						$('#prodID-c').html(prodID);
						$('#prodDesc-c').html(prodName);
						$("body").append(appendthis);
						$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
						var modalBox = 'conversions_popup';
						
						$('#'+modalBox).fadeIn(function() {
							$('#conversions').DataTable().columns.adjust().draw();
						});
						var view_width = $(window).width();
						var view_top = $(window).scrollTop() + 150;
						$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
						$('#'+modalBox).css("top", view_top);
					}
					else {
						$('#noConv').fadeIn(1000).fadeOut(1500);
						return;
					}
				}
			});
		}
	});
	$('button[data-modal-id]').click(function(e) {
		e.preventDefault();
		var check = false;
		if($('#list tr').hasClass('active_')){
			$('#oldID').html($('#list .active_ td').eq(0).text());
			$.ajax({
				url: 'fetch/get-product.php',
				data: {prodID:$('#list .active_ td').eq(0).text(),type:true},
				dataType:'json',
					success: function(s){
						console.log(s);
						$('#name').val(s[0]);
						$('#measurement1 option[value="'+s[9]+'"]').attr('selected','selected');
						$('#id').val(s[2]);
						$('#stock').val(s[3]);
						$('#price').val(s[4]);
						$('#reorder').val(s[6]);
						$('#maximum').val(s[7]);
						$('#leadtime').val(s[8]);
						s[5]=='Active' ? $('#status').prop('checked',true) : $('#status').prop('checked',false);
						$.ajax({
							url: 'fetch/get-products.php?aom&so&prodID='+s[2],
							dataType: 'json',
							success: function(ss){
								console.log(ss);
								$('#add_conversion').css({'color':'#337ab7','cursor':'pointer'});
								if(ss[2]!='false'){
									var opt = new String();
									$.ajax({
										url: 'fetch/get-measurement.php',
										data: {all:true,measID:$('#measurement1').val()},
										dataType:'json',
										success: function(n){
											console.log(n);
											var i = $('#edit-popup tbody > tr').length;
											if(i>10)
												$('[data-id=conversions]').remove()
											var i = $('#edit-popup tbody > tr').length;
											for(var x=0; x<n.length; x++)
												opt += '<option value="'+n[x][0]+'" data-measurement="'+n[x][2]+'">'+n[x][1]+'</option>';
											$value = (n[0][2]=='Whole Number') ? "0" : "0.00";
											for(var j=2; j<=ss.length; j++,i++){
												$('#edit-popup tbody > tr').eq(i-3).before('<tr data-id="conversions"><td>Alternate '+new Number(new Number(i-10)+new Number(1))+' :</td><td><select id="'+new Number(new Number(i-10)+new Number(1))+'" onchange="AOMchange(this)" data-select="alternateMeasurement" class="select form-control" style="background: url(media/images/arrow_down.png) 98% 50% / 14px no-repeat transparent;width:52%;float:left;margin-right:1%">'+opt+'</select><input value="'+ss[j][2]+'" onkeyup="numberUP(this)" onkeypress="return numberPress(this)" class="form-control" style="width:25%;float:left;margin-left:0px" placeholder="'+$('#measurement1 option[value="'+$('#measurement1').val()+'"]').html()+'(s)'+'"><a class="remove" onClick="removeParent(this)" ><span style="margin-top:9px;margin-left:5px" class="glyphicon glyphicon-remove" aria-hidden="true" style="margin-right: 3px;"></span></a></td></tr>');
												$('#edit-popup tbody > tr').eq(i-3).find('option[value="'+ss[j][0]+'"]').attr('selected','selected'); 
											}		
										}
									});
								}
							}
						});
					}
				});
			check = true;	
		}
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			var modalBox = $(this).attr('data-modal-id');
			$('#'+modalBox).fadeIn($(this).data());
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 150;
			$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
			$('#'+modalBox).css("top", view_top);
		}
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
	$('#print').click(function() {
		var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
		$("body").append(appendthis);
		$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
		$(".modal-overlay").css('position','fixed');
		var modalBox = 'print_popup';
		$('iframe').attr('src','report/product-list.php?type='+$('select').val()+'&value='+$('#search').val());
		$('#'+modalBox).fadeIn(1000);
		$('#'+modalBox+' iframe').css('height',$(window).height()-90);
		var view_width = $(window).width();
		$('#'+modalBox).css('height',$(window).height()-60);
		var view_top = $(window).scrollTop() + 10;
		$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
		$('#'+modalBox).css("top", view_top);
	});
	$(".js-modal-close, .modal-overlay").click(function() {
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	});
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
							$('#list .active_ td').remove();
							//alert($('#edit select').prop('selected', true).val());
							//window.location.assign('products.php?success=delete');
						}
					},
					cancel: {
						label: 'Cancel',
						className: "btn",
						callback: function() {
							$('.modal-overlay.js-modal-close').hide();
						}
					}
				}
			});	
		}
	});

	$('button[data-bb-handler="cancel"]').on('click', function() {
		alert('asas');
	});

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
		var i = $('#edit-popup tbody > tr').length;
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
				$('#edit-popup tbody > tr').eq(i-3).before('<tr data-id="conversions"><td>Alternate '+new Number(new Number(i-10)+new Number(1))+' :</td><td><select onchange="AOMchange(this)" data-select="alternateMeasurement" class="select form-control" style="background: url(media/images/arrow_down.png) 98% 50% / 14px no-repeat transparent;width:52%;float:left;margin-right:1%">'+opt+'</select><input value="'+$value+'" onkeyup="numberUP(this)" onkeypress="return numberPress(this)" class="form-control" style="width:25%;float:left;margin-left:0px" placeholder="'+$('#measurement1 option[value="'+$('#measurement1').val()+'"]').html()+'(s)'+'"><a class="remove" onClick="removeParent(this)" ><span style="margin-top:9px;margin-left:5px" class="glyphicon glyphicon-remove" aria-hidden="true" style="margin-right: 3px;"></span></a></td></tr>');
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
		$('#edit-popup input[type=text],edit-popup select').each(function() {
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
			if($('#oldID').html()!=$.trim($('#id').val()))
			$.ajax({
				url: 'check/check.php',
				dataType: 'json',
				data: {prodID:$.trim($('#id').val())},
				success: function(s){
					console.log(s);
					$('#check33').val(s[0]);
				}
			});
			if($('#check33').val()=='true'){
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
									data: {prodID:$.trim($('#id').val()),prodMeasurement:$('#measurement1').val(),prodName:$('#name').val(),prodStock:$('#stock').val().replace(/,/g, ""),prodPrice:$('#price').val().replace(/,/g, ""),prodStatus:y,reorderQty:$('#reorder').val().replace(/,/g, ""),maxQuantity:$('#maximum').val().replace(/,/g, ""),leadtime:$('#leadtime').val().replace(/,/g, ""),update:true,oldID:$('#oldID').html()},
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
														url: 'save/delete_conversions.php?id='+$('#oldID').html(),
														dataType: 'json',
														type: 'GET',
														
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
											$('.js-modal-close').click();
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
	$('#edit-popup .close').click(function() {
		$('.modal-body').css('padding-top','2em');
	});
});
$(document).on('click', '.bootbox', function(event) {
  var target = event.target;

  // Check the parentNodes until
  // we hit the actual backdrop
  while(target !== this){
    target = target.parentNode;
    // If the click was inside a 
    // modal-dialog ABORT
    if(target.className.indexOf('modal-dialog') !== -1){
      return;
    }
  }
  // Fire some close Callback here
  bootbox.hideAll();
});
</script>
<script src="media/js/product-popup.js"></script>
<div class="print_info" id="noData" style="display:none"><h6>No data!</h6></div>
<div class="print_info" id="noConv" style="display:none"><h6>No Conversions!</h6></div>
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
mysql_free_result($rsProduct);
}
else
header("Location: index.php");
?>
