$(document).ready(function () {
  if(!$(event.target).closest('tbody').length) {
	  if($('#lookup').is(':visible')){
		  $('#product tr.selected').removeClass('selected');
	  }
	  else if($('#customer_pop').is(':visible')){
		  $('#customer11 tr.selected').removeClass('selected');
	  }
  } 
var product =   $('#product').dataTable( {
	  "scrollY":        "200px",
	  "scrollCollapse": true,
	  "paging":         false,
	  "bProcessing": true,
	  "bServerSide": true,
	  "sAjaxSource": "fetch/server-side.php"
  } );
  $('#product').DataTable().columns.adjust().draw();
  $('#product tbody').on( 'click', 'tr', function () {
	  if ( $(this).hasClass('selected') ) {
		  $(this).removeClass('selected');
	  }
	  else {
		  product.$('tr.selected').removeClass('selected');
		  $(this).addClass('selected');
	  }
  } );
  $('#select_prod').click(function() {
	  if(product.$('tr').hasClass('selected')){
		  if($('#product .selected td').eq(3).text()!='Inactive'){
			  $('#id').val($('#product .selected td').eq(1).text());
			  $('#desc').val($('#product .selected td').eq(2).text());
			  
			  $('.js-modal-close').click();
			  $.ajax({
			  	url: 'fetch/get-products.php?aom',
				type: 'get',
				data: {'prodID':$('#id').val()},
				dataType: 'json',
				success: function(s){
					console.log(s);
					$('#desc').val(s[0][0]);
					if(s[1][0]!=false){
						var choices = '<button style="padding:8px;margin:0px 2.5px" onClick="clickUOM(this)" class="btn" data-conversion="1" data-measDesc="'+s[0][1]+'" data-measurement="'+s[0][3]+'" data-choice="'+s[0][2]+'">'+s[0][1]+'<div id="key" style="font-size:10px">'+'1.00 '+s[0][2]+'</div></button>';
						for(var i=1; i<s.length; i++)
							choices += '<button style="padding:8px;margin:0px 2.5px" onclick="clickUOM(this)" class="btn" data-conversion="'+s[1][2]+'" data-measDesc="'+s[i][1]+'"  data-measurement="'+s[i][3]+'" data-choice="'+s[i][0]+'">'+s[i][1]+'<div id="key" style="font-size:10px">'+s[i][2]+' '+s[0][2]+'</div></button>';
						$('#choices').html(choices);
						$('#selectprodID').html($('#product .selected td').eq(1).text());
						$('#id').focusout();
						$('#select_uom').fadeIn(500);
					}
					else{
						$('#desc').val($('#desc').val()+' ('+s[0][1]+')');
						$('#data-measurement').val(s[0][3]);
						$('#data-conversion').val('1');
						$('#data-unit').val(s[0][2]);
						$('#price').focus();
						destroy('qty',s[0][3]);
					}
				}
			  });
			  
			  //$('#price').focus();
		  }
	  }
  });
   shortcut.add('F8',function() {
	   $('#refresh').click();
	   });
   $('#refresh').click(function() {
	   window.location.assign('add-purchase.php');
	   });
  function clear(){
	$('#id').val();
  	$('#price').val('');
	$('#desc').val('');
	$('#qty').val('');
	$('#qtyy').html('');
	$('#freight').val('');
  }
  $('#id').keydown(function() {
	  clear();
	  });
  function num(num) {
    var n= num.toString().split(".");
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return n.join(".");
  }
  
  /*$('#id').blur(function() {
	   $.ajax({
		   url: 'fetch/get-product.php?pur',
		   type: 'get',
		   data: {'prodID':$(this).val()},
		   dataType: 'json',
		   success: function(s){
			  console.log(s);
			  $('#desc').val(s[1]);
			  $('#price').focus();
			  if(s[1]==false){
			  	$('#input input').each(function() {
				  $(this).val("");
				  });
			  	$('#id').focus();
			  }
		   }
	  });
  });*/
  destroy = function(id,measurement){
	  $('#'+id).autoNumeric('destroy');
	  if(measurement=='Whole Number')
	  	$('#'+id).autoNumeric('init',{'vMin':0,'mDec':0});
	  else	
	  	$('#'+id).autoNumeric('init',{'vMin':0,'mDec':2});
  }
  clickUOM = function(e){
	  //alert($(e).attr('data-choice'));
	  $('#desc').val($('#desc').val()+' ('+$(e).attr('data-measdesc')+')');
	  $('#data-measurement').val($(e).attr('data-measurement'));
	  $('#data-conversion').val($(e).attr('data-conversion'));
	  $('#data-unit').val($(e).attr('data-choice'));
	  $('#select_uom').fadeOut(500);
	  $('#price').focus();
	  destroy('qty',$(e).attr('data-measurement'));
  }
  $('#closeParent').click(function() {
	  $('#select_uom').fadeOut(500);
  });
  
  /** START OF UPDATE 2/24 **/
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
				 url: 'fetch/get-product.php',
				 type: 'get',
				 data: {'prodID':prodID},
				 dataType: 'json',
				 success: function(s){
					console.log(s);
					if(s[0][0]!=''){
					   $.ajax({
						   url: 'fetch/get-products.php?aom',
						   type: 'get',
						   data: {'prodID':prodID},
						   dataType: 'json',
						   success: function(s){
							  console.log(s);
							  $('#desc').val(s[0][0]);
							  if(s[1][0]!=false){
								var choices = '<button style="padding:8px;margin:0px 2.5px" onClick="clickUOM(this)" class="btn" data-conversion="1" data-measDesc="'+s[0][1]+'" data-measurement="'+s[0][3]+'" data-choice="'+s[0][2]+'">'+s[0][1]+'<div id="key" style="font-size:10px">'+'1.00 '+s[0][2]+'</div></button>';
								for(var i=1; i<s.length; i++)
									choices += '<button style="padding:8px;margin:0px 2.5px" onclick="clickUOM(this)" class="btn" data-conversion="'+s[1][2]+'" data-measDesc="'+s[i][1]+'"  data-measurement="'+s[i][3]+'" data-choice="'+s[i][0]+'">'+s[i][1]+'<div id="key" style="font-size:10px">'+s[i][2]+' '+s[0][2]+'</div></button>';
								$('#choices').html(choices);
								$('#selectprodID').html(prodID);
								$('#id').blur();
								$('#select_uom').fadeIn(500);
							  }
							  else{
								$('#desc').val($('#desc').val()+' ('+s[0][1]+')');
								$('#data-measurement').val(s[0][3]);
								$('#data-conversion').val('1');
								$('#data-unit').val(s[0][2]);
								$('#price').focus();
								destroy('qty',s[0][3]);
							  }
						   }
					  });
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
  */
  /** END OF UPDATE 2/24 **/
  $("#id").autocomplete({
	  source:'fetch/get-product.php?purchase',
	  minLength:1,
	  autoFocus:true,
	  select: function( event, ui ) {
		  var prodID = $(ui)[0].item.value;
		   $.ajax({
			   url: 'fetch/get-products.php?aom',
			   type: 'get',
			   data: {'prodID':prodID},
			   dataType: 'json',
			   success: function(s){
				  console.log(s);
				  $('#desc').val(s[0][0]);
				  if(s[1][0]!=false){
				  	var choices = '<button style="padding:8px;margin:0px 2.5px" onClick="clickUOM(this)" class="btn" data-conversion="1" data-measDesc="'+s[0][1]+'" data-measurement="'+s[0][3]+'" data-choice="'+s[0][2]+'">'+s[0][1]+'<div id="key" style="font-size:10px">'+'1.00 '+s[0][2]+'</div></button>';
					for(var i=1; i<s.length; i++)
						choices += '<button style="padding:8px;margin:0px 2.5px" onclick="clickUOM(this)" class="btn" data-conversion="'+s[1][2]+'" data-measDesc="'+s[i][1]+'"  data-measurement="'+s[i][3]+'" data-choice="'+s[i][0]+'">'+s[i][1]+'<div id="key" style="font-size:10px">'+s[i][2]+' '+s[0][2]+'</div></button>';
				  	$('#choices').html(choices);
					$('#selectprodID').html(prodID);
					$('#id').blur();
					$('#select_uom').fadeIn(500);
				  }
				  else{
					$('#desc').val($('#desc').val()+' ('+s[0][1]+')');
					$('#data-measurement').val(s[0][3]);
					$('#data-conversion').val('1');
					$('#data-unit').val(s[0][2]);
					$('#price').focus();
					destroy('qty',s[0][3]);
				  }
				  /*if(s[4]!=false && s[5]!=false){
				  	$('#sub_prodID').val(s[4]);
					x = s[5].split('.');
					if(new Number(x[1])==0)
						s[5] = new String(new Number(s[5].replace(/,/g, "")).toFixed(0)).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					$('#sub_prodQty').val(s[5]);
					$('#sub_prodName').val(s[6]);
				  }
				  $('#qty,#qty_edit').autoNumeric('destroy');
				  if(s[7]=='Decimal')
					  $('#qty').autoNumeric('init',{'vMin':0,'mDec':2});
				  else if(s[7]=='Whole Number')
					  $('#qty').autoNumeric('init',{'vMin':0,'mDec':0});
					$('#meas').val(s[7]);
				  $('#price').focus();*/
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
  shortcut.add('f1',function() {
  	$('#add').click();
  });
  shortcut.add('f2',function() {
  	$('#look').click();
  });
  shortcut.add('f4',function() {
  	$('#save').click();
  });
  shortcut.add('f3',function() {
  	$('#delete').click();
  });
  shortcut.add('f5',function() {
  	$('#edit-qty').click();
  });
   shortcut.add('f6',function() {
  	$('#edit-cost').click();
  });
   shortcut.add('f7',function() {
  	$('#refresh').click();
  });
  shortcut.add('ctrl+p',function() {
  	if($('#status').html()=='Saved')
		printPreview($('#poID').html());
  });
  function printPreview(purcID){
	var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
	$("body").append(appendthis);
	$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
	$(".modal-overlay").css('position','fixed');
	var modalBox = 'print_popup';
	$('#report').attr('src','report/purchase_order.php?purcID='+purcID);
	$('#'+modalBox).fadeIn(1000);
	$('#'+modalBox+' iframe').css('height',$(window).height()-90);
	var view_width = $(window).width();
	$('#'+modalBox).css('height',$(window).height()-90);
	var view_top = $(window).scrollTop() + 10;
	$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
	$('#'+modalBox).css("top", view_top);
  }
  $('#add').click(function() {
	  var check = true;
	  $('#input input').each(function() {
		  if($(this).val() == '' && $(this).attr('id')!='sub_prodID' && $(this).attr('id')!='sub_prodQty' && $(this).attr('id')!='sub_prodName' ){
			  check = false;
			  return;
		  }
	  });
	  if(check){
			check = true;
			check1 = true;
			$('#list2').find('tr').each(function (i, el) {
				var $tds = $(this).find('td');
				var id = $tds.eq(1).text();
				var unit = $tds.eq(0).text();
				if($('#id').val() == id.trim() && $('#data-unit').val() == unit.trim()){
					var tot_old = new Number($tds.eq(6).text().replace(/,/g, ""));
					var qty_old = $tds.eq(4).text().replace(/,/g, "");
					var freight_old = $tds.eq(5).text().replace(/,/g, "");
					
					var QTY = new Number(qty_old) + new Number($('#qty').val().replace(/,/g, ""));
					$tds.eq(4).html(new String(new Number(new Number(qty_old) + new Number($('#qty').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					var total = new Number(new Number($('#price').val().replace(/,/g, "")) * new Number(QTY) + new Number($('#freight').val().replace(/,/g, ""))).toFixed(2);
					var freight = new Number($('#freight').val().replace(/,/g, ""));
					$tds.eq(6).html(new String(total).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$tds.eq(3).html($('#price').val().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$tds.eq(5).html(new String(freight).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					
					$('#total1').html(new String(new Number(new Number(new Number($('#total1').html().replace(/,/g, ""))-new Number(tot_old))+ new Number(total)).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#tot-qty').html(new String(new Number($('#tot-qty').html().replace(/,/g, ""))+new Number($('#qty').val().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#tot-freight').html(new String(new Number(new Number(new Number($('#tot-freight').html().replace(/,/g, ""))-freight_old) + freight).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					
					var unit_ = $tds.eq(0).text();
					var freight_ = freight;
					var new_qty = $tds.eq(4).text().replace(/,/g, "");
					$.ajax({
						url: 'save/save.php',
						type: 'GET',
						data: {prodID:$('#id').val(),prodQty:new_qty,cost:total,accID:$('#accID').html(),temp_list:'UPDATE',purchase:true, unit:unit_, freight:freight_}
					});
					$('#input input').each(function() {
						$(this).val('');
					});
					check1 = false;
				} 
			});
			if(check1){
				var gross = new Number(new Number(new Number($('#price').val().replace(/,/g, ""))*new Number($('#qty').val().replace(/,/g, "")))+new Number($('#freight').val().replace(/,/g, ""))).toFixed(2);
				var qty = $('#qty').val().replace(/,/g, "");
				$('#desc').val($('#desc').val().substring(0,$('#desc').val().lastIndexOf("(")));
				$('#list2').prepend('<tr><td>'+$('#data-unit').val()+'</td><td>'+$('#id').val()+'</td><td>'+$('#desc').val()+'</td><td>'+new Number($('#price').val().replace(/,/g, "")).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td>'+new Number($('#qty').val().replace(/,/g, "")).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td>'+new Number($('#freight').val().replace(/,/g, "")).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td>'+new String((new Number($('#price').val().replace(/,/g, ""))*new Number($('#qty').val().replace(/,/g, ""))+new Number($('#freight').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td style="display:none">'+$('#data-measurement').val()+'</td></tr>');
				
				$('#total1').html(new String((new Number($('#total1').html().replace(/,/g, ""))+new Number(gross)).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#tot-qty').html(new String((new Number($('#tot-qty').html().replace(/,/g, ""))+new Number($('#qty').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#tot-freight').html(new String((new Number($('#tot-freight').html().replace(/,/g, ""))+new Number($('#freight').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				
				$.ajax({
					url: 'save/save.php',
					type: 'GET',
					data: {prodID:$('#id').val(),prodQty:qty,cost:gross,accID:$('#accID').html(),temp_list:'INSERT',purchase:true,unit:$('#data-unit').val(),freight:$('#freight').val().replace(/,/g, "")}
				});
			}
			$('#input input').each(function() {
				$(this).val('');
			});
			$('#id').focus();
		}
	  else {
		  clear();
		  $('#id').focus();
	  }
  });
  $('#save').click(function() {
	  var check1 = true;
	  if($('#list2').find('tr').text().trim()=='')
	  	check1 = false;
	  if(check1 && $('#status').html()!='Saved'){	
	  	  var check = true;
		  var msg = 'Please fill in the required information for ';
		  $('fieldset input').each(function(index, element) {
			 
			if($(this).attr('id')!='note' && $(this).val()==''){
				check = false;
				if($(this).attr('type')=='date')
					msg += ' Date of Purchase';
				else
					msg += $(this).attr('placeholder')+', ';
			}
		  });
		  if(check){
			  bootbox.dialog({
				  message: "Please make sure all data are correct. Do you want to complete this transaction?",
				  buttons: {
					  main: {
						  label: 'Yes',
						  className: "btn",
						  callback: function() {
							  $('#box').hide();
							  $.ajax({
								  url: 'save/save.php',
								  data: {purcCost:$('#total1').html().replace(/,/g, ""),qty:$('#tot-qty').html().replace(/,/g, ""),accID:$('#accID').html(),suppName:$('#suppName').val(),suppAddress:$('#suppAddress').val(),note:$('#note').val(),charges:$('#tot-freight').html().replace(/,/g, ""),date:$('#date').val(),invoiceNo:$('#invoicenum').val()},
								  dataType: 'json',
								  success: function(s){
									  $('#poID').html(s[0][0]);
									  $('#save_info').fadeIn(1000);
									  printPreview(s[0][0]);
									  $('#status').html('Saved'); 
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
		  else if(!check){
			  msg = msg.substring(0,msg.lastIndexOf(","));
			  $('#box').show();
			  $('#msg').html(msg+'.');
			  $('#hi').css('padding-top','0px');
		  }
	  }
	  else if(!check1){
	  	$('#count_info').fadeIn(1000).fadeOut(2000);
	  }
  });
  $('.close').click(function() {
	$('#hi').css('padding-top','25px');  
  });
  $('#delete').click(function() {
	  if($('#list tr').hasClass('active_')){
		  var id = $('#list .active_ td').eq(1).text();
		  var unit = $('#list .active_ td').eq(0).text();
		  bootbox.dialog({
			  message: "Are you sure you want to remove "+$('#list .active_ td').eq(1).text()+" from the list?",
			  buttons: {
				  main: {
					  label: 'OK',
					  className: "btn",
					  callback: function() {
						  $.ajax({
							  url: 'save/save.php',
							  type: 'POST',
							  data: {prodID:id,update:true,delete:true,loc:'po_product_temp_list',unit:unit},
							  dataType: 'json',
							  success: function(s){
								  console.log(s);
								  $('#tot-qty').html(new String(new Number($('#tot-qty').html().replace(/,/g, "")) - new Number($('#list .active_ td').eq(4).text().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
								  $('#total1').html(new String((new Number($('#total1').html().replace(/,/g, "")) - new Number($('#list .active_ td').eq(6).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
								  $('#tot-freight').html(new String(new Number($('#tot-freight').html().replace(/,/g, "")) - new Number($('#list .active_ td').eq(5).text().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
								  $('#list tr.active_').remove();
							  }
						  });
						  
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
  shortcut.add('delete', function() {
       $('#delete').click();
   });
   
	
});
$(function(){

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
	$('a[data-modal-id],button[data-modal-id],[data-modal-id]').click(function(e) {	
		if($('#status').html()=='Saved')
			return;
		if($('.modal-box').is(':visible') && ($(this).attr('data-modal-id') == 'customer_pop' || $(this).attr('data-modal-id') == 'lookup')){
			$('.modal-box').hide();
			$(".modal-overlay").remove();
		}
		var check = true;
		if($(this).attr('data-modal-id') == 'customer_pop'){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			$('#customer_pop').fadeIn($(this).data());
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 150;
			$('#customer_pop').css("left", (view_width - $('#customer_pop').width() ) / 2 );
			$('#customer_pop').css("top", view_top);
			check = false;
		}
		else if($(this).attr('data-modal-id') == 'edit_qty_popup' || $(this).attr('data-modal-id') == 'edit_cost_popup'){
			 if(!$('#list tr').hasClass('active_')){
			 	check = false;
				return;
			 }
			if($(this).attr('data-modal-id') == 'edit_qty_popup' ){
				x = $('#list .active_ td').eq(3).text();
				$('#qty_edit').val(x.trim());
				$('#qty_edit').autoNumeric('destroy');
				if($('#list .active_ td').eq(7).text()=='Decimal')
					$('#qty_edit').autoNumeric('init',{'vMin':0,'mDec':2});
				else if($('#list .active_ td').eq(7).text()=='Whole Number')
					$('#qty_edit').autoNumeric('init',{'vMin':0,'mDec':0});
			}
			else
				$('#cost_edit').val($('#list .active_ td').eq(3).text());
		}
		else if($(this).attr('data-modal-id') == 'edit_freight_popup'){
			if(!$('#list tr').hasClass('active_')){
			 	check = false;
				return;
			 }
			 $('#freight_edit').val($('#list .active_ td').eq(5).text());
			 $('#freight_edit').autoNumeric('destroy');
			 $('#freight_edit').autoNumeric('init',{'vMin':0,'mDec':2});
		}
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
			var modalBox = $(this).attr('data-modal-id');
			//$('#'+modalBox).fadeIn($(this).data());
			 $('#'+modalBox).fadeIn(function(){
				$('#product').DataTable().columns.adjust().draw();
			});
			$(this+' input').focus();
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 150;
			$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
			$('#'+modalBox).css("top", view_top);
		}
		shortcut.add('escape',function() {
			$(".modal-overlay").click();
		});
		$(".modal-overlay").click(function() {
			if($('#lookup,#customer_pop,#payment_popup,#edit_qty_popup,#edit_disc_popup').is(':visible')){
				$(".modal-box, .modal-overlay").fadeOut(100, function() {
					$(".modal-overlay").remove();
				});
			}
		});
	});  
	
  
  
$(".js-modal-close, .modal-overlay").click(function() {
	var check=true;
	if($('#lookup').is(':visible')){
		check=true;
	}
	if($('#edit_freight_popup').is(':visible')){
		if($('#freight_edit').val()=='')
			$('#freight_edit').val($('#list .active_ td').eq(5).text());
		if(this.id == 'freight_button'){
			y = $('#list .active_ td').eq(5).text();
			if($('#freight_edit').val()!=y){
				var tot_old = new Number($('#list .active_ td').eq(6).text().replace(/,/g, ""));
				var freight_old = new Number(y.replace(/,/g, ""));
				$('#list .active_ td').eq(5).html($('#freight_edit').val().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#list .active_ td').eq(6).html(new String((new Number($('#list .active_ td').eq(6).text().replace(/,/g, ""))-new Number(freight_old)+ new Number($('#freight_edit').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#total1').html(new String(new Number(new Number($('#total1').html().replace(/,/g, "")- new Number(tot_old)) + new Number($('#list .active_ td').eq(6).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				id1 = $('#list .active_ td').eq(1).text();
				$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {prodID:id1,prodQty:$('#qty_edit').val().replace(/,/g, ""),cost:new Number($('#list .active_ td').eq(6).text().replace(/,/g, "")),accID:$('#accID').html(),temp_list:'UPDATE',purchase:true,unit:$('#list .active_ td').eq(0).text(),freight:$('#list .active_ td').eq(5).text()}
				});
				 $('#list .active_').removeClass('active_');
				 check = true;
			}
		}
	}
	if($('#edit_qty_popup').is(':visible')){
		if($('#qty_edit').val()=='')
			$('#qty_edit').val($('#list .active_ td').eq(4).text());
		if(this.id == 'qty_button'){
			y = $('#list .active_ td').eq(4).text();
			if($('#qty_edit').val()!=y){
				var tot_old = new Number($('#list .active_ td').eq(6).text().replace(/,/g, ""));
				var qty_old = new Number(y.replace(/,/g, ""));
				$('#list .active_ td').eq(4).html($('#qty_edit').val().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#list .active_ td').eq(6).html(new String((new Number($('#qty_edit').val().replace(/,/g, ""))*new Number($('#list .active_ td').eq(3).text().replace(/,/g, ""))+ new Number($('#list .active_ td').eq(5).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#total1').html(new String(new Number(new Number($('#total1').html().replace(/,/g, "")- new Number(tot_old)) + new Number($('#list .active_ td').eq(6).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#tot-qty').html(new String((new Number($('#tot-qty').html().replace(/,/g, ""))-new Number(qty_old)) + new Number($('#qty_edit').val().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				id1 = $('#list .active_ td').eq(1).text();
				$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {prodID:id1,prodQty:$('#qty_edit').val().replace(/,/g, ""),cost:new Number($('#list .active_ td').eq(6).text().replace(/,/g, "")),accID:$('#accID').html(),temp_list:'UPDATE',purchase:true,unit:$('#list .active_ td').eq(0).text(),freight:$('#list .active_ td').eq(5).text()}
				});
				 $('#list .active_').removeClass('active_');
				 check = true;
			}
		}
	}
	if($('#edit_cost_popup').is(':visible')){
		if($('#cost_edit').val()=='')
			$('#cost_edit').val($('#list .active_ td').eq(3).text());
		if(this.id == 'qty_button1'){
			if($('#cost_edit').val()!=$('#list .active_ td').eq(3).text()){
				var tot_old = new Number($('#list .active_ td').eq(6).text().replace(/,/g, ""));
				var qty_old = new Number($('#list .active_ td').eq(4).text().replace(/,/g, ""));
				$('#list .active_ td').eq(3).html($('#cost_edit').val().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#list .active_ td').eq(6).html(new String((new Number($('#cost_edit').val().replace(/,/g, ""))*new Number($('#list .active_ td').eq(4).text().replace(/,/g, ""))+ new Number($('#list .active_ td').eq(5).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#total1').html(new String(new Number(new Number($('#total1').html().replace(/,/g, "")- new Number(tot_old)) + new Number($('#list .active_ td').eq(6).text().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {prodID:$('#list .active_ td').eq(1).text(),prodQty:$('#list .active_ td').eq(4).text().replace(/,/g, ""),cost:new Number($('#list .active_ td').eq(6).text().replace(/,/g, "")),accID:$('#accID').html(),temp_list:'UPDATE',purchase:true,unit:$('#list .active_ td').eq(0).text(),freight:$('#list .active_ td').eq(5).text()}
				});
				 $('#list .active_').removeClass('active_');
				 check = true;
			}
		}
	}
	if(check){
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	}
	});
});