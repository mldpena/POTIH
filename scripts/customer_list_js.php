<script type="text/javascript">
	var isProcessing = false;

	var tab = document.createElement('table');
	tab.className = "tblstyle";
	tab.id = "tableid";
	tab.setAttribute("style","border-collapse:collapse;");
	tab.setAttribute("class","border-collapse:collapse;");
    
    var colarray = [];
	
	var spnid = document.createElement('span');
	colarray['id'] = { 
        header_title: "",
        edit: [spnid],
        disp: [spnid],
        td_class: "tablerow tdid",
		headertd_class : "tdheader_id"
    };

    var spnnumber = document.createElement('span');
	colarray['number'] = { 
        header_title: "",
        edit: [spnnumber],
        disp: [spnnumber],
        td_class: "tablerow tdnumber"
    };

    var spnusercode = document.createElement('span');
	colarray['customer_code'] = { 
        header_title: "Customer Code",
        edit: [spnusercode],
        disp: [spnusercode],
        td_class: "tablerow column_click column_hover tdusercode"
    };

	var spnname = document.createElement('span');
	colarray['name'] = { 
        header_title: "Company Name",
        edit: [spnname],
        disp: [spnname],
        td_class: "tablerow column_click column_hover tdname"
    };

    var spncontact = document.createElement('span');
	colarray['contact'] = { 
        header_title: "Contact",
        edit: [spncontact],
        disp: [spncontact],
        td_class: "tablerow column_click column_hover tdcontact"
    };

    var spntin = document.createElement('span');
	colarray['tin'] = { 
        header_title: "Tin",
        edit: [spntin],
        disp: [spntin],
        td_class: "tablerow column_click column_hover tdtin"
    };

    var spnisvatable = document.createElement('span');
	colarray['isvatable'] = { 
        header_title: "Is Vatable",
        edit: [spnisvatable],
        disp: [spnisvatable],
        td_class: "tablerow column_click column_hover tdisvatable"
    };

    var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");

	<?php if (!$permission_list['allow_to_delete']) : ?>

	imgDelete.setAttribute("style","display:none;");

	<?php endif; ?>

	colarray['coldelete'] = { 
		header_title: "",
		edit: [imgDelete],
		disp: [imgDelete],
		td_class: "tablerow column_hover tddelete"
	};

	var myjstbl;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : true, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);
	root.appendChild(myjstbl.mypage.pagingtable);

	myjstbl.mypage.set_mysql_interval(100);
	myjstbl.mypage.isOldPaging = true;
	myjstbl.mypage.pass_refresh_filter_page(triggerSearchRequest);

	$('#tbl, #loadingimg_upload').hide();

	bind_asc_desc('order_type');

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", 
										  controller : 'customer', 
										  deleteHeadName : 'delete_customer',
										  notFoundMessage : 'No customer found!',
										  token : '<?= $token ?>',
										  permissions : { allow_to_view : Boolean(<?= $permission_list['allow_to_view_detail'] ?>) } 
										});

	tableHelper.headContent.bindAllEvents( { searchEventsBeforeCallback : triggerSearchRequest, 
											deleteEventsAfterCallback : actionAfterDelete } );

	triggerSearchRequest();

	$('#uploadFile').live('click', function(e){
		
		e.preventDefault();
		
		if (isProcessing) 
			return;

		$('#messagebox_4').html('');

		var uploadedFile = $('#fileData').prop('files')[0];   

		if (typeof uploadedFile === 'undefined') 
		{
			build_message_box('messagebox_4', 'Please upload a file!', 'danger');
			return;
		};

		var formData = new FormData();    
		var tokenValue = notificationToken.substr(Number(notificationToken.indexOf('=')) + 1);    
		var tokenName = notificationToken.substr(1, Number(notificationToken.indexOf('=')) - 1);

		formData.append('file', uploadedFile);                         
		formData.append(tokenName, tokenValue);
		formData.append('fnc', 'import_customer');
		
		isProcessing = true;

		$('#loadingimg_upload').show();

		$.ajax
		(
			{
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				data: formData,                         
				type: 'post',
				success: function(response)
				{
					if (response.error != '')
						build_message_box('messagebox_4', response.error, 'danger');
					else
					{
						$('#uploadModal').modal('hide');
						window.location = "<?= base_url() ?>customer/logs/import_customer";
					}
					
					isProcessing = false;

					$('#loadingimg_upload').hide();
				}
		 	}	
		 );
	});

    function triggerSearchRequest(rowStart, rowEnd)
	{
		if((typeof rowStart === 'undefined') && (typeof rowEnd === 'undefined'))
			myjstbl.clear_table();
		else
			myjstbl.clean_table();

		var filterResetValue = (typeof rowStart === 'undefined') ? 1 : 0;
		var rowStartValue = (typeof rowStart === 'undefined') ? 0 : rowStart;
		var rowEndValue = (typeof rowEnd === 'undefined') ? (myjstbl.mypage.mysql_interval-1) : rowEnd;
		var search_string_val = $('#search_string').val();
		var order_by_val 	= $('#order_by').val();
		var order_type_val 	= $('#order_type').val();
		var is_vatable_val 	= $('#is-vatable').val();
		
		var objectValues = 	{ 
								fnc 	 	: 'get_customer_list', 
								search_string : search_string_val,
								order_by  	: order_by_val,
								order_type 	: order_type_val,
								is_vat 		: is_vatable_val,
								filter_reset : filterResetValue,
								row_start 	: rowStartValue,
								row_end 	: rowEndValue
							};

		tableHelper.contentHelper.refreshTableWithLimit(objectValues);
	}

	function actionAfterDelete()
	{
		triggerSearchRequest();
		build_message_box('messagebox_1','Customer successfully deleted!','success');
	}
</script>