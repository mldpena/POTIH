$(function(){
	$(".sidebar-group a[href*='"+current_url+"'] .link").css({
		"background" : "#ecffe8",
		"border-left" : "2px #00923F solid"
	});

	$(".sidebar-group a[href*='"+current_url+"']").parent().parent().addClass("active-tab");
	$(".sidebar-group.active-tab .subgroup-toggle div i").removeClass("fa-plus-square").addClass("fa-minus-square");
	$(".sidebar-group.active-tab .link-menu").css("display", "block");

	$(".subgroup-toggle").click(function(){
		var currentTabId = "#" + $(this).parent().attr("id");
		var linkMenu = $(currentTabId+" .link-menu").css("display");

		if(linkMenu == "none"){
			$('div i', this).removeClass("fa-plus-square").addClass("fa-minus-square");
			$(currentTabId+" .link-menu").css("display", "block");
		}else{
			$('div i', this).removeClass("fa-minus-square").addClass("fa-plus-square");
			$(currentTabId+" .link-menu").css("display", "none");
		}
	});
});