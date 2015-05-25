<script type="text/javascript">
	var flag = 0;

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
		if (flag == 1) { return; };
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
				}
				else
				{

					fill_dropdown_option('branch',response.branches);

					if (response.branches.length >= 2) 
					{
						$('#myModal').modal('show');
					}
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
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
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
				{
					build_message_box('messagebox_2',response.error,'danger');
				}
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