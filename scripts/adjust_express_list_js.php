<script type="text/javascript">
	var state = {
		Pending : { name : "Pending", value : 1 },
		Approved : { name : "Approved", value : 2 },
		Declined : { name : "Declined", value : 3 }
	};

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

    var spninventory = document.createElement('span');
	colarray['oldinventory'] = { 
        header_title: "Old Inv",
        edit: [spninventory],
        disp: [spninventory],
        td_class: "tablerow column_click column_hover tdinv"
    };

    var spnqty = document.createElement('span');
   	var txtqty = document.createElement('input');
    txtqty.setAttribute('class','form-control txtqty');
	colarray['newinventory'] = { 
        header_title: "New Inv",
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
	var imgEdit = document.createElement('i');
	imgEdit.setAttribute("class","imgedit fa fa-pencil");
	colarray['update'] = { 
		header_title: "",
		edit: [imgUpdate],
		disp: [imgEdit],
		td_class: "tablerow column_hover tdupdate",
		headertd_class: "tdupdate"
	};

	var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");
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

	$('#tbl').hide();

	$('#date_from, #date_to').datepicker();
    $('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );
    $('#date_from, #date_to').datepicker("setDate",new Date());

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", controller : 'adjust' });


	tableHelper.detailContent.bindUpdateEvents(getRowDetailsBeforeSubmit,false,onAfterAdjustInsert);
	tableHelper.detailContent.bindAutoComplete(onAfterProductSelect);

    refreshTable();

    $('.imgedit').live('click',function(){
        var rowIndex = $(this).parent().parent().index();
        myjstbl.edit_row(rowIndex);
    });

    $('#search').click(function(){
    	refreshTable();
    });

    $('#search_string').keypress(function(e){
    	if (e.keyCode == 13) 
    	{
    		e.preventDefault();
    		refreshTable();
    	}
    });

    $('.tddelete').live('click',function(){
		global_row_index 	= $(this).parent().index();
		global_id 			= tableHelper.contentProvider.getData(global_row_index,'id');

		if (global_id != 0)
			$('#deleteAdjustRequest').modal('show');
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
					recompute_row_count(myjstbl,colarray);

					if (myjstbl.get_row_count() == 1) 
						$('#tbl').hide();

					$('#deleteAdjustRequest').modal('hide');

					build_message_box('messagebox_1','Inventory Request entry successfully deleted!','success');
				}

				flag = 0;
			}       
		});
	});

	function refreshTable()
	{
		if (flag == 1)
			return;

		flag = 1;

		var dateFrom	= $('#date_from').val();
		var dateTo		= $('#date_to').val();
		var searchString = $('#search_string').val();
		var orderBy 	= $('#order_by').val();
		var orderType 	= $('#order_type').val();

		var arr = 	{ 
						fnc 	 : 'get_adjust_express_list', 
						search_string 	: searchString,
						order_by  		: orderBy,
						order_type 		: orderType,
						date_from		: dateFrom,
						date_to 		: dateTo
					};

		$('#tbl').hide();
		$('#loadingimg').show();

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				myjstbl.clear_table();
				clear_message_box();

				if (response.rowcnt == 0) 
					build_message_box('messagebox_1','No inventory adjust found!','info');
				else
				{
					if(response.rowcnt <= 10)
						myjstbl.mypage.set_last_page(1);
					else
						myjstbl.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(myjstbl.mypage.filter_number)));

					myjstbl.insert_multiplerow_with_value(1,response.data);
				}

				for (var i = 1; i < myjstbl.get_row_count(); i++) {
					var requestStatus = tableHelper.contentProvider.getData(i,'status');
					if (requestStatus != state.Pending.name)
					{
						var updateElement = tableHelper.contentProvider.getElement(i,'update');
						var deleteElement = tableHelper.contentProvider.getElement(i,'delete');

						$(updateElement).hide();
						$(deleteElement).hide();
					}
				};

				tableHelper.contentProvider.addRow();

				$('#loadingimg').hide();
				$('#tbl').show();
				flag = 0;
			}       
		});
	}

	function getRowDetailsBeforeSubmit(element)
	{
		var rowIndex 	= $(element).parent().parent().index();
		var productId 	= tableHelper.contentProvider.getData(rowIndex,'product',1);
		var newInventory = tableHelper.contentProvider.getData(rowIndex,'newinventory');
		var oldInventory = tableHelper.contentProvider.getData(rowIndex,'oldinventory');
		var memo 		= tableHelper.contentProvider.getData(rowIndex,'memo');
		var adjustId 	= tableHelper.contentProvider.getData(rowIndex,'id');
		var status 		= $.getEnumValue(state,tableHelper.contentProvider.getData(rowIndex,'status'));
		var fnc 		= adjustId != 0 ? "update_inventory_adjust" : "insert_inventory_adjust"; 

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
            tableHelper.contentProvider.setData(rowIndex,'oldinventory',['']);
            tableHelper.contentProvider.setData(rowIndex,'newinventory',['']);
            tableHelper.contentProvider.setData(rowIndex,'memo',['']);
        }
        else
        {
            tableHelper.contentProvider.setData(rowIndex,'product',[response[1],response[0]]);
            tableHelper.contentProvider.setData(rowIndex,'material_code',[response[2]]);
            tableHelper.contentProvider.setData(rowIndex,'oldinventory',[response[3]]);
        }
	}

	function onAfterAdjustInsert(rowIndex,response)
	{
		var message = (response.status == state.Pending.value) ? 'Inventory adjust request successfully submitted!' : 'Inventory successfully adjusted!';

		build_message_box('messagebox_1',message,'success');

		tableHelper.contentProvider.setData(rowIndex,'status',[$.getEnumString(state,response.status)]);

		if (response.status != state.Pending.value) {
			var updateElement = tableHelper.contentProvider.getElement(rowIndex,'update');
			var deleteElement = tableHelper.contentProvider.getElement(rowIndex,'delete');

			$(updateElement).hide();
			$(deleteElement).hide();
		};
	}
</script>