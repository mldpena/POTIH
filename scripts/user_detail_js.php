<script type="text/javascript">
	var ProfileStatus = {
		OwnProfile : 1,
		OtherProfile : 0
	};

	var isOwnProfile = false;
	var flag = 0;
	var token = '<?= $token ?>';

	$('#branches').chosen();
	$('#user_code').binder('setRule','alphaNumeric');
	$('#contact').binder('setRule','numeric');

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

		var user_code_val	= $("#user_code").val();
		var full_name_val 	= $("#full_name").val();
		var status_val 		= $("#is_active").is(":checked") ? 1 : 0;
		var user_name_val 	= $("#user_name").val();
		var password_val 	= $("#password").val();
		var password_temp 	= password_val == '******' ? '123456' : password_val;
		var contact_val 	= $("#contact").val();
		var branches_val    = $('#branches').val() == null ? '' : $('#branches').val();
		var fnc_val 		= "<?= $this->uri->segment(3) ?>" != "" ? "update_user" : "insert_new_user";

		var errorList = $.dataValidation([	{ 	
											value : user_code_val,
											fieldName : 'User Code',
											required : true,
											rules : 'code' 
										},
										{
											value : full_name_val,
											fieldName : 'Full Name',
											required : true,
											rules : 'letterChar'
										},
										{
											value : user_name_val,
											fieldName : 'User Name',
											required : true,
											rules : 'credential',
											minLength : 4
										},
										{
											value : password_temp,
											fieldName : 'Password',
											required : true,
											rules : 'credential',
											minLength : 6
										},
										{
											value : contact_val,
											fieldName : 'Contact',
											required : false,
											rules : 'numeric'
										}
										]);

		if (branches_val.length == 0) 
			errorList.push('Please select at least one branch!');

		if (errorList.length > 0) {
			build_message_box('messagebox_1',build_error_message(errorList),'danger');
			return;
		};

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

		flag = 1;

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
					var link = (isOwnProfile) ? 'controlpanel' : 'user/list'
					window.location = "<?= site_url() ?>/" + link;
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
						isOwnProfile = true;
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