$(function(){
	$(".sidebar-group a[href='"+current_url+"'] .link").css({
		"background" : "#ecffe8",
		"border-left" : "2px #00923F solid"
	});

	$(".sidebar-group a[href='"+current_url+"']").parent().css("display", "block");
});