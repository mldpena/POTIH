<script type="text/javascript">
	function fill_dropdown_option(id,data,is_chosen)
	{
		var options = "";
		$.each(data, function(key,value){
			options += "<option value = '" + key + "'>" + value + "</option>";
		});

		clear_dropdown_option(id,options,is_chosen);
	}

	function clear_dropdown_option(id,options,is_chosen)
	{
		if (is_chosen) 
		{
			$('#'+id).html('').trigger("liszt:updated");
		}
		else
		{
			$('#'+id).html('');
		}

		if (options) 
		{
			$('#'+id).append(options);
		};

    	if (is_chosen) 
    	{
			$('#'+id).trigger("liszt:updated");
		}
	}

</script>