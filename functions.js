// JavaScript Document
$(document).ready(function () {
	$(document).on('dblclick',function(e) {
		e.preventDefault();
		});
	x = window.screen.width;
	
	if(x < 1366)
		$('body').css({'width':'1366px','height':'768px'});
	else
		$('body').css('width',window.screen.width);
	$('#home').click(function () {
		window.location.assign('home.php');
		});
	$('#sell').click(function () {
		window.location.assign('sales.php');
		});
	$('#modules').click(function () {
		window.location.assign('add-product.php');
		});
	$('#reports').click(function () {
		window.location.assign('view-sales.php');
		});
	$('#setup').click(function () {
		window.location.assign('view-accounts1.php');
	});
	$('#about').click(function () {
		window.location.assign('view-accounts.php');
	});
	function Hide(){
		$('#navbar ul li').removeClass('active');
		$('#home_menu').hide();
		$('#sell_menu').hide();
		$('#modules_menu').hide();
		//$('#inventory_menu').hide();
		$('#reports_menu').hide();
		$('#setup_menu').hide();
		$('#about_menu').hide();
	}
	
	
	function hh(){
		Hide();
		$('#'+$('#parent').html()).addClass('active');
		$('#'+$('#parent').html()+'_menu').show();
	};	
	$('#navbar ul li').mouseover(function() {
		Hide();
		$(this).addClass('active');
		$('#'+$(this).attr('id')+'_menu').show();
	});
	$('#navbar ul li,#submenu').hover(function() {
	  window.clearInterval(timer);   
	}, function() {
	  timer = window.setInterval(hh, 1000);
	});
	$('.close').click(function() {
			$(this).parent().hide();
			$('#header2').css('margin-bottom','25px');
		});
	$('#refresh').click(function() {
		window.location.assign(window.location.href);
	});
	shortcut.add('ctrl+p',function() {});
	shortcut.add('ctrl+shift+i',function() {return false;});
	$('.dropdown').hover(function() {
		$('.dropdown-menu').addClass('show-dropdown');
	});
	$('.dropdown').mouseleave(function() {
		$('.dropdown-menu').removeClass('show-dropdown');
	});
	
});
