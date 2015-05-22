<script type="text/javascript">
	var flag = 0;

	$('#branches').chosen();

	$("#show-info-btn").click(function(){
		var value = $(this).val();

        if (value == 'Show Advanced Info') 
        {
            $(this).val('Hide Advanced Info');
        }
        else
        {
            $(this).val('Show Advanced Info');
        }

		$("#show-info").toggle("fast");
	});

	$('#save').click(function(){

		if(flag==1){return;}
		flag = 1;

		var token_val		= '<?= $token ?>';
		var user_code_val	= $("#user_code").val();
		var full_name_val 	= $("#full_name").val();
		var status_val 		= $("#is_active").is(":checked") ? 1 : 0;
		var user_name_val 	= $("#user_name").val();
		var password_val 	= $("#password").val();
		var contact_val 	= $("#contact").val();
		var branches_val    = $('#branches').val() == null ? '' : $('#branches').val();
		var fnc_val 		= "<?= $this->uri->segment(3) ?>" != "" ? "update_user" : "insert_new_user";

		var arr = 	{ 
						fnc 		: fnc_val, 
						user_code 	: user_code_val, 
						full_name 	: full_name_val,
						status 		: status_val,
						user_name 	: user_name_val,
						password 	: password_val,
						contact 	: contact_val,
						branches 	: branches_val
					};

		$.ajax({
			type: "POST",
			url: "",
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
					build_message_box('messagebox_1','Account successfully saved!','success');
					window.location = "<?= site_url() ?>/user/list";
				}

				flag = 0;
			}       
		});

	});

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{

		var token_val		= '<?= $token ?>';
		var user_id_val 	= "<?= $this->uri->segment(3) ?>";

		var arr = 	{ 
						fnc 		: fnc_val, 
						user_id 	: user_id_val, 
					};

		$.ajax({
			type: "POST",
			url: "",
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
					
				}

				flag = 0;
			}       
		});
	};
</script>