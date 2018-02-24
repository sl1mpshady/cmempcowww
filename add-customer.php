<?php require_once('Connections/connSIMS.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
if(isset($_SESSION['MM_Username'])){
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
<script src="media/js/jquery.dataTables.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
</head>
<script>
	$(document).ready(function () {
		onselectstart="return false;";
		$('#modules').addClass('active');
		$('#modules_menu').show();
		$('#add-customer_menu').addClass('selected');
		$("#photo").on('change', function () {
			var objheight = 150;
			var objwidth = 150;
			var imgPath = $(this)[0].value;
			var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
			if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
				if (typeof (FileReader) != "undefined") {
					var image_holder = $("#preview");
					image_holder.empty();
					var reader = new FileReader();
					reader.onload = function (e) {
						$("<img />", {
							"src": e.target.result,
								"class": "thumb-image",
								"style": "width:100%;height:108px !important"
						}).appendTo(image_holder);
						$('#empty').hide();
					}
					image_holder.show();
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
<body>
<div style="display:none" id="parent">modules</div>
<div style="padding:25px">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;margin-bottom:25px" id="header2">Add Customer</div>
    <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<span id="blank" style="display:none">Please fill all the boxes in red.</span>
        <span id="id" style="display:none">Account ID has already exist. Please refer to the customer list.</span>
        <span id="educ" style="display:none">Please select or enter your educational attainment.</span>
        <span id="date" style="display:none">Date is invalid.</span>
        <span id="name" style="display:none">Customer under the name has already exist.</span>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="/*border:1px solid lightgray;border-radius:4px*/">
    	<!--<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-plus-sign"></span> Add Customer</div>-->
        <div style="padding:20px;overflow:auto;padding-top:4px">
        	<style>
				.form-control {
					width:100%;
					margin-left:20px;
				}
				td:nth-child(1){
					text-align:right;
					font-weight:bold;
					width:21%
				}
				td:nth-child(2),td:nth-child(3),td:nth-child(4){
					padding:5px;
				}
				.head-title1 {
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
				placeholder #yy {
					color:black;
				}
				.im_upload:hover {
				  background-color: #efefef;
				}
				.im_upload {
				  border-radius: 4px;
				  color: white;
				  border: 1px solid #cdcdcd;
				  background-color: #fff;
				  display: inline-block;
				  font-weight: bold;
				  text-decoration: none;
				}
				.image-placeholder-show {
				  display: inline-block;
				}
				.image-placeholder {
				  color: #555;
				  position: relative!important;
				  font-size: 12px!important;
				  width: 120px!important;
				  height: 120px!important;
				  margin-top: 10px!important;
				}
				.image-placeholder input[type="file"] {
				  position: absolute;
				  top: 0;
				  right: 0;
				  float: left;
				  margin: 0;
				  opacity: 0;
				  width: 100%;
				  height: 120px;
				  filter: alpha(opacity=0);
				  border: 0;
				  cursor: pointer;
				  cursor: hand;
				  font-size: 13px;
				  z-index: 100;
				}
				.thumb-container {
					position: relative;
					text-align: center;
					cursor: pointer;
					text-align: center;
					vertical-align: middle;
					background-color: transparent;
					background-repeat: no-repeat;
					color: #6d6d6d;
					height: 100%;
					width: 100%;
					margin: 0 auto;
				  }
				 .sprite_ai_camera {
				  width: 57px;
				  height: 47px;
				  background: url("media/images/ai.png?16afd28648844eb1e3bb92444d2e4444c1a475f2") no-repeat -198px -2px;
				}
				.thumb-camera {
				  width: 56px;
				  height: 46px;
				  border-radius: 5px;
				  position: relative;
				  margin: 20px auto 16px auto;
				}
				.thumb-image {
				  width:100%;				
				}
				.error {
					.has-error .form-control {
					border-color: #a94442;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
				  }
				 .form-control .succ{
					border-color: #3c763d;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
				  }

			</style>
            <input type="hidden" id="checkName" />
            <div style="float:left;width:48%">
            <div class="head-title1">Personal Information</div>
                <table width="50%" style="width:100% !important">
                    <tr>
                        <td><span class='required'>*</span> Name :</td>
                        <td><input type="text" class="form-control alpha" id="fname" style="text-transform:capitalize" placeholder="First Name" /></td>
                        <td><input type="text" class="form-control alpha" id="mname" style="text-transform:capitalize" placeholder="Middle Name" /></td>
                        <td><input type="text" class="form-control alpha" id="lname" style="text-transform:capitalize" placeholder="Last Name" /></td>
                  </tr>
                  <tr>
                        <td><span class='required'>*</span> Address :</td>
                        <td colspan="3"><input type="text" class="form-control" id="addr" style="text-transform:capitalize" placeholder="Complete Address"/></td>
                  </tr>
                </table>
                 <table width="50%" style="width:100% !important">
                  <tr>
                        <td><span class='required'>*</span> Birthdate :</td>
                        <td colspan="1">
                            <input type="date" class="form-control" id="bdate" style="cursor:auto;width: 154px;">
                        </td>
                        <td width="25%" style="font-weight: bold;padding-left:26px"><span class='required'>*</span> Gender : <input style="float:right" type="radio" value="Male" name="gender" id="gender" checked="checked" /></td>
                        <td><span>Male</span><input  style="margin:10px" type="radio" value="Female" name="gender" id="gender" /><span>Female</span></td>
                  </tr>
                  <tr>
                    <td><span class='required'>*</span> Place of Birth :</td>
                     <td colspan="3"><input type="text" class="form-control" id="baddr" style="text-transform:capitalize" placeholder="Birth Place"/></td>
                  </tr>
                  <tr>
                    <td><span class='required'>*</span> Mobile Number :</td>
                    <td colspan="1">
                        <input type="text" class="form-control" id="mobile" style="text-transform:capitalize" placeholder="Mobile Number"/>
                    </td>
                    <td style="font-weight: bold;padding-left:26px"><span class='required'>*</span> Civil Status :</td>
                    <td>
                        <select id="civstat" class="form-control">
                            <option value="single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Annuled / Divorced">Annuled / Divorced</option>
                            <option value="Widow / er">Widow / er</option>
                            <option value="Legally Separated">Legally Separated</option>
                        </select>
                    </td>
                  </tr>
                  <tr>
                    <td> Photo :</td>
                    <td class="im_upload nohistory image-placeholder image-placeholder-show" style="margin-left:25px">
						<form id="fileForm" enctype="multipart/form-data">
                        	<input type="file" size="1" style="display: block; cursor: pointer;" id="photo" />
						</form>
                        <div id="preview" src="#"></div>
                        <div class="thumb-container thumb-container-empty" id="empty">
                            <div id="thumb-camera" class="thumb-camera sprite_ai_camera" style="display: block;"></div>
                            
                        </div>
                    </td>
                    <td style="font-weight: bold;padding-left:26px"><span class='required'>*</span> Education :</td>
                    <td>
                        <div><input type="checkbox" data-id="educ" id="elem"  style="margin-right:15px"/>  Elementary</div>
                        <div><input type="checkbox" data-id="educ" id="hs"  style="margin-right:15px"/>  High School</div>
                        <div><input type="checkbox" data-id="educ" id="coll"  style="margin-right:15px"/>  College</div>
                        <div><input type="checkbox" data-id="educ" id="voc"  style="margin-right:15px"/>  Vocational</div>
                        <div><input type="text" data-id="educ" id="others" placeholder="others"  style="width:100%" class="form-control"/></div>
                    </td>
                  </tr>
                </table>
       		</div>
            <div style="float:right;width:48%">
            <div class="head-title1">Work Information</div>
            	<table width="100%" border="0">
                  <tr>
                    <td><span class='required'>*</span> Designation:</td>
                    <td colspan="3"><input type="text" class="form-control" id="desig" style="text-transform:capitalize" placeholder="Designation"/></td>
                  </tr>
                  <tr>
                    <td width="25%"><span class='required'>*</span> Section:</td>
                    <td width="25%"><input type="text" class="form-control" id="sect" style="text-transform:capitalize" placeholder="Section"/></td>
                    <td width="22%" style="font-weight: bold;padding-left:26px"><span class='required'>*</span> Department:</td>
                    <td width="25%"><input type="text" class="form-control" id="dept" style="text-transform:capitalize" placeholder="Department"/></td>
                  </tr>
                </table>
				<div class="head-title1" style="margin-top:25px">Membership Information</div>
              <table width="100%" border="0">
                <tr>
                  	<td><span class='required'>*</span> Account Type:</td>
                    <td width="30%">
                    	<select id="accType1" class="form-control" style="  padding: 6px 3px;">
                        	<option value="reg">Regular</option>
                            <option value="casSki">Casual Skilled</option>
                            <option value="casNon">Casual Non Skilled</option>
                        </select>
                    </td>
                  	<td width="20%" style="font-weight: bold;padding-left:26px"> Credit Limit:</td>
                    <td width="15%"><input type="text" class="form-control" id="limit" style="cursor:auto" value="15000.00"></td>
         
                </tr>
                <tr>
         			 <td><span class='required'>*</span> Account ID:</td>
                     <td><input type="text" class="form-control" id="accID1"></td>
                     <td width="20%" style="font-weight: bold;padding-left:26px"><span class='required'>*</span> Expirey:</td>   
                     <td width="15%"><input type="date" class="form-control" id="expDate" style="cursor:auto" disabled="disabled"></td>   
                </tr>
              </table>
			<div style="margin-top:68px">
            	<button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Submit</button>
                <button id="clear" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);" onclick="javascript:window.location.assign(window.location.href);">Clear</button>
                <button id="cancel" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);" onclick="javascript:window.location.assign('customer.php');">Cancel</button>
				
            </div>
            </div>
    </div>
</div>
<script>
$(document).ready(function() {
	var check_date = function(input){
		var startDay = new Date(input);
		var endDay = new Date();
		var millisecondsPerDay = 1000 * 60 * 60 * 24;
		var millisBetween = startDay.getTime() - endDay.getTime();
		var days = millisBetween / millisecondsPerDay;
		return Math.ceil(days);
	}
	var isDate_ = function(input) {
		var status = false;
		if (!input || input.length <= 0) {
		  status = false;
		} else {
		  var result = new Date(input);
		  if (result == 'Invalid Date') {
			status = false;
		  } else {
			status = true;
		  }
		}
		return status;
	}
	var d = new Date();
	var n = d.getFullYear();
	$('#limit').autoNumeric('init',{'vMin':0,'mDec':2});
	$('#yy').autoNumeric('init',{'vMin':0,'mDec':0,'vMax':n,'aSep':''});
	$('.alpha').on("keydown", function(event){
		var arr = [8,9,16,17,20,32,35,36,37,38,39,40,45,46];
	
		for(var i = 65; i <= 90; i++){
		  arr.push(i);
		}
	  // Prevent default if not in array
		  if(jQuery.inArray(event.which, arr) === -1){
			event.preventDefault();
		  }
	});
	function clear(){
		$('#blank,#id,#educ,#date,#name').hide();
	}
	$('#accType1').change(function() {
		if($(this).val() == 'reg'){
			$('#limit').val('15,000.00');
			$('#expDate').val('');
			$('#expDate').prop('disabled',true);
			
		}
		else if($(this).val() == 'casSki'){
			$('#limit').val('8,000.00');
			$('#expDate').prop('disabled',false);
		}
		else{
			$('#limit').val('3,000.00');
			$('#expDate').prop('disabled',false);
		}
		/*$('#limit').autoNumeric('destroy');
		$('#limit').autoNumeric('init',{'vMin':0,'mDec':2});*/
	});
	$('input[type="text"]').keyup(function(evt){
		var txt = $(this).val();
		$(this).val(txt.replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase( ); }));
	});
	$('#submit').click(function() {
		$('.close').click();
		var check = true;
		$('input[type=text]').each(function(index, element) {
			if(this.id != 'others' && this.id != 'limit'){
				if($(this).val()==''){
					check = false;
					$(this).parent('td').removeClass('has-success');
					$(this).parent('td').addClass('has-error');
				}
				else{
					$(this).parent('td').removeClass('has-error');
					$(this).parent('td').addClass('has-success');
				}
			}
        });
		$('select').each(function(index, element) {
            if($(this).val()=='0'){
				check = false;
				$(this).css({'border-color': '#a94442','-webkit-box-shadow': 'inset 0 1px 1px rgba(0,0,0,.075)','box-shadow': 'inset 0 1px 1px rgba(0,0,0,.075)'});
			}
			else
				$(this).css({'border-color': '#3c763d','-webkit-box-shadow': 'inset 0 1px 1px rgba(0,0,0,.075)','box-shadow': 'inset 0 1px 1px rgba(0,0,0,.075))'});
        });
		
		if(check_date($('#bdate').val())<0){
			check = true;
			$('#bdate').parent('td').removeClass('has-error');
			$('#bdate').parent('td').addClass('has-success');
		}
		else{
			$('#header2').css('margin-bottom','0');
			clear();
			$('#date').show();
			$('#box').show();
			check = false;
			$('#bdate').parent('td').removeClass('has-success');
			$('#bdate').parent('td').addClass('has-error');
		}
		if(!check){
			$('#header2').css('margin-bottom','0');
			clear();
			$('#blank').show();
			$('#box').show();		
		}
		else{
			check = false;
			$('input[type=checkbox]').each(function(index, element) {
            	if($(this).prop('checked')==true)
					check = true;
            });
			if(check==false && $('#others').val()==''){
				$('#header2').css('margin-bottom','0');
				clear();
				$('#educ').show();
				$('#box').show();
				check = false;
			}
			if($('#accType1').val()!='reg' && $('#expDate').val()==''){
				check = false;
				$('#expDate').parent('td').removeClass('has-success');
				$('#expDate').parent('td').addClass('has-error');
				$('#header2').css('margin-bottom','0');
				clear();
				$('#blank').show();
				$('#box').show();	
			}
			else if($('#accType1').val()!='reg'){
				$('#expDate').parent('td').removeClass('has-error');
				$('#expDate').parent('td').addClass('has-success');
				if(isDate_($('#expDate').val())){
					x = check_date($('#expDate').val())
					if(x < 0){
						$('#header2').css('margin-bottom','0');
						clear();
						$('#date').show();
						$('#box').show();
						check = false;
						$('#expDate').parent('td').removeClass('has-success');
						$('#expDate').parent('td').addClass('has-error');
					}
				}
				else{
					$('#header2').css('margin-bottom','0');
					clear();
					$('#date').show();
					$('#box').show();
					check = false;
					$('#expDate').parent('td').removeClass('has-success');
					$('#expDate').parent('td').addClass('has-error');
				}
					
			}
			else{
				$('#expDate').parent('td').removeClass('has-error');
				$('#expDate').parent('td').removeClass('has-success');
				$('#expDate').val('0000-00-00');
			}
			if(check){
				   $.ajax({
					   url: 'check/check.php',
					   type: 'get',
					   data: {'custFName':$('#fname').val(),'custMName':$('#mname').val(),'custLName':$('#lname').val()},
					   dataType: 'json',
					   success: function(s){
						  console.log(s);
						  if(s[0]==true){
							 $('#header2').css('margin-bottom','0');
							 clear();
							 $('#name').show();
							 $('#box').show();
						  }
						  else{
							  $.ajax({
								 url: 'check/check.php',
								 type: 'get',
								 data: {'custID':$('#accID1').val()},
								 dataType: 'json',
								 success: function(s){
									console.log(s);
									if(s[0]==true){
										$('#header2').css('margin-bottom','0');
										clear();
										$('#id').show();
										$('#box').show();
									}
									else {
										  $('.close').click();
										  $.ajax({
											  url: 'save/save.php',
											  data: {custID:$('#accID1').val(),fname:$('#fname').val(),mname:$('#mname').val(),lname:$('#lname').val(),address:$('#addr').val(),bdate:$('#bdate').val(),gender:$('#gender').val(),baddress:$('#baddr').val(),mobile:$('#mobile').val(),civil_status:$('#civstat').val(),photo:$('#photo').val(),elem:$('#elem').val(),hs:$('#hs').val(),coll:$('#coll').val(),voc:$('#voc').val(),others:$('#others').val(),designation:$('#desig').val(),section:$('#sect').val(),dept:$('#dept').val(),accType:$('#accType1').val(),limit:$('#limit').val().replace(/,/g, ""),expDate:$('#expDate').val(),new_:true}, 
											  dataType: 'json',
											  success: function(s){
												console.log(s);
												try {
													$('#photo').each(function() {
														var file =  this.files[0];
														var name = file.name;
														var filePath = $(this)[0].value;
														var extn = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
														
														var file_data = $('#photo').prop('files')[0];   
														var form_data = new FormData();                  
														form_data.append('file', file_data);
														$.ajax({
															url: "save/pic.php?i="+$('#accID1').val(), // what to expect back from the PHP script, if anything
															cache: false,
															contentType: false,
															processData: false,
															data: form_data,                         
															type: 'post',
															success: function (s) {
																window.location.assign('customer-view.php?i='+$('#accID1').val());
															}
														});
													});
												}
												catch(err){
													window.location.assign('customer-view.php?i='+$('#accID1').val());
												}
											}
										  });
									}
									
								 },
								 error: function(e){
									console.log(e);
								 }
							});
						  }
					   }
					});
			}
		}
	});
	
});
</script>
</body>
</html>
<?php
}else
	header("Location: index.php");
	?>