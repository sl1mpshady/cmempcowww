$(document).click(function(event) { 
	if(!$(event.target).closest('tbody').length) {
		if($('#lookup').is(':visible')){
			$('#product tr.selected').removeClass('selected');
		}
		else if($('#customer_pop').is(':visible')){
			$('#customer11 tr.selected').removeClass('selected');
		}
	}        
})
$(document).ready(function() {
	destroy2 = function(id,measurement,max){
		$('#'+id).autoNumeric('destroy');
		if(measurement=='Whole Number')
			$('#'+id).autoNumeric('init',{'vMin':0,'mDec':0,'vMax':max});
		else	
			$('#'+id).autoNumeric('init',{'vMin':0,'mDec':2,'vMax':max});
	}
	$('a[data-modal-id="edit_qty_popup"],#delete').attr('disabled',true);
	
	var product = $('#product1').dataTable( {
			"scrollY":        "200px",
			"scrollCollapse": false,
			"paging":         false,
			"order": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "fetch/server-side.php",
			"fnRowCallback": function (nRow, aData, iDisplayIndex) {
				$(nRow).removeClass('selected');
				if (iDisplayIndex == 0) {
					$(nRow).addClass('selected');
				}
			}
		});
	var customer = $('#customer11').dataTable({
			"scrollY":        "200px",
			"scrollCollapse": false,
			"paging":         false,
			"order": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_processing/sales-customer_server_processing.php",
			"createdRow": function ( row, data, index) {
				$(row).removeClass('selected');
				if (index == 0) {
					$(row).addClass('selected');
				}
			}
		});
	$('#customer11 tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('selected') ) {
			$(this).removeClass('selected');
		}
		else {
			customer.$('tr.selected').removeClass('selected');
			//alert(product.$('tr').hasClass('selected').find('td').eq(0).text());
			$(this).addClass('selected');
		}
	} );
	
	$('#select_cust').click(function() {
		if(customer.$('tr').hasClass('selected')){
			//alert($('#product .selected td').eq(0).text());
			
			$('#custID').val($('#customer11 .selected td').eq(0).text());
			$('#name').val($('#customer11 .selected td').eq(1).text());
			$('#credit').val($('#customer11 .selected td').eq(2).text());
			$('#limit').val($('#customer11 .selected td').eq(3).text());
			$('.js-modal-close').click();
			$('#id').focus();
		}
	});
	
	$('#add_item').click(function(e) {
		var check = true;
		e.preventDefault();
		$('#input input').each(function() {
			if($(this).val() == ''){
				check = false;	
				return
			}
		});
		if(check){
			$('#list2 tr.active_').removeClass('active_');
			check = true;
			check1 = true;
			$('#list2').find('tr').each(function (i, el) {
				var $tds = $(this).find('td');
				if($('#id').val() == $tds.eq(1).text() && $('#data-unit').val() == $tds.eq(0).text()){
					if((new Number($tds.eq(4).text().replace(/,/g, ""))+new Number($('#qty').val().replace(/,/g, "")))>new Number($('#qtyy').html().replace(/,/g, ""))){
						check1= false;
						$('#input input').each(function() {
							$(this).val('');
						});
						return;
					}
					var net_old = new Number($tds.eq(5).text().replace(/,/g, ""));
					var gross_old = new Number($tds.eq(4).text().replace(/,/g, ""));
					$tds.eq(4).html(new String(new Number(new Number($tds.eq(4).text()) + new Number($('#qty').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					var gross = new Number(new Number($('#price').val().replace(/,/g, ""))* new Number($tds.eq(4).text())).toFixed(2);
					$tds.eq(5).html(new String(gross).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					
					$tds.eq(6).html($('#qtyy').html().replace(/,/g, ""));
					
					row = $('#list2').find("tr:first");
					
					$(this).addClass('active_');
					$(this).insertBefore(row);
					
					
					$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")-net_old) + gross).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#sum-prodName').html($('#desc').val()+' ('+$('#id').val()+')');
					$('#sum-pricexqty').html($('#price').val()+' x '+new String(new Number($tds.eq(4).text().replace(/,/g, "")).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' '+$('#data-unit').val());
					$('#sum-prodTotal').html(new String(gross).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$.ajax({
						url: 'save/save.php',
						type: 'get',
						data: {prodID:$('#id').val(),unit:$('#data-unit').val(),conversion:$('#data-conversion').val(),prodQty:$('#qty').val().replace(/,/g, ""),grossPrice:gross,netPrice:gross,accID:$('#accID').html(),temp_list:'UPDATE'}
					});
					$('#input input').each(function() {
						$(this).val('');
					});
					$('#percent').val('');
					check1 = false;
				}
			});
			if(check1){
				var gross = new Number(new Number($('#price').val().replace(/,/g, ""))*new Number(new Number($('#qty').val().replace(/,/g, "")))).toFixed(2);
				var net = gross;
				
				$('#list2').prepend('<tr><td>'+$('#data-unit').val()+'</td><td>'+$('#id').val()+'</td><td>'+$('#desc').val()+'</td><td>'+$('#price').val()+'</td><td>'+new String(new Number($('#qty').val().replace(/,/g, "")).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td>'+new Number(net).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td style="display:none">'+ $('#qtyy').html()+'</td><td style="display:none">'+$('#data-measurement').val()+'</td></tr>');
				$('#list2 tr:first').addClass('active_');
				
				$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")) + new Number(net)).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				
				$('#sum-prodName').html($('#desc').val()+' ('+$('#id').val()+')');
				$('#sum-pricexqty').html($('#price').val()+' x '+new String(new Number($('#qty').val().replace(/,/g, "")).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' '+$('#data-unit').val());
				$('#sum-prodTotal').html(new String(net).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				
				$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {prodID:$('#id').val(),unit:$('#data-unit').val(),conversion:$('#data-conversion').val(),prodQty:$('#qty').val().replace(/,/g, ""),grossPrice:gross,netPrice:net,accID:$('#accID').html(),temp_list:'INSERT'}
				});
				$('#input input').each(function() {
					$(this).val('');
				});
			}
			$('a[data-modal-id="edit_qty_popup"],#delete').attr('disabled',false);
			$('#id').focus();
		
		}
	});
} );
$(function(){

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
	$('a[data-modal-id],button[data-modal-id]').click(function(e) {	
		if($('#pay_stats').html()=='Ready' || $('#pay_stats').html()=='Saved')
			return;
		if($('.modal-box').is(':visible') && ($(this).attr('data-modal-id') == 'customer_pop' || $(this).attr('data-modal-id') == 'lookup')){
			$('.modal-box').hide();
			$(".modal-overlay").remove();
		}
		$(document).find('input').each(function(index, element) {
            var x = $(this).attr('id');
			if(x!='custID' && x!='name' && x!='credit' && x!='limit'){
				$(this).val('').blur();
			}
        });
		
		e.preventDefault();
		var check = true;
		if($(this).attr('data-modal-id') == 'percent_popup'){
			$('#input input').each(function() {
				if($(this).val() == ''){
					check = false;	
					return
				}
			});
			if(check){
				$('#qty').blur();
				$('#percent').val($('#discountP').val()).focus();
			}
		}
		else if($(this).attr('data-modal-id') == 'edit_qty_popup' || $(this).attr('data-modal-id') =='edit_disc_popup'){
			if($('#list2 tr').hasClass('active_')){
				if($(this).attr('data-modal-id') == 'edit_qty_popup'){
					$('#qty_edit').val($('tr.active_').find('td').eq(4).text());
					/*destroy2('qty_edit',$('tr.active_').find('td').eq(7).text(),$('tr.active_').find('td').eq(6).text());*/
					$('#qty_edit').autoNumeric('destroy');
					
					if($('tr.active_').find('td').eq(7).text()=='Whole Number')
						$('#qty_edit').autoNumeric('init',{'vMin':0,'mDec':0,'vMax':$('tr.active_').find('td').eq(6).text()});
					else	
						$('#qty_edit').autoNumeric('init',{'vMin':0,'mDec':2,'vMax':$('tr.active_').find('td').eq(6).text()});
				}
				if($(this).attr('data-modal-id') == 'edit_disc_popup')
					$('#percent_edit').val((new Number($('tr.active_').find('td').eq(7).text())/0.01).toFixed(2));
			}
			else
				check = false;
		}
		else if($(this).attr('data-modal-id') == 'customer_pop'){
			
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	

			$('#customer_pop').fadeIn(function() {
				$('#customer11').DataTable().columns.adjust().draw();
			});
			
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 150;
			$('#customer_pop').css("left", (view_width - $('#customer_pop').width() ) / 2 );
			$('#customer_pop').css("top", view_top);
			$('#customer_pop').find('input[type=text]').focus();
			check = false;
		}
		else if($(this).attr('data-modal-id') == 'payment_popup'){
			var count = 0;
			$('#list1').find('tr').each(function (i, el){
				count++;
				return
			});
			if($('#name').val()!='' && count > 0){
				check = true;
				$('#nett').val($('#total').html());
				$('#tend').val($('#total').html());
				$('#title').html('Amount Tendered');
				$('option[value=cash]').prop('selected',true);
				($('#custID').val()=='WALKIN') ? $('#type').prop('disabled',true) : $('#type').prop('disabled',false);
			}
			else{
				check=false;
				if(count <= 0)
					$('#count_info').fadeIn(1000).fadeOut(1500);
				else
					$('#customer_info').fadeIn(1000).fadeOut(1500);
			}
		}
		else if($(this).attr('data-modal-id') == 'lookup'){
			$('#lookup').find('input[type=text]').focus();
		}
		
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
			//$(".js-modalbox").fadeIn(500);
			var modalBox = $(this).attr('data-modal-id');
			
			$('#'+modalBox).fadeIn(function(){
				$('#product').DataTable().columns.adjust().draw();
			});
			if(!$(this).attr('data-modal-id') == 'payment_popup')
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
	if($('#percent_popup').is(':visible')){
		if($('#percent').val()!=''){
			check = true;
			check1 = true;
			$('#list2').find('tr').each(function (i, el) {
				var $tds = $(this).find('td');
				if($('#id').val() == $tds.eq(0).text()){
					var net_old = new Number($tds.eq(5).text().replace(/,/g, ""));
					var gross_old = new Number($tds.eq(4).text().replace(/,/g, ""));
					$tds.eq(3).html(new String(new Number($tds.eq(3).text()) + new Number($('#qty').val().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					var gross = new Number(new Number($('#price').val().replace(/,/g, ""))* new Number($tds.eq(3).text())).toFixed(2);
					$tds.eq(4).html(new String(gross).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					var net = gross - (gross * (new Number($('#percent').val())*0.01));
					$tds.eq(5).html(new Number(net).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$tds.eq(6).html($('#qtyy').html().replace(/,/g, ""));
					$tds.eq(7).html(new Number($('#percent').val())*0.01);
					$(this).addClass('active_');
					
					$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")-net_old) + net).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					//alert(new String((new Number($('#sum-total').html().replace(/,/g, ""))-gross_old + new Number($('#price').val().replace(/,/g, "")) * new Number($tds.eq(3).text())).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#sum-total').html(new String((new Number($('#sum-total').html().replace(/,/g, ""))-gross_old + new Number($('#price').val().replace(/,/g, "")) * new Number($tds.eq(3).text())).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$.ajax({
						url: 'save/save.php',
						type: 'get',
						data: {prodID:$('#id').val(),prodQty:$('#qty').val().replace(/,/g, ""),grossPrice:gross,netPrice:net,accID:$('#accID').html(),temp_list:'UPDATE'}
					});
					$('#input input').each(function() {
						$(this).val('');
					});
					$('#percent').val('');
					check1 = false;
					return
				}
				// do something with productId, product, Quantity
			});
			if(check1){
				var gross = new Number(new Number($('#price').val().replace(/,/g, ""))*new Number($('#qty').val().replace(/,/g, ""))).toFixed(2);
				var net = gross - (gross * (new Number($('#percent').val())*0.01));
				$('#list2').append('<tr><td>'+$('#id').val()+'</td><td>'+$('#desc').val()+'</td><td>'+$('#price').val()+'</td><td>'+$('#qty').val()+'</td><td>'+new String((new Number($('#price').val().replace(/,/g, ""))*new Number($('#qty').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td>'+new Number(net).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td style="display:none">'+$('#qtyy').html()+'</td><td style="display:none">'+new Number($('#percent').val())*0.01+'</td></tr>');
				$('#list2 tr:last').addClass('active_');
				$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")) + net).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#sum-total').html(new String (new Number(new Number($('#sum-total').html().replace(/,/g, "")) + new Number($('#price').val().replace(/,/g, "")) * new Number($('#qty').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				//q = '$_GET["prodID"],$_GET["prodQty"],$_GET["grossPrice"],$_GET["netPrice"],$_GET["accID"]';
				$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {prodID:$('#id').val(),prodQty:$('#qty').val().replace(/,/g, ""),grossPrice:gross,netPrice:net,accID:$('#accID').html(),temp_list:'INSERT'}
				});
				$('#input input').each(function() {
					$(this).val('');
				});
				$('#percent').val('');
			}
		}
		else
			check = false;
		$('#id').focus();
	}
	if($('#edit_qty_popup').is(':visible')){
		if($('#qty_edit').val()=='')
			$('#qty_edit').val($('tr.active_').find('td').eq(4).text());
		if(this.id == 'qty_button'){
			if($('#qty_edit').val()!=$('tr.active_').find('td').eq(4).text()){
				var net_old = new Number($('tr.active_').find('td').eq(5).text().replace(/,/g, ""));
				var gross_old = new Number($('tr.active_').find('td').eq(5).text().replace(/,/g, ""));
				gross =( new Number($('tr.active_').find('td').eq(3).text().replace(/,/g, "")) * new Number($('#qty_edit').val().replace(/,/g, ""))).toFixed(2);
				net = gross;
				qty = new Number($('tr.active_').find('td').eq(4).text().replace(/,/g, ""));
				$('tr.active_').find('td').eq(4).html((new Number($('#qty_edit').val().replace(/,/g, "")).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				prodQty = new Number($('tr.active_').find('td').eq(4).text().replace(/,/g, ""))- qty;
				
				$('tr.active_').find('td').eq(5).html(new String(net).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")- new Number(net_old)) + new Number(net)).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				
				$('#sum-prodName').html($('#list2 tr.active_ td').eq(2).text()+' ('+$('#list2 tr.active_ td').eq(1).text()+')');
				$('#sum-pricexqty').html($('#list2 tr.active_ td').eq(3).text()+' x '+new String(new Number($('#list2 tr.active_ td').eq(4).text().replace(/,/g, "")).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' '+$('#list2 tr.active_ td').eq(0).text());
				$('#sum-prodTotal').html($('#list2 tr.active_ td').eq(5).text());
				
				$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {prodID:$('tr.active_').find('td').eq(1).text(),unit:$('tr.active_').find('td').eq(0).text(),conversion:'',prodQty:prodQty,grossPrice:gross,netPrice:net,accID:$('#accID').html(),temp_list:'UPDATE'}
				});
				//$('#list2 tr.active_').removeClass('active_');
			}
		}
		
	}
	if($('#edit_disc_popup').is(':visible')){
		if($('#percent_edit').val()==''){
			$('#qty_edit').val((new Number($('tr.active_').find('td').eq(7).text())/0.01).toFixed(2));
		}
		if(this.id == 'percent-edit_button'){
			if($('#percent_edit').val()!=(new Number($('tr.active_').find('td').eq(7).text())/0.01).toFixed(2)){
				$('tr.active_').find('td').eq(7).html(new Number($('#percent_edit').val())*0.01);
				var gross = new Number($('tr.active_').find('td').eq(4).text().replace(/,/g, ""));
				net_old = new Number($('tr.active_').find('td').eq(5).text().replace(/,/g, ""));
				net = (gross - (gross*new Number($('#percent_edit').val())*0.01)).toFixed(2);
				$('tr.active_').find('td').eq(5).html(net.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")-new Number(net_old)) + new Number(net)).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {prodID:$('tr.active_').find('td').eq(0).text(),prodQty:0,grossPrice:gross,netPrice:net,accID:$('#accID').html(),temp_list:'UPDATE'}
				});
				 $('#list2 tr.active_').removeClass('active_');
			}
		}
	}
	if($('#payment_popup').is(':visible')){
		if(this.id == 'pay_button'){
			check = true;
			var type = $('#type').val();
			//var check = false;
			if(type == 'cash' && new Number($('#tend').val().replace(/,/g, "")) >= new Number($('#total').html().replace(/,/g, ""))){
				//check = true;
				$('#value').html($('#total').html().replace(/,/g, ""));
				$('#sum-tend').val($('#tend').val());
				//$('#sum-change').html(new String((new Number($('#tend').val().replace(/,/g, ""))-new Number($('#total').html().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","))
			}
			else if(type == 'credit' || type == 'panel') {
			  //alert((new Number($('#credit').val().replace(/,/g, "")) + new Number($('#total').html().replace(/,/g, ""))));
			  if((new Number($('#credit').val().replace(/,/g, "")) + new Number($('#total').html().replace(/,/g, ""))) <= new Number($('#limit').val().replace(/,/g, ""))){
				  check = true;
				  $('#value').html($('#date').val());
			  }
			  else{
				  check = false;
				  bootbox.dialog({
					  message: "The total net amount exceeded the customer's credit limit.",
					  buttons: {
						  main: {
							  label: 'Ok',
							  className: "btn",
							  callback: function() {
								  $('#pay_stats').html('');
							  }
						  }
					  }
				  });
				  //alert("Total net amount exceeded the customer's allowable credit.");
			  }
			}
			if(check){
				$('#type_det').html($('#type').val());
				$('#pay_stats').html('Ready');
				if($('#type').val() == 'credit'){
					//$('#value').html($('#date').val());
					if($('#askEntrusted').html()=='true'){
					  $(".modal-box").fadeOut(100);
					  var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
					  $("body").append(appendthis);
					  $(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
					  var modalBox = 'entrusted_popup';
					  $('#'+modalBox).fadeIn(100);
					  $('#'+modalBox+' input').focus();
					  var view_width = $(window).width();
					  var view_top = $(window).scrollTop() + 150;
					  $('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
					  $('#'+modalBox).css("top", view_top);
					}
				}
				$('#id').attr('disabled','disabled');
				$('#qty').attr('disabled','disabled');
				$('#custID').attr('disabled','disabled');
			}
			
		}
	}
	if($('#entrusted_popup').is(':visible')){
		if($('#entrusted_person').val()!=''){
			$('#entrusted_person_name').val($('#entrusted_person').val());
			check = true;
		}
		else{
			$('#box').show();
			check = false;
		}
	}
	if($('#lookup').is(':visible')){
		check=true;
	}
	if(check){
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	}
 
});
 
$(window).resize(function() {
	//alert(($(window).height() - $(".modal-box").outerHeight()) / 2);
    
});
 
//$(window).resize();
 
});
// JavaScript Document