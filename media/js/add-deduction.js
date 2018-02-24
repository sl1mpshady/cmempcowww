// JavaScript Document
$(document).ready(function () {
  function clear(){
	$('#cust_info input').each(function() {
		$(this).val("");
	});
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
					$('#name').val(s[0]);
					$('#credit').val(s[1]);
					$('#limit').val(s[2]);
					if(new Number(s[3])<= 15 && s[3]!=false){
						$('#days').html(s[3])
						$('#expire_info').fadeIn(1000).fadeOut(3500);
					}
					$('#deduction').focus();
					$('#deduction').autoNumeric('init',{'vMin':0,'vMax':s[1].replace(/,/g, "")});
				  //$('#custID').val('').blur();
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
			  $('#deduction').autoNumeric('destroy');
		  }
	  }
  }).on("keydown", function(e) {
    	$('#name,#limit,#credit,#deduction').val('');
		$('#deduction').autoNumeric('destroy');
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
						$('#price').val(s[0]);
						$('#desc').val(s[1]);
						$('#qty').val(1);
						$('#qtyy').html(s[2]);
						$('#discountP').val(s[3]);
						//$('a[data-modal-id="percent_popup"]').click();
						//$('#percent_button').click();
						$('#add_item').click();
					}
					else{
						$('#input input').each(function() {
							$(this).val("");
							});
						$("#id").val("").blur();
						bootbox.dialog({
							message: "Item with zero(0) quantity on inventory cannot be selected.",
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
  $("#id").autocomplete({
	  source:'fetch/get-product.php',
	  minLength:1,
	  autoFocus:true,
	  select: function( event, ui ) {
		  var prodID = $(ui)[0].item.value;
		   $.ajax({
			   url: 'fetch/get-product.php',
			   type: 'get',
			   data: {'prodID':prodID},
			   dataType: 'json',
			   success: function(s){
				  console.log(s);
				  if(new Number(s[2]) > 0){
					  $('#price').val(s[0]);
					  $('#desc').val(s[1]);
					  $('#qty').focus();
					  $('#qtyy').html(s[2]);
					  $('#discountP').val(s[3]);
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

   $('#qty').focus(function() {
	   if($('#id').val()=='' || $('#price').val()=='' || $('#desc').val()==''){
	   		clear();
			$('#id').focus();
	   }
   });
   shortcut.add('f6', function() {
	   $('#refresh').click();
	   });
   shortcut.add('f7', function() {
	   if( $('#deduction_stats').html() != 'Saved')
	   $('#save').click();
	   });
   shortcut.add('f5', function() {
	   if( $('#deduction_stats').html() != 'Saved')
	   	$('#delete').click();
	   });
   $('#delete').click(function() {
	   if( $('#deduction_stats').html() != 'Saved')
   		if($('#list2 tr').hasClass('active_')){
			$('#total').html(new String((new Number($('#total').html().replace(/,/g, "")) - new Number($('#list2 tr.active_').find('td').eq(3).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			$('#sum-credit').html(new String((new Number($('#sum-credit').html().replace(/,/g, "")) - new Number($('#list2 tr.active_').find('td').eq(2).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			$('#sum-customer').html(new String((new Number($('#sum-customer').html().replace(/,/g, "")) - new Number(1))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			$.ajax({
				url: 'save/save.php',
				type: 'get',
				data: {custID:$('#list2 tr.active_').find('td').eq(0).text(),grossCredit:0,deduction:0,netCredit:0,accID:$('#accID').html(),temp_list:'DELETE',payroll:true}
			});
			$('#list2 tr.active_').remove();
			$('#custID').focus();
		}
   });
   shortcut.add('Ctrl+P', function() {
	   /*if( $('#deduction_stats').html() == 'Saved')
	   		$.ajax({
				url: 'print/receipt.php',
				type: 'get',
				data: {saleID:	$('#SALEIDPRINT').html()},
				dataType: 'json'
			});
		*/
   });
   shortcut.add('delete', function() {
	   if( $('#deduction_stats').html() != 'Saved')
       $('#delete').click();
   });
   $('#deduction_edit').keypress(function(e) {
	  if ($.isNumeric(String.fromCharCode(e.keyCode)) || (e.keyCode == 46 && $(this).val().indexOf('.')==-1)){
		  if($(this).val().indexOf('.')!=-1){
			  x = $(this).val().split('.');
			  if(x[1].length<2) 
			  	if(new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode))<=new Number($('tr.active_').find('td').eq(2).text().replace(/,/g, "")))
			  		return true;
				else
				    $(this).val($(this).val().concat('00'));
			  return false;
		  }
		  else if(new Number($(this).val().replace(/,/g, "") + String.fromCharCode(e.keyCode)) <= new Number($('tr.active_').find('td').eq(2).text().replace(/,/g, "")))
			  return true;
		  return false;
	  }
	  return false;
  });

   shortcut.add('f2', function() {
	   if( $('#deduction_stats').html() != 'Saved')
		$('a[data-modal-id="customer_pop"]').click();
   });
   shortcut.add('f3', function() {
	   if( $('#deduction_stats').html() != 'Saved')
   		$('a[data-modal-id="edit-deduction_popup"]').click();
   });

   shortcut.add('f1',function() {
		var check = true;
		$('#cust_info input').each(function() {
			if($(this).val() == ''){
				check = false;
				return
			}
		});
		if(check){
				$('#add_customer').click();
				//$('#percent').focus();
			}
		else {
			clear();
			$('#custID').focus();
		}
   });
  shortcut.add('f4',function() {
	  if( $('#deduction_stats').html() != 'Saved')
	 	$('a[data-modal-id=upload_popup]').click();
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
		else if($('#edit-deduction_popup').is(':visible')){
			$('#deduction_button').click();
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
					if(new Number(s[3])<= 15 && s[3]!=false){
						$('#days').html(s[3])
						$('#expire_info').fadeIn(1000).fadeOut(3500);
					}
					$('#name').val(s[0]);
					$('#credit').val(s[1]);
					$('#limit').val(s[2]);
					$('#id').focus();
					 //$('#custID').val('').blur();
				 }
			});	  
		}
		//$('#id').focus();
	});
	function update_total(amount){
		$('#total').html(new String(new Number(new Number($('#total').html()) + amount).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
	}
	$(document).on("click","#list2 tr", function(){
		if ( $(this).hasClass('active_') ) {
            $(this).removeClass('active_');
        }
        else {
            $('#list2 tr.active_').removeClass('active_');
            $(this).addClass('active_');
        }
	});
	$(document).on("click", function(event){
		
		if(!$(event.target).parents().andSelf().is("#list2") && !$(event.target).parent().andSelf().is("[data-modal-id='edit-deduction_popup']") && !$(event.target).parent().andSelf().is("[data-modal-id='edit_disc_popup']") && !$(event.target).parents().andSelf().is(".modal-box") && !$(event.target).parents().andSelf().is(".modal-overlay") && !$(event.target).is("#add_item")){
			 $('#list2 tr.active_').removeClass('active_');
		}
	});

	$('#qty_edit').keypress(function(e) {
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
   });
   $('#type').change(function() {
   	  if($(this).val() == 'cash'){
		$('#tend').val($('#total').html().replace(/,/g, "")).prop('disabled',false).focus();
		$('#title').html('Amount Tendered:');
	  }
	  else{
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
	   window.location.assign('add-payroll-deduction.php');
	   });
   shortcut.add('F8',function() {
	   if( $('#pay_stats').html() != 'Saved')
	   $('#save').click();
	   });
   shortcut.add('backspace',function() {
   	  if($('input').is(':focus'))
	  	e.preventDefault();
   });
   $('#save').click(function() {
	 if($('#list1 tbody > tr').length > 0 && $('#deduction_stats').html()!='Saved')
		bootbox.dialog({
				message: "Please make sure all data are correct. Do you want to complete this transaction?",
				buttons: {
					main: {
						label: 'Yes',
						className: "btn",
						callback: function() {
							$('#save_info').fadeIn(100);
							$.ajax({
								url: 'save/save.php',
								type: 'get',
								async: false,
								data: {total:$('#total').html().replace(/,/g, ""), customerCredit:$('#sum-credit').html().replace(/,/g, ""),customer:$('#sum-customer').html().replace(/,/g, ""), accID:$('#accID').html(), save:true, payroll:true},
								dataType: 'json',
								success: function(s){
									console.log(s);
									
									var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
									$("body").append(appendthis);
									$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
									$(".modal-overlay").css('position','fixed');
									var modalBox = 'print_popup';
									$('iframe').attr('src','report/deduction.php?i='+s);
									$('#'+modalBox).fadeIn(1000);
									$('#'+modalBox+' iframe').css('height',$(window).height()-90);
									var view_width = $(window).width();
									$('#'+modalBox).css('height',$(window).height()-60);
									var view_top = $(window).scrollTop() + 10;
									$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
									$('#'+modalBox).css("top", view_top);
									
								}
							});
							$('#save_info').fadeOut(1000);
							$('#print_info').fadeIn(1000);
							$('#deduction_stats').html('Saved');
						}
					},
					no: {
						label: 'No',
						className: 'btn'
					}
				}
		});
   });
   $('#import').click(function() {
	   if($('#deduction_stats').html()!='Saved')
		bootbox.dialog({
			message: "Once you open a file, the current list will all be replaced.\nDo you want to continue?",
			buttons: {
				main: {
					label: 'Yes',
					className: "btn",
					callback: function() {
						
						$('#file').val('').click();
					}
				},
				cancel: {
					label: 'No',
					className: "btn"
				}
			}
		});	
	});
   function progressHandlingFunction(e){
		if(e.lengthComputable){
			var x = Math.round((e.loaded / e.total) * 100)
			$('#import_loading .bar').css('width',x + "%");
			$('#import_loading .bar').html(x + "%");
		}
	}
	$("#file").on('change', function () {
	  var file = this.files[0];
	  var name = file.name;
	  var filePath = $(this)[0].value;
	  var extn = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
		if (true) {
			if (typeof (FileReader) != "undefined") {
				$('#file-name').val(name);
				var file_data = $('#file').prop('files')[0];   
				var form_data = new FormData();                  
				form_data.append('file', file_data);
					var progressTrigger;
					$.ajax({
						url: "excel/get-customers-for-deduction.php",
						dataType: 'json',  
						cache: false,
						contentType: false,
						processData: false,
						data: form_data,                         
						type: 'post',
						xhr: function() {  
							var myXhr = $.ajaxSettings.xhr();
							if(myXhr.upload){ 
								myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
							}
							return myXhr;
						},
						beforeSend: function (thisXHR) {
							$('#import_loading').fadeIn(100);
						},
						success: function (s) {
							$('#import_loading').fadeOut(100);
							$('#import_loading .bar').css('width',"0%");
							$('#import_loading .bar').html("0%");
							console.log(s);
							if(s[0]!='Error'){
								$('#list2').html('<table>');
								$.ajax({
										url: 'save/clear-pd.php',
										data: {accID:$('#accID').html()},
										success: function(jj){
											for(i=0; i<s.length-1; i++){
												$('#list2').append('<tr><td>'+s[i][0]+'</td><td>'+s[i][1]+'</td><td>'+s[i][2]+'</td><td>'+s[i][3]+'</td><td>'+s[i][4]+'</td></tr>');
												$.ajax({
													url: 'save/save.php',
													type: 'get',
													data: {custID:s[i][0],grossCredit:s[i][2].replace(/,/g, ""),deduction:s[i][3].replace(/,/g, ""),netCredit: s[i][4].replace(/,/g, ""),accID:$('#accID').html(),temp_list:'INSERT',payroll:true}
												});
											}
											$('#list2').append('</table>');
											$('#sum-customer').html(s[s.length-1][0]);
											$('#sum-credit').html(s[s.length-1][1]);
											$('#total').html(s[s.length-1][2]);
										}
								});
								
								
							}
							else{
								$('#rowcol').html(s[1]);
								$('#suggestion').html(s[2]);
								$('#import_error').fadeIn(1000);
							}
							
						}
					});
			} else {
				alert("This browser does not support FileReader.");
			}
		} else {
			alert("Pls select Excel files.");
	  }
	});
	$(document).on('click',function(){
		$('#import_error').fadeOut(1000);
	});
});