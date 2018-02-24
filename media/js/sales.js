// JavaScript Document
$(document).ready(function () {
  function clear(){
	$('#id').val();
  	$('#price').val('');
	$('#desc').val('');
	$('#qty').val('');
	$('#qtyy').html('');
  }
  $('#id').keydown(function() {
	  clear();
  });
  function num(num) {
    var n= num.toString().split(".");
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return n.join(".");
  }

  $('#custID').autocomplete({
	  source:'fetch/get-customer.php',
	  minLength:1,
	  autoFocus:true,
	  select: function( event, ui ) {
		  $.ajax({
			  url: 'fetch/get-customer.php?sql=sale',
			   type: 'get',
			   data: {'custID':$(ui)[0].item.value},
			   dataType: 'json',
			   success: function(s){
				  console.log(s);
				  if(!s[0] && !s[1] && !s[2] && !s[3]){
					
				  	bootbox.dialog({
						message: "Warning! Customer's account is temporarily blocked.",
						buttons: {
							main: {
								label: 'OK',
								className: "btn"
							}
						}
					});
					$('#custID').val('').focus();
				  }
				  else {
					  if(s[3]!=false && new Number(s[3]) < 0){
						bootbox.dialog({
							message: "Warning! Customer's account has already expired",
							buttons: {
								main: {
									label: 'OK',
									className: "btn"
								}
							}
						});
					}
					else{
						if(new Number(s[3])<= 15 && s[3]!=false){
						$('#days').html(s[3])
						$('#expire_info').fadeIn(1000).fadeOut(3500);
						}
						$('#name').val(s[0]);
						$('#credit').val(s[1]);
						$('#limit').val(s[2]);
						$('#id').focus();
					}
				  }
			   }
		  });
	  },
	  change: function(e, ui) {
		  if (!ui.item) {
			  $(this).val("");
			  $('#cust_info input').each(function() {
				  $(this).val("");
				  });
			  $(this).focus();
		  }
	  }
  }).on("keydown", function(e) {
    	$('#name,#limit,#credit').val('');
  });
  /*$('#id').blur(function() {
	   $.ajax({
		   url: 'fetch/get-product.php',
		   type: 'get',
		   data: {'prodID':$(this).val()},
		   dataType: 'json',
		   success: function(s){
			  console.log(s);
			  if(new Number(s[2]) > 0){
				  $('#price').val(s[0]);
				  $('#desc').val(s[1]);
				  $('#qty').focus();
				  $('#qtyy').html(s[2]);
			  }
			  else{
				  $(this).val("");
				  $('#input input').each(function() {
					  $(this).val("");
					  });
				  $(this).focus();
			  }
		   }
	  });
  });*/
	/*$(document).scannerDetection({
		timeBeforeScanTest: 100, // wait for the next character for upto 200ms
		startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
		endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
		onComplete: function(barcode, qty){
			if(!$('input').is(':focus') || $('#id').is(':focus')){ 
				$("#id").val(barcode);
				var prodID = barcode;
				$.ajax({
					url: 'fetch/get-products.php?aom&so',
					type: 'get',
					data: {'prodID':prodID},
					dataType: 'json',
					success: function(s){
						console.log(s);
						if(new Number(s[0][2]) > 0){
							$('#price').val(s[0][0].replace(/\B(?=(\d{3})+(?!\d))/g, ","));
							$('#desc').val(s[0][1]);
							$('#qty').val(1);
							$('#qtyy').html(s[0][2]);
							$('#qtyType').html(s[0][3]);
							//$('#discountP').val(s[3]);
							//$('a[data-modal-id="percent_popup"]').click();
							//$('#percent_button').click();
							if(s[2][0]!=false){
								var choices = '<button style="padding:8px;margin:0px 2.5px" onClick="clickUOM(this)" class="btn" data-conversion="1" data-measDesc="'+s[1][1]+'" data-measurement="'+s[1][3]+'" data-choice="'+s[1][2]+'">'+s[1][1]+'<div id="key" style="font-size:10px">'+'1.00 '+s[1][2]+'</div></button>';
								for(var i=2; i<s.length; i++)
									choices += '<button style="padding:8px;margin:0px 2.5px" onclick="clickUOM(this)" class="btn" data-conversion="'+s[i][2]+'" data-measDesc="'+s[i][1]+'"  data-measurement="'+s[i][3]+'" data-choice="'+s[i][0]+'">'+s[i][1]+'<div id="key" style="font-size:10px">'+s[i][2]+' '+s[1][2]+'</div></button>';
								$('#choices').html(choices);
								$('#selectprodID').html(prodID);
								$('#select_uom').fadeIn(500);
							} 
							else 
								$('#add_item').click();
							if(s[7] == 'Whole Number')
								$('#qty').autoNumeric('init',{'vMin':0,'mDec':0});
							else
								$('#qty').autoNumeric('init',{'vMin':0,'mDec':2});
						}
						else{
							$('#input input').each(function() {
								$(this).val("");
								});
							$("#id").val("").blur();
							var msg='';
							msg = "Item with zero(0) quantity on inventory cannot be selected."
							if(s[2]=='false')
								msg = "Item not found.";
							bootbox.dialog({
								message: msg,
								buttons: {
									main: {
										label: 'OK',
										className: "btn"
									}
								}
							});
							$('#lookup input').focus();
						}
						$('#id').blur();
					}
				});
			}
				
		} // main callback function	
	});*/

  $(document).scannerDetection({
	 	 timeBeforeScanTest: 100, // wait for the next character for upto 200ms
	  startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
	  endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	  avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	  onComplete: function(barcode, qty){
		  if(!$('input').is(':focus') || $('#id').is(':focus')){ 
	  		$("#id").val(barcode);
			var prodID = barcode;
			 $.ajax({
				 url: 'fetch/get-product.php',
				 type: 'get',
				 data: {'prodID':prodID},
				 dataType: 'json',
				 success: function(s){
					console.log(s);
					if(new Number(s[2]) > 0){
						$('#price').val(s[0].replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						$('#desc').val(s[1]);
						$('#qty').val(1);
						$('#qtyy').html(s[2]);
						$('#qtyType').html(s[7]);
						$('#discountP').val(s[3]);
						$('#data-unit').val(s[8]);
						$('#data-conversion').val('1');
						//$('a[data-modal-id="percent_popup"]').click();
						//$('#percent_button').click();
						$('#add_item').click();
						if(s[7] == 'Whole Number')
							$('#qty').autoNumeric('init',{'vMin':0,'mDec':0});
						else
							$('#qty').autoNumeric('init',{'vMin':0,'mDec':2});
					}
					else{
						$('#input input').each(function() {
							$(this).val("");
							});
						$("#id").val("").blur();
						var msg='';
						msg = "Item with zero(0) quantity on inventory cannot be selected."
						if(s[2]=='false')
							msg = "Item not found.";
						bootbox.dialog({
							message: msg,
							buttons: {
								main: {
									label: 'OK',
									className: "btn"
								}
							}
						});
						$('#lookup input').focus();
					}
					$('#id').blur();
				 }
			});
		  }
			
	  } // main callback function	
  });
  $('#closeParent').click(function() {
	  $('#select_uom').fadeOut(500);
  });
   destroy = function(id,measurement){
	  $('#'+id).autoNumeric('destroy');
	  if(measurement=='Whole Number')
	  	$('#'+id).autoNumeric('init',{'vMin':0,'mDec':0});
	  else	
	  	$('#'+id).autoNumeric('init',{'vMin':0,'mDec':2});
  }
  clickUOM = function(e){
	  $('#desc').val($('#desc').val()+' ('+$(e).attr('data-measdesc')+')');
	  $('#data-measurement').val($(e).attr('data-measurement'));
	  $('#data-conversion').val($(e).attr('data-conversion'));
	  $('#data-unit').val($(e).attr('data-choice'));
	  $('#data-price').val(new Number($('#data-price').val())*new Number($(e).attr('data-conversion')));
	  $('#price').val(new String(new Number($('#data-price').val().replace(/,/g, "")).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
	  $('#select_uom').fadeOut(500);
	  $('#qty').focus();
	  destroy('qty',$(e).attr('data-measurement'));
  }
  $('#select_prod').click(function() {
		if($('#product').dataTable().$('tr').hasClass('selected')){
			//alert($('#product .selected td').eq(0).text());
			if(new Number($('#product .selected td').eq(4).text().replace(/,/g, "")) > 0 && $('#product .selected td').eq(3).text()!='Inactive'){
				/*$('#id').val($('#product .selected td').eq(0).text());
				$('#price').val($('#product .selected td').eq(4).text());
				$('#desc').val($('#product .selected td').eq(1).text());
				$('#qtyy').html($('#product .selected td').eq(3).text().replace(/,/g, ""));
				$('#discountP').val($('#product .selected td').eq(5).text());
				$('.js-modal-close').click();
				$('#qty').focus();*/
				prodID = $('#product .selected td').eq(1).text();
				 $.ajax({
					url: 'fetch/get-products.php?aom&so',
					type: 'get',
					data: {'prodID':prodID},
					dataType: 'json',
					success: function(s){
						console.log(s);
						if(new Number(s[0][2]) > 0){
							$('#edit').click();
							$('#id').val(prodID);
							$('#data-price').val(s[0][0]);
							$('#desc').val(s[0][1]);
							$('#qtyy').html(s[0][2]);
							$('#qtyType').html(s[0][3]);
							$('#data-measurement').val(s[0][3]);
							$('#data-conversion').val('1');
							$('#data-unit').val(s[0][4]);
							if(s[2][0]!=false){
								var choices = '<button style="padding:8px;margin:0px 2.5px" onClick="clickUOM(this)" class="btn" data-conversion="1" data-measDesc="'+s[1][1]+'" data-measurement="'+s[1][3]+'" data-choice="'+s[1][2]+'">'+s[1][1]+'<div id="key" style="font-size:10px">'+'1.00 '+s[1][2]+'</div></button>';
								for(var i=2; i<s.length; i++)
									choices += '<button style="padding:8px;margin:0px 2.5px" onclick="clickUOM(this)" class="btn" data-conversion="'+s[i][2]+'" data-measDesc="'+s[i][1]+'"  data-measurement="'+s[i][3]+'" data-choice="'+s[i][0]+'">'+s[i][1]+'<div id="key" style="font-size:10px">'+s[i][2]+' '+s[1][2]+'</div></button>';
								$('#choices').html(choices);
								$('#selectprodID').html(prodID);
								$('#select_uom').fadeIn(500);
							}
							else{
								$('#price').val($('#data-price').val());
								$('#qty').focus();
							}
						}
						else{
							$('#input input').each(function() {
								$(this).val("");
								});
							$('#id').val("").focus();
							bootbox.dialog({
								message: "Item with zero(0) quantity on inventory cannot be selected.",
								buttons: {
									main: {
										label: 'OK',
										className: "btn"
									}
								}
							});
						}
					}
				});
			}
			else{
			var msg = new String();
			if($('#product .selected td').eq(3).text() == 'Inactive')
				msg = "Inactive product can not be selected.";
			else 
				msg = "Item with zero(0) quantity on inventory can not be selected.";
			bootbox.dialog({
				message: msg,
				buttons: {
					main: {
						label: 'OK',
						className: "btn"
					}
				}
			});
			}
		}
	});
  $("#id").autocomplete({
	  source:'fetch/get-product.php',
	  minLength:1,
	  autoFocus:true,
	  select: function( event, ui ) {
		  var prodID = $(ui)[0].item.value;
		  
		   $.ajax({
			   url: 'fetch/get-products.php?aom&so',
			   type: 'get',
			   data: {'prodID':prodID},
			   dataType: 'json',
			   success: function(s){
				  console.log(s);
				  //alert('asas');
				  if(new Number(s[0][2]) > 0){
					  $('#data-price').val(new String(s[0][0]).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					  $('#desc').val(s[0][1]);
					  //$('#qty').focus();
					  $('#qtyy').html(s[0][2]);
					  $('#qtyType').html(s[0][3]);
					  $('#data-measurement').val(s[0][3]);
					  $('#data-conversion').val('1');
					  $('#data-unit').val(s[0][4]);
					  if(s[2][0]!=false){
						var choices = '<button style="padding:8px;margin:0px 2.5px" onClick="clickUOM(this)" class="btn" data-conversion="1" data-measDesc="'+s[1][1]+'" data-measurement="'+s[1][3]+'" data-choice="'+s[1][2]+'">'+s[1][1]+'<div id="key" style="font-size:10px">'+'1.00 '+s[1][2]+'</div></button>';
						for(var i=2; i<s.length; i++)
							choices += '<button style="padding:8px;margin:0px 2.5px" onclick="clickUOM(this)" class="btn" data-conversion="'+s[i][2]+'" data-measDesc="'+s[i][1]+'"  data-measurement="'+s[i][3]+'" data-choice="'+s[i][0]+'">'+s[i][1]+'<div id="key" style="font-size:10px">'+s[i][2]+' '+s[1][2]+'</div></button>';
						$('#choices').html(choices);
						$('#selectprodID').html(prodID);
						$('#id').blur();
						$('#select_uom').fadeIn(500);
					   }
					  else{
						  $('#price').val($('#data-price').val());
						  $('#qty').focus();
					  }
				  }
				  else{
					  $(this).val("");
					  $('#input input').each(function() {
						  $(this).val("");
						  });
					  $(this).focus();
					  bootbox.dialog({
						  message: "Item with zero(0) quantity on inventory cannot be selected.",
						  buttons: {
							  main: {
								  label: 'OK',
								  className: "btn"
							  }
						  }
					  });
				  }
			   }
		  });
	  },
	  change: function(e, ui) {
		  if (!ui.item) {
			  $(this).val("");
			  $('#input input').each(function() {
				  $(this).val("");
				  });
			  $(this).focus();
		  }
	  }
  });
  /*$('#qty').keypress(function(e) {
	  if(new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number($('#qtyy').html())){
	  	return true;
	  }
	  else
	  	return false;
  });*/
  
  $('#qty').keypress(function(e) {
	  if ($.isNumeric(String.fromCharCode(e.keyCode))){
			  if($(this).val().indexOf(".")>=0){
			  	x = $(this).val();
				if((x.substr($(this).val().indexOf(".")+1)+String.fromCharCode(e.keyCode)).length<=2 && new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number($('#qtyy').html()))
					return true;
				else
					return false;
			  }
			  if(new Number($(this).val() + String.fromCharCode(e.keyCode)) <= 0)
				  return false;
			  else{
				  if(new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number($('#qtyy').html())){
					  return true;
				  }
				  return false;
			  }
		  }
	  else if(String.fromCharCode(e.keyCode)=='.' && $('#qtyType').html()=='Decimal'){
	  	if($(this).val() == '')
			return false;
		else if($(this).val().indexOf(".")>=0)
			return false;
		else{
			if(new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number($('#qtyy').html())){
				return true;
			}
			return false;
		}
	  }
	  return false;
  });
  /*$('#qty').keyup(function() {
	  $(this).val(function(index, value) {
		  return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		});
   });*/
   $('#qty').focus(function() {
	   if($('#id').val()=='' || $('#price').val()=='' || $('#desc').val()==''){
	   		clear();
			$('#id').focus();
	   }
   });
   shortcut.add('f7', function() {
	   if( $('#pay_stats').html() != 'Ready')
	   $('#refresh').click();
	   });
   shortcut.add('f8', function() {
	   if( $('#pay_stats').html() != 'Saved')
	   $('#save').click();
	   });
   shortcut.add('f6', function() {
	   if( $('#pay_stats').html() != 'Saved')
	   	$('#delete').click();
	   });
   $('#delete').click(function() {
	   if( $('#pay_stats').html() != 'Saved' && $('#pay_stats').html() != 'Ready')
   		if($('#list2 tr').hasClass('active_')){
			$('#total').html(new String((new Number($('#total').html().replace(/,/g, "")) - new Number($('#list2 tr.active_').find('td').eq(5).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			
			$.ajax({
				url: 'save/save.php',
				type: 'get',
				data: {prodID:$('#list2 tr.active_').find('td').eq(1).text(),unit:$('#list2 tr.active_').find('td').eq(0).text(),accID:$('#accID').html(),temp_list:'DELETE'}
			});
			$('#list2 tr.active_').remove();
		}
   });
   shortcut.add('Ctrl+P', function() {
	   if( $('#pay_stats').html() == 'Saved')
	   		$.ajax({
				url: 'print/receipt.php',
				type: 'get',
				data: {saleID:	$('#SALEIDPRINT').html()},
				dataType: 'json'
			});
   });
   shortcut.add('delete', function() {
	   if( $('#pay_stats').html() != 'Saved')
       $('#delete').click();
   });
   shortcut.add('f2', function() {
	   if( $('#pay_stats').html() != 'Saved')
		$('a[data-modal-id="lookup"]').click();
   });
   shortcut.add('f5', function() {
	   if( $('#pay_stats').html() != 'Saved')
   		$('a[data-modal-id="payment_popup"]').click();
   });

   shortcut.add('f1',function() {
		var check = true;
		$('#input input').each(function() {
			if($(this).val() == ''){
				check = false;
				return
			}
		});
		if(check){
				$('#add_item').click();
			}
		else {
			clear();
			$('#id').focus();
		}
   });
   shortcut.add('f3',function() {
	   if( $('#pay_stats').html() != 'Saved')
	   $('a[data-modal-id=edit_qty_popup]').click();
	});
  shortcut.add('f4',function() {
	  if( $('#pay_stats').html() != 'Saved')
	 $('a[data-modal-id=edit_disc_popup]').click();
  	});
	  $('#percent,#percent_edit').keypress(function(e) {
		if($(this).val().length > 4)
		  return false;
	 });
	 $('#tend').autoNumeric('init',{'vMin':0});
	 $('#percent,#percent_edit').priceFormat({
		  prefix: '',
		  thousandsSeparator: '',
		  allowNegative: false
	  });
	// $('#percent_edit').autoNumeric({'set',value});
	shortcut.add('enter',function() {
		if($('#percent_popup').is(':visible')){
			if($('#qty').val()!=''){
				$('#percent_button').click();
			}
		}
		else if($('.modal-dialog').is(':visible')){
			$('button[data-bb-handler=main]').click();
		}
		else if($('#edit_disc_popup').is(':visible')){
			if($('#percent_edit').val()!=''){
				$('#percent-edit_button').click();
			}
		}
		else if($('#edit_qty_popup').is(':visible')){
			if($('#qty_edit').val()!=''){
				$('#qty_button').click();
			}
		}
		else if($('#payment_popup').is(':visible')){
				$('#pay_button').click();
		}
		else if($('#lookup').is(':visible')){
			$('#select_prod').click();
		}
		else if($('#customer_pop').is(':visible')){
			$('#select_cust').click();
		}
		else if($('#custID').is(':focus')){
			$.ajax({
				url: 'fetch/get-customer.php?sql=sale',
				 type: 'get',
				 data: {'custID':$(this).val()},
				 dataType: 'json',
				 success: function(s){
					console.log(s);
					if(s[0] == false){
					  $(this).val("");
					  $('#cust_info input').each(function() {
						  $(this).val("");
					  });
					  $(this).focus();
					  bootbox.dialog({
						  message: "Customer not found!",
						  buttons: {
							  main: {
								  label: 'OK',
								  className: "btn"
							  }
						  }
					  });
					  return;
					}
					if(s[3]!=false && new Number(s[3]) < 0){
						bootbox.dialog({
							message: "Warning! Customer's account has already expired",
							buttons: {
								main: {
									label: 'OK',
									className: "btn"
								}
							}
						});
					  }
					  else{
						if(new Number(s[3])<= 15 && s[3]!=false){
							$('#days').html(s[3])
							$('#expire_info').fadeIn(1000).fadeOut(3500);
						}
						$('#name').val(s[0]);
						$('#credit').val(s[1]);
						$('#limit').val(s[2]);
						$('#id').focus();
					  }
					 //$('#custID').val('').blur();
				 }
			});	  
		}
		//$('#id').focus();
	});
	function update_total(amount){
		$('#total').html(new String(new Number(new Number($('#total').html()) + amount).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
	}
	function clearSummary(){
		$('#sum-prodName').html('');
		$('#sum-pricexqty').html('');
		$('#sum-prodTotal').html('');
	}
	$(document).on("click","#list2 tr", function(){
		if ( $(this).hasClass('active_') ) {
            $(this).removeClass('active_');
			$('a[data-modal-id="edit_qty_popup"],#delete').attr('disabled',true);
			clearSummary();
        }
        else {
            $('#list2 tr.active_').removeClass('active_');
            $(this).addClass('active_');
			$('a[data-modal-id="edit_qty_popup"],#delete').attr('disabled',false);
			$('#sum-prodName').html($('#list2 tr.active_ td').eq(2).text()+' ('+$('#list2 tr.active_ td').eq(1).text()+')');
			$('#sum-pricexqty').html($('#list2 tr.active_ td').eq(3).text()+' x '+$('#list2 tr.active_ td').eq(4).text()+' '+$('#list2 tr.active_ td').eq(0).text());
			$('#sum-prodTotal').html($('#list2 tr.active_ td').eq(5).text());
        }
		
	});
	$(document).on("click", function(event){
		
		if(!$(event.target).parents().andSelf().is("#list2") && !$(event.target).parent().andSelf().is("[data-modal-id='edit_qty_popup']") && !$(event.target).parent().andSelf().is("[data-modal-id='edit_disc_popup']") && !$(event.target).parents().andSelf().is(".modal-box") && !$(event.target).parents().andSelf().is(".modal-overlay") && !$(event.target).is("#add_item")){
			 $('#list2 tr.active_').removeClass('active_');
			 clearSummary();
			 $('a[data-modal-id="edit_qty_popup"],#delete').attr('disabled',true);
		}
	});
	/*$('#qty_edit').keypress(function(e) {
	  if(String.fromCharCode(e.keyCode)=='0' && $(this).val().length < 1)
	  	 return false;
	  if($.isNumeric(String.fromCharCode(e.keyCode))){
		  var x = new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode));
		  var y = new Number($('tr.active_').find('td').eq(6).text());
		  if( x>0 && new Number(x)<=y){
		  	return true;
		  }
		  else
			  return false;
	  }
	  else
	  	return false;
	});
	$('#qty_edit').keyup(function() {
	  $(this).val(function(index, value) {
		  return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		});
   });*/
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
					$('#tend').val(new String(new Number((new Number($('#total').html().replace(/,/g, ""))*new Number(new Number(100)-new Number(s[0][19])))*new Number(0.01)).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				},
				error: function(ss){
					console.log(ss);
					alert('Error: '.ss);
				}
			  });
			  $('#tend').val('').prop('disabled',true);
		  }
		  else
			$('#tend').val('').prop('disabled',true);
	  }
   });
   $('.alpha').on("keydown", function(event){
		var arr = [8,9,16,17,20,32,35,36,37,38,39,40,45,46,190];
		for(var i = 65; i <= 90; i++){
		  arr.push(i);
		}
	  // Prevent default if not in array
		  if(jQuery.inArray(event.which, arr) === -1){
			event.preventDefault();
			return false;
		  }
	});
   shortcut.add('F7',function() {
	   $('#refresh').click();
	   });
   $('#refresh').click(function() {
	   window.location.assign('sales.php');
	   });
   shortcut.add('F8',function() {
	   if( $('#pay_stats').html() != 'Saved')
	   $('#save').click();
	   });
   /*shortcut.add('backspace',function() {
   	  if($('input').is(':focus'))
	  	e.preventDefault();
   });*/
   $('#save').click(function() {
	   if($('#pay_stats').html()=='Ready'){
		  // if($('#type_det').html() == 'credit' && new Number($('#total').html().replace(/,/g, ""))<=new Number($('#limit').val().replace(/,/g, ""))){
			  $.ajax({
				  url: 'save/save.php',
				  type: 'get',
				  data: {custID:$('#custID').val(), net:$('#total').html().replace(/,/g, ""), gross:$('#total').html().replace(/,/g, ""), type:$('#type_det').html(), accID:$('#accID').html(), due:$('#value').html(),cash:$('#sum-tend').val().replace(/,/g, ""),entrusted:$('#entrusted_person_name').val(), save:true,credit:$('#credit').val().replace(/,/g, "")},
				  dataType: 'json',
				  success: function(s){
				  	console.log(s);
					if($('#printReceipt').html()=='automatic')
					  $.ajax({
						  url: 'print/receipt.php',
						  type: 'get',
						  data: {saleID:s[0][0]},
						  dataType: 'json',
						  success: function(s){
						  	
						  }
					  });
				  	$('#SALEIDPRINT').html(s[0][0]);
				  }
			  });
			 
			  $('#print_info').fadeIn(1000);
			  $('#pay_stats').html('Saved');
	  }
	  else {
		   $('#payment_info').fadeIn(1000).fadeOut(1500);
	  }
   });
});