$(document).ready(function(e) {
    $('#custID').autocomplete({
	  source:'fetch/get-customer.php',
	  minLength:1,
	  autoFocus:true,
	  select: function( event, ui ) {
		  $.ajax({
			  url: 'fetch/get-customer.php?sql=payment',
			   type: 'get',
			   data: {'custID':$(ui)[0].item.value},
			   dataType: 'json',
			   success: function(s){
				  console.log(s);
				  $('#custName').val(s[0]);
				  $('#custCred').val(s[1]);
				  $('#last').html(s[2] == false ? 'None' : s[2] );
				  var oTable = $('#list').dataTable();
			   	  oTable.fnClearTable();
					for(var i = 3; i < s.length; i++) {
						oTable.fnAddData([
						'<input type="checkbox" data-check-id='+s[i][0]+'>',
						s[i][0],
						s[i][1],
						s[i][2],
						s[i][3],
						s[i][4],
						s[i][5]
						]);
					} // End For
			   }
		  });
	  }
  }).on("keydown", function(e) {
    	 $('#custName, #custCred').val('');
		 $('#last').html('');
		 var oTable = $('#list').dataTable();
		 oTable.fnClearTable();
  });
  function getCust(){
  	$.ajax({
		url: 'fetch/get-customer.php?sql=payment',
		 type: 'get',
		 data: {'custID':$('#custID').val()},
		 dataType: 'json',
		 success: function(s){
			console.log(s);
			$('#custName').val(s[0]);
			$('#custCred').val(s[1]);
			$('#last').html(s[2] == false ? 'None' : s[2] );
			var oTable = $('#list').dataTable();
			oTable.fnClearTable();
			  for(var i = 3; i < s.length; i++) {
				  oTable.fnAddData([
				  '<input type="checkbox" data-check-id='+s[i][0]+'>',
				  s[i][0],
				  s[i][1],
				  s[i][2],
				  s[i][3],
				  s[i][4],
				  s[i][5]
				  ]);
			  } // End For
			if(s[0]==false){
			  $('#custName, #custCred, #custID').val('');
		 	  $('#last').html('');
			}
		 }
	  });
  }
  shortcut.add('enter',function() {
	  getCust();
  });
  shortcut.add('tab',function() {
	  getCust();
  });
  $('#checkAll').click(function() {
	 if($(this).prop('checked')==true){
	 	$('[data-check-id]').prop('checked',true);
		var x = new Number();
	 	$('#list').find('tr').each(function(index, element) {
            x +=new Number($(this).find('td').eq(4).text().replace(/,/g, ""));
        });
		$('#total').html(x.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
	 }
	 else{
	 	$('[data-check-id]').prop('checked',false);
		$('#total').html('0.00');
	 }
	 
  });
  shortcut.add("ctrl+p",function() {
	  window.print();
	  });
  $('#save').click(function() {
	  if($('#pay-stat').html()!='Save' && $('#pay-stat').html()=='Ready'){
		  var check = true;
		  var tend = new Number($('#tend').val().replace(/,/g, ""));
		 // alert(new Number($('#tend').val().replace(/,/g, "")) >= new Number($('#nett').val().replace(/,/g, "")));
		  //alert(new Number($('#nett').val().replace(/,/g, "")) > 0);
		  if(new Number($('#tend').val().replace(/,/g, "")) >= new Number($('#nett').val().replace(/,/g, "")) && new Number($('#nett').val().replace(/,/g, "")) > 0){		
			//var tend = new Number($('#tend').val().replace(/,/g, ""));
			 $('#list tbody').find('tr').each(function(index, element) {
				 if($(this).find('input[data-check-id]').prop('checked')==true){
					tend -= new Number($(this).find('td').eq(4).text().replace(/,/g, ""));
					//alert($(this).find('td').eq(1).text());
					$.ajax({
						url: 'save/save.php',
						dataType: 'json',
						data:{saleID:$(this).find('td').eq(1).text(),payReceive:new Number($(this).find('td').eq(4).text().replace(/,/g, "")),saleBalance:0,accID:$('#accID').html()}
					});
				 }
			 });
		  }
		  else if(new Number($('#nett').val().replace(/,/g, "")) <= 0){
			  //var check = true;
			  //alert('asasa');
			  $('#list').find('tr').each(function(index, element) {
					if($(this).find('td').eq(0).text()=='No data available in table'){
						check = false;
					}
					return;
				});
			  if(check){
					 $('#list tbody').find('tr').each(function(index, element) {
						  if(tend <= 0)
							return;
							
						  var y = 0;
						  var z = 0;
						  y = ((tend - new Number($(this).find('td').eq(4).text().replace(/,/g, ""))) <= 0) ? new Number($(this).find('td').eq(4).text().replace(/,/g, ""))-tend : 0;
						  z = ((new Number($(this).find('td').eq(4).text().replace(/,/g, ""))) - tend > 0) ? tend : new Number($(this).find('td').eq(4).text().replace(/,/g, ""));
						  tend -= new Number($(this).find('td').eq(4).text().replace(/,/g, ""));	
						  $.ajax({
							  url: 'save/save.php',
							  dataType: 'json',
							  data:{saleID:$(this).find('td').eq(1).text(),payReceive:z,saleBalance:y,accID:$('#accID').html()}
						  });
					 });
			  }
		  }
		  if(check){
				$('#pay-stat').html("Save");
				$('#custID').attr('disabled','disabled');
				$.ajax({
					url: 'save/save.php',
					dataType: 'json',
					data:{custID:$('#custID').val(),payReceive:new Number($('#tend').val().replace(/,/g, "")),change: tend >= 0 ? tend : 0,accID:$('#accID').html(),main:true},
					success: function(s){
						console.log(s);
						
						$('#payID').html(s[0][0]);
						$('#datetime').html(s[0][1]);
						$('#custID2').html(s[0][2]);
						$('#custName2').html(s[0][3]);
						$('#totSo').html(new String(s.length-2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						$('#totOutCred').html($('#custCred').val());
						$('#totNet').html(s[s.length-1][0]);
						$('#totPaid,#sum-tend').html(s[s.length-1][1]);
						$('#totRemCredit').html(s[0][4]);
						
						for(i=0;i<s.length-2;i++){
							i+=1;
							$('#receipt-list').find('tbody').append('<tr><td>'+s[i][0]+'</td><td>'+s[i][1]+'</td><td>'+s[i][2]+'</td><td>'+s[i][3]+'</td><td>'+s[i][4]+'</td><td>'+s[i][3]+'</td></tr>');
						}
						$('#custName3').html(s[0][3]);
						$('#accName1').html($('#accname').html());
						window.print() ;
					}
				});
				$('.print_info').fadeIn(1000);
		  }
	  }else if($('#pay-stat').html() == 'Saved')
	  	bootbox.dialog({
			message: "The payment has been already save. Click New to start new.",
			buttons: {
				main: {
					label: 'Ok',
					className: "btn"
				},
				new: {
					label: 'New',
					className: "btn",
					callback: function(){
						window.location.assign('payment.php');
					}
				}
			}
		});	
  });
});