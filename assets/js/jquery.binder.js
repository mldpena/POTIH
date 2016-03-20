(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {

	var keyCodes = [46, 8, 9, 27, 13, 110];

	var methods =
	{
		init : function(){

		},

		setRule : function(format){
			$(this).live('keydown',function (e) {
		        if ($.inArray(e.keyCode,keyCodes) !== -1 ||
		            (e.keyCode == 65 && e.ctrlKey === true) || 
		            (e.keyCode == 86 && e.ctrlKey === true) || 
		            (e.keyCode == 67 && e.ctrlKey === true) || 
		            (e.keyCode >= 35 && e.keyCode <= 40)) {
		                 return;
		        }

		        switch(format){
					case 'numeric':
						if (e.keyCode == 173 || (e.keyCode === 190 && !e.shiftKey)) 
							return;

						if (e.shiftKey) 
							e.preventDefault();
						
						if (((e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105))
				            e.preventDefault();
						break;

					case 'alphaNumeric':
						if (e.shiftKey && (e.keyCode < 65 || e.keyCode > 90))
							e.preventDefault();
						else if ((e.keyCode < 48 || e.keyCode > 90) && (e.keyCode < 96 || e.keyCode > 105))
				            e.preventDefault();
						break;

					case 'letter':
						if (e.shiftKey && (e.keyCode < 65 || e.keyCode > 90))
							e.preventDefault();
						else if ((e.keyCode < 65 || e.keyCode > 90) && (e.keyCode < 96 || e.keyCode > 105))
				            e.preventDefault();
				}
		    });
		}
	}

	$.fn.binder = function(method)
	{
		if(methods[method]) { return methods[method].apply(this, Array.prototype.slice.call(arguments, 1)); }
		else if(typeof method === "object" || !method) { return methods.init.apply(this, arguments); }
		else { $.error("Method "+ method + " does not exist on jQuery.timepicker"); }
	};
}));