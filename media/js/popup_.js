$(function(){

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('a[data-modal-id]').click(function(e) {
		e.preventDefault();
		var check = true;
		if($(this).attr('data-modal-id') == 'percent_popup'){
			$('#input input').each(function() {
				if($(this).val() == ''){
					check = false;
					return
				}
			});
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
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7);
			//$(".js-modalbox").fadeIn(500);
			var modalBox = $(this).attr('data-modal-id');
			$('#'+modalBox).fadeIn($(this).data());
			$(this+' input').focus();
		}
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
					$tds.eq(3).html(new String(new Number($tds.eq(3).text()) + new Number($('#qty').val())).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					var gross = new Number(new Number($('#price').val().replace(/,/g, ""))* new Number($tds.eq(3).text())).toFixed(2);
					$tds.eq(4).html(new String(gross).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					var net = gross - (gross * (new Number($('#percent').val())*0.01));
					$tds.eq(5).html(new Number(net).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$tds.eq(6).html($('#qtyy').html().replace(/,/g, ""));
					$tds.eq(7).html(new Number($('#percent').val())*0.01);
					$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")-net_old) + net).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#sum-total').html(new String ((new Number($('#sum-total').html().replace(/,/g, ""))-gross_old) + new Number($('#price').val().replace(/,/g, "")) * new Number($('#qty').val().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$.ajax({
						url: 'save/save.php',
						type: 'get',
						data: {prodID:$('#id').val(),prodQty:$('#qty').val(),grossPrice:gross,netPrice:net,accID:$('#accID').html(),temp_list:'UPDATE'}
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
				$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")) + net).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#sum-total').html(new String (new Number(new Number($('#sum-total').html().replace(/,/g, "")) + new Number($('#price').val().replace(/,/g, "")) * new Number($('#qty').val().replace(/,/g, ""))).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				//q = '$_GET["prodID"],$_GET["prodQty"],$_GET["grossPrice"],$_GET["netPrice"],$_GET["accID"]';
				$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {prodID:$('#id').val(),prodQty:$('#qty').val(),grossPrice:gross,netPrice:net,accID:$('#accID').html(),temp_list:'INSERT'}
				});
				$('#input input').each(function() {
					$(this).val('');
				});
				$('#percent').val('');
			}
		}
		else
			check = false;
	}
	if($('#edit_qty_popup').is(':visible')){
		if($('#qty_edit').val()=='')
			$('#qty_edit').val($('tr.active_').find('td').eq(3).text());
		if(this.id == 'qty_button'){
			if($('#qty_edit').val()!=$('tr.active_').find('td').eq(3).text()){
				var net_old = new Number($('tr.active_').find('td').eq(5).text().replace(/,/g, ""));
				var gross_old = new Number($('tr.active_').find('td').eq(4).text().replace(/,/g, ""));
				gross =( new Number($('tr.active_').find('td').eq(2).text().replace(/,/g, "")) * new Number($('#qty_edit').val().replace(/,/g, ""))).toFixed(2);
				net = (gross - (gross*new Number($('tr.active_').find('td').eq(7).text()))).toFixed(2);
				qty = new Number($('tr.active_').find('td').eq(3).text().replace(/,/g, ""));
				$('tr.active_').find('td').eq(3).html($('#qty_edit').val().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				prodQty = new Number($('tr.active_').find('td').eq(3).text().replace(/,/g, ""))- qty;
				$('tr.active_').find('td').eq(4).html(new String(gross).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('tr.active_').find('td').eq(5).html(new String(net).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")-net_old) + net).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#sum-total').html(new String (new Number((new Number($('#sum-total').html().replace(/,/g, ""))-gross_old) + gross).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {prodID:$('tr.active_').find('td').eq(0).text(),prodQty:prodQty,grossPrice:gross,netPrice:net,accID:$('#accID').html(),temp_list:'UPDATE'}
				});
				 $('#list2 tr.active_').removeClass('active_');
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
				$('tr.active_').find('td').eq(5).html(net);
				$('#total').html(new String(new Number(new Number($('#total').html().replace(/,/g, "")-net_old) + net).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				/*$.ajax({
					url: 'save/save.php',
					type: 'get',
					data: {prodID:$('tr.active_').find('td').eq(0).text(),prodQty:$('tr.active_').find('td').eq(3).text().replace(/,/g, ""),grossPrice:gross,netPrice:net,accID:$('#accID').html(),temp_list:'UPDATE'}
				});*/
				 $('#list2 tr.active_').removeClass('active_');
			}
		}
	}
	if(check){
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	}
 
});
 
$(window).resize(function() {
    $(".modal-box").css({
        top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
        left: ($(window).width() - $(".modal-box").outerWidth()) / 2
    });
});
 
$(window).resize();
 
});
// JavaScript Document