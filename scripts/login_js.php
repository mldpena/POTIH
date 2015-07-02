<script type="text/javascript">
	var flag = 0;
	var token_val = '<?= $token ?>';

	$('#username').focus();
	
	$('#password').live('keydown',function(e){
		if (e.keyCode == 13) 
		{
			e.preventDefault();
			check_user_login();
		};
	});

	$('#submit').live('click',function(){
		$(this).attr('disabled','disabled');
		 check_user_login();
	});

	$('#proceed').live('click',function(){
		set_branch_session();
	});

	$('#myModal').live('hidden.bs.modal', function (e) {
		$('#submit').removeAttr('disabled');
		flag = 0;
	});

	function check_user_login()
	{
		if (flag == 1) 
			return;

		flag = 1;

		var token_val		= '<?= $token ?>';
		var username_val	= $("#username").val();
		var password_val 	= $("#password").val();

		var arr = 	{ 
						fnc : 'check_user', 
						user_name : username_val, 
						password : password_val
					};

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
				{
					$('#submit').removeAttr('disabled');
					build_message_box('messagebox_1',response.error,'danger');
					$("#username, #password").val('');
				}
				else
				{
					if(response.is_first_login == 0)
					{
						var defaultPasswordMessage = (response.is_default_password) ? 'You are using a default password.' : '';
						alert(defaultPasswordMessage + "Please change your password after logging in. To change your password, please go to My Profile under your name.");
					}

					fill_dropdown_option('branch',response.branches);

					if (response.branches.length >= 2) 
						$('#myModal').modal('show');
					else
					{
						flag = 0;
						set_branch_session();
					}
				}

				flag = 0;

			}       
		});
	}

	function set_branch_session()
	{
		if (flag == 1) 
			return;

		flag = 1;

		var username_val	= $("#username").val();
		var password_val 	= $("#password").val();
		var branch_val		= $('#branch').val();

		var arr = 	{ 
						fnc : 'set_branch_user_session', 
						user_name : username_val, 
						password : password_val,
						branch_id : branch_val 
					};

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_2',response.error,'danger');
				else
				{
					build_message_box('messagebox_1','Connected!','success');
					window.location = '<?= base_url() ?>controlpanel';
					$('#myModal').hide();
				}

				flag = 0;
			}       
		});
	}
</script>