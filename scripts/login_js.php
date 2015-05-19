<script type="text/javascript">
	var flag = 0;

	$('#submit').live('click',function()
	{
		if (flag == 1) { return; };
		flag = 1;
		
		var token_val 	 = '<?= $token; ?>';
		var username_val = $("#username").val();
		var password_val = $("#password").val();

		var arr = 	{ 
						fnc: 'check_user', 
						user: username_val, 
						pass: password_val
					};

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(data) {
				
				flag = 0;
			}       
		});
	});

</script>