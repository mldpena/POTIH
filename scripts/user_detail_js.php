<script type="text/javascript">
	var ProfileStatus = {
		OwnProfile : 1,
		OtherProfile : 0
	};

	var flag = 0;
	var token = '<?= $token ?>';

	$('#branches').chosen();

	$("#show-info-btn").click(function(){
		var value = $(this).val();

        if (value == 'Show Advanced Info') 
            $(this).val('Hide Advanced Info');
        else
            $(this).val('Show Advanced Info');

		$("#show-info").toggle("fast");
	});

	$('#save').click(function(){

		if(flag==1)
			return;

		//flag = 1;

		var user_code_val	= $("#user_code").val();
		var full_name_val 	= $("#full_name").val();
		var status_val 		= $("#is_active").is(":checked") ? 1 : 0;
		var user_name_val 	= $("#user_name").val();
		var password_val 	= $("#password").val();
		var contact_val 	= $("#contact").val();
		var branches_val    = $('#branches').val() == null ? '' : $('#branches').val();
		var fnc_val 		= "<?= $this->uri->segment(3) ?>" != "" ? "update_user" : "insert_new_user";

		if ($.inArray('0',branches_val) != -1) 
		{
			branches_val = [];
			$('#branches > option').each(function(key,element){
				var value = $(element).val();
				
				if (value != 0) 
					branches_val.push(value);
			});
		}

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
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_1',response.error,'danger');
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
		var arr = 	{ 
						fnc : 'get_user_details'
					};

		$.ajax({
			type: "POST",
			url: "",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_1',response.error,'danger');
				else
				{
					$('#user_code').val(response.user_code);
					$('#user_name').val(response.username);
					$('#full_name').val(response.full_name);
					$('#password').val('******');
					$('#contact').val(response.contact);
					$('#branches').val(response.branches);

					if (response.is_active == 0) 
						$('#is_active').removeAttr('checked');

					if (response.is_own_profile == ProfileStatus.OwnProfile) 
					{
						$('#branches, #user_code, #full_name, #is_active, #contact').attr('disabled','disabled');
						$('#show-info-btn').hide();
					}

					$('#branches').trigger("liszt:updated");
				}

				flag = 0;
			}       
		});
	};
</script>