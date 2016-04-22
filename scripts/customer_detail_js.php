<script type="text/javascript">

	var flag = false;

	$('#save').click(function(){
		
		if (flag) 
			return;

		var code_val			= $.trim($("#code").val());
		var company_name_val 	= $.trim($("#company-name").val());
		var office_address_val 	= $.trim($("#office-address").val());
		var plant_address_val 	= $.trim($("#plant-address").val());
		var contact_val 		= $("#contact").val();
		var contact_person_val 	= $.trim($("#contact-person").val());
		var tin_val 			= $("#tin").val();
		var tax_val 			= $("#tax").val();
		var business_style_val  = $('#business-style').val();

		var fnc_val 		= "<?= $this->uri->segment(3) ?>" != "" ? "update_customer" : "insert_new_customer";
		
		var errorList = $.dataValidation([	
											{ 	
												value : code_val,
												fieldName : 'Code',
												required : true,
												rules : 'code' 
											},
											{
												value : company_name_val,
												fieldName : 'Company Name',
												required : true,
												rules : 'alphaNumericChar'
											},
											{
												value : office_address_val,
												fieldName : 'Office Address',
												required : true,
												rules : 'alphaNumericChar'
											},
											{
												value : plant_address_val,
												fieldName : 'Plant Address',
												required : true,
												rules : 'alphaNumericChar'
											},
											{
												value : contact_val,
												fieldName : 'Contact',
												required : false,
												rules : 'alphaNumeric'
											},
											{
												value : contact_person_val,
												fieldName : 'Contact Person',
												required : false,
												rules : 'letterChar'
											},
											{
												value : tin_val,
												fieldName : 'Tin',
												required : false,
												rules : 'numericReg'
											},
											{
												value : business_style_val,
												fieldName : 'Business Style',
												required : true,
												rules : 'alphaNumericChar'
											}
										]);

		if (errorList.length > 0) 
		{
			build_message_box('messagebox_1', build_error_message(errorList),'danger');
			return;
		};

		var arr = 	{ 
						fnc 			: fnc_val, 
						code 			: code_val, 
						company_name 	: company_name_val,
						office_address 	: office_address_val,
						plant_address 	: plant_address_val,
						contact 		: contact_val,
						contact_person 	: contact_person_val,
						tin 			: tin_val,
						tax 			: tax_val,
						business_style 	: business_style_val
					};

		flag = true;

		$.ajax({
			type: "POST",
			url: "",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + notificationToken,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_1', response.error, 'danger');
				else
				{
					build_message_box('messagebox_1','Customer successfully saved!','success');
					window.location = "<?= site_url() ?>/customer/list";
				}

				flag = false;
			}       
		});

	});

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		flag = true;

		var arr = 	{ 
						fnc : 'get_customer_details'
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
					$('#code').val(response.code);
					$('#company-name').val(response.company_name);
					$('#office-address').val(response.office_address);
					$('#plant-address').val(response.plant_address);
					$('#contact').val(response.contact);
					$('#contact-person').val(response.contact_person);
					$('#tin').val(response.tin);
					$('#tax').val(response.tax);
					$('#business-style').val(response.business_style);

					if (!Boolean(<?= $permission_list['allow_to_edit'] ?>))
						$('#save').hide();
				}

				flag = false;
			}       
		});
	};
</script>