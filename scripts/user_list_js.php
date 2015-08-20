<script type="text/javascript">
	var token = '<?= $token ?>';

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
	colarray['user_code'] = { 
        header_title: "User Code",
        edit: [spnusercode],
        disp: [spnusercode],
        td_class: "tablerow column_click column_hover tdusercode"
    };

	var spnname = document.createElement('span');
	colarray['name'] = { 
        header_title: "Name",
        edit: [spnname],
        disp: [spnname],
        td_class: "tablerow column_click column_hover tdname"
    };

	var spnusername = document.createElement('span');
	colarray['user_name'] = { 
        header_title: "Username",
        edit: [spnusername],
        disp: [spnusername],
        td_class: "tablerow column_click column_hover tdusername"
    };

    var spncontact = document.createElement('span');
	colarray['contact'] = { 
        header_title: "Contact",
        edit: [spncontact],
        disp: [spncontact],
        td_class: "tablerow column_click column_hover tdcontact"
    };

    var spnstatus = document.createElement('span');
	colarray['status'] = { 
        header_title: "Status",
        edit: [spnstatus],
        disp: [spnstatus],
        td_class: "tablerow column_click column_hover tdstatus"
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

	$('#tbl').hide();

	bind_asc_desc('order_type');

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", 
										  controller : 'user', 
										  deleteHeadName : 'delete_user',
										  notFoundMessage : 'No user found!',
										  permissions : { allow_to_view : Boolean(<?= $permission_list['allow_to_view_detail'] ?>) } 
										});

	tableHelper.headContent.bindAllEvents( { searchEventsBeforeCallback : triggerSearchRequest, 
											deleteEventsAfterCallback : actionAfterDelete } );

	triggerSearchRequest();

    function triggerSearchRequest()
	{
		var search_string_val = $('#search_string').val();
		var order_by_val 	= $('#order_by').val();
		var status_val 		= $('#status').val();
		var order_type_val 	= $('#order_type').val();
		
		var objectValues = 	{ 
								fnc 	 	: 'get_user_list', 
								search_string : search_string_val,
								order_by  	: order_by_val,
								order_type 	: order_type_val,
								status 		: status_val
							};

		tableHelper.contentHelper.refreshTableWithLimit(objectValues);
	}

	function actionAfterDelete()
	{
		triggerSearchRequest();
		build_message_box('messagebox_1','Material successfully deleted!','success');
	}
</script>