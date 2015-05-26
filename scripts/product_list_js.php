<script type="text/javascript">
	var flag = 0;
	var global_product_id = 0;
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

	$('#date_from, #date_to').datepicker();
    $('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );

    $('#search').click(function(){
    	refresh_table();
    });

    $('.column_click').live('click',function(){

    	var token_val			= '<?= $token ?>';
    	global_row_index 	= $(this).parent().index();
    	global_product_id 	= myjstbl.getvalue_by_rowindex_tdclass(global_row_index, colarray["id"].td_class)[0];

    	var arr = 	{ 
						fnc 	 	: 'get_product_details', 
						product_id 	: global_product_id
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

					$('#new_itemcode').val(response.data['material_code']);
					$('#new_product').val(response.data['product']);
					$('#new_min').val(response.data['min_inv']);
					$('#new_max').val(response.data['max_inv']);
					$('#material_id').val(response.data['material_id']);
					$('#subgroup_id').val(response.data['subgroup_id']);
					if (response.data['type'] == 0) 
					{
						$('#new_nonstack').attr('checked','checked');
					};

					$('#material_text').html(response.data['material_type']);
					$('#subgroup_text').html(response.data['subgroup']);

					$('#createProductModal').modal('show');
				}
			}       
		});
    });
	
	$('.tddelete').live('click',function(){
		global_row_index 	= $(this).parent().index();
		global_product_id 	= myjstbl.getvalue_by_rowindex_tdclass(global_row_index, colarray["id"].td_class)[0];

		$('#deleteProductModal').modal('show');
	});

    $('#save').click(function(){
    	if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
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
		var fnc_val 		= product_id_val == 0 ? 'insert_new_product' : 'update_product_details';

		var arr = 	{ 
						fnc 	 : fnc_val, 
						code 	 : itemcode_val,
						product  : product_val,
						subgroup : subgroup_val,
						material : material_val,
						min_inv  : min_inv_val,
						max_inv  : max_inv_val,
						is_nonstack : is_nonstack_val,
						product_id : product_id_val
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

            		if (product_id_val == 0) 
            		{
            			myjstbl.add_new_row();
        				row_index = myjstbl.get_row_count() - 1;
        				myjstbl.setvalue_to_rowindex_tdclass([response.id],row_index, colarray["id"].td_class);
        				myjstbl.setvalue_to_rowindex_tdclass([row_index],row_index, colarray["number"].td_class);
            		}

            		var type_name = (is_nonstack_val == 0) ? 'Non - Stack' : 'Stack';

            		myjstbl.setvalue_to_rowindex_tdclass([itemcode_val],row_index,colarray["material_code"].td_class);
        			myjstbl.setvalue_to_rowindex_tdclass([product_val],row_index,colarray["name"].td_class);
        			myjstbl.setvalue_to_rowindex_tdclass([type_name],row_index, colarray["type"].td_class);
        			myjstbl.setvalue_to_rowindex_tdclass([material_text],row_index, colarray["material"].td_class);
        			myjstbl.setvalue_to_rowindex_tdclass([subgroup_text],row_index, colarray["subgroup"].td_class);
        			myjstbl.setvalue_to_rowindex_tdclass([0],row_index, colarray["inv"].td_class);

        			$('#createProductModal').modal('hide');
					build_message_box('messagebox_1','Product successfully saved!','success');
				}

				flag = 0;
			}       
		});

    });
	
	$('#delete').click(function(){
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
		var row_index 		= global_row_index;
		var product_id_val 	= global_product_id;

		var arr = 	{ 
						fnc 	 	: 'delete_product', 
						product_id 	: product_id_val
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

					$('#deleteProductModal').modal('hide');

					build_message_box('messagebox_1','Product successfully deleted!','success');
				}

				flag = 0;
			}       
		});
	});

	$('#new_itemcode').blur(function(){
		var is_nonstack = $('#new_nonstack').is(':checked');

		if (flag == 1) { return; };
		if (is_nonstack) { return };

		flag = 1;

		$(this).val($(this).val().toUpperCase());

		var token_val		= '<?= $token ?>';
		var itemcode_val 	= $('#new_itemcode').val();

		var arr = 	{ 
						fnc 	 : 'get_material_and_subgroup', 
						code 	 : itemcode_val
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
					$('#material_id').val(response.material_id);
					$('#subgroup_id').val(response.subgroup_id);
					$('#material_text').html(response.material_name);
					$('#subgroup_text').html(response.subgroup_name);
				}

				flag = 0;
			}       
		});
	});

	$('#new_nonstack').click(function(){
		if ($(this).is(':checked')) 
		{
			$('#material_id').val(0);
			$('#subgroup_id').val(0);
			$('#material_text').html('');
			$('#subgroup_text').html('');
		};
	});

    $('#createProductModal, #deleteProductModal').live('hidden.bs.modal', function (e) {
		$('.modal-fields').val('');
		$('.modal-fields').html('');
		$('.modal-fields').removeAttr('checked');
		$('#new_min').val(1);
		$('#new_max').val(1);
		global_row_index 	= 0;
		global_product_id 	= 0;
	});


	function refresh_table()
	{
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
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
					build_message_box('messagebox_1','No product found!','info');
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