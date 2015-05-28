<script type="text/javascript">
	var flag = 0;
	
	$('#create_new').click(function(){
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';

		var arr = 	{ 
						fnc 	 	: 'create_reference_number'
					};
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
				{
					build_message_box('messagebox_1',response.error,'danger');
				}
				else
				{
					window.location = '<?= base_url() ?>poreceive/view/'+response.id;
				}

				flag = 0;
			}       
		});
	});
</script>