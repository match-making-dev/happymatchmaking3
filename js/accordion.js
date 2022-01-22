$(function() {

	function demo01() {
		$(this).next().slideToggle(300);
	}

	$(".simple .toggle").click(demo01);



	function demo02() {
		$(this).toggleClass("active").next().slideToggle(300);
	}

	$(".switch .toggle").click(demo02);

});