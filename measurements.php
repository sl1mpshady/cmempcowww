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
<title><?php if(isset($_GET['name'])){echo 'asasas';}?></title>

<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>
<span id="addCategory" style="display:none;visibility:0"><?php echo $row_rsAccount['addCategory'];?></span>
<span id="editCategory" style="display:none;visibility:0"><?php echo $row_rsAccount['editCategory'];?></span>
<span id="deleteCategory" style="display:none;visibility:0"><?php echo $row_rsAccount['deleteCategory'];?></span>
<script>
	$(document).ready(function () {
		$('#home').addClass('active');
		$('#home_menu').show();
		$('#measurement_menu').addClass('selected');
		
		$('#list').dataTable({
			"scrollY":        "300px",
			"scrollCollapse": false,
			"paging":         false,
			searchHighlight: true,
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_processing/measurement_server_processing.php"
		});
		
		if(['IM','AV'].indexOf($('#accType').val())<0){
			$('#add').remove();
			$('#edit').remove();
		}
		
		if($('#deleteCategory').html()!='True')
			$('#delete').remove();
		$('#edit').attr('data-modal-id',"edit-popup");
		$('#add').attr('data-modal-id',"add-popup");
		$('#add').html('<span class="glyphicon glyphicon-plus-sign" style="color:navy;margin-right:3px"></span><span style="text-decoration:underline">A</span>dd New');
		$(document).on("click","#list tr", function(){
			if ( $(this).hasClass('active_') ) {
				$(this).removeClass('active_');
			}
			else {
				$('#list tr.active_').removeClass('active_');
				$(this).addClass('active_');
			}
			if($('#list .active_ td').eq(0).text() == 'No data available in table')
				$(this).removeClass('active_');
		});
		$(document).on("click", function(event){
			if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("a.btn") && !$(event.target).parent().andSelf().is("#list_filter button") && !$(event.target).parent().andSelf().is(".modal-body table")){
				 $('#list tr.active_').removeClass('active_');
			}
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
	td>label>span {
		margin-right:25px;
		font-weight:normal;
	}
</style>
<body>
<div style="display:none" id="parent">home</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Measurements</div>
    <div id="box" class="bg-success" style="display:<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'block;' : 'none;')?> padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<?php
			if($_GET['success']=='add'){
				echo 'Measurement has been added successfuly.';
			}
			else if($_GET['success']=='delete'){
				echo 'Measurement has been deleted successfully.';
			}
			else if($_GET['success']=='edit'){
				echo 'Measurement has been updates successfully.';
			}
		?>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="border:1px solid lightgray;border-radius:4px">
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list"></span> Measurement List</div>
        <div style="padding:5px">
        	<style>
				#list td:nth-child(6) {
					text-align:right;	
				}
				th, #list td:nth-child(3),#list td:nth-child(4),#list td:nth-child(5) {
					text-align:center !important;
				}
				
			</style>
        	<table width="100%" id="list">
              	<thead>
                	<th width="20%">Unit of Measure</th>
                    <th>Description</th>
                    <th width="20%">Measurement of Quantity</th>
                </thead>
                
            </table>

        </div>
    </div>
    <div id="add-popup" class="modal-box" style="width:50%">
    	<header>
          <h3>Add Measurement</h3>
        </header>
        <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Please fill all the boxes in red.
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div id="id1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Unit of measure has already exist. Please make a new one.
            <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
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
				#edit .btn {
					font-size: 14px;
  					padding: 0.75em 1.5em;
				}
			</style>
            <input type="hidden" id="measID" />
        	<table width="100%" id="edit">
              <tr>
                <td><span class='required'>*</span> Unit of Measure :</td>
                <td><input type="text" class="form-control" id="measU" style="text-transform:uppercase;width:40%"/></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Description : </td>
                <td><input type="text" class="form-control" id="measDesc"/></td>
              </tr>
              <tr>
              	<td>Measurement of Quantity : </td>
                <td><label style="margin-left:40px" class="radio"><span><input type="radio" name="measurement" id="measurement1" value="Whole Number" checked="checked">Whole Number </span><span><input type="radio" name="measurement" id="measurement2" value="Decimal">Decimal</span></label></td>
              </tr>
              
              <tr>
                <td>&nbsp;</td>
                <td><button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Submit</button><button id="cancel" class="js-modal-close btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Cancel</button></td>
              </tr>
            </table>
        </div>
    </div>
    <div id="edit-popup" class="modal-box" style="width:50%">
    	<header>
          <h3>Edit Measurement</h3>
        </header>
        <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Please fill all the boxes in red.
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div id="id1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Category name has already exist. Please refer to the category list.
            <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
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
				#edit .btn {
					font-size: 14px;
  					padding: 0.75em 1.5em;
				}
			</style>
        	<table width="100%" id="edit">
              <tr>
                <td><span class='required'>*</span> Unit of Measure :</td>
                <td><input type="text" class="form-control" id="measU" style="text-transform:uppercase;width:40%"/></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Description : </td>
                <td><input type="text" class="form-control" id="measDesc"/></td>
              </tr>
              <tr>
              	<td>Measurement of Quantity : </td>
                <td><label style="margin-left:40px" class="radio"><span><input type="radio" name="measurement" id="measurement1" value="Whole Number" checked="checked">Whole Number </span><span><input type="radio" name="measurement" id="measurement2" value="Decimal">Decimal</span></label></td>
              </tr>
              <tr>             <td>&nbsp;</td>
                <td><button id="submit-edit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Submit</button><button id="cancel" class="js-modal-close btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Cancel</button></td>
              </tr>
            </table>
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
		var modalBox = $(this).attr('data-modal-id');
		var check=false;
		if(modalBox == 'edit-popup' && $('#list tr').hasClass('active_')){
			$('#measID').val($('#list .active_ td').eq(0).text());
			$.ajax({
				url: 'fetch/get-measurement.php',
				data: {measID:$('#list .active_ td').eq(0).text()},
				dataType:'json',
				success: function(s){
					console.log(s);
					$('#edit-popup #measU').val(s[0]);
					$('#edit-popup #measDesc').val(s[1]);
					//s[2]=='Active' ? $('#edit-popup #status').prop('checked','checked') : '';
					$('#edit-popup input[value="'+s[2]+'"]').prop('checked',true);
					//$('#edit-popup input[value="'+s[4]+'"]').prop('checked',true);
				}
			});
			check = true;
		}
		else if(modalBox == 'add-popup'){
			check = true;
			$('#'+modalBox+' input[value="Whole Number"]').prop('checked',true);
			$('#'+modalBox+' input[value="False"]').prop('checked',true);
		}
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			//$(".js-modalbox").fadeIn(500);
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
								data: {catID:id,update:true,delete:true},
								dataType: 'json',
								success: function(s){
									console.log(s);
								}
							});
							
							window.location.assign('category.php?success=delete');
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
	
   	$('#name').keypress(function(e){     
		var str = String.fromCharCode(e.keyCode);
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test(str) && str!=' ') 
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
	$('#submit,#submit-edit').click(function() {
		var check = true;
		if($('#edit-popup').is(':visible'))
			modalBox = 'edit-popup';
		else
			modalBox = 'add-popup';
		$('#'+modalBox+' input[type=text]').each(function() {
			if(this.value == ''){
				//alert(this.value);
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
			$('#'+modalBox+' .modal-body').css('padding-top','0');
			$('#'+modalBox+' #id1').hide();
			$('#'+modalBox+' #box').show();
			//alert(false);
		}
		else{
			$.ajax({
				url: 'check/check.php',
				dataType: 'json',
				data: {measID:$.trim($('#'+modalBox+' #measU').val())},
				success: function(s){
					console.log(s);
					if($('#'+modalBox+' #measU').val() == $('#measID').val())
						s[0] = false;
					if(s[0]==true){
						$('.modal-body').css('padding-top','0');
						$('#'+modalBox+' #box').hide();
						$('#'+modalBox+' #id1').show();
						$('#id').parent().addClass('has-error');
					}
					else {
						$.ajax({
							url: 'save/save.php',
							data: {measU:modalBox=='edit-popup' ? $('#measID').val() : '',measID:modalBox=='edit-popup' ? $('#'+modalBox+' #measU').val() : $('#measU').val(),measDesc:$('#'+modalBox+' #measDesc').val(),measMeasurement:$('#'+modalBox+' input[name=measurement]:checked').val(),type:modalBox=='edit-popup' ? 'UPDATE' : 'INSERT'},
							dataType: 'json',
							type:'POST',
							success: function(s){
								console.log(s);
								
							}
						});
						var x = modalBox=='edit-popup' ? 'edit' : 'add';
						window.location.assign('measurements.php?success='+x);
						
					}
				}
			});
		}
	});
	$('#edit-popup .close').click(function() {
		$('.modal-body').css('padding-top','2em');
	});
	$('.close').click(function() {
		$('#'+modalBox+' .modal-body').css('padding-top','2em');	
	});
	shortcut.add('ctrl+p',function() {
			$('#print').click();
		});
	$('#print').click(function() {
		var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
		$("body").append(appendthis);
		$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
		$(".modal-overlay").css('position','fixed');
		var modalBox = 'print_popup';
		$('iframe').attr('src','report/measurement-list.php?value='+$('input[type=search]').val());
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
});
</script>
<script src="media/js/product-popup.js"></script>
</body>
</html>
<?php
}else
header("Location: index.php");
?>
