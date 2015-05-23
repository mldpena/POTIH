<script type="text/javascript">
	var flag = 0;
	var global_branch_id = 0;
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

    var spnbranchcode = document.createElement('span');
	colarray['code'] = { 
        header_title: "Branch Code",
        edit: [spnbranchcode],
        disp: [spnbranchcode],
        td_class: "tablerow column_click column_hover tdcode"
    };

	var spnname = document.createElement('span');
	colarray['name'] = { 
        header_title: "Branch Name",
        edit: [spnname],
        disp: [spnname],
        td_class: "tablerow column_click column_hover tdname"
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

	 $('.tddelete').live('click',function(){
		global_row_index 	= $(this).parent().index();
		global_branch_id 	= myjstbl.getvalue_by_rowindex_tdclass(global_row_index, colarray["id"].td_class)[0];

		$('#deleteBranchModal').modal('show');
	});
   

	$('#search').click(function(){
    	refresh_table();
    });
  

	$('#save').click(function(){
    	if (flag == 1) { return; };
		flag = 1;

		var token_val	     	= '<?= $token ?>';
		var row_index 		    = global_row_index;
		var branch_id_val	    = global_branch_id;
		var code_val 			= $('#code').val();
		var name_val 		    = $('#name').val();
	    var fnc_val 			= branch_id_val == 0 ? 'insert_new_branch' : 'edit_branch';
		
	   	 	
		var arr = 	{ 
						fnc 	 : fnc_val, 
						code 	 : code_val,
						name     : name_val,
			     		branch_id : branch_id_val
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
					if (myjstbl.get_row_count() - 1 == 0) 
					{
  						$("#tbl").show();
            		};

            		if (branch_id_val == 0) 
            		{
            			myjstbl.add_new_row();
        				row_index = myjstbl.get_row_count() - 1;
        				myjstbl.setvalue_to_rowindex_tdclass([response.id],row_index, colarray["id"].td_class);
        				myjstbl.setvalue_to_rowindex_tdclass([row_index],row_index, colarray["number"].td_class);
            		}

            		myjstbl.setvalue_to_rowindex_tdclass([code_val],row_index,colarray["code"].td_class);
        			myjstbl.setvalue_to_rowindex_tdclass([name_val],row_index,colarray["name"].td_class);
        		

        			$('#createBranchModal').modal('hide');

					build_message_box('messagebox_1','Branch successfully saved!','success');
				}

				flag = 0;
			}       
		});

    });

    $('.column_click').live('click',function(){

    	var token_val			= '<?= $token ?>';
    	global_row_index 	= $(this).parent().index();
    	global_branch_id 	= myjstbl.getvalue_by_rowindex_tdclass(global_row_index, colarray["id"].td_class)[0];

    	var arr = 	{ 
						fnc 	 	: 'get_branch_details', 
						branch_id 	: global_branch_id
					};

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
				{
					build_message_box('messagebox_1',response.error,'danger');
				}
				else
				{
					$('#code').val(response.data['code']);
					$('#name').val(response.data['name']);
					$('#createBranchModal').modal('show');
				}
			}       
		});
	});

	$('#delete').click(function(){
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
		var row_index 		= global_row_index;
		var branch_id_val 	= global_branch_id;

		var arr = 	{ 
						fnc 	 	: 'delete_branch', 
						branch_id 	: branch_id_val
					};
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
				{
					build_message_box('messagebox_3',response.error,'danger');
				}
				else
				{
					myjstbl.delete_row(row_index);
					recompute_row_count(myjstbl,colarray);

					if (myjstbl.get_row_count() == 1) 
					{
						$('#tbl').hide();
					}

					$('#deleteBranchModal').modal('hide');

					build_message_box('messagebox_1','Branch successfully deleted!','success');
				}

				flag = 0;
			}       
		});
	});

	$('#createBranchModal, #deleteBranchModal').live('hidden.bs.modal', function (e) {
		$('.modal-fields').val('');
		$('.modal-fields').html('');
		$('.modal-fields').removeAttr('checked');
		global_row_index 	= 0;
		global_branch_id 	= 0;
	});

	function refresh_table()
	{

		if (flag == 1) { return; };
		flag = 1;

		var token_val	= '<?= $token ?>';
		var search_val 	= $('#searchstring').val();
		var order_val 	= $('#orderby').val();

		var arr = 	{ 
						fnc 	 : 'search_branch_list', 
						search 	 : search_val,
						orderby  : order_val
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
					build_message_box('messagebox_1','No branch found!','info');
				}
				else
				{
					if(response.rowcnt <= 10)
					{
						myjstbl.mypage.set_last_page(1);
					}
					else
					{
						myjstbl.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(myjstbl.mypage.filter_number)));
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