<script type="text/javascript">
	var flag = 0;
	var global_subgroup_id = 0;
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

    var spnmaterialcode = document.createElement('span');
	colarray['code'] = { 
        header_title: "subgroup code",
        edit: [spnmaterialcode],
        disp: [spnmaterialcode],
        td_class: "tablerow column_click column_hover tdcode"
    };

	var spnproduct = document.createElement('span');
	colarray['name'] = { 
        header_title: "subgroup name",
        edit: [spnproduct],
        disp: [spnproduct],
        td_class: "tablerow column_click column_hover tdproduct"
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
	
   
	$('#search').click(function(){
    	refresh_table();
    	
    });
   
    $('.tddelete').live('click',function(){
		global_row_index 	= $(this).parent().index();
		global_subgroup_id 	= myjstbl.getvalue_by_rowindex_tdclass(global_row_index, colarray["id"].td_class)[0];

		$('#deleteModal').modal('show');
	});
	
	 $('.column_click').live('click',function(){

    	var token_val			= '<?= $token ?>';
    	var global_row_index 	= $(this).parent().index();
    	var global_subgroup_id 	= myjstbl.getvalue_by_rowindex_tdclass(global_row_index, colarray["id"].td_class)[0];

    	var arr = 	{ 
						fnc 	 	: 'get_subgroup_details', 
						subgroup_id : global_subgroup_id
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
					
					$('#myModal').modal('show');

				}

			}       
		});

	});

	$('#delete').click(function(){
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
		var row_index 		= global_row_index;
		var subgroup_id_val 	= global_subgroup_id;

		var arr = 	{ 
						fnc 	 	: 'delete_subgroup', 
						subgroup_id	: subgroup_id_val
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

					$('#deleteModal').modal('hide');

					build_message_box('messagebox_1','Subgroup successfully deleted!','success');
				}

				flag = 0;
			}       
		});
	});

	$('#save').click(function(){
    	if (flag == 1) { return; };
		flag = 1;


		var token_val		= '<?= $token ?>';
		var row_index 		= global_row_index;
		var subgroup_id_val 	= global_subgroup_id;
		var code_val 	= $('#code').val();
		var name_val 	= $('#name').val();
	   var fnc_val 		= subgroup_id_val == 0 ? 'insert_subgroup' : 'edit_subgroup';
		
	   	 	
		var arr = 	{ 
						fnc 	 : fnc_val, 
						code 	 : code_val,
						name     : name_val,
				 subgroup_id     : subgroup_id_val
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

            		if (subgroup_id_val == 0) 
            		{
            			myjstbl.add_new_row();
        				row_index = myjstbl.get_row_count() - 1;
        				myjstbl.setvalue_to_rowindex_tdclass([response.id],row_index, colarray["id"].td_class);
        				myjstbl.setvalue_to_rowindex_tdclass([row_index],row_index, colarray["number"].td_class);
            		}

            	

            		myjstbl.setvalue_to_rowindex_tdclass([code_val],row_index,colarray["code"].td_class);
        			myjstbl.setvalue_to_rowindex_tdclass([name_val],row_index,colarray["name"].td_class);
        		

        			$('#myModal').modal('hide');

					build_message_box('messagebox_1','Subgroup successfully saved!','success');
				}

				flag = 0;
			}       
		});

    });

  $('#myModal, #deleteModal').live('hidden.bs.modal', function (e) {
		$('.modal-fields').val('');
		$('.modal-fields').html('');
		$('.modal-fields').removeAttr('checked');
		global_row_index 	= 0;
		global_subgroup_id 	= 0;
	});
	
	function refresh_table()
	{

		if (flag == 1) { return; };
		flag = 1;

		var token_val	= '<?= $token ?>';
		var search_val 	= $('#searchstring').val();
		var order_val 	= $('#orderby').val();
		


		var arr = 	{ 
						fnc 	 : 'search_subgroup_list', 
						search 	 :  search_val,
						orderby  : order_val
					};

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
					build_message_box('messagebox_1','No material found!','info');
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

				flag = 0;
			}       
		});
	
	}
</script>