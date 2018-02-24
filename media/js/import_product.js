// JavaScript Document
$(document).ready(function(e) {
    $(document).on('click',function(){
		$('#import_error').fadeOut(100);
	});
	$(document).on("click","#list tr", function(){
		if ( $(this).hasClass('active_') ) {
			$(this).removeClass('active_');
			$('.btn[data-id="rowOpt"]').attr('disabled',true);
		}
		else {
			$('#list tr.active_').removeClass('active_');
			$(this).addClass('active_');
			$('.btn[data-id="rowOpt"]').attr('disabled',false);
		}
	});
	$(document).on("click", function(event){
		if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("a.btn") && !$(event.target).parent().andSelf().is(".modal-overlay") && !$(event.target).parent().andSelf().is(".modal-box input, .modal-box select")){
			 $('#list tr.active_').removeClass('active_');
			 $('.btn[data-id="rowOpt"]').attr('disabled',true);
		}
	});
	
	function progressHandlingFunction(e){
		if(e.lengthComputable){
			var x = Math.round((e.loaded / e.total) * 100)
			$('#import_loading .bar').css('width',x + "%");
			$('#import_loading .bar').html(x + "%");
		}
	}
	function progressHandlingFunction1(e){
		if(e.lengthComputable){
			var x = Math.round((e.loaded / e.total) * 100)
			$('#save_loading .bar').css('width',x + "%");
			$('#save_loading .bar').html(x + "%");
			alert(x);
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
						url: "excel/get-products.php",
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
								var list = $('#list').dataTable();
								$('#-clear,#save').attr('disabled',true);
								list.fnClearTable();
								for(i=0; i<s.length; i++){
									list.fnAddData([
										s[i][0],
										s[i][1],
										s[i][2],
										s[i][3],
										s[i][4]
									]);
								}
								$('#sum-qty').html(s.length);
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
	$('#-remove').click(function(e) {
        bootbox.dialog({
		  message: "Are you sure you want to remove this product from the list?",
		  buttons: {
			  main: {
				  label: 'Yes',
				  className: "btn",
				  callback: function() {
					  $('#list tr.active_').remove();
					  $('.btn[data-id="rowOpt"]').attr('disabled',true);
				  }
			  },
			  cancel: {
				  label: 'No',
				  className: "btn"
			  }
		  }
	  });	
    });
	$('#-clear').click(function(e) {
        var oTable = $('#list').dataTable();
		oTable.fnClearTable();
		$(this).attr('disabled',true);
		$('#file-name,#file').val('');
		$('.btn[data-id="rowOpt"],#save').attr('disabled',true);
		$('#sum-qty').html('0');
    });
	$('#price').autoNumeric('init',{'vMin':0,'mDec':2});
	$('#reorder').autoNumeric('init',{'vMin':0,'mDec':0});
	$('#category1').change(function(e) {
        ($(this).attr('data-measurement') == 'Decimal') ? $('#reorder').autoNumeric('init',{'vMin':0,'mDec':2}) : $('#reorder').autoNumeric('init',{'vMin':0,'mDec':0});
		($(this).val() > 0) ? (($('#list tbody tr:first td').eq(0).text() != 'No data available in table') ? $('#save').attr('disabled',false) : $('#save').attr('disabled',true)) : $('#save').attr('disabled',true);
    });
	$('#save').click(function(e) {
        bootbox.dialog({
			message: "Please make sure all data are correct. Do you want to complete this transaction?",
			buttons: {
				main: {
					label: 'Yes',
					className: "btn",
					callback: function() {
						var products = [];
						
						var count = 0;
						$('#list tbody tr').each(function(index1, element) {
							var sub1 = [];
                            $(this).find('td').each(function(index2, element1) {
                                sub1.push($(this).text());
                            });
							products[index1] = sub1;
                        });
						
						products = JSON.stringify(products);
						$.ajax({
						  url: "excel/save-products.php",
						  dataType: 'json',  // what to expect back from the PHP script, if anything
						  data: products,      
						  cache: false,
						  contentType: false,
						  processData: false,                   
						  type: 'post',
						  xhr: function() {  // Custom XMLHttpRequest
							  var myXhr = $.ajaxSettings.xhr();
							  if(myXhr.upload){ // Check if upl
								  alert(myXhr.upload.addEventListener('progress1',progressHandlingFunction1, false));
								  myXhr.upload.addEventListener('progress1',progressHandlingFunction1, false); // For handling the progress of the upload
							  	  
							  }
							  return myXhr;
						  },
						  beforeSend: function (thisXHR) {
							  $('#save_loading').fadeIn(100);
						  },
						  success: function (s) {
							  $('#save_loading').fadeOut(100);
							  console.log(s);
							  /*if(s[0]!='Error'){
								  var list = $('#list').dataTable();
								  $('#-clear,#save').attr('disabled',true);
								  list.fnClearTable();
								  for(i=0; i<s.length; i++){
									  list.fnAddData([
										  s[i][0],
										  s[i][1],
										  s[i][2],
										  s[i][3],
										  s[i][4]
									  ]);
								  }
								  $('#sum-qty').html(s.length);
							  }
							  else{
								  $('#rowcol').html(s[1]);
								  $('#suggestion').html(s[2]);
								  $('#save_error').fadeIn(1000);
							  }*/
							  
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
    });
});
$(function(){
	var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('.btn[data-modal-id]').click(function(e) {

		if($('#import-stat').html()!='Save'){
		
		e.preventDefault();
		check = true;
		
		//$(".js-modalbox").fadeIn(500);
		var modalBox = $(this).attr('data-modal-id');
		if(!$('#list tr').hasClass('active_'))
				check = false;
		if(check){
			if(modalBox=='prodID_popup')
				$('#prodID').val($('#list tr.active_ td').eq(0).text());
			else if(modalBox=='desc_popup')
				$('#desc').val($('#list tr.active_ td').eq(1).text());
			else if(modalBox=='status_popup')
				$('option[value="'+$('#list tr.active_ td').eq(2).text()+'"]').prop('selected',true);
			else if(modalBox=='reorder_popup')
				$('#reorder').val($('#list tr.active_ td').eq(3).text());
			else if(modalBox=='price_popup')
				$('#price').val($('#list tr.active_ td').eq(4).text());
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			$('#'+modalBox).fadeIn($(this).data());
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 150;
			$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
			$('#'+modalBox).css("top", view_top);
			$('#'+modalBox).find('input,select').focus();
		}
		}
	});  
	
	$(".js-modal-close, .modal-overlay").click(function() {
		var check = false;
		if(this.id == "prodID_button")
			$('#list tr.active_ td').eq(0).text($('#prodID').val());
		else if(this.id == "desc_button")
			$('#list tr.active_ td').eq(1).html($('#desc').val());
		else if(this.id == "status_button")
			$('#list tr.active_ td').eq(2).html($('#status').val());
		else if(this.id == "reorder_button")
			$('#list tr.active_ td').eq(3).html($('#reorder').val());
		else if(this.id == "price_button")
			$('#list tr.active_ td').eq(4).html($('#price').val());

		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	});
});