<script type="text/javascript">
	function build_message_box(id,string,status)
	{
		var dom = "<div class='alert alert-"+status+"' role='alert'>"+string+"</div>";
		$('#'+id).html(dom);
	}

	function build_error_message(errors)
	{
		var string = '';

		for (var i = 0; i < errors.length; i++) {
			string += "<i class='fa fa-exclamation-triangle' />&nbsp;&nbsp;"+errors[i]+"<br/>";
		};

		$('#'+id).html(string);
	}

	function clear_message_box()
	{
		$('messageobx_1').html('');
		$('messageobx_2').html('');
		$('messageobx_3').html('');
	}
</script>