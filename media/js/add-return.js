$(document).ready(function(e) {
  function getPO(){
  	$.ajax({
		url: 'fetch/get-purchase_orders.php',
		 type: 'get',
		 data: {'purcID':$('#typeValue').val(),account:true,check:true},
		 dataType: 'json',
		 success: function(s){
			console.log(s);
			if(s[s.length-1]==''){
				$('#no_po_product').fadeIn(1000).fadeOut(6500);
				$('#typeValue').val('').focus();
				return;
			}
			var oTable = $('#list').dataTable();
			oTable.fnClearTable();
			var oTable1 = $('#history').dataTable();
			oTable1.fnClearTable();
			var x = new Number();
			for(var i = 0; i < s[0][1].length; i++){
				oTable1.fnAddData([
				'<a href="view-return.php?'+s[0][1][i][0]+'">'+s[0][1][i][0]+'</a>',
				s[0][1][i][1],
				s[0][1][i][2]
				]);
			}
			for(var i = 1; i < s.length-2; i++) {
				if(new Number(s[i][3]) > 0){
				oTable.fnAddData([
				'<input type="checkbox" data-check-id='+s[i][0]+'>',
				s[i][0],
				s[i][1],
				s[i][6],
				s[i][2],
				s[i][3],
				s[i][4],
				'0.00','Good',s[i][7]
				]);
				x +=new Number(s[i][3].replace(/,/g, ""));
				}
			} // End For
			$('#saleID').focusout();
			$('#qty').html(new String(x).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			$('#accName').html(s[s.length-1][0]);
		},
		error: function(ss){
			console.log(ss);
			$('#returnType1,#returnType2').html('Purchase Order');
			$('#no_product').fadeIn(1000).fadeOut(6500);
		}
	  });
  }
  function getSO(){
	  $.ajax({
		 url: 'fetch/get-sales_orders.php',
		 type: 'get',
		 data: {'saleID':$('#typeValue').val(),account:true,check:true},
		 dataType: 'json',
		 success: function(s){
		  console.log(s);
		  if(new Number(s[0][0][0]) > new Number($('#salesReturn').val())){
			  $('#date_error').fadeIn(1000).fadeOut(5500);
			  $(this).val('').focus();
		  }
		  else{
			  $('#net2').val(s[0][0][2]);
			  var oTable = $('#list').dataTable();
			  oTable.fnClearTable();
			  var oTable1 = $('#history').dataTable();
			  oTable1.fnClearTable();
			  var x = new Number();
			  for(var i = 0; i < s[0][1].length; i++){
				  oTable1.fnAddData([
				  '<a href="view-return.php?'+s[0][1][i][0]+'">'+s[0][1][i][0]+'</a>',
				  s[0][1][i][1],
				  s[0][1][i][2]
				  ]);
			  }
			  for(var i = 1; i < s.length-1; i++) {
				  if(new Number(s[i][3]) > 0){
				  oTable.fnAddData([
				  '<input type="checkbox" data-check-id='+s[i][0]+'>',
				  s[i][1],
				s[i][2],
				s[i][0],
				s[i][3],
				s[i][4],
				s[i][5],
				0.00,'Good',s[i][9]
				  ]);
				  x +=new Number(s[i][3].replace(/,/g, ""));
				  }
			  } // End For
			  $('#saleID').focusout();
			  $('#qty').html(new String(x).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			  $('#accName').html(s[s.length-1][0]);
		  }
	   },error: function(ss){
		   console.log(ss);
		   if(ss.length=='undefined'){
			  $('#returnType1,#returnType2').html('Sales Order');
		   	  $('#no_product').fadeIn(1000).fadeOut(6500);
		   }
		   else{
			  $('#typeValue').val('').focus();
			  $('#no_so_product').fadeIn(1000).fadeOut(6500);
		   }
	   }
	  });
  }
  shortcut.add('G',function() {
	  if($('#list tr').hasClass('active_')){
	  if($('#list .active_ td').eq(0).find('input').prop('checked') == true){	  
		 if($('#list .active_ td').eq(8).text() == 'Damage'){
			$('#sum-bad').html(new String(new Number($('#sum-bad').html().replace(/,/g, ""))-new Number($('#list .active_ td').eq(7).text().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		 	$('#sum-good').html(new String(new Number($('#sum-good').html().replace(/,/g, ""))+new Number($('#list .active_ td').eq(7).text().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		 }
	  }
	  	$('#list .active_ td').eq(8).html('Good');
	  }
  });
  shortcut.add('D',function() {
	  if($('#list tr').hasClass('active_')){
		if($('#list .active_ td').eq(0).find('input').prop('checked') == true){	  
		   if($('#list .active_ td').eq(8).text() == 'Good'){
			  $('#sum-good').html(new String(new Number($('#sum-good').html().replace(/,/g, ""))-new Number($('#list .active_ td').eq(7).text().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			  $('#sum-bad').html(new String(new Number($('#sum-bad').html().replace(/,/g, ""))+new Number($('#list .active_ td').eq(7).text().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		   }
		}
	  	$('#list .active_ td').eq(8).html('Damage');
	  }
  });
  shortcut.add('enter',function(e) {
	  if($('#typeValue').is(':focus') && $('#typeValue').val()!=''){
	  	if($('#type').val()=='toInventory')
			getSO();
		else
			getPO();
	  }
	  else
	  	e.preventDefault();
  });
  /*shortcut.add('tab',function() {
	  if($('#custID').is(':focus'))
	  	getCust();
	  else if($('#saleID').is(':focus'))
	  	getProducts();
  });*/
  $('#clear').click(function() {
	 window.location.assign('add-return.php'); 
  });
  $('#checkAll').click(function() {
	 if($(this).prop('checked')==true){
	 	$('[data-check-id]').prop('checked',true);
		var x = new Number();
		var z = x;
		var y = [];
		var qty = new Number();
		var good = new Number();
		var bad = new Number();
	 	$('#list tbody').find('tr').each(function(index, element) {
			//y.push(new Number($(this).find('td').eq(4).text().replace(/,/g, "")));
			$(this).find('td').eq(7).text(new String(new Number($(this).find('td').eq(5).text().replace(/,/g, ""))).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            x +=new Number($(this).find('td').eq(4).text().replace(/,/g, ""));
			qty +=new Number($(this).find('td').eq(5).text().replace(/,/g, ""));
			if($(this).find('td').eq(8).text() == 'Good')
				good += new Number($(this).find('td').eq(5).text().replace(/,/g, ""));
			else if($(this).find('td').eq(8).text() == 'Damage')
				bad += new Number($(this).find('td').eq(5).text().replace(/,/g, ""));
		});
		$('#total').html(x.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		$('#sum-qty').html(new String(qty).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		$('#sum-good').html(new String(good).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		$('#sum-bad').html(new String(bad).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
	 }
	 else{
	 	$('[data-check-id]').prop('checked',false);
		$('#total').html('0.00');
		$('#sum-qty,#sum-bad,#sum-good').html('0');
		$('#list tbody').find('tr').each(function(index, element) {
			$(this).find('td').eq(7).text('0');
		});
	 }
	 
  });
  function printF(){ 
		if($('#return-stat').html()!='Saved')
			return;
		var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
		$("body").append(appendthis);
		$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
		$(".modal-overlay").css('position','fixed');
		var modalBox = 'print_popup';
		$('iframe').attr('src','report/add-return.php?retID='+$('#retID').val());
		$('#'+modalBox).fadeIn(1000);
		$('#'+modalBox+' iframe').css('height',$(window).height()-90);
		var view_width = $(window).width();
		$('#'+modalBox).css('height',$(window).height()-90);
		var view_top = $(window).scrollTop() + 10;
		$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
		$('#'+modalBox).css("top", view_top);
	};
	$(".js-modal-close, .modal-overlay").click(function() {
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	});
  shortcut.add("ctrl+p",function() {
	  printF();
	  });
  $('#save').click(function() {
	  if($('#return-stat').html()!='Save'){
		  var check = true;
		  var total = new Number($('#total').html().replace(/,/g, ""));
		  if(total > 0){
			 bootbox.dialog({
				  message: "Please make sure all data are correct. Do you want to complete this transaction?",
				  buttons: {
					  main: {
						  label: 'Yes',
						  className: "btn",
						  callback: function() {
							  var type;
							   if($('#type').val() == 'toInventory')
								  type = 'SO';
							   else
								  type = 'PO';
							   $.ajax({
								  url: 'return/save.php',
								  dataType: 'json',
								  type:'POST',
								  data:{retType:type,retSubject:$('#typeValue').val(),retCost:new Number($('#total').html().replace(/,/g, "")),retQty:new Number($('#sum-qty').html().replace(/,/g, "")),retGood:$('#sum-good').html(),retBad:$('#sum-bad').html(),accID:$('#accID').html(),new:true,note:$('#note').val(),type1:$('#type1').val()},
								  success: function(s){
									  console.log(s)
									  retID = s[0][0];
									  $('#retID').val(retID);
									  $('#list tbody').find('tr').each(function(index, element) {
										 if($(this).find('input[data-check-id]').prop('checked')==true){
											$.ajax({
												url: 'return/save.php',
												dataType: 'json',
												type:'POST',
												data:{prodID:$(this).find('td').eq(1).text(),prodCost:new Number($(this).find('td').eq(4).text().replace(/,/g, ""))*new Number($(this).find('td').eq(7).text().replace(/,/g, "")),prodQty:new Number($(this).find('td').eq(5).text().replace(/,/g, "")),prodQtyReturn:new Number($(this).find('td').eq(7).text().replace(/,/g, "")),prodStatus:$(this).find('td').eq(8).text().replace(/,/g, ""),accID:$('#accID').html(),retID:retID,retSubject:$('#typeValue').val(),retType:type,unit:$(this).find('td').eq(3).text().replace(/,/g, ""),type1:$('#type1').val()},
												success: function(p){
													  
												},
												error: function(hh){
													$('#save_success').fadeIn(1000);
													  $('#return-stat').html('Saved');
													  $('#list tr.active_').removeClass('active_');
													  $('input,select,button,#save').attr('disabled',true);
													  $('#clear').html('New <span id="key">F5</span>');
													  printF();
												}
											});
										 }
									 });
								  },error: function(j){
									  alert('Error');
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
		  else {
		  	$('#save_error').fadeIn(1000).fadeOut(2500);
		  }
	  }else if($('#return-stat').html() == 'Saved')
	  	$('#save_success').fadeIn(1000);
  });
});// JavaScript Document