<script type="text/javascript">
	var flag = 0;

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
        td_class: "tablerow column_click column_hover"
    };

	var spnproduct = document.createElement('span');
	colarray['name'] = { 
        header_title: "Product",
        edit: [spnproduct],
        disp: [spnproduct],
        td_class: "tablerow column_click column_hover"
    };

	var spntype = document.createElement('span');
	colarray['type'] = { 
        header_title: "Type",
        edit: [spntype],
        disp: [spntype],
        td_class: "tablerow column_click column_hover"
    };

    var spnmaterial = document.createElement('span');
	colarray['material'] = { 
        header_title: "Material Type",
        edit: [spnmaterial],
        disp: [spnmaterial],
        td_class: "tablerow column_click column_hover"
    };

    var spnsubgroup = document.createElement('span');
	colarray['subgroup'] = { 
        header_title: "Sub Group",
        edit: [spnsubgroup],
        disp: [spnsubgroup],
        td_class: "tablerow column_click column_hover"
    };

    var spninv = document.createElement('span');
	colarray['inv'] = { 
        header_title: "Inv",
        edit: [spninv],
        disp: [spninv],
        td_class: "tablerow column_click column_hover"
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
	$('#datefrom, #dateto').datepicker();
    $('#datefrom, #dateto').datepicker("option","dateFormat", "yy-mm-dd" );

    $('#search').click(function(){
    	refresh_table();
    });

    $('#save').click(function(){
    	if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
		var itemcode_val 	= $('#new_itemcode').val();
		var product_val 	= $('#new_product').val();
		var min_inv_val 	= $('#new_min').val();
		var max_inv_val 	= $('#new_max').val();
		var material_val	= $('#material_id').val() == '' ? 0 : $('#material_id').val();
		var subgroup_val	= $('#subgroup_id').val() == '' ? 0 : $('#subgroup_id').val();
		var is_nonstack_val = $('#new_nonstack').is(':checked') ? 0 : 1;

		var arr = 	{ 
						fnc 	 : 'insert_new_product', 
						code 	 : itemcode_val,
						product  : product_val,
						subgroup : subgroup_val,
						material : material_val,
						min_inv  : min_inv_val,
						max_inv  : max_inv_val,
						is_nonstack : is_nonstack_val
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
					$('#createProductModal').modal('hide');
					build_message_box('messagebox_1','Product successfully saved!','success');
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

    $('#createProductModal').live('hidden.bs.modal', function (e) {
		$('.modal-fields').val('');
		$('.modal-fields').html('');
		$('.modal-fields').removeAttr('checked');
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
		var datefrom_val	= $('#material').val();
		var dateto_val		= $('#material').val();
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
						myjstbl.mypage.set_last_page( Math.ceil(Number(rowcnt) / Number(myjstbl.mypage.filter_number)));
					}

					myjstbl.insert_multiplerow_with_value(1,response.data);

					$('#tbl').show();
				}

				flag = 0;
			}       
		});
	}
</script>