function build_message_box(id,string,status)
{
	var dom = "<div class='lblmsg " + status + "'>"+string+"</div>";
	$('#'+id).html(dom);
}

function build_error_message(errors)
{
	var string = '';

	for (var i = 0; i < errors.length; i++) {
		//string += "<i class='fa fa-exclamation-triangle' />&nbsp;&nbsp;"+errors[i]+"<br/>";
		string += errors[i]+"<br/>";
	};

	return string;
}

function clear_message_box()
{
	$('#messagebox_1').html('');
	$('#messagebox_2').html('');
	$('#messagebox_3').html('');
}