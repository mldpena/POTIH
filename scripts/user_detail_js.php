<script type="text/javascript">

	var isOwnProfile = false;
	var processingFlag = false;

	$('#user_code').binder('setRule','alphaNumeric');
	$('#contact').binder('setRule','numeric');

	$('.preset').click(function(){

		var id = $(this).attr('id');
		id = id.replace('-permission','');

		var presetDetailPermissionClass = (id == 'admin')  ?  'check-detail, .permission-section' : id + '-preset';

		if ($(this).is(':checked'))
		{
			$('.' + presetDetailPermissionClass).attr('checked','checked');
			$('.preset').attr('disabled', 'disabled');
			$(this).removeAttr('disabled');
		} 
		else
		{
			$('.' + presetDetailPermissionClass).removeAttr('checked');
			$('.permission-section').removeAttr('checked');
			$('.preset').removeAttr('disabled');
		}

		checkSectionPermission();
	});

	$('.permission-section').click(function(){

		var id = $(this).attr('id');
		id = id.replace('-permission','');

		var detailPermissionClass = id + '-detail';

		if ($(this).is(':checked'))
			$('.' + detailPermissionClass).attr('checked','checked');
		else
			$('.' + detailPermissionClass).removeAttr('checked');
	});

	$('.check-detail').click(function(){
		var permissionSection = $(this).attr('class');
		permissionSection = permissionSection.split(' ');
		permissionSectionId = permissionSection[1].replace('-detail','');

		//For section permissions
		if ($('.' + permissionSection[1]).length == $('.' + permissionSection[1] + ':checked').length)
			$('#' + permissionSectionId + '-permission').attr('checked','checked');
		else if ($('.' + permissionSection[1]).length != $('.' + permissionSection[1] + ':checked').length)
			$('#' + permissionSectionId + '-permission').removeAttr('checked');
	});


	$("#show-info-btn").click(function(){
		var value = $(this).val();

        if (value == 'Show Advanced Info') 
            $(this).val('Hide Advanced Info');
        else
            $(this).val('Show Advanced Info');

		$("#show-info").toggle("fast");
	});

	$('#save').click(function(){

		if(processingFlag)
			return;

		var user_code_val	= $("#user_code").val();
		var full_name_val 	= $("#full_name").val();
		var status_val 		= $("#is_active").is(":checked") ? 1 : 0;
		var user_name_val 	= $("#user_name").val();
		var password_val 	= $("#password").val();
		var password_temp 	= password_val == '******' ? '123456' : password_val;
		var contact_val 	= $("#contact").val();
		var branches_val    = $('#branches').val() == null ? '' : $('#branches').val();
		var permission_list = [];
		var user_type 		= $('.preset:checked').length > 0 ? $('.preset:checked').val() : UserType.NormalUser;
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

		if ($('.check-detail:checked').length == 0) 
			errorList.push('Please select at least one permission!');

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

		if ($('#admin-permission').is(':checked'))
			permission_list.push("<?= \Permission\SuperAdmin_Code::ADMIN ?>");
		else
		{
			$('.check-detail:checked').each(function(key,element){
				permission_list.push($(element).val());
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
						branches 	: branches_val,
						user_type 	: user_type,
						permission_list : permission_list
					};

		processingFlag = true;

		$.ajax({
			type: "POST",
			url: "",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + notificationToken,
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

				processingFlag = false;
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
			data: 'data=' + JSON.stringify(arr) + notificationToken,
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

					if (response.branches.length == ($('#branches option').length - 1))
						$('#branches').val(0);

					if (response.is_active == 0) 
						$('#is_active').removeAttr('checked');

					if (response.is_own_profile == ProfileStatus.OwnProfile) 
					{
						isOwnProfile = true;
						$('#branches, #user_code, #full_name, #is_active, #contact').attr('disabled','disabled');
						$('#show-info-btn').hide();
					}

					$(".preset").not(".preset[value='" + response.type + "']").attr('disabled', 'checked');
					$(".preset[value='" + response.type + "']").attr('checked', 'checked');

					for (var i = 0; i < response.permissions.length; i++)
						$('.check-detail[value=' + response.permissions[i] + ']').attr('checked','checked');

					if (response.type == UserType.Admin) 
						$('.check-detail').attr('checked','checked');

					checkSectionPermission();
					
					if (Boolean(<?= $permission_list['allow_to_edit'] ?>) == false && response.is_own_profile != ProfileStatus.OwnProfile)
						$('#save').hide();

					$('#branches').select2();
				}

				processingFlag = false;
			}       
		});
	}
	else
		$('#branches').select2();

	function checkSectionPermission()
	{
		if ($('.data-detail').length == $('.data-detail:checked').length)
			$('#data-permission').attr('checked','checked');

		if ($('.purchase-detail').length == $('.purchase-detail:checked').length)
			$('#purchase-permission').attr('checked','checked');

		if ($('.return-detail').length == $('.return-detail:checked').length)
			$('#return-permission').attr('checked','checked');

		if ($('.transfer-detail').length == $('.transfer-detail:checked').length)
			$('#transfer-permission').attr('checked','checked');

		if ($('.other-detail').length == $('.other-detail:checked').length)
			$('#other-permission').attr('checked','checked');

		if ($('.reports-detail').length == $('.reports-detail:checked').length)
			$('#reports-permission').attr('checked','checked');

		if ($('.sales-detail').length == $('.sales-detail:checked').length)
			$('#sales-permission').attr('checked','checked');

		if ($('.pickup-detail').length == $('.pickup-detail:checked').length)
			$('#pickup-permission').attr('checked','checked');
	}
</script>