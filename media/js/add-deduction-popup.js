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
	var product = $('#product').dataTable( {
			"scrollY":        "200px",
			"scrollCollapse": false,
			"paging":         false,
			"order": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "fetch/server-side.php",
			"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
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
			"createdRow": function ( row, data, index ) {
				$(row).removeClass('selected');
				if (index == 0) {
					$(row).addClass('selected');
				}
			}
		});
	$('#product tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('selected') ) {
			$(this).removeClass('selected');
		}
		else {
			product.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}
	} );
	$('#customer11 tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('selected') ) {
			$(this).removeClass('selected');
		}
		else {
			customer.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}
	} );
	$('#select_prod').click(function() {
		if(product.$('tr').hasClass('selected')){
			if(new Number($('#product .selected td').eq(3).text().replace(/,/g, "")) > 0 && $('#product .selected td').eq(2).text()!='Inactive'){
				$('#id').val($('#product .selected td').eq(0).text());
				$('#price').val($('#product .selected td').eq(4).text());
				$('#desc').val($('#product .selected td').eq(1).text());
				$('#qtyy').html($('#product .selected td').eq(3).text().replace(/,/g, ""));
				$('#discountP').val($('#product .selected td').eq(5).text());
				$('.js-modal-close').click();
				$('#qty').focus();
			}
			else{
			var msg = new String();
			if($('#product .selected td').eq(2).text() == 'Inactive')
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
	$('#select_cust').click(function() {
		if(customer.$('tr').hasClass('selected')){
			
			$('#custID').val($('#customer11 .selected td').eq(0).text());
			$('#name').val($('#customer11 .selected td').eq(1).text());
			$('#credit').val($('#customer11 .selected td').eq(2).text());
			$('#limit').val($('#customer11 .selected td').eq(3).text());
			$('#deduction').autoNumeric('init',{'vMin':0,'vMax':$('#customer11 .selected td').eq(2).text().replace(/,/g, "")});
			$('.js-modal-close').click();
			$('#deduction').focus();
		}
	});
	$('#add_customer').click(function(e) {
		var check = true;
		e.preventDefault();
		$('#cust_info input').each(function() {
			if($(this).val() == ''){
				check = false;	
				return
			}
		});
		if(check){
			$('#deduction').autoNumeric('destroy');
			$('#list2 tr.active_').removeClass('active_');
			check = true;
			check1 = true;
			$('#list2').find('tr').each(function (i, el) {
				var $tds = $(this).find('td');
				if($('#custID').val() == $tds.eq(0).text()){
					bootbox.dialog({
					  message: "The customer is already on the list.<br> Would you like to change the info on the list with the new one?",
					  buttons: {
						  main: {
							  label: 'Confirm',
							  className: "btn",
							  callback: function() {
								 check1= false;
								 var old_deduction = new Number($tds.eq(3).text().replace(/,/g, ""));
								 $tds.eq(3).html(new String(new Number($('#deduction').val().replace(/,/g, "")).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
								 $tds.eq(4).html(new String(new Number(new Number($('#credit').val().replace(/,/g, ""))-new Number($('#deduction').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
								 $('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")-new Number(old_deduction)) + $('#deduction').val().replace(/,/g, "")).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
								 $.ajax({
									url: 'save/save.php',
									type: 'get',
									data: {custID:$('#custID').val(),grossCredit:$('#credit').val().replace(/,/g, ""),deduction:$('#deduction').val().replace(/,/g, ""),netCredit: $tds.eq(4).html().replace(/,/g, ""),accID:$('#accID').html(),temp_list:'UPDATE',payroll:true}
								});
								$('#cust_info input').each(function() {
									 $(this).val('');
								 });
								 $('#custID').blur().focus();
							  }
						  },
						  cancel: {
							  label: 'Cancel',
							  className: 'btn',
							  callback: function() {
								 $('#cust_info input').each(function() {
									 $(this).val('');
								 });
								 $('#custID').focus();
							  }
						  }
					  }
					});
					check1 = false;
				}
			});
			if(check1){
				var net_credit = new Number(new Number($('#credit').val().replace(/,/g, ""))-new Number($('#deduction').val().replace(/,/g, ""))).toFixed(2);
				
				$('#list2').prepend('<tr><td>'+$('#custID').val()+'</td><td>'+$('#name').val()+'</td><td>'+$('#credit').val()+'</td><td>'+new String(new Number($('#deduction').val().replace(/,/g, "")).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td>'+net_credit.replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td></tr>');
				$('#list2 tr:first').addClass('active_');
				$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")) +  new Number($('#deduction').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#sum-credit').html(new String (new Number(new Number($('#sum-credit').html().replace(/,/g, "")) + new Number($('#credit').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#sum-customer').html(new String (new Number(new Number($('#sum-customer').html().replace(/,/g, "")) + new Number(1))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				
				$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {custID:$('#custID').val(),grossCredit:$('#credit').val().replace(/,/g, ""),deduction:$('#deduction').val().replace(/,/g, ""),netCredit:net_credit,accID:$('#accID').html(),temp_list:'INSERT',payroll:true}
				});
				$('#cust_info input').each(function() {
					$(this).val('');
				});
				$('#custID').focus();
			}
		}
	});
} );
$(function(){

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
	$('a[data-modal-id],button[data-modal-id]').click(function(e) {	
		if($('#deduction_stats').html()=='Ready' || $('#pay_stats').html()=='Saved')
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
		if($(this).attr('data-modal-id') == 'edit-deduction_popup'){
			if($('#list2 tr').hasClass('active_')){
				$('#deduction_edit').val($('tr.active_').find('td').eq(3).text()).focus();
   				$('#deduction_edit').autoNumeric('init',{'vMin':0,'vMax':$('tr.active_').find('td').eq(2).text().replace(/,/g, "")});
			}
			else 
				check = false;
		}
		else if($(this).attr('data-modal-id') == 'edit_qty_popup' || $(this).attr('data-modal-id') =='edit_disc_popup'){
			if($('#list2 tr').hasClass('active_')){
				if($(this).attr('data-modal-id') == 'edit_qty_popup')
					$('#qty_edit').val($('tr.active_').find('td').eq(3).text());
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
			}
			else
				check=false;
			}
		else if($(this).attr('data-modal-id') == 'lookup'){
			$('#lookup').find('input[type=text]').focus();
		}
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
			var modalBox = $(this).attr('data-modal-id');
			
			$('#'+modalBox).fadeIn($(this).data());
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
	if($('#edit-deduction_popup').is(':visible')){
		if($('#deduction_edit').val()==''){
			$('#deduction_edit').val((new Number($('tr.active_').find('td').eq(3).text())).toFixed(2));
		}
		if(this.id == 'deduction_button'){
			if($('#deduction_edit').val()!=$('tr.active_').find('td').eq(3).text()){
			   var old_deduction = new Number( $('tr.active_').find('td').eq(3).text().replace(/,/g, ""));
			   $('tr.active_').find('td').eq(3).html($('#deduction_edit').val());
			   $('tr.active_').find('td').eq(4).html(new String((new Number($('tr.active_').find('td').eq(2).html().replace(/,/g, ""))-new Number($('#deduction_edit').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			   
			   $('#total').html(new String(new Number(new Number(new Number($('#total').html().replace(/,/g, ""))-new Number(old_deduction)) + new Number($('#deduction_edit').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			   $.ajax({
				  url: 'save/save.php',
				  type: 'get',
				  data: {custID:$('tr.active_').find('td').eq(0).html(),grossCredit:$('tr.active_').find('td').eq(2).html().replace(/,/g, ""),deduction:$('tr.active_').find('td').eq(3).html().replace(/,/g, ""),netCredit: $('tr.active_').find('td').eq(4).html().replace(/,/g, ""),accID:$('#accID').html(),temp_list:'UPDATE',payroll:true}
			  });
			  $('#cust_info input').each(function() {
				   $(this).val('');
			   });
			   $('#custID').blur().focus();
				 $('#list2 tr.active_').removeClass('active_');
			}
		}
		$('#deduction_edit').autoNumeric('destroy');
	}
	if($('#payment_popup').is(':visible')){
		if(this.id == 'pay_button'){
			check = true;
			var type = $('#type').val();
			if(type == 'cash' && new Number($('#tend').val().replace(/,/g, "")) >= new Number($('#total').html().replace(/,/g, ""))){
				$('#value').html($('#total').html().replace(/,/g, ""));
				$('#sum-tend').html($('#tend').val());
				$('#sum-change').html(new String((new Number($('#tend').val().replace(/,/g, ""))-new Number($('#total').html().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","))
			}
			else {
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
			  }
			}
			if(check){
				$('#type_det').html($('#type').val());
				$('#pay_stats').html('Ready');
				if($('#type').val() == 'credit'){
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
});