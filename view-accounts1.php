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
<title></title>

<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/sales-view.dataTable"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>
<span id="addPurchase" style="display:none;visibility:0"><?php echo $row_rsAccount['addPurchase'];?></span>
<script>
$(document).ready(function () {
	$('.print_info').fadeIn(1000).fadeOut(2500);
	$('#setup').addClass('active');
	$('#setup_menu').show();
	$('#users_menu').addClass('selected');
	$(document).ajaxStart(function(){
		$(this).css('cursor','wait');
	});
	$(document).ajaxSuccess(function(){
		$(this).css('cursor','auto');
	});
	$('#list').dataTable({
		"scrollY":        "300px",
		"scrollCollapse": false,
		"paging":         false
	});

	$('#print').attr('data-modal-id',"print_popup");
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
	$('select').change(function(){
		($(this).val()!='all') ? $('#search').attr('disabled',false) : $('#search').val('').attr('disabled',true);
	});
	function getData(){
		var list = $('#list').dataTable();
		$.ajax({
			url: 'fetch/get-aCC.php',
			dataType: 'json',
			type:'get',
			data: {type:$('select').val(),value:$('#search').val(),from:$('#from').val(),to:$('#to').val()},
			success: function(s){
				console.log(s);
				list.fnClearTable();
				for(var i = 0; i < s.length; i++) {
					list.fnAddData([
					i+1,	
					s[i][0],
					s[i][1],
					s[i][2],
					s[i][3],
					s[i][4],
					s[i][5],
					s[i][6]
					]);
				} // End For
				if(s.length==0)
					$('#noData').fadeIn(1000).fadeOut(1500);
				
			},
			error: function(e){
				console.log(e.responseText);
			}
		});
	}
	$('#go').click(function() {
		getData();	
	});
	shortcut.add('enter',function(){
		$('#go').click();
	});
	
});	
</script>
<style>
	#list_filter {
		display:none;
	}
	#list a {
	  color: #337ab7 !important;
	}
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
	.modal-body input {
	  text-align: center;
	  font-size: 31px !important;
	  height: auto;
	  border:0;
	}
	.modal-body input:focus {
	  border:none !important;
	  box-shadow:none !important;
	  background-color:none !important;
	  border-color:0 !important;
	}
	.modal-body .form-control {
	  overflow: hidden !important;
	  height: inherit !important;
	}
	.modal-body input[type=date]{
	  margin-left: -20px;
	  padding-left: 4px;
	  height: 75px;
	  margin-top: -15px;
	  width: 326px;
	}
	.modal-body div .form-control {
	  height:60px !important;
	}
	#print_popup .btn {
	  font-size: 14px;
	  padding: 0.75em 1.5em;
	  /* margin-left: 20px; */
	  font-weight: bold;
	}
	#edit_popup{
		height: 350px !important;
	} 
</style>
<body>
<div style="display:none" id="parent">setup</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Accounts</div>
    <div id="success" class="bg-success" style="display:<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'block;' : 'none;')?> padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<?php
			if($_GET['success']=='add'){
				echo 'Account has been added successfuly.';
			}
			else if($_GET['success']=='delete'){
				echo 'Account has been deleted successfully.';
			}
			else if($_GET['success']=='edit'){
				echo 'Account has been updated successfully.';
			}
		?>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
	<div id="update-success" class="bg-success" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
		Account has been updated successfully.
		<button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
	</div>	
	<div id="delete-success" class="bg-success" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
		Account has been deleted successfully.
		<button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
	</div>
	<div id="update-error" class="bg-error" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
		Account did not update successfully.
		<button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
	</div>	
	<div id="delete-error" class="bg-error" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
		Account did not deleted successfully.
		<button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
	</div>	
    <div style="border:1px solid lightgray;border-radius:4px">
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list"></span> Accounts List</div>
        <div style="padding:5px">
        	<style>
				
				th,#list td:nth-child(5),#list td:nth-child(4),#list td:nth-child(6) {
					text-align:center !important;
				}
				#list td:nth-child(4) {
					text-align:left;
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
				input[type=date] {
					height: 28px;
					width: inherit;
				}
				#edit_popup td:nth-child(1) {
					text-align: right;
					font-weight: bold;
					width: 40%;
				}
				#edit_popup td:nth-child(2) {
					padding: 5px;
					padding-left: 23px;
				}
				#edit_popup .form-control {
					width:70%;
				}
				#list td:nth-child(8),#list th:nth-child(8){
					display:none;
				}
			</style>
			<table width="100%" style="margin:0px 0px 5px 0px">
				<tr>
					<td width="29%">
						<div style="float:left;width:72%" class="input-group"><span class="input-group-addon" style="background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;"><span class="glyphicon glyphicon-search"></span> </span><input type="text" class="form-control" placeholder="Search" style="font-weight:normal" aria-controls="list" id="search" disabled></div>
						<select class="form-control1" style="margin-left:5px;float:right;height: 28px;width:101px">
							<option value="all">-- All --</option>
							<option value="uname">Username</option>
							<option value="name">Name</option>
						</select>
						
					</td>
					<td style="text-align:right">&nbsp;</td>
					<td width="20%" style="text-align:right"><button style="color:navy;font-weight:bolder" class="btn" id="go">{ }</button><button class="btn" id="edit" data-modal-id="edit_popup"><span class="glyphicon glyphicon-pencil" style="color:navy;margin-right:3px"></span>Edit</button><button class="btn" id="print" data-modal-id="print_popup"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button></td>
				</tr>
			</table>
        	<table width="100%" id="list">
              	<thead>
					<th width="2%">#</th>  
                	<th width="10%">Username</th>
                    <th>Account Name</th>
					<th width="8%">Status</th>
                    <th width="13%">Last Login</th>
					<th width="13%">Current Login</th>
                    <th width="12%">Account Type</th>
					<th style="display:none"></th>
                </thead>
            </table>
			<input type="hidden" id="uname-e">
			<input type="hidden" id="accountname-e">
			<input type="hidden" id="password-e">
			<input type="hidden" id="type-e">
			<input type="hidden" id="status-e">
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
	<div id="edit_popup" class="modal-box" style="width:50%;top:10px;height:350px;">
		<header>
			<h3>User Account<span title="Close" class="glyphicon glyphicon-remove-sign js-modal-close" aria-hidden="true" style="float:right;font-size: 17px;cursor: pointer;"></span></h3>
		</header>
		<div id="popup-header" style="text-align:center;padding:2px;display:initial;height:initial">
			<div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
				<span id="blank" style="display:none">Please fill all the boxes in red.</span>
				<span id="pass" style="display:none">Password did not match.</span>
				<span id="id1" style="display:none">Username already exist. Please user other username.</span>
				<span id="name1" style="display:none">Account name already exist. Please user other name.</span>
				<span id="length" style="display:none">Password must contain six characters above.</span>
				<span id="upper" style="display:none">Password must contain atleast one uppercase character.</span>
				<span id="lower" style="display:none">Password must contain atleast one lowercase character.</span>
				<span id="number" style="display:none">Password must contain atleast one number.</span>
				<span id="error" style="display:none"></span>
				<button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true" style="font-size: 17px;">&times;</span></button>
			</div>
			<table width="100%" style="width:100% !important">
				<input type="hidden" id="editaccid">
				<input type="hidden" id="username">
				<tr>
					<td><span class='required'>*</span> Username :</td>
					<td><input type="text" class="form-control" id="uname" placeholder="Username" disabled></td>
				</tr>
				<tr>
					<td><span class='required'>*</span> Account Name :</td>
					<td><input type="text" class="form-control" id="accName" placeholder="Account Name" style="text-transform: capitalize;"></td>
				</tr>
				<tr>
					<td><span class='required'>*</span> Password :</td>
					<td><input type="password" class="form-control" id="pass1" placeholder="Password"></td>
				</tr>
				<tr>
					<td><span class='required'>*</span> Confirm Password :</td>
					<td><input type="password" class="form-control" id="pass2" placeholder="Confirm Password"></td>
				</tr>
				<tr>
					<td>Account Type :</td>
					<td>
						<select id="type" class="form-control" style="width: 30%; background: url(http://127.0.0.1:57509/media/images/arrow_down.png) 98% 50% / 14px no-repeat transparent;font-size: 14px;padding: initial;padding-left: 9px;">
							<option value="CS">Cashier</option>
							<option value="BS">Basic</option>
							<option value="IM">Intermediate</option>
							<option value="AV">Advance</option>
					</td>
				</tr>
				<tr>
					<td>Status :</td>
					<td style="text-align:left"><input type="checkbox" id="status" checked="checked" style="margin-left:20px"> Active</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td style="text-align:left">
						<button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);font-size: 14px;padding: 0.65em 1.25em;">Save</button>
						<button id="cancel" class="btn js-modal-close" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);font-size: 14px;padding: 0.65em 1.25em;">Cancel</button>
					</td>
              </tr>
		    </table>
		</div>
    </div>
</div>
<script>
$(document).ready(function() {
	function decode_base64(r){var t,e=l=0,n="",r=r.split(""),i="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".split("");for(t in r)for(e=(e<<6)+i.indexOf(r[t]),l+=6;l>=8;)n+=String.fromCharCode(e>>>(l-=8)&255);return n.trim()}function ord(r){var t=r+"",e=t.charCodeAt(0);if(e>=55296&&56319>=e){var n=e;if(1===t.length)return e;var i=t.charCodeAt(1);return 1024*(n-55296)+(i-56320)+65536}return e>=56320&&57343>=e?e:e}function substr(r,t,e){var n=0,i=!0,s=0,u=0,a=0,c="";r+="";var h=r.length;switch(this.php_js=this.php_js||{},this.php_js.ini=this.php_js.ini||{},this.php_js.ini["unicode.semantics"]&&this.php_js.ini["unicode.semantics"].local_value.toLowerCase()){case"on":for(n=0;n<r.length;n++)if(/[\uD800-\uDBFF]/.test(r.charAt(n))&&/[\uDC00-\uDFFF]/.test(r.charAt(n+1))){i=!1;break}if(!i){if(0>t)for(n=h-1,s=t+=h;n>=s;n--)/[\uDC00-\uDFFF]/.test(r.charAt(n))&&/[\uD800-\uDBFF]/.test(r.charAt(n-1))&&(t--,s--);else for(var o=/[\uD800-\uDBFF][\uDC00-\uDFFF]/g;null!=o.exec(r);){var f=o.lastIndex;if(!(t>f-2))break;t++}if(t>=h||0>t)return!1;if(0>e){for(n=h-1,u=h+=e;n>=u;n--)/[\uDC00-\uDFFF]/.test(r.charAt(n))&&/[\uD800-\uDBFF]/.test(r.charAt(n-1))&&(h--,u--);return t>h?!1:r.slice(t,h)}for(a=t+e,n=t;a>n;n++)c+=r.charAt(n),/[\uD800-\uDBFF]/.test(r.charAt(n))&&/[\uDC00-\uDFFF]/.test(r.charAt(n+1))&&a++;return c}case"off":default:return 0>t&&(t+=h),h="undefined"==typeof e?h:0>e?e+h:e+t,t>=r.length||0>t||t>h?!1:r.slice(t,h)}return void 0}function chr(r){return r>65535?(r-=65536,String.fromCharCode(55296+(r>>10),56320+(1023&r))):String.fromCharCode(r)}function decrypt(r,t){$decrypted="",$string=decode_base64(r);for(var e=0;e<$string.length;e++)$char=substr($string,e,1),$keychar=substr(t,e%t.length-1,1),$char=chr(ord($char)-ord($keychar)),$decrypted+=$char;return $decrypted.replace(/[^ -~]+/g,"")}
	
	$('#print,#edit').click(function() {
		var modalBox = $(this).attr('data-modal-id');
		var view_width = $(window).width();
		var view_top = $(window).scrollTop() + 10;
		$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
		
		if(modalBox=='print_popup'){
			$('iframe').attr('src','report/account-list.php?type='+$('select').val()+'&value='+$('#search').val());
			$('#'+modalBox+' iframe').css('height',$(window).height()-90);
			$('#'+modalBox).css('height',$(window).height()-90);
			$('#'+modalBox).css("top", view_top);
		}
		else{
			if(!$('#list tr').hasClass('active_'))
				return;
			$('#'+modalBox).css('height','350px !important');
			$('#'+modalBox).css("top", view_top+150);
			$('#editaccid').val($('#list tr.active_').find('td').eq(7).text());
			$('#username').val($('#list tr.active_').find('td').eq(1).text());
			$.ajax({
				url: 'fetch/get-aCC.php',
				dataType: 'json',
				type:'get',
				data: {'edit-acc':true,accID:$('#editaccid').val()},
				success: function(s){
					console.log(s);
					s[0][2] = decrypt(s[0][2],s[0][0]).substr(0, s[0][5]);
					$('#uname,#uname-e').val(s[0][0]);
					$('#accName,#accountname-e').val(s[0][1]);
					$('#pass1,#pass2,#password-e').val(s[0][2]);
					$('#type-e').val(s[0][3]);
					$('#status-e').val(s[0][4]);
					$('option[value='+s[0][3]+']').prop('selected',true);
					
					(s[0][4]=='Active') ? $('#status').prop('checked',true) : $('#status').prop('checked',false); 
				},
				error: function(e){
					console.log(e.responseText);
				}
			});
			
		}
		var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
		$("body").append(appendthis);
		$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
		$(".modal-overlay").css('position','fixed');
		$('#'+modalBox).fadeIn(1000);
	});
	function checkChanges(){
		if($('#accName').val()!=$('#accountname-e').val())
			return true;
		if($('#pass1').val()!=$('#password-e').val())
			return true;
		if($('#type').val()!=$('#type-e').val())
			return true;
		if(($('#status').prop('checked')==true && $('#status-e').val()!='Active') || ($('#status').prop('checked')!=true && $('#status-e').val()!='Banned'))
			return true;
		if($('#pass1').val()!=$('#pass2').val())
			return true;
		return false;
	}
	function clear1(){
		$('#update-success,#delete-success,#update-error,#delete-error,#success').hide();
	}
	function clear(){
		$('#blank,#pass,#id1,#name1,#length,#upper,#lower,#number').hide();
	}
	function showError1(id){
		$('#header2').css('margin-bottom','0');
		clear1();
		$('#'+id).show();
	}
	function showError(id){
		$('#popup-header').css('padding','0px');
		clear();
		$('#box,#'+id).show();
		var pass = ["length","upper","lower","number"];
		if(pass.indexOf(id)>=0)
			showErrorPassword();
	}
	$('.close').click(function() {
		$('#popup-header').css('padding','2px');
	});
	function showErrorPassword(){
		$('#pass1,#pass2').parent('td').removeClass('has-success');
		$('#pass1,#pass2').parent('td').removeClass('has-error');
	}
	$('#submit').click(function() {
		if(checkChanges()){
			$('.close').click();
			var check = true;
			$('#edit_popup input[type=text],#edit_popup input[type=password]').each(function(index, element) {
				if(this.id!='uname'){
					if($(this).val().trim()==''){
						check = false;
						$(this).val('');
						$(this).parent('td').removeClass('has-success');
						$(this).parent('td').addClass('has-error');
					}
					else{
						$(this).parent('td').removeClass('has-error');
						$(this).parent('td').addClass('has-success');
					}
				}
			});
			if(!check)
				showError('blank');
			else{
				if($('#pass1').val() != $('#pass2').val()){
					showError('pass');
					check = false;
				}
			}
			if(check){
				name = $('#accName').val();
				name = name.split();
				for(var i=0; i<name.length; i++)
					if(i==0 || name[i]==' ')
						(i==0) ? name[i] = name[i].toUpperCase() : name[i-1] = name[i-1].toUpperCase(); 
				$.ajax({
					url: 'check/check.php',
					dataType: 'json',
					data: {'accName':name,'accUsername':$('#uname-e').val()},
					success: function(s){
						console.log(s);
						if(s[0]==true){
							showError('name1');
							$('#accName').parent('td').removeClass('has-error');
						}
						else{
							if($('#pass1').val().length < 6)
								showError('length');
							else{
								if($('#pass1').val().match(/[A-Z]/g) == null)
									showError('upper');
								else{
									if($('#pass1').val().match(/[a-z]/g) == null)
										showError('lower');
									else{
										if($('#pass1').val().match(/[0-9]/g) == null)
											showError('number');
										else{
											$.ajax({
												url: 'save/saveA.php',
												dataType: 'json',
												type: 'post',
												data: {'update':true,'uname':$('#uname-e').val(),'name':$('#accName').val(),'pass':$('#pass1').val(),'type':$('#type').val(),'status':$('input[type=checkbox]').prop('checked'),'account':$('#accID').html()},
												success: function(s){
													console.log(s);
													$('.js-modal-close').click();
													showError1('update-success');
													alert('Sucess');
												},
												error: function(e){
													console.log(e);
													$('#error').html(f);
													showError('error');
													alert('Error');
												}
											});
										}
									}
								}
							}
							
						}
					},
					error: function(f){
						$('#error').html(f);
						showError('error');
					}	
				});
			}
		}
		else {
			$('.js-modal-close').click();
		}
	});
	$(".js-modal-close, .modal-overlay").click(function() {
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	});
});
</script>
<!--<div class="print_info" style="display:none"><h6>Hint!</h6><p>When searching for a Sales Order by date, enter the date in format YYYY-MM-DD or by time in format HH:MM in 24H format</p></div>-->
<div class="print_info" id="noData" style="display:none"><h6>No data!</h6></div>
</body>
</html>
<?php
}
else
header("Location: index.php");
?>
