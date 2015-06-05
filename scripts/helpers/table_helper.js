function add_new_row(object,object_array,element_focus)
{
	var ok = true;
	var count = object.get_row_count() - 1;

	if (count > 0) {
		var id =  object.getvalue_by_rowindex_tdclass(count,object_array["id"].td_class);
		if (id == 0)
			ok = false;
	}

	if (ok == false) 
		return;

	var last_row_index = object.get_row_count();
	object.add_new_row();
	object.setvalue_to_rowindex_tdclass([last_row_index],last_row_index,object_array['number'].td_class);

	if (element_focus) 
		$('.' + element_focus + ':last').focus();
}

function recompute_row_count(object,object_array)
{
	for (var i = 1; i < object.get_row_count(); i++)
		object.setvalue_to_rowindex_tdclass([i],i,object_array["number"].td_class);
}

function recompute_total_qty(object,object_array,span_id)
{
	var total_count = 0;

	for (var i = 1; i < object.get_row_count(); i++) {
		var current_qty = object.getvalue_by_rowindex_tdclass(i,object_array["qty"].td_class);
		total_count += Number(current_qty);
	};

	if (span_id) 
		$('#' + span_id).html(total_count);
	else
		return total_count;
}


function table_get_column_data(row_index,object_array_column,array_column_index,object,object_array)
{
	var value = "";
	var column_index = 0;
	var js_table = myjstbl;
	var js_table_array = colarray;

	if (array_column_index) 
		column_index = array_column_index;

	if (object) 
	{
		js_table = object;
		js_table_array = object_array;
	};

	value = js_table.getvalue_by_rowindex_tdclass(row_index, js_table_array[object_array_column].td_class)[column_index];

	return value;
}

function table_set_column_data(row_index,object_array_column,values,object,object_array)
{
	var js_table = myjstbl;
	var js_table_array = colarray;

	if (object) 
	{
		js_table = object;
		js_table_array = object_array;
	};

	js_table.setvalue_to_rowindex_tdclass(values,row_index,js_table_array[object_array_column].td_class);
}
