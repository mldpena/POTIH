$(function(){
	$(document).mouseup(function (e){
		var container = $("#notifications-panel");
		if (!container.is(e.target) && container.has(e.target).length === 0) 
				container.hide();
	});
	
	$("#drop-notif").toggle(
		function(){
			$("#notifications-panel").show();
		},
		function(){
			$("#notifications-panel").hide();
		}
	);

	$(".notifications-single").click(function(){
		$("#notifications-panel").hide();
	});

	var arr = { fnc : 'check_notifications' };

	if (currentURL != (baseURL + '/') && currentURL != (baseURL + 'login/')) 
	{
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + notificationToken,
			success: function(response) {

				if (response.error != '') 
					alert(response.error);
				else
				{
					var notificationContainer = $('#notification-container').html();

					if (response.notification.all_count == 0)
						$('#header-notification').html('No notifications found!');
					else
					{
						$('.notifications-icon').addClass('active');
						$('.notif-circle').html(response.notification.all_count).show();

						var notificationContainerElements = [
																{
																	notificationSection : 'inventoryAdjust',
																	notificationSubject : 'New Pending Inventory Adjustment',
																	notificationMessage : 'You have ({0}) new pending inventory adjust!',
																	notificationIcon 	: 'pendinginventoryadjust-new.png',
																	notificationLink 	: 'pending/list/1'
																},
																{
																	notificationSection : 'itemRequest',
																	notificationSubject : 'New Item Request',
																	notificationMessage : 'You have ({0}) requested by other branches!',
																	notificationIcon 	: 'itemreceive-new.png',
																	notificationLink 	: 'requestfrom/list/1'
																},
																{
																	notificationSection : 'itemReceive',
																	notificationSubject : 'New Item Receive',
																	notificationMessage : 'You have ({0}) item receive from other branches!',
																	notificationIcon 	: 'pendinginventoryadjust-new.png',
																	notificationLink 	: 'delreceive/list/1'
																},
																{
																	notificationSection : 'minInventory',
																	notificationSubject : 'Minimum Inventory Warning',
																	notificationMessage : 'You have ({0}) item/s that have reached the minimum inventory!',
																	notificationIcon 	: 'mininventory.png',
																	notificationLink 	: 'product/warning'
																},
																{
																	notificationSection : 'maxInventory',
																	notificationSubject : 'Maximum Inventory Warning',
																	notificationMessage : 'You have ({0}) item/s that have reached the maximum inventory!',
																	notificationIcon 	: 'maxinventory.png',
																	notificationLink 	: 'product/warning'
																},
																{
																	notificationSection : 'negativeInventory',
																	notificationSubject : 'Negative Inventory Warning',
																	notificationMessage : 'You have ({0}) item/s that have reached negative inventory!',
																	notificationIcon 	: 'negativeinventory.png',
																	notificationLink 	: 'product/warning'
																},
																{
																	notificationSection : 'dueIncompleteRequest',
																	notificationSubject : 'Incomplete Item Request Due Warning',
																	notificationMessage : 'You have ({0}) incomplete request with incomplete delivery!',
																	notificationIcon 	: 'incomplete-warning.png',
																	notificationLink 	: 'requestto/list/1'
																},
																{
																	notificationSection : 'noDeliveryRequest',
																	notificationSubject : 'No Delivery Item Request Due Warning',
																	notificationMessage : 'You have ({0}) request with no delivery!',
																	notificationIcon 	: 'no-delivery.png',
																	notificationLink 	: 'requestto/list/2'
																}
															];

						var i = 0;

						$.each(response.notification, function(index, value){

							if (i == notificationContainerElements.length)
								return;

							if (value > 0)
							{
								notificationContainer  = $('#notification-container').html();
								notificationContainer += "	<a href = \" " + baseURL + notificationContainerElements[i].notificationLink + "\">" + 
																"<div class=\"notifications-single\">" + 
																	"<div class=\"detail-container\">" + 
																		"<div class=\"subject\">" +  notificationContainerElements[i].notificationSubject + "</div>" + 
																		"<div class=\"time\">" +  notificationContainerElements[i].notificationMessage.replace('{0}', value) + "</div>" + 
																	"</div>" + 
																	"<img class=\"icon\" src=\"" + baseURL + "assets/img/" +  notificationContainerElements[i].notificationIcon + "\">" + 
																"</div>" +
															"</a>";

								$('#notification-container').html(notificationContainer);
							}

							i++;
						});
					}
				}
			}       
		});
	};
	
});