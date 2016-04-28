<script type="text/javascript">

	var processingFlag = false;
	var token = '<?= $token ?>';
	var isUsed = '';

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

	var spnsalesid = document.createElement('span');
	colarray['salesid'] = { 
		header_title: "",
		edit: [spnsalesid],
		disp: [spnsalesid],
		td_class: "tablerow hide-elem tdsalesid",
		headertd_class : "hide-elem tdsalesid"
	};

	var spnreservationreference = document.createElement('span');
	colarray['reservationreference'] = { 
		header_title: "",
		edit: [spnreservationreference],
		disp: [spnreservationreference],
		td_class: "tablerow hide-elem tdreservationref",
		headertd_class : "hide-elem tdreservationref"
	};

	var chkchecktransfer = document.createElement('input');
	chkchecktransfer.setAttribute('type','checkbox');
	chkchecktransfer.setAttribute('class','chktransfer');
	var chkchecktransferdisabled = document.createElement('input');
	chkchecktransferdisabled.setAttribute('type','checkbox');
	chkchecktransferdisabled.setAttribute('disabled','disabled');
	chkchecktransferdisabled.setAttribute('class','chktransfer');
	colarray['istransfer'] = { 
		header_title: "FT",
		edit: [chkchecktransfer],
		disp: [chkchecktransferdisabled],
		td_class: "tablerow tdistransfer",
		headertd_class : "tdistransfer"
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

	var description = document.createElement('textarea');
	description.setAttribute('class','nonStackDescription form-control desc-margin');
	description.setAttribute('placeholder', 'Description')
	description.setAttribute('style','display:none;');

	var disabledDescription = document.createElement('textarea');
	disabledDescription.setAttribute('class','nonStackDescription form-control');
	disabledDescription.setAttribute('style','display:none;');
	disabledDescription.setAttribute('disabled','disabled');

	var productType = document.createElement('span');
	productType.setAttribute('style','display:none;');

	var newline = document.createElement('span');

	colarray['product'] = { 
		header_title: "Product",
		edit: [txtproduct,spnproductid,productType,newline,description,spnproductid],
		disp: [spnproduct,spnproductid,productType,newline,disabledDescription,spnproductid],
		td_class: "tablerow column_click column_hover tdproduct"
	};

	var spnmaterialcode = document.createElement('span');
	colarray['code'] = { 
		header_title: "Material Code",
		edit: [spnmaterialcode],
		disp: [spnmaterialcode],
		td_class: "tablerow column_click column_hover tdcode"
	};
	
	var spnuom = document.createElement('span');
	colarray['uom'] = { 
		header_title: "UOM",
		edit: [spnuom],
		disp: [spnuom],
		td_class: "tablerow column_click column_hover tduom"
	};
	
	var spnqty = document.createElement('span');
	var txtqty = document.createElement('input');
	txtqty.setAttribute('class','form-control txtqty');
	colarray['qty'] = { 
		header_title: "Qty",
		edit: [txtqty],
		disp: [spnqty],
		td_class: "tablerow column_click column_hover tdqty"
	};

	var spnreceive = document.createElement('span');
	colarray['receive'] = { 
		header_title: "Received Qty",
		edit: [spnreceive],
		disp: [spnreceive],
		td_class: "tablerow column_click column_hover tdreceive",
		headertd_class : "tdreceive"
	};

	var spncustomer = document.createElement('span');
	var txtcustomer = document.createElement('input');
	txtcustomer.setAttribute('class','form-control txtcustomer');
	colarray['customer'] = { 
		header_title: "Customer",
		edit: [txtcustomer],
		disp: [spncustomer],
		td_class: "tablerow column_click column_hover tdcustomer",
		headertd_class : "tdcustomer"
	};

	var spninvoice = document.createElement('span');
	var txtinvoice = document.createElement('input');
	txtinvoice.setAttribute('class','form-control txtinvoice');
	colarray['invoice'] = { 
		header_title: "Invoice",
		edit: [txtinvoice],
		disp: [spninvoice],
		td_class: "tablerow column_click column_hover tdinvoice"
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
	
	var imgUpdate = document.createElement('i');
	imgUpdate.setAttribute("class","imgupdate fa fa-check");
	var imgEdit = document.createElement('i');
	imgEdit.setAttribute("class","imgedit fa fa-pencil");
	colarray['colupdate'] = { 
		header_title: "",
		edit: [imgUpdate],
		disp: [imgEdit],
		td_class: "tablerow column_hover tdupdate",
		headertd_class: "tdupdate"
	};

	var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");
	colarray['coldelete'] = { 
		header_title: "",
		edit: [imgDelete],
		disp: [imgDelete],
		td_class: "tablerow column_hover tddelete",
		headertd_class: "tddelete"
	};


	/**
	 * Initialization for JS table Reseravation Lists
	 */

	var tab_sales_list = document.createElement('table');
	tab_sales_list.className = "tblstyle";
	tab_sales_list.id = "table_reservationlist_id";
	tab_sales_list.setAttribute("style","border-collapse:collapse;");
	tab_sales_list.setAttribute("class","border-collapse:collapse;");
	
	var colarray_sales_list = [];

	var spnsalesid = document.createElement('span');
	colarray_sales_list['id'] = { 
		header_title: "",
		edit: [spnsalesid],
		disp: [spnsalesid],
		td_class: "tablerow tdid",
		headertd_class : "tdheader_id"
	};

	var chkdetails = document.createElement('input');
	chkdetails.setAttribute('type','checkbox');
	chkdetails.setAttribute('class','chkdetails');
	colarray_sales_list['check'] = { 
		header_title: "",
		edit: [chkdetails],
		disp: [chkdetails],
		td_class: "tablerow tddetails"
	};

	var spnreferencenumber = document.createElement('span');
	colarray_sales_list['reservation_number'] = { 
		header_title: "Ref #",
		edit: [spnreferencenumber],
		disp: [spnreferencenumber],
		td_class: "tablerow tdrefnumber"
	};

	var spndate = document.createElement('span');
	colarray_sales_list['date'] = { 
		header_title: "Date",
		edit: [spndate],
		disp: [spndate],
		td_class: "tablerow tddate"
	};

	var spnsalesman = document.createElement('span');
	colarray_sales_list['salesman'] = { 
		header_title: "Salesman",
		edit: [spnsalesman],
		disp: [spnsalesman],
		td_class: "tablerow tdsalesman"
	};

	var spntotalqty = document.createElement('span');
	colarray_sales_list['totalqty'] = { 
		header_title: "Qty",
		edit: [spntotalqty],
		disp: [spntotalqty],
		td_class: "tablerow tdtotalqty"
	};

	var myjstbl;
	var myjstbl_sales_list;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);

	var root_sales_list = document.getElementById("tbl_sales");
	myjstbl_sales_list = new my_table(tab_sales_list, colarray_sales_list, {	
																				ispaging : false, 
																				tdhighlight_when_hover : "tablerow",
																				iscursorchange_when_hover : true,
																				isdeleteicon_when_hover : false
																			}
										);

	root_sales_list.appendChild(myjstbl_sales_list.tab);

	$('#tbl').hide();

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", controller : 'delivery' });

	var salesListTableHelper = new TableHelper ({ tableObject : myjstbl_sales_list, tableArray : colarray_sales_list }, { token : notificationToken });

	tableHelper.detailContent.bindAllEvents({ 	
												saveEventsBeforeCallback : getHeadDetailsBeforeSubmit,
												updateEventsBeforeCallback : getRowDetailsBeforeSubmit,
												deleteEventsAfterCallback : checkImportedSales,
												addInventoryChecker : true
											});

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date').datepicker();
		$('#date').datepicker("option","dateFormat", "mm-dd-yy");
		$('#date').datepicker("setDate", new Date());

		var arr = { fnc : 'get_stock_delivery_details' };
		
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
					$('#delivery_type').val(response.delivery_type);
					$('#to_branch').val(response.to_branchid);

					if (response.to_branchid != response.own_branch)
						$("#to_branch option[value=" + response.own_branch + "]").remove();

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);	

					if (response.delivery_type == DeliveryType.Sales)  
						$('#delivery_to_list').hide();

					if (!response.is_saved)
					{
						isUsed = ", .tdreceive";
						$('#print').hide();
					}
				}
				
				if (response.detail_error == '') 
					myjstbl.insert_multiplerow_with_value(1,response.detail);

				if (response.sales_list_error == '') 
					myjstbl_sales_list.insert_multiplerow_with_value(1, response.sales_lists);

				if (!response.is_editable || (Boolean(<?= $permission_list['allow_to_edit']?>) == false && response.is_saved == true) || (Boolean(<?= $permission_list['allow_to_add']?>) == false && response.is_saved == false))
				{
					$('input, textarea, select').not('#print').attr('disabled','disabled');
					$('.tdupdate, .tddelete, #save').hide();

					if (response.is_saved && response.is_incomplete && (response.own_branch == response.transaction_branch) && (Boolean(<?= $permission_list['allow_to_edit_incomplete']?>) == true))
					{
						$('input, textarea, select').not('#print').removeAttr('disabled');
						$('.tdupdate, #save').show();
					}
				}
				else
					tableHelper.contentProvider.addRow();

				toggleDeliveryType(false);
					
				tableHelper.contentProvider.recomputeTotalQuantity();
				tableHelper.contentHelper.checkProductInfo();

				$('#tbl').show();
			}       
		});
	}
	else
		$('input, textarea').attr('disabled','disabled');

	$('#delivery_type').live('change', function(){	
		toggleDeliveryType();
	});

	$('.chkdetails').live('click',function(){

		if (processingFlag) 
			return;

		processingFlag = true;

		var rowIndex = $(this).parent().parent().index();
		var self = $(this);

		if ($(self).is(':checked')) 
		{
			$(self).attr('disabled','disabled');

			var sales_head_id = salesListTableHelper.contentProvider.getData(rowIndex, 'id');

			var arr = 	{ 
							fnc : 'get_sales_details',
							sales_head_id : sales_head_id
						};

			$.ajax({
				type: "POST",
				dataType : 'JSON',
				data: 'data=' + JSON.stringify(arr) + notificationToken,
				success: function(response) {
					clear_message_box();

					if (response.error != '') 
						build_message_box('messagebox_1', response.error, 'danger');
					else
					{
						var nextRow = myjstbl.get_row_count() - 1;

						myjstbl.insert_multiplerow_with_value(nextRow, response.detail);
						tableHelper.contentProvider.recomputeTotalQuantity();
						tableHelper.contentProvider.recomputeRowNumber();
						tableHelper.contentHelper.checkProductInfo();
						checkDeliveryDetails();
					}

					$(self).removeAttr('disabled');

					processingFlag = false;
				}       
			});
		}
		else
		{
			var importReferenceNumber = salesListTableHelper.contentProvider.getData(rowIndex, 'reservation_number');

			for(var i = myjstbl.get_row_count() - 1; i > 0; i--)
			{
				var tableDetailRowSalesNumber = tableHelper.contentProvider.getData(i, 'reservationreference');

				if (importReferenceNumber == tableDetailRowSalesNumber) 
					myjstbl.delete_row(i);
			};

			tableHelper.contentProvider.recomputeTotalQuantity();
			tableHelper.contentProvider.recomputeRowNumber();

			processingFlag = false;
		}
	});

	$('.imgdel').live('click', function(){

		var rowIndex = $(this).parent().parent().index();
		var salesInvoice = tableHelper.contentProvider.getData(rowIndex, 'reservationreference');
		var deliveryDetailId = Number(tableHelper.contentProvider.getData(rowIndex, 'id'));

		if (salesInvoice !== '' && deliveryDetailId === 0)
		{
			myjstbl.delete_row(rowIndex);
			checkDeliveryDetails();
			tableHelper.contentProvider.recomputeRowNumber();
		}

		if (deliveryDetailId === 0) 
			tableHelper.contentProvider.setData(rowIndex, 'invoice', ['']);

	});

	$('.print').click(function(){
		goToPrintOut($(this));
	});

	function goToPrintOut(element)
	{
		var printType = "both";

		if (element) 
			printType = $(element).attr('id');

		var arr = 	{ 
						fnc : 'set_session_delivery',
						print_type : printType 
					}

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				if(response.error != '') 
					alert(response.error);
				else
				{
					$('#print').show();
					window.open('<?= base_url() ?>printout/delivery/Delivery');
				}
			}
		});
	}

	function toggleDeliveryType(isUpdateType)
	{
		isUpdateType = typeof isUpdateType !== 'undefined' ? isUpdateType : true;

		var delivery_type = $('#delivery_type').val();

		if (delivery_type == DeliveryType.Sales) 
		{
			$('#delivery_to_list, .tdistransfer').hide();
			$('#tbl_sales, .tdcustomer').show();
			toggleHideColumns({ isTransfer : true, isSales : false });
		}
		else if (delivery_type == DeliveryType.Transfer) 
		{
			$('#delivery_to_list').show();
			$('#tbl_sales, .tdistransfer').hide();
			
			if (isUpdateType)
			{
				alert("Products imported from Sales Invoice will be removed upon changing to transfer.");
				removeImportedSales();
			}

			toggleHideColumns({ isTransfer : false, isSales : true });
		}
		else if (delivery_type == DeliveryType.Both)
		{
			if (isUpdateType)
			{
				for (var i = 1; i < myjstbl.get_row_count(); i++) 
				{
					myjstbl.edit_row(i);
					tableHelper.contentHelper.descriptionAccessibilty(i);
				}

				$('.chktransfer').removeAttr('checked');
			}

			$('#delivery_to_list, #tbl_sales, .tdcustomer, .tdistransfer').show();
			toggleHideColumns();
		}

		if (isUpdateType) 
		{
			var arr = 	{ 
							fnc : 'update_delivery_type',
							delivery_type : delivery_type
						};

			$.ajax({
				type: "POST",
				dataType : 'JSON',
				data: 'data=' + JSON.stringify(arr) + notificationToken,
				success: function(response) 
				{
					clear_message_box();

					if (response.error != '')
					{
						build_message_box('messagebox_1', response.error, 'danger');
						window.reload();
					}
				}       
			});	
		}
	}

	function getHeadDetailsBeforeSubmit()
	{
		var date_val	= moment($('#date').val(),'MM-DD-YYYY').format('YYYY-MM-DD');
		var memo_val 	= $('#memo').val();
		var type_val 	= $('#delivery_type').val();
		var to_branch 	= type_val == DeliveryType.Sales ? 0 : $('#to_branch').val();

		for (var i = 1; i < myjstbl.get_row_count() - 1; i++) 
		{
			var isTransfer 	= Number(tableHelper.contentProvider.getData(i, 'istransfer'));
			var customer 	= tableHelper.contentProvider.getData(i, 'customer');

			if ((type_val == DeliveryType.Both && isTransfer == TransferState.ForSales && customer == '') || (type_val == DeliveryType.Sales && customer == '')) 
			{
				alert('Please add a customer name!');
				return false;
			}
		};

		var arr = 	{ 
						fnc 	 	: 'save_stock_delivery_head', 
						entry_date 	: date_val,
						memo 		: memo_val,
						type 		: type_val,
						to_branch 	: to_branch
					};

		return arr;
	}

	function getRowDetailsBeforeSubmit(element)
	{
		var type_val 		= $('#delivery_type').val();
		var rowIndex 		= $(element).parent().parent().index();
		var productId 		= tableHelper.contentProvider.getData(rowIndex,'product',1);
		var qty 			= tableHelper.contentProvider.getData(rowIndex,'qty');
		var memo 			= $.sanitize(tableHelper.contentProvider.getData(rowIndex, 'memo'));
		var customer 		= $.sanitize(tableHelper.contentProvider.getData(rowIndex, 'customer'));
		var invoice 		= $.sanitize(tableHelper.contentProvider.getData(rowIndex,'invoice'));
		var rowId 			= tableHelper.contentProvider.getData(rowIndex,'id');
		var isTransfer 		= Number(tableHelper.contentProvider.getData(rowIndex,'istransfer'));
		var description 	= $.sanitize(tableHelper.contentProvider.getData(rowIndex,'product',4));
		var sales_detail_id = tableHelper.contentProvider.getData(rowIndex,'salesid');
		var actionFunction 	= rowId != 0 ? "update_stock_delivery_detail" : "insert_stock_delivery_detail";

		if ((type_val == DeliveryType.Both && isTransfer == TransferState.ForSales && customer == '') || (type_val == DeliveryType.Sales && customer == '')) 
		{
			alert('Please add a customer name in remarks!');
			return false;
		}

		var arr = 	{ 
						fnc 	 	: actionFunction, 
						product_id 	: productId,
						qty     	: qty,
						memo 		: memo,
						detail_id 	: rowId,
						istransfer 	: isTransfer,
						description : description,
						invoice 	: invoice,
						customer_name 	: customer,
						sales_detail_id : sales_detail_id
					};

		return arr;
	}

	function checkDeliveryDetails()
	{
		for (var i = 1; i < myjstbl.get_row_count(); i++) 
		{
			var deliveryDetailId = tableHelper.contentProvider.getData(i, 'id');
			var reservationReference = tableHelper.contentProvider.getData(i, 'reservationreference');
			var salesDetailId = tableHelper.contentProvider.getData(i, 'salesid');

			if (deliveryDetailId == 0) 
			{
				myjstbl.edit_row(i);
				tableHelper.contentHelper.descriptionAccessibilty(i);
			}

			if (reservationReference != '' && salesDetailId != '') 
			{
				var checkboxElement = tableHelper.contentProvider.getElement(i, 'istransfer');
				$(checkboxElement).attr('disabled', 'disabled');
			}
		};
	}

	function removeImportedSales()
	{
		$('.chkdetails').removeAttr('checked');

		var arr = 	{ 
						fnc : 'remove_imported_sales'
					};

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + notificationToken,
			success: function(response) 
			{
				if (response.error !== '') 
				{
					alert(response.error);
					window.reload();
				}
				else
				{
					for(var i = myjstbl.get_row_count() - 1; i > 0; i--) 
					{
						var reservationReference = tableHelper.contentProvider.getData(i, 'reservationreference');

						if (reservationReference != '')
							myjstbl.delete_row(i);
					};

					tableHelper.contentProvider.recomputeRowNumber();
					tableHelper.contentProvider.recomputeTotalQuantity();
				}
			}
		});
	}

	function checkImportedSales()
	{
		for (var i = 1; i < myjstbl_sales_list.get_row_count(); i++) 
		{
			var salesHeadInvoice = salesListTableHelper.contentProvider.getData(i, 'reservation_number');
			var salesHeadCheckbox = salesListTableHelper.contentProvider.getElement(i, 'check');
			var instance = 0;

			for (var x = 1; x < myjstbl.length; x++) 
			{
				var rowSalesInvoice = tableHelper.contentProvider.getData(i, 'reservationreference');

				if (salesHeadInvoice == rowSalesInvoice) 
				{
					instance++;
					break;
				}
			}

			if (instance == 0)
				$(salesHeadCheckbox).removeAttr('checked');
		};
	}

	function toggleHideColumns(columnList)
	{
		$('#dynamic-css').html('');

		var css = "<style>";
		css += ".tdsample" + isUsed;

		if (typeof columnList !== 'undefined') 
		{
			if (columnList.isTransfer) 
				css += ", .tdistransfer";

			if (columnList.isSales) 
				css += ", .tdcustomer";
		}

		css += " { display:none; } </style>";

		$('#dynamic-css').html(css);
	}
</script>