// JavaScript Document
$(document).ready(function () {
  function num(num) {
    var n= num.toString().split(".");
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return n.join(".");
  }
  $('#custID').autocomplete({
	  source:'fetch/get-customer.php',
	  minLength:2,
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
				  $('#id').focus();
				  
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
				  $('#price').val(s[0]);
				  $('#desc').val(s[1]);
				  $('#qty').focus();
				  $('#qtyy').html(s[2]);
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
  $('#qty').keypress(function(e) {
	  if ($.isNumeric(String.fromCharCode(e.keyCode))){
		  if(new Number($(this).val() + String.fromCharCode(e.keyCode)) <= 0)
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
  $('#qty').keyup(function() {
	  $(this).val(function(index, value) {
		  return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		});
   });
   shortcut.add('f1',function() {
		var check = true;
		$('#input input').each(function() {
			if($(this).val() == ''){
				check = false;
				return
			}
			if(check){
				$('a[data-modal-id=percent_popup]').click();
				$('#percent').focus();
			}
		});
   });
   shortcut.add('f3',function() {
	   $('a[data-modal-id=edit_qty_popup]').click();
	});
  shortcut.add('f4',function() {
	 $('a[data-modal-id=edit_disc_popup]').click();
  	});
	  $('#percent,#percent_edit').keypress(function(e) {
		if($(this).val().length > 4)
		  return false;
	 });
	 $('#percent_edit').priceFormat({
		  prefix: '',
		  thousandsSeparator: '',
		  allowNegative: false
	  });
	 $('#percent').priceFormat({
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
		$('#id').focus();
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
		if(!$(event.target).parents().andSelf().is("#list2") && !$(event.target).parent().andSelf().is("a.btn") && !$(event.target).parents().andSelf().is(".modal-box") && !$(event.target).parents().andSelf().is(".modal-overlay")){
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
});// JavaScript Document