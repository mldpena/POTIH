function add_new_row(object,object_array,element_focus)
{
	var ok = true;
	var count = object.get_row_count() - 1;

	if (count > 0) {
		var id =  object.getvalue_by_rowindex_tdclass(count,object_array["id"].td_class);
		if (id == 0) {
			ok = false;
		};
	}

	if (ok == false) return;

	var last_row_index = object.get_row_count();
	object.add_new_row();
	object.setvalue_to_rowindex_tdclass([last_row_index],last_row_index,object_array['number'].td_class);

	$('.' + element_focus + ':last').focus();
}

function recompute_row_count(object,object_array)
{
	for (var i = 1; i < object.get_row_count(); i++) {
		object.setvalue_to_rowindex_tdclass([i],i,object_array["number"].td_class);
	};
}