<script type="text/javascript">
	var flag = 0;
	var global_detail_id = 0;
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

    var spnpodetailid = document.createElement('span');
	colarray['podetailid'] = { 
        header_title: "",
        edit: [spnpodetailid],
        disp: [spnpodetailid],
        td_class: "tablerow tdid tdpodetailid",
		headertd_class : "tdheader_id"
    };

    var spnnumber = document.createElement('span');
	colarray['number'] = { 
        header_title: "",
        edit: [spnnumber],
        disp: [spnnumber],
        td_class: "tablerow tdnumber"
    };

    var spnponumber = document.createElement('span');
	colarray['ponumber'] = { 
        header_title: "PO Number",
        edit: [spnponumber],
        disp: [spnponumber],
        td_class: "tablerow tdponumber"
    };

    var spnproduct = document.createElement('span');
    var spnproductid = document.createElement('span');
    var txtproduct = document.createElement('input');
    txtproduct.setAttribute('class','form-control txtproduct');
    spnproductid.setAttribute('style','display:none;');

    var disabledDescription = document.createElement('textarea');
    disabledDescription.setAttribute('class','nonStackDescription');
    disabledDescription.setAttribute('style','display:none;');
    disabledDescription.setAttribute('disabled','disabled');

    var newline = document.createElement('span');

	colarray['product'] = { 
        header_title: "Product",
        edit: [spnproduct,spnproductid,newline,disabledDescription],
        disp: [spnproduct,spnproductid,newline,disabledDescription],
        td_class: "tablerow column_click column_hover tdproduct"
    };

	var spnmaterialcode = document.createElement('span');
	colarray['code'] = { 
        header_title: "Material Code",
        edit: [spnmaterialcode],
        disp: [spnmaterialcode],
        td_class: "tablerow column_click column_hover tdcode"
    };
   	
   	var spnqty = document.createElement('span');
	colarray['qty'] = { 
        header_title: "Qty Ordered",
        edit: [spnqty],
        disp: [spnqty],
        td_class: "tablerow column_click column_hover tdqty"
    };

    var spnmemo = document.createElement('span');
	colarray['memo'] = { 
        header_title: "Remarks",
        edit: [spnmemo],
        disp: [spnmemo],
        td_class: "tablerow column_click column_hover tdmemo"
    };

    var spnqtyremaining = document.createElement('span');
	colarray['qtyremaining'] = { 
        header_title: "Qty Remaining",
        edit: [spnqtyremaining],
        disp: [spnqtyremaining],
        td_class: "tablerow column_click column_hover tdqtyremaining"
    };

    var chkreceiveall = document.createElement('input');
    var spnreceive = document.createElement('span');
	chkreceiveall.className = "chkreceiveall";
	chkreceiveall.type = "checkbox";
	colarray['receiveall'] = { 
		header_title: "",
		edit: [chkreceiveall],
		disp: [spnreceive],
		td_class: "tablerow tdreceiveall",
	};

    var spnqtyrecv = document.createElement('span');
    var txtqtyrecv = document.createElement('input');
    txtqtyrecv.setAttribute('class','form-control txtqtyrecv');
	colarray['qtyrecv'] = { 
        header_title: "Qty Received",
        edit: [txtqtyrecv],
        disp: [spnqtyrecv],
        td_class: "tablerow column_click column_hover tdqtyrecv"
    };

    var imgUpdate = document.createElement('i');
	imgUpdate.setAttribute("class","imgupdate fa fa-check");
	var imgEdit = document.createElement('i');
	imgEdit.setAttribute("class","imgedit fa fa-pencil");
	colarray['colupdate'] = { 
		header_title: "",
		edit: [imgUpdate],
		disp: [imgEdit],
		td_class: "tablerow column_hover tdupdate"
	};

    var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");
	colarray['coldelete'] = { 
		header_title: "",
		edit: [imgDelete],
		disp: [imgDelete],
		td_class: "tablerow column_hover tddelete"
	};

	/**
	 * Initialization for JS table PO Lists
	 */

	var tab_po_list = document.createElement('table');
	tab_po_list.className = "tblstyle";
	tab_po_list.id = "table_polist_id";
	tab_po_list.setAttribute("style","border-collapse:collapse;");
	tab_po_list.setAttribute("class","border-collapse:collapse;");
    
    var colarray_po_list = [];

	var spnpoid = document.createElement('span');
	colarray_po_list['id'] = { 
        header_title: "",
        edit: [spnpoid],
        disp: [spnpoid],
        td_class: "tablerow tdid",
		headertd_class : "tdheader_id"
    };

    var chkdetails = document.createElement('input');
    chkdetails.setAttribute('type','checkbox');
    chkdetails.setAttribute('class','chkdetails');
	colarray_po_list['check'] = { 
        header_title: "",
        edit: [chkdetails],
        disp: [chkdetails],
        td_class: "tablerow tddetails"
    };

    var spnponumber = document.createElement('span');
	colarray_po_list['ponumber'] = { 
        header_title: "PO Number",
        edit: [spnponumber],
        disp: [spnponumber],
        td_class: "tablerow tdponumber"
    };

    var spndate = document.createElement('span');
	colarray_po_list['date'] = { 
        header_title: "PO Date",
        edit: [spndate],
        disp: [spndate],
        td_class: "tablerow tddate"
    };

    var spntotalqty = document.createElement('span');
	colarray_po_list['totalqty'] = { 
        header_title: "Total Qty",
        edit: [spntotalqty],
        disp: [spntotalqty],
        td_class: "tablerow tdtotalqty"
    };

	var myjstbl;
	var myjstbl_po_list;

	$('.txtqtyrecv').binder('setRule','numeric');

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);

	var root_po_list = document.getElementById("tbl_po");
	myjstbl_po_list = new my_table(tab_po_list, colarray_po_list, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root_po_list.appendChild(myjstbl_po_list.tab);

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", controller : 'poreceive', isAddRow : false });

	var poListTableHelper = new TableHelper ({ tableObject : myjstbl_po_list, tableArray : colarray_po_list });

	tableHelper.detailContent.bindUpdateEvents(getRowDetailsBeforeSubmit,false,removeCheckBoxValueAfterSubmit);
	tableHelper.detailContent.bindEditEvents();
	
	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date').datepicker();
    	$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
    	$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
    	$('#date').datepicker("setDate", new Date());

		var arr = 	{ 
						fnc : 'get_purchase_receive_details'
					};
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_1',response.error,'danger');
				else
				{
					$('#reference_no').val(response.reference_number);
					$('#memo').val(response.memo);

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);
				}
				
				if (response.detail_error == '') 
				{
					myjstbl.insert_multiplerow_with_value(1,response.detail);
					checkReceivedDetails();
				};

				if (response.po_list_error == '') 
					myjstbl_po_list.insert_multiplerow_with_value(1,response.po_lists);

				tableHelper.contentProvider.recomputeTotalQuantity();
				tableHelper.contentHelper.showDescriptionFields();
			}       
		});
	}
	else
		$('input, textarea').attr('disabled','disabled');
	
	$('.chkdetails').live('click',function(){
		if (flag == 1) 
			return;

		flag = 1;

		var rowIndex = $(this).parent().parent().index();
		var self = $(this);

		if ($(self).is(':checked')) 
		{
			$(self).attr('disabled','disabled');

			var po_head_id_val = poListTableHelper.contentProvider.getData(rowIndex,'id');

			var arr = 	{ 
							fnc : 'get_po_details',
							po_head_id : po_head_id_val
						};

			$.ajax({
				type: "POST",
				dataType : 'JSON',
				data: 'data=' + JSON.stringify(arr) + token,
				success: function(response) {
					clear_message_box();

					if (response.detail_error != '') 
						build_message_box('messagebox_1',response.detail_error,'danger');
					else
					{
						myjstbl.insert_multiplerow_with_value(1,response.detail);
						tableHelper.contentProvider.recomputeTotalQuantity();
						tableHelper.contentHelper.showDescriptionFields();
						checkReceivedDetails();
					}

					$(self).removeAttr('disabled');

					flag = 0;
				}       
			});
		}
		else
		{
			var po_number = poListTableHelper.contentProvider.getData(rowIndex,'ponumber');
			for(var i = myjstbl.get_row_count() - 1; i > 0; i--)
			{
				var row_po_number = tableHelper.contentProvider.getData(i,'ponumber');
				if (po_number == row_po_number) 
					myjstbl.delete_row(i);
			};

			tableHelper.contentProvider.recomputeTotalQuantity();
			tableHelper.contentHelper.showDescriptionFields();

			flag = 0;
		}
	});
	
	$('.chkreceiveall').live('click',function(){
		var rowIndex = $(this).parent().parent().index();
		var totalQuantity = 0;

		if ($(this).is(':checked')) 
			 totalQuantity = Number(tableHelper.contentProvider.getData(rowIndex,'qty'));
		
		tableHelper.contentProvider.setData(rowIndex,'qtyrecv',[totalQuantity]);
	});

	$('.tddelete').live('click',function(){
		global_rowIndex 	= $(this).parent().index();
		global_detail_id 	= tableHelper.contentProvider.getData(global_rowIndex,'id');

		if (global_detail_id != 0) 
			$('#deletePurchaseReceiveModal').modal('show');
		else{
			myjstbl.delete_row(global_rowIndex);
			tableHelper.contentProvider.recomputeTotalQuantity();
		}
	});

	$('#delete').click(function(){
		if (flag == 1) 
			return;

		flag = 1;

		var rowIndex 		= global_rowIndex;
		var detail_id_val 	= global_detail_id;

		var arr = 	{ 
						fnc 	 	: 'delete_purchase_receive_detail', 
						detail_id 	: detail_id_val
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
					myjstbl.delete_row(rowIndex);
					tableHelper.contentProvider.recomputeTotalQuantity();
					tableHelper.contentProvider.recomputeRowNumber();
					$('#deletePurchaseReceiveModal').modal('hide');
				}

				flag = 0;
			}       
		});
	});

	$('#save').click(function(){
        if (flag == 1)
            return;

        if (myjstbl.get_row_count() - 1 == 0) 
        {
            alert('Please receive at least one product!');
            return;
        }

        for (var i = 1; i < myjstbl.get_row_count() - 1; i++) 
        {
            var updateImage = tableHelper.contentProvider.getElement(i,'colupdate');
            if ($(updateImage).hasClass('imgupdate')) 
            {
                alert('Please finalize all rows!');
                return;
            }
        };

        var date_val	= $('#date').val();
		var memo_val 	= $('#memo').val();

		var arr = 	{ 
						fnc 	 	: 'save_purchase_receive_head', 
						entry_date 	: date_val,
						memo 		: memo_val
					};

        flag = 1;

        $.ajax({
            type: "POST",
            dataType : 'JSON',
            data: 'data=' + JSON.stringify(arr) + token,
            success: function(response) {
                clear_message_box();

                if (response.error != '') 
                    build_message_box('messagebox_1',response.error,'danger');
                else
                    window.location = "<?= base_url() ?>poreceive/list";

                flag = 0;
            }       
        });
    });

	function getRowDetailsBeforeSubmit(element)
	{
		var rowIndex = $(element).parent().parent().index();

		var receiveDetailId 	= tableHelper.contentProvider.getData(rowIndex,'id');
		var purchaseDetailId 	= tableHelper.contentProvider.getData(rowIndex,'podetailid');
		var receivedQty 		= tableHelper.contentProvider.getData(rowIndex,'qtyrecv');
		var productId 			= tableHelper.contentProvider.getData(rowIndex,'product',1);
		var actionFunction 		= receiveDetailId == 0 ? 'insert_receive_detail' : 'update_receive_detail';

		var errorList = $.dataValidation(	[{
	                                            value : receivedQty,
	                                            fieldName : 'Quantity',
	                                            required : true,
	                                            rules : 'numeric',
	                                            isNotEqual : { value : 0, errorMessage : 'Quantity must be greater than 0!'}
                                        	}]);

		if (errorList.length > 0) {
            clear_message_box();
            build_message_box('messagebox_1',build_error_message(errorList),'danger');
            return false;
        };

		var arr = 	{ 
						fnc : actionFunction,
						detail_id : receiveDetailId,
						purchase_detail_id : purchaseDetailId,
						quantity : receivedQty,
						product_id : productId
					};

		return arr;
	}

	function removeCheckBoxValueAfterSubmit(rowIndex, response)
	{
		tableHelper.contentProvider.setData(rowIndex,'receiveall',['']);
	}

	function checkReceivedDetails()
	{
		for (var i = 1; i < myjstbl.get_row_count(); i++) 
		{
			var rowReceiveDetailId = tableHelper.contentProvider.getData(i,'id');

			if (rowReceiveDetailId == 0) 
				myjstbl.edit_row(i);
		};
	}
</script>