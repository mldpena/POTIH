<script type="text/javascript">
	function build_message_box(string,status)
	{
		var dom = "<div class='alert alert-"+status+"' role='alert'>"+string+"</div>";
		return dom;
	}

	function build_error_message(errors)
	{
		var string = '';

		for (var i = 0; i < errors.length; i++) {
			string += "<i class='fa fa-exclamation-triangle' />&nbsp;&nbsp;"+errors[i]+"<br/>";
		};

		return string;
	}
</script>