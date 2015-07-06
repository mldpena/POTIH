<script type="text/javascript">
	var flag = 0;
	var global_material_id = 0;
	var global_row_index = 0;
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

	var spnmaterialcode = document.createElement('span');
	colarray['code'] = { 
		header_title: "Material Code",
		edit: [spnmaterialcode],
		disp: [spnmaterialcode],
		td_class: "tablerow column_click column_hover tdcode"
	};

	var spnname = document.createElement('span');
	colarray['name'] = { 
		header_title: "Material Name",
		edit: [spnname],
		disp: [spnname],
		td_class: "tablerow column_click column_hover tdname"
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
	$('#code').binder('setRule','letter');

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ deleteHeadName : 'delete_material',
										  notFoundMessage : 'No material type found!' });

	tableHelper.headContent.bindSearchEvent(getSearchFilter);
	tableHelper.headContent.bindDeleteEvents(actionAfterDelete);
	tableHelper.contentHelper.refreshTable(getSearchFilter);

	function getSearchFilter()
	{
		var search_val 	= $('#search_string').val();
		var order_val 	= $('#orderby').val();

		var arr = 	{ 
						fnc 	 : 'search_material_list', 
						search 	 : search_val,
						orderby  : order_val
					};

		return arr;
	}

	function actionAfterDelete()
	{
		tableHelper.contentHelper.refreshTable(getSearchFilter);
		build_message_box('messagebox_1','Material successfully deleted!','success');
	}

	$('#save').click(function(){
		if (flag == 1) 
			return;

		var row_index 		    = global_row_index;
		var material_id_val	    = global_material_id;
		var code_val 			= $('#code').val();
		var name_val 		    = $('#name').val();
		var fnc_val 			= material_id_val == 0 ? 'insert_new_material' : 'edit_material';
		
		var errorList = $.dataValidation([	{ 	
											value : code_val,
											fieldName : 'Code',
											required : true,
											rules : 'letter' 
										},
										{
											value : name_val,
											fieldName : 'Name',
											required : true,
											rules : 'alphaNumeric'
										}
										]);

		if (errorList.length > 0) {
			build_message_box('messagebox_3',build_error_message(errorList),'danger');
			return;
		};

		var arr = 	{ 
						fnc 	 : fnc_val, 
						code 	 : code_val,
						name     : name_val,
						material_id : material_id_val
					};

		flag = 1;

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_3',response.error,'danger');
				else
				{
					if (myjstbl.get_row_count() - 1 == 0) 
						$("#tbl").show();

					if (material_id_val == 0) 
					{
						tableHelper.contentProvider.addRow();
						row_index = myjstbl.get_row_count() - 1;
						tableHelper.contentProvider.setData(row_index,'id',[response.id]);
					}

					tableHelper.contentProvider.setData(row_index,'code',[code_val]);
					tableHelper.contentProvider.setData(row_index,'name',[name_val]);
				
					$('#createModal').modal('hide');

					build_message_box('messagebox_1','Material successfully saved!','success');
				}

				flag = 0;
			}       
		});

	});
	
	<?php if($permission_list['allow_to_edit']) : ?>

	$('.column_click').live('click',function(){

		global_row_index 	= $(this).parent().index();
		global_material_id 	= tableHelper.contentProvider.getData(global_row_index,'id');

		var arr = 	{ 
						fnc 	 	: 'get_material_details', 
						material_id : global_material_id
					};

		clear_message_box();
		
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				if (response.error != '') 
					build_message_box('messagebox_1',response.error,'danger');
				else
				{
					$('#code').val(response.data['code']);
					$('#name').val(response.data['name']);
					$('#createModal').modal('show');
				}
			}       
		});
	});

	<?php endif; ?>
	
	$('#deleteModal, #createModal').live('hidden.bs.modal', function (e) {
        global_material_id 	= 0;
        global_row_index 	= 0;
    });

	$('#code').blur(function(){
		$(this).val($(this).val().toUpperCase());
	});

</script>