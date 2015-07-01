<script type="text/javascript">
	
	var flag = 0;
	var global_product_id = 0;
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
	colarray['material_code'] = { 
		header_title: "Material Code",
		edit: [spnmaterialcode],
		disp: [spnmaterialcode],
		td_class: "tablerow column_click column_hover tdmaterial"
	};

	var spnproduct = document.createElement('span');
	colarray['name'] = { 
		header_title: "Product",
		edit: [spnproduct],
		disp: [spnproduct],
		td_class: "tablerow column_click column_hover tdproduct"
	};

	var spntype = document.createElement('span');
	colarray['type'] = { 
		header_title: "Type",
		edit: [spntype],
		disp: [spntype],
		td_class: "tablerow column_click column_hover tdtype"
	};

	var spnmaterial = document.createElement('span');
	colarray['material'] = { 
		header_title: "Material Type",
		edit: [spnmaterial],
		disp: [spnmaterial],
		td_class: "tablerow column_click column_hover tdmaterial_type"
	};

	var spnsubgroup = document.createElement('span');
	colarray['subgroup'] = { 
		header_title: "Sub Group",
		edit: [spnsubgroup],
		disp: [spnsubgroup],
		td_class: "tablerow column_click column_hover tdsubgroup"
	};

	var spninv = document.createElement('span');
	colarray['inv'] = { 
		header_title: "Inv",
		edit: [spninv],
		disp: [spninv],
		td_class: "tablerow column_click column_hover tdinv"
	};

	var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");
	colarray['delete'] = { 
		header_title: "",
		edit: [imgDelete],
		disp: [imgDelete],
		td_class: "tablerow column_hover tddelete"
	};

	var tab_min_max = document.createElement('table');
	tab_min_max.className = "tblstyle";
	tab_min_max.id = "table_min_max_id";
	tab_min_max.setAttribute("style","border-collapse:collapse;");
	tab_min_max.setAttribute("class","border-collapse:collapse;");
	
	var colarray_min_max = [];
	
	var spnid = document.createElement('span');
	colarray_min_max['id'] = { 
		header_title: "",
		edit: [spnid],
		disp: [spnid],
		td_class: "tablerow tdid",
		headertd_class : "tdheader_id"
	};

	var spnnumber = document.createElement('span');
	colarray_min_max['number'] = { 
		header_title: "",
		edit: [spnnumber],
		disp: [spnnumber],
		td_class: "tablerow tdnumber"
	};

	var spnbranch = document.createElement('span');
	var spnbranchid = document.createElement('span');
	spnbranchid.setAttribute('style','display:none');
	colarray_min_max['branch'] = { 
		header_title: "Branch",
		edit: [spnbranch,spnbranchid],
		disp: [spnbranch,spnbranchid],
		td_class: "tablerow column_hover tdbranch"
	};

	var txtmininv = document.createElement('input');
	txtmininv.setAttribute('class','form-control minvalue');
	colarray_min_max['min_inv'] = { 
		header_title: "Min. Inv.",
		edit: [txtmininv],
		disp: [txtmininv],
		td_class: "tablerow column_hover tdmin"
	};

	var txtmaxinv = document.createElement('input');
	txtmaxinv.setAttribute('class','form-control maxvalue');
	colarray_min_max['max_inv'] = { 
		header_title: "Max. Inv.",
		edit: [txtmaxinv],
		disp: [txtmaxinv],
		td_class: "tablerow column_hover tdmax"
	};

	var myjstbl;
	var myjstbl_min_max;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : true, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);
	root.appendChild(myjstbl.mypage.pagingtable);

	var root_min_max = document.getElementById("tbl_min_max");
	myjstbl_min_max = new my_table(tab_min_max, colarray_min_max, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root_min_max.appendChild(myjstbl_min_max.tab);

	$('#tbl').hide();
	$('.minvalue, .maxvalue').binder('setRule','numeric');
	$('#new_itemcode').binder('setRule','alphaNumeric');

	$('#date_from, #date_to').datepicker();
	$('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ deleteHeadName : 'delete_product', 
										  notFoundMessage : 'No product found!' });
	
	var minMaxTableHelper = new TableHelper({ tableObject : myjstbl_min_max, tableArray : colarray_min_max });

	tableHelper.headContent.bindSearchEvent(getSearchFilter);
	tableHelper.headContent.bindDeleteEvents(actionAfterDelete);
	tableHelper.contentHelper.refreshTable(getSearchFilter);


	function getSearchFilter()
	{
		var itemcode_val 	= $('#itemcode').val();
		var product_val 	= $('#product').val();
		var subgroup_val 	= $('#subgroup').val();
		var type_val 		= $('#type').val();
		var material_val	= $('#material').val();
		var datefrom_val	= $('#date_from').val();
		var dateto_val		= $('#date_to').val();
		var branch_val		= $('#branch').val();
		var inv_val			= $('#invstatus').val();
		var orderby_val		= $('#orderby').val();


		var arr = 	{ 
						fnc 	 : 'get_product_list', 
						code 	 : itemcode_val,
						product  : product_val,
						subgroup : subgroup_val,
						type 	 : type_val,
						material : material_val,
						datefrom : datefrom_val,
						dateto 	 : dateto_val,
						branch 	 : branch_val,
						invstat  : inv_val,
						orderby  : orderby_val
					};

		return arr;
	}

	function actionAfterDelete()
	{
		tableHelper.contentHelper.refreshTable(getSearchFilter);
		build_message_box('messagebox_1','Product successfully deleted!','success');
	}

	//Event for edit product
	$('.column_click').live('click',function(){
		global_row_index 	= $(this).parent().index();
		global_product_id 	= tableHelper.contentProvider.getData(global_row_index,'id');

		var arr = 	{ 
						fnc 	 	: 'get_product_details', 
						product_id 	: global_product_id
					};

		clear_message_box();
		
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				myjstbl_min_max.clear_table();
				
				if (response.error != '') 
					build_message_box('messagebox_1',response.error,'danger');
				else
				{
					$('#new_itemcode').val(response.data['material_code']);
					$('#new_product').val(response.data['product']);
					$('#new_min').val(response.data['min_inv']);
					$('#new_max').val(response.data['max_inv']);
					$('#material_id').val(response.data['material_id']);
					$('#subgroup_id').val(response.data['subgroup_id']);

					if (response.data['type'] == 0) 
						$('#new_nonstack').attr('checked','checked');

					$('#material_text').html(response.data['material_type']);
					$('#subgroup_text').html(response.data['subgroup']);

					myjstbl_min_max.insert_multiplerow_with_value(1,response.branch_inventory);

					$('#createModal').modal('show');
				}
			}       
		});
	});
	
	//Event for detecting material type and subgroup after losing focus in material code
	$('#new_itemcode').blur(function(){
		checkProductCodeGroup();
	});

	//Event for clicking non stack checkbox. Will delete current ids for subgroup and material type
	$('#new_nonstack').click(function(){
		if ($(this).is(':checked')) 
			clearProductCodeGroup();
		else
			checkProductCodeGroup();
	});

	//Event for create product popup
	$('#create_product').click(function(){
		if (flag == 1) 
			return;

		flag = 1;

		var arr = { fnc : 'get_branch_list_for_min_max' };

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				clear_message_box();
				myjstbl_min_max.clear_table();
				myjstbl_min_max.insert_multiplerow_with_value(1,response.data);
				flag = 0;
			}       
		});
	});

	//Event for saving and updating product
	$('#save').click(function(){
		if (flag == 1) 
			return;

		var row_index 		= global_row_index;
		var product_id_val 	= global_product_id;
		var itemcode_val 	= $('#new_itemcode').val();
		var product_val 	= $('#new_product').val();
		var min_inv_val 	= $('#new_min').val();
		var max_inv_val 	= $('#new_max').val();
		var material_val	= $('#material_id').val() == '' ? 0 : $('#material_id').val();
		var subgroup_val	= $('#subgroup_id').val() == '' ? 0 : $('#subgroup_id').val();
		var material_text 	= $('#material_text').html();
		var subgroup_text 	= $('#subgroup_text').html();
		var is_nonstack_val = $('#new_nonstack').is(':checked') ? 0 : 1;
		var min_max_values 	= [];
		var fnc_val 		= product_id_val == 0 ? 'insert_new_product' : 'update_product_details';

		var errorList = $.dataValidation([{ 	
											value : itemcode_val,
											fieldName : 'Material Code',
											required : true,
											rules : 'code' 
										},
										{
											value : product_val,
											fieldName : 'Material Name',
											required : true
										}
										]);

		for (var i = 1; i < myjstbl_min_max.get_row_count(); i++) {
			var branch_inventory_id = minMaxTableHelper.contentProvider.getData(i,'id');
			var branch_id = minMaxTableHelper.contentProvider.getData(i,'branch',1);
			var min_inv = minMaxTableHelper.contentProvider.getData(i,'min_inv');
			var max_inv = minMaxTableHelper.contentProvider.getData(i,'max_inv');

			if (min_inv == '' || max_inv == '') 
			{
				errorList.push('Please fill up all the min and max values!');
				break;
			}
			else if ((min_inv > max_inv && max_inv != 0)) 
			{
				errorList.push('Minimum inventory should not be greater than maximum inventory!');
				break;
			}

			min_max_values.push([branch_inventory_id,branch_id,min_inv,max_inv]);
		};

		if (errorList.length > 0) {
			build_message_box('messagebox_3',build_error_message(errorList),'danger');
			return;
		};

		var arr = 	{ 
						fnc 	 : fnc_val, 
						code 	 : itemcode_val,
						product  : product_val,
						subgroup : subgroup_val,
						material : material_val,
						min_inv  : min_inv_val,
						max_inv  : max_inv_val,
						is_nonstack : is_nonstack_val,
						product_id : product_id_val,
						min_max_values : min_max_values
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
					//Set the inserted data in js table
					if (myjstbl.get_row_count() - 1 == 0) 
						$("#tbl").show();

					//If new data, add new row to table and update its content
					if (product_id_val == 0) 
					{
						tableHelper.contentProvider.addRow();
						row_index = myjstbl.get_row_count() - 1;
						tableHelper.contentProvider.setData(row_index,'id',[response.id]);
						tableHelper.contentProvider.setData(row_index,'inv',[0]);
					}

					var type_name = (is_nonstack_val == 0) ? 'Non - Stock' : 'Stock';

					tableHelper.contentProvider.setData(row_index,'material_code',[itemcode_val]);
					tableHelper.contentProvider.setData(row_index,'name',[product_val]);
					tableHelper.contentProvider.setData(row_index,'type',[type_name]);
					tableHelper.contentProvider.setData(row_index,'material',[material_text]);
					tableHelper.contentProvider.setData(row_index,'subgroup',[subgroup_text]);

					$('#createModal').modal('hide');
					build_message_box('messagebox_1','Product successfully saved!','success');
				}

				flag = 0;
			}       
		});

	});
	
	function clearProductCodeGroup()
	{
		$('#material_id').val(0);
		$('#subgroup_id').val(0);
		$('#material_text').html('');
		$('#subgroup_text').html('');
	}

	function checkProductCodeGroup()
	{

		$('#new_itemcode').val($('#new_itemcode').val().toUpperCase());
		var is_nonstack = $('#new_nonstack').is(':checked');
		var itemcode_val 	= $('#new_itemcode').val();

		if (flag == 1 || is_nonstack || itemcode_val.length < 2)
		{
			clearProductCodeGroup();
			return;
		}

		var arr = 	{ 
						fnc 	 : 'get_material_and_subgroup', 
						code 	 : itemcode_val
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
					$('#material_id').val(response.material_id);
					$('#subgroup_id').val(response.subgroup_id);
					$('#material_text').html(response.material_name);
					$('#subgroup_text').html(response.subgroup_name);
				}

				flag = 0;
			}       
		});
	}
</script>