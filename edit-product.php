<?php require_once('Connections/connSIMS.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

mysql_select_db($database_connSIMS, $connSIMS);
$query_rsCategory = "SELECT catID, catName FROM category WHERE category.catStatus ='Active'";
$rsCategory = mysql_query($query_rsCategory, $connSIMS) or die(mysql_error());
$row_rsCategory = mysql_fetch_assoc($rsCategory);
$totalRows_rsCategory = mysql_num_rows($rsCategory);
include('class/class.php');
$product = new Product($_GET['prodID']);
?>
<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="media/css/bootstrap.min.css" rel="stylesheet">
<script src="media/js/bootstrap.min.js"></script>
<script src="media/js/jquery.min.js"></script>
<script src="functions.js"></script>
</head>
<script>
	$(document).ready(function () {
		$('#modules').addClass('active');
		$('#modules_menu').show();
		$('#add-product').addClass('selected');
	});
</script>
<body>
        	<style>
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
			</style>
        	<table width="100%">
              <tr>
                <td><span class='required'>*</span> Product Description :</td>
                <td><input type="text" class="form-control" id="name" style="text-transform:capitalize" value="<?php echo $product->prodName;?>"/></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Category :</td>
                <td><select id="category" class="form-control" value="<?php echo $product->prodCategory;?>">
                	<option value="0">Select Category</option>
					<?php 
						do {
							echo '<option value="'.$row_rsCategory['catID'].'" '.($row_rsCategory['catID']==$product->prodCategory ? '': 'selected' ).'>'.$row_rsCategory['catName'].'</option>';
						}while($row_rsCategory = mysql_fetch_array($rsCategory));
					?>
                    </select>
                </td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Product ID/Barcode :</td>
                <td><input type="text" class="form-control" id="id" value="<?php echo $product->prodID;?>" /></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Stock(#UNITS) :</td>
                <td><input type="text" class="form-control" id="stock" value="<?php echo $product->prodStock;?>" /></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Price : </td>
                <td><input type="text" class="form-control" id="price" value="<?php echo $product->prodPrice;?>" /></td>
              </tr>
              <tr>
                <td>Status :</td>
                <td><input type="checkbox" id="status"  style="margin-left:20px" <?php echo ($product->prodStatus=='Active' ? '' : 'checked="checked"');?> /> Active</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Submit</button><button id="cancel" class="js-modal-close btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Cancel</button></td>
              </tr>
            </table>
<script>
$(document).ready(function() {
	$('#stock').keypress(function(e) {
	  if ($.isNumeric(String.fromCharCode(e.keyCode))){
		  if(new Number($(this).val() + String.fromCharCode(e.keyCode)) <= 0)
			  return false;
		  else
			  return true;
	  }
	  return false;
  });
  $('#cancel').click(function() {
	  window.location.assign('products.php');
	  });
  $('#price').autoNumeric('init',{'vMin':0});
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
	$('#submit').click(function() {
			var check = true;
			$('input[type=text],select').each(function() {
				if(this.value == '' || (this.type=='select-one' && this.value =='0')){
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
				$('#header2').css('margin-bottom','0');
				$('#id1').hide();
				$('#box').show();
			}
			else{
				$('.close').click();
				//alert($('input[type=checkbox]').prop('checked'));
				$.ajax({
					url: 'check/check.php',
					dataType: 'json',
					data: {prodID:$.trim($('#id').val())},
					success: function(s){
						console.log(s);
						if(s[0]=='true'){
							$('#header2').css('margin-bottom','0');
							$('#box').hide();
							$('#id1').show();
						}
						else {
							$.ajax({
								url: 'save/save.php',
								data: {prodID:$.trim($('#id').val()),prodCategory:$('#category').val(),prodName:$('#name').val(),prodStock:$('#stock').val(),prodPrice:$('#price').val(),prodStatus:$('input[type=checkbox]').prop('checked')}	,
								dataType: 'json',
								success: function(s){
									console.log(s);
								}
							});
							window.location.assign('products.php?success=add');
							
						}
					}
				});
			}
		});
	
});
</script>
</body>
</html>
<?php
mysql_free_result($rsCategory);
?>
