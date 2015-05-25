<script type="text/javascript">
	var flag = 0;
	var global_user_id = 0;
	var global_row_index = 0;

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
	
	refresh_table();
	bind_asc_desc('order_type');

	$('#search').click(function(){
    	refresh_table();
    });

	$('.column_click').live('click',function(){
		var row_index 	= $(this).parent().index();
		var user_id 	= myjstbl.getvalue_by_rowindex_tdclass(row_index,colarray['id'].td_class)[0];

		window.open('<?= base_url() ?>user/view/' + user_id);
	});

	$('.tddelete').live('click',function(){
		global_row_index 	= $(this).parent().index();
		global_user_id 		= myjstbl.getvalue_by_rowindex_tdclass(global_row_index, colarray["id"].td_class)[0];

		$('#deleteUserModal').modal('show');
	});

	$('#delete').click(function(){
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
		var row_index 		= global_row_index;
		var user_id_val 	= global_user_id;

		var arr = 	{ 
						fnc 	 	: 'delete_user', 
						user_id 	: user_id_val
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
					myjstbl.delete_row(row_index);
					
					recompute_row_count(myjstbl,colarray);

					if (myjstbl.get_row_count() == 1) 
					{
						$('#tbl').hide();
					}

					$('#deleteUserModal').modal('hide');

					build_message_box('messagebox_1','User successfully deleted!','success');
				}

				flag = 0;
			}       
		});
	});

    function refresh_table()
	{
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
		var search_string_val = $('#search_string').val();
		var order_by_val 	= $('#order_by').val();
		var status_val 		= $('#status').val();
		var order_type_val 	= $('#order_type').val();

		var arr = 	{ 
						fnc 	 	: 'get_user_list', 
						search_string : search_string_val,
						order_by  	: order_by_val,
						order_type 	: order_type_val,
						status 		: status_val
					};

		$('#loadingimg').show();

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				myjstbl.clear_table();
				clear_message_box();

				if (response.rowcnt == 0) 
				{
					$('#tbl').hide();
					build_message_box('messagebox_1','No user found!','info');
				}
				else
				{
					if(response.rowcnt <= 10)
					{
						myjstbl.mypage.set_last_page(1);
					}
					else
					{
						myjstbl.mypage.set_last_page( Math.ceil(Number(rowcnt) / Number(myjstbl.mypage.filter_number)));
					}

					myjstbl.insert_multiplerow_with_value(1,response.data);

					$('#tbl').show();
				}

				$('#loadingimg').hide();

				flag = 0;
			}       
		});
	}

</script>