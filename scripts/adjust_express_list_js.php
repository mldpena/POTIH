<script type="text/javascript">
	var flag = 0;
	var token = '<?= $token ?>';
	var global_row_index = 0;
	var global_id = 0;

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

	var spnproduct = document.createElement('span');
    var spnproductid = document.createElement('span');
    var txtproduct = document.createElement('input');
    txtproduct.setAttribute('class','form-control txtproduct');
    spnproductid.setAttribute('style','display:none;');
	colarray['product'] = { 
        header_title: "Product",
        edit: [txtproduct,spnproductid],
        disp: [spnproduct,spnproductid],
        td_class: "tablerow column_click column_hover tdproduct"
    };

    var spnmaterialcode = document.createElement('span');
	colarray['material_code'] = { 
        header_title: "Material Code",
        edit: [spnmaterialcode],
        disp: [spnmaterialcode],
        td_class: "tablerow column_click column_hover tdmaterial"
    };

    var spnuom = document.createElement('span');
	colarray['uom'] = { 
		header_title: "UOM",
		edit: [spnuom],
		disp: [spnuom],
		td_class: "tablerow column_click column_hover tduom"
	};

    var spninventory = document.createElement('span');
	colarray['oldinventory'] = { 
        header_title: "Old Inventory",
        edit: [spninventory],
        disp: [spninventory],
        td_class: "tablerow column_click column_hover tdinv"
    };

    var spnqty = document.createElement('span');
   	var txtqty = document.createElement('input');
    txtqty.setAttribute('class','form-control txtqty');
	colarray['newinventory'] = { 
        header_title: "New Inventory",
        edit: [txtqty],
        disp: [spnqty],
        td_class: "tablerow column_click column_hover tdqty"
    };

    var spnmemo = document.createElement('span');
    var txtmemo = document.createElement('input');
    txtmemo.setAttribute('class','form-control txtmemo');
	colarray['memo'] = { 
        header_title: "Remarks",
        edit: [txtmemo],
        disp: [spnmemo],
        td_class: "tablerow column_click column_hover tdmemo"
    };

    var spnstatus = document.createElement('span');
	colarray['status'] = { 
        header_title: "Status",
        edit: [spnstatus],
        disp: [spnstatus],
        td_class: "tablerow column_click column_hover tdstatus"
    };

    var imgUpdate = document.createElement('i');
	imgUpdate.setAttribute("class","imgupdate fa fa-check");

	var imgEdit = document.createElement('span');
	
	<?php if ($permission_list['allow_to_edit']) : ?>

	var imgEdit = document.createElement('i');
	imgEdit.setAttribute("class","imgedit fa fa-pencil");

	<?php endif; ?>

	colarray['update'] = { 
		header_title: "",
		edit: [imgUpdate],
		disp: [imgEdit],
		td_class: "tablerow column_hover tdupdate",
		headertd_class: "tdupdate"
	};

	var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");

	<?php if (!$permission_list['allow_to_delete']) : ?>

	imgDelete.setAttribute("style","display:none;");

	<?php endif; ?>

	colarray['delete'] = { 
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

	$('#tbl').hide();

	$('#date_from, #date_to').datepicker();
    $('#date_from, #date_to').datepicker("option","dateFormat", "mm-dd-yy");
    $('#date_from, #date_to').datepicker("setDate",new Date());

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", controller : 'adjust' });


	tableHelper.detailContent.bindUpdateEvents(getRowDetailsBeforeSubmit,false,onAfterAdjustInsert);
	tableHelper.detailContent.bindAutoComplete(onAfterProductSelect);
	tableHelper.headContent.bindSearchEvent(triggerSearchRequest);

	bind_asc_desc('order_type');
    
	triggerSearchRequest();

    $('.imgedit').live('click',function(){
        var rowIndex = $(this).parent().parent().index();
        myjstbl.edit_row(rowIndex);
    });

    $('.imgdel').live('click',function(){
		global_row_index 	= $(this).parent().parent().index();
		global_id 			= tableHelper.contentProvider.getData(global_row_index,'id');

		if (global_id != 0)
			$('#deleteAdjustRequest').modal('show');
		else
		{
			tableHelper.contentProvider.setData(global_row_index,'product',['','']);
			tableHelper.contentProvider.setData(global_row_index,'material_code',['']);
			tableHelper.contentProvider.setData(global_row_index,'uom',['']);
			tableHelper.contentProvider.setData(global_row_index,'oldinventory',['']);
			tableHelper.contentProvider.setData(global_row_index,'newinventory',['']);
			tableHelper.contentProvider.setData(global_row_index,'memo',['']);
		}
	});

    $('#delete').click(function(){
		if (flag == 1) 
			return;

		flag = 1;

		var row_index 	= global_row_index;
		var detail_id 	= global_id;

		var arr = 	{ 
						fnc 	 	: 'delete_inventory_request', 
						detail_id 	: detail_id
					};
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_2',response.error,'danger');
				else
				{
					myjstbl.delete_row(row_index);
					tableHelper.contentProvider.recomputeRowNumber();

					if (myjstbl.get_row_count() == 1) 
						$('#tbl').hide();

					$('#deleteAdjustRequest').modal('hide');

					build_message_box('messagebox_1','Inventory Request entry successfully deleted!','success');
				}

				flag = 0;
			}       
		});
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

		var dateFrom		= $('#date_from').val() != '' ? moment($('#date_from').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var dateTo			= $('#date_to').val() != '' ? moment($('#date_to').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var searchString 	= $('#search_string').val();
		var orderBy 		= $('#order_by').val();
		var orderType 		= $('#order_type').val();

		var filterValues = 	{ 
								fnc 	 : 'get_adjust_express_list', 
								search_string 	: searchString,
								order_by  		: orderBy,
								order_type 		: orderType,
								date_from		: dateFrom,
								date_to 		: dateTo,
								filter_reset : filterResetValue,
								row_start : rowStartValue,
								row_end : rowEndValue
							};

		tableHelper.contentHelper.refreshTableWithLimit(filterValues, hideDeleteAfterLoading);
	}

	function hideDeleteAfterLoading()
	{
		for (var i = 1; i < myjstbl.get_row_count(); i++) {
			var requestStatus = tableHelper.contentProvider.getData(i,'status');
			if (requestStatus != AdjustState.Pending.name)
			{
				var updateElement = tableHelper.contentProvider.getElement(i,'update');
				var deleteElement = tableHelper.contentProvider.getElement(i,'delete');

				$(updateElement).hide();
				$(deleteElement).hide();
			}
		};

		$('#tbl').show();
		
		if (Boolean(<?= $permission_list['allow_to_add']?>) != false)
			tableHelper.contentProvider.addRow();
	}

	function getRowDetailsBeforeSubmit(element)
	{
		var rowIndex 	= $(element).parent().parent().index();
		var productId 	= tableHelper.contentProvider.getData(rowIndex,'product',1);
		var newInventory = tableHelper.contentProvider.getData(rowIndex,'newinventory');
		var oldInventory = tableHelper.contentProvider.getData(rowIndex,'oldinventory');
		var memo 		= $.sanitize(tableHelper.contentProvider.getData(rowIndex,'memo'));
		var adjustId 	= tableHelper.contentProvider.getData(rowIndex,'id');
		var status 		= $.getEnumValue(AdjustState,tableHelper.contentProvider.getData(rowIndex,'status'));
		var fnc 		= adjustId != 0 ? "update_inventory_adjust" : "insert_inventory_adjust"; 

		var errorList = $.dataValidation([  {   
                                                value : productId,
                                                fieldName : 'Product',
                                                required : true,
                                                isNotEqual : { value : 0, errorMessage : 'Please select a valid product!'}
                                            },
                                            {
                                                value : newInventory,
                                                fieldName : 'Quantity',
                                                required : true,
                                                rules : 'numeric'
                                            }
                                            ]);

        if (errorList.length > 0) {
            clear_message_box();
            build_message_box('messagebox_1',build_error_message(errorList),'danger');
            return false;
        };

		var arr = 	{
						fnc 	 	: fnc, 
						product_id 	: productId,
						old_inventory : oldInventory,
						new_inventory : newInventory,
			     		memo 		: memo,
			     		detail_id 	: adjustId,
			     		status 		: status
					};

		return arr;
	}

	function onAfterProductSelect(rowIndex, error, response)
	{
		if (error.length > 0) 
		{
            tableHelper.contentProvider.setData(rowIndex,'product',['',0]);
            tableHelper.contentProvider.setData(rowIndex,'material_code',['']);
            tableHelper.contentProvider.setData(rowIndex,'uom',['']);
            tableHelper.contentProvider.setData(rowIndex,'oldinventory',['']);
            tableHelper.contentProvider.setData(rowIndex,'newinventory',['']);
            tableHelper.contentProvider.setData(rowIndex,'memo',['']);
        }
        else
        {
            tableHelper.contentProvider.setData(rowIndex,'product',[response[1],response[0]]);
            tableHelper.contentProvider.setData(rowIndex,'material_code',[response[2]]);
            tableHelper.contentProvider.setData(rowIndex,'oldinventory',[response[3]]);
            tableHelper.contentProvider.setData(rowIndex,'uom',[response[4]]);
        }
	}

	function onAfterAdjustInsert(rowIndex,response)
	{
		var message = (response.status == AdjustState.Pending.value) ? 'Inventory adjust request successfully submitted!' : 'Inventory successfully adjusted!';

		build_message_box('messagebox_1',message,'success');

		tableHelper.contentProvider.setData(rowIndex,'status',[$.getEnumString(AdjustState,response.status)]);

		if (response.status != AdjustState.Pending.value) {
			var updateElement = tableHelper.contentProvider.getElement(rowIndex,'update');
			var deleteElement = tableHelper.contentProvider.getElement(rowIndex,'delete');

			$(updateElement).hide();
			$(deleteElement).hide();
		};
	}
</script>