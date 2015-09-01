<script type="text/javascript">
	
	var flag = 0;
	var global_adjust_id = 0;
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

    var spnuom = document.createElement('span');
	colarray['uom'] = { 
		header_title: "UOM",
		edit: [spnuom],
		disp: [spnuom],
		td_class: "tablerow column_click column_hover tduom"
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
        header_title: "Inventory",
        edit: [spninv],
        disp: [spninv],
        td_class: "tablerow column_click column_hover tdinv"
    };

    var spnrequest = document.createElement('span');
    var spnrequesthidden = document.createElement('span');
    spnrequesthidden.setAttribute('style','display:none');
	colarray['request'] = { 
        header_title: "Adjusted Inventory",
        edit: [spnrequest,spnrequesthidden],
        disp: [spnrequest,spnrequesthidden],
        td_class: "tablerow column_click column_hover tdrequest"
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

	$('#tbl').hide();
	$('#new_inventory').binder('setRule','numeric');

	$('#date_from, #date_to').datepicker();
    $('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );

    var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", 
										  controller : 'adjust',
										  notFoundMessage : 'No product found!' });

	tableHelper.headContent.bindSearchEvent(triggerSearchRequest);

	triggerSearchRequest();
	
    $('.column_click').live('click',function(){
    	if (flag == 1)
			return;

    	global_row_index = $(this).parent().index();
    	global_product_id = tableHelper.contentProvider.getData(global_row_index,'id');
    	global_adjust_id = tableHelper.contentProvider.getData(global_row_index,'request',1);
    	
    	if (Boolean(<?= $permission_list['allow_to_add'] ?>) == false && global_adjust_id == 0)
    		return;

    	if (Boolean(<?= $permission_list['allow_to_edit'] ?>) == false && global_adjust_id != 0)
    		return;

    	var arr = 	{ 
						fnc 	 : 'get_adjust_details', 
						product_id : global_product_id,
						adjust_id  : global_adjust_id,
					};

		flag = 1;

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				if (response.error != '') 
					build_message_box('messagebox_2',response.error,'danger');
				else
				{
					if (global_adjust_id == 0) 
						$('#div-pending').hide();
					else
						$('#div-pending').show();

					$('#product_name').html(response.product_name);
					$('#product_code').html(response.material_code);
					$('#old_inventory').html(response.old_inventory);
					$('#new_inventory').val(response.new_inventory);
				}

				$('#requestAdjustModal').modal('show');
				flag = 0;
			}       
		});
    });

	$('#save').click(function(){
		if (flag == 1)
			return;

		var fnc_val 			= global_adjust_id == 0 ? 'insert_inventory_adjust' : 'update_inventory_adjust';
		var new_inventory_val 	= $('#new_inventory').val();
		var old_inventory_val 	= $('#old_inventory').html();
		var memo_val 			= $.sanitize($('#memo').val());

		var errorList = $.dataValidation([{
	                                        value : new_inventory_val,
	                                        fieldName : 'New Inventory',
	                                        required : true,
	                                        rules : 'numeric'
                                         }]);

		if (errorList.length > 0) {
            clear_message_box();
            build_message_box('messagebox_2',build_error_message(errorList),'danger');
            return;
        };

		var arr = 	{ 
						fnc 	 : fnc_val, 
						product_id : global_product_id,
						detail_id  : global_adjust_id,
						old_inventory : old_inventory_val,
						new_inventory : new_inventory_val,
						memo : memo_val
					};

		flag = 1;

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				if (response.error != '') 
					build_message_box('messagebox_2',response.error,'danger');
				else
				{
					var message = '';
					if (response.status == 1) 
					{
						var adjust_id = Number(global_adjust_id) == 0 ? response.id : global_adjust_id;
						message = 'Inventory adjust request successfully submitted!';
						tableHelper.contentProvider.setData(global_row_index,'request',[new_inventory_val,adjust_id]);
					}
					else if (response.status == 2)
					{
						message = 'Inventory successfully adjusted!';
						tableHelper.contentProvider.setData(global_row_index,'inv',[new_inventory_val]);
					} 
						
					$('#requestAdjustModal').modal('hide');
					build_message_box('messagebox_1',message,'success');
				}
				flag = 0;
			}       
		});
	});

    //Callback function for modals. Will reset values to default
    $('#requestAdjustModal').live('hidden.bs.modal', function (e) {
		global_row_index 	= 0;
		global_adjust_id 	= 0;
		global_product_id 	= 0;
		clear_message_box();
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

		var itemcode_val 	= $('#itemcode').val();
		var product_val 	= $('#product').val();
		var subgroup_val 	= $('#subgroup').val();
		var type_val 		= $('#type').val();
		var material_val	= $('#material').val();
		var datefrom_val	= $('#date_from').val();
		var dateto_val		= $('#date_to').val();
		var inv_val			= $('#invstatus').val();
		var orderby_val		= $('#orderby').val();

		var filterValues = 	{ 
								fnc 	 : 'get_product_and_adjust_list', 
								code 	 : itemcode_val,
								product  : product_val,
								subgroup : subgroup_val,
								type 	 : type_val,
								material : material_val,
								datefrom : datefrom_val,
								dateto 	 : dateto_val,
								invstat  : inv_val,
								orderby  : orderby_val,
								filter_reset : filterResetValue,
								row_start : rowStartValue,
								row_end : rowEndValue
							};

		tableHelper.contentHelper.refreshTableWithLimit(filterValues);
	}
</script>