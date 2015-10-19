function fill_dropdown_option(id,data,is_chosen)
{
	var options = "";

	for (var i = 0; i < data.length; i++)
		options += "<option value = '" + data[i]['id'] + "'>" + data[i]['value'] + "</option>";

	clear_dropdown_option(id,options,is_chosen);
}

function clear_dropdown_option(id,options,is_chosen)
{
	if (is_chosen) 
		$('#'+id).html('').trigger("liszt:updated");
	else
		$('#'+id).html('');

	if (options) 
		$('#'+id).append(options);

	if (is_chosen) 
		$('#'+id).trigger("liszt:updated");
}

function bind_asc_desc(element)
{
	$('#' + element).click(function(){
		var value = $(this).val();
		if (value == 'ASC') 
			$(this).val('DESC');
		else
			$(this).val('ASC');
	});
}