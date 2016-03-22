<script type="text/javascript">
	var CustomerType = {
		Regular : 1,
		Walkin : 2
	};

	var TransactionState = {
		Saved : 1,
		Unsaved : 0
	}

	var processingFlag = false;
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

	var spnsales_detail_id = document.createElement('span');
	colarray['salesid'] = { 
		header_title: "",
		edit: [spnsales_detail_id],
		disp: [spnsales_detail_id],
		td_class: "tablerow hide-elem tdsalesid",
		headertd_class : "hide-elem tdsalesid"
	};

	var spnsalesreference = document.createElement('span');
	colarray['salesreference'] = { 
		header_title: "",
		edit: [spnsalesreference],
		disp: [spnsalesreference],
		td_class: "tablerow hide-elem tdsalesref",
		headertd_class : "hide-elem tdsalesref"
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
	description.setAttribute('placeholder','Description');
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
	colarray['released'] = { 
		header_title: "Released Qty",
		edit: [spnreceive],
		disp: [spnreceive],
		td_class: "tablerow column_click column_hover tdreleased",
		headertd_class : "tdreleased"
	};

	var spnqtyremaining = document.createElement('span');
	colarray['remaining'] = { 
		header_title: "Remaining Qty",
		edit: [spnqtyremaining],
		disp: [spnqtyremaining],
		td_class: "tablerow column_click column_hover tdremaining",
		headertd_class : "tdremaining"
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
	tab_sales_list.id = "table_saleslist_id";
	tab_sales_list.setAttribute("style","border-collapse:collapse;");
	tab_sales_list.setAttribute("class","border-collapse:collapse;");
	
	var colarray_sales_list = [];

	var spnreservationid = document.createElement('span');
	colarray_sales_list['id'] = { 
		header_title: "",
		edit: [spnreservationid],
		disp: [spnreservationid],
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
	colarray_sales_list['reference_number'] = { 
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

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray},
										{ baseURL : "<?= base_url() ?>", 
										  controller : 'assort',
										  recentNameElementId : 'customer' } );

	var root_sales_list = document.getElementById("tbl_sales");
	myjstbl_sales_list = new my_table(tab_sales_list, colarray_sales_list, {	
																				ispaging : false, 
																				tdhighlight_when_hover : "tablerow",
																				iscursorchange_when_hover : true,
																				isdeleteicon_when_hover : false
																			}
											);

	root_sales_list.appendChild(myjstbl_sales_list.tab);

	var salesListTableHelper = new TableHelper ({ tableObject : myjstbl_sales_list, tableArray : colarray_sales_list }, { token : notificationToken });

	tableHelper.detailContent.bindAllEvents({ 
												saveEventsBeforeCallback : getHeadDetailsBeforeSubmit,
												updateEventsBeforeCallback : getRowDetailsBeforeSubmit,
												deleteEventsAfterCallback : checkRemainingSalesDetail,
												addInventoryChecker : true
											});

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$.toggleOption('customer-type', [
											{
												optionValue : 1,
												elementId : 'customer_chzn'
											},
											{
												optionValue : 2,
												elementId : 'walkin-customer'
											}
										]);

		$('#customer').chosen();
		$('#date').datepicker();
		$('#date').datepicker("option","dateFormat", "mm-dd-yy");
		$('#date').datepicker("setDate", new Date());

		var arr = 	{ 
						fnc : 'get_assortment_details'
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
					$('#customer').val(response.customer_id).trigger('liszt:updated');
					$('#walkin-customer').val(response.customer_name);

					if (response.customer_id == 0)
					{
						$('#customer_chzn').hide();
						$('#walkin-customer').show();
						$('input[name=customer-type][value=' + CustomerType.Walkin + ']').attr('checked', 'checked');
					}
					else
					{
						$('#customer_chzn').show();
						$('#walkin-customer').hide();
						$('input[name=customer-type][value=' + CustomerType.Regular + ']').attr('checked', 'checked');
					}

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);	

					if (response.is_saved == TransactionState.Unsaved)
						hideQuantityReleasedColumn();
				}
				
				if (response.detail_error == '') 
					myjstbl.insert_multiplerow_with_value(1,response.detail);

				if (response.sales_list_error == '') 
					myjstbl_sales_list.insert_multiplerow_with_value(1, response.sales_lists);

				if (!response.is_editable || (Boolean(<?= $permission_list['allow_to_edit']?>) == false && response.is_saved == true) || (Boolean(<?= $permission_list['allow_to_add']?>) == false && response.is_saved == false))
				{
					$('input, textarea, select').not('#print').attr('disabled','disabled');
					$('.tdupdate, .tddelete, #save, #transfer').hide();

					if (response.is_saved && response.is_incomplete && (response.own_branch == response.transaction_branch) && (Boolean(<?= $permission_list['allow_to_edit_incomplete']?>) == true))
					{
						$('input, textarea, select').not('#print').removeAttr('disabled');
						$('.tdupdate, .tddelete, #save').show();
					}
				}	
				else
					tableHelper.contentProvider.addRow();

				if (!response.is_saved)
					$('#print').hide();

				tableHelper.contentProvider.recomputeTotalQuantity();
				tableHelper.contentHelper.checkProductInfo();

			}       
		});
	}
	else
		$('input, textarea').attr('disabled','disabled');

	$("input[name='customer-type'], #customer").change(function(){
		
		var value = $(this).val();

		if (value == CustomerType.Walkin)
			$('#customer').val(0).trigger('liszt:updated');
		
		removeImportedSales();
	});

	$('.imgdel').live('click', function(){

		var rowIndex = $(this).parent().parent().index();
		var salesInvoice = tableHelper.contentProvider.getData(rowIndex, 'salesreference');
		var salesDetailId = Number(tableHelper.contentProvider.getData(rowIndex, 'id'));

		if (salesInvoice !== '' && salesDetailId === 0)
		{
			myjstbl.delete_row(rowIndex);
			checkRemainingSalesDetail();
			tableHelper.contentProvider.recomputeRowNumber();
		}
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
						checkImportedSalesMode();
					}

					$(self).removeAttr('disabled');

					processingFlag = false;
				}       
			});
		}
		else
		{
			var importReferenceNumber = salesListTableHelper.contentProvider.getData(rowIndex, 'reference_number');

			for(var i = myjstbl.get_row_count() - 1; i > 0; i--)
			{
				var tableDetailRowSalesNumber = tableHelper.contentProvider.getData(i, 'salesreference');

				if (importReferenceNumber == tableDetailRowSalesNumber) 
					myjstbl.delete_row(i);
			};

			tableHelper.contentProvider.recomputeTotalQuantity();
			tableHelper.contentProvider.recomputeRowNumber();

			processingFlag = false;
		}
	});

	$('#print').click(function(){
		goToPrintOut();
	});

	function goToPrintOut()
	{
		var arr = { fnc : 'set_session' }

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
					window.open('<?= base_url() ?>printout/assortment/Release');
				}
			}
		});
	}

	function getHeadDetailsBeforeSubmit()
	{
		var date_val	= moment($('#date').val(),'MM-DD-YYYY').format('YYYY-MM-DD');
		var memo_val 	= $('#memo').val();
		var customer_type 	= $("input[name='customer-type']:checked").val();
		var customer_id_val = customer_type == CustomerType.Regular ? $('#customer').val() : 0;
		var walkin_customer_name_val = customer_type == CustomerType.Regular ? '' : $('#walkin-customer').val();

		if (
				(customer_type == CustomerType.Regular && customer_id_val == 0) ||
				(customer_type == CustomerType.Walkin && walkin_customer_name_val == '')
			) 
		{
			alert('Customer should not be empty!');
			return false;
		};
		
		var arr = 	{ 
						fnc 	 	: 'save_assortment_head', 
						entry_date 	: date_val,
						memo 		: memo_val,
						customer_name : walkin_customer_name_val,
						customer_id : customer_id_val
					};

		return arr;
	}

	function getRowDetailsBeforeSubmit(element)
	{
		var rowIndex 		= $(element).parent().parent().index();
		var productId 		= tableHelper.contentProvider.getData(rowIndex, 'product', 1);
		var qty 			= tableHelper.contentProvider.getData(rowIndex, 'qty').replace(/\,/gi,"");
		var memo 			= $.sanitize(tableHelper.contentProvider.getData(rowIndex, 'memo'));
		var assortment_detail_id = tableHelper.contentProvider.getData(rowIndex, 'id');
		var sales_detail_id = tableHelper.contentProvider.getData(rowIndex, 'salesid');
		var description 	= (tableHelper.contentProvider.getData(rowIndex, 'product', 4));
		var actionFunction 	= assortment_detail_id != 0 ? "update_assortment_detail" : "insert_assortment_detail";

		var errorList = $.dataValidation([ 
											{   
												value : productId,
												fieldName : 'Product',
												required : true,
												isNotEqual : { value : 0, errorMessage : 'Please select a valid product!'}
											},
											{
												value : qty,
												fieldName : 'Quantity',
												required : true,
												rules : 'numeric'
											}
										]);

		if (errorList.length > 0) 
		{
			clear_message_box();
			build_message_box('messagebox_1', build_error_message(errorList), 'danger');
			return false;
		};

		var arr = 	{ 
						fnc 	 	: actionFunction, 
						product_id 	: productId,
						qty     	: qty,
						memo 		: memo,
						detail_id 	: assortment_detail_id,
						sales_detail_id : sales_detail_id,
						description : description
					};

		return arr;
	}

	function removeImportedSales()
	{
		alert("Products imported from Sales Invoice will be removed upon changing customer.");
		
		var customer_id 	= $('#customer').val();
		var customer_type 	= $("input[name='customer-type']:checked").val();

		var arr = 	{ 
						fnc : 'remove_imported_sales',
						customer_id : customer_id
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
						var salesReference = tableHelper.contentProvider.getData(i, 'salesreference');

						if (salesReference != '')
							myjstbl.delete_row(i);
					};
					
					myjstbl_sales_list.clear_table();
					tableHelper.contentProvider.recomputeRowNumber();

					if (customer_id == 0 && customer_type == CustomerType.Regular) 
						return false;

					if (response.sales.sales_list_error == '') 
						myjstbl_sales_list.insert_multiplerow_with_value(1, response.sales.sales_lists);
				}
			}
		});
	}

	function checkImportedSalesMode()
	{
		for (var i = 1; i < myjstbl.get_row_count(); i++) 
		{
			var assortmentDetailId = tableHelper.contentProvider.getData(i, 'id');

			if (assortmentDetailId == 0) 
			{
				myjstbl.edit_row(i);
				tableHelper.contentHelper.descriptionAccessibilty(i);
			}
		};
	}

	function checkRemainingSalesDetail()
	{
		$(".chkdetails:checked").each(function(index, element){
			var detailExist = false;
			var salesRowIndex = $(element).parent().parent().index();

			var importSalesNumber = salesListTableHelper.contentProvider.getData(salesRowIndex, 'reference_number');

			for (var i = 1; i < myjstbl.get_row_count(); i++) 
			{
				var tableDetailRowsSalesNumber = tableHelper.contentProvider.getData(i, 'salesreference');

				if (tableDetailRowsSalesNumber == importSalesNumber)
				{
					detailExist = true;
					break;
				}
			};

			if (!detailExist) 
				$(element).removeAttr('checked');
		});
	}

	function hideQuantityReleasedColumn()
	{
		$('#dynamic-css').html('');
		$('#dynamic-css').html("<style> .tdreleased, .tdremaining{ display:none; } </style>");
	}
</script>