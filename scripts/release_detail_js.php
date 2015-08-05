<script type="text/javascript">
	var flag = 0;
	var globalDetailId = 0;
	var globalRowIndex = 0;
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
	disabledDescription.setAttribute('class','nonStackDescription form-control');
	disabledDescription.setAttribute('style','display:none;');
	disabledDescription.setAttribute('disabled','disabled');

	var productType = document.createElement('span');
	productType.setAttribute('style','display:none;');

	var newline = document.createElement('span');

	colarray['product'] = { 
		header_title: "Product",
		edit: [spnproduct,spnproductid,productType,newline,disabledDescription],
		disp: [spnproduct,spnproductid,productType,newline,disabledDescription],
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
	var spnqtyremainingHidden = document.createElement('span');
	spnqtyremainingHidden.setAttribute('style', 'display:none');
	colarray['qtyremaining'] = { 
		header_title: "Qty Remaining",
		edit: [spnqtyremaining, spnqtyremainingHidden],
		disp: [spnqtyremaining, spnqtyremainingHidden],
		td_class: "tablerow column_click column_hover tdqtyremaining"
	};

	var chkreceiveall = document.createElement('input');
	var spnreceive = document.createElement('span');
	chkreceiveall.className = "chkreceiveall";
	chkreceiveall.type = "checkbox";

	var chkReceiveAllDis = document.createElement('input');
	chkReceiveAllDis.setAttribute('type','checkbox');
	chkReceiveAllDis.setAttribute('disabled','disabled');

	var chktransferall = document.createElement('input');
	chktransferall.setAttribute('type','checkbox');
	chktransferall.setAttribute('id','chkcheckall');

	colarray['receiveall'] = { 
		header_title: chktransferall,
		edit: [chkreceiveall],
		disp: [chkReceiveAllDis],
		td_class: "tablerow tdreceiveall",
		header_elem: chktransferall
	};

	var spnqtyrecv = document.createElement('span');
	var spnqtyrecvHidden = document.createElement('span');
	spnqtyrecvHidden.setAttribute('style','display:none;');

	var txtqtyrecv = document.createElement('input');
	txtqtyrecv.setAttribute('class','form-control txtqtyrecv');
	colarray['qtyrecv'] = { 
		header_title: "Qty Released",
		edit: [txtqtyrecv,spnqtyrecvHidden],
		disp: [spnqtyrecv,spnqtyrecvHidden],
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
		td_class: "tablerow column_hover tdupdate",
		headertd_class : "tdupdate"
	};

	var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");
	colarray['coldelete'] = { 
		header_title: "",
		edit: [imgDelete],
		disp: [imgDelete],
		td_class: "tablerow column_hover tddelete",
		headertd_class : "tddelete"
	};

	/**
	 * Initialization for JS table Release Order Lists
	 */

	var tab_po_list = document.createElement('table');
	tab_po_list.className = "tblstyle";
	tab_po_list.id = "table_release_order_list_id";
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
	colarray_po_list['panumber'] = { 
		header_title: "PA Number",
		edit: [spnponumber],
		disp: [spnponumber],
		td_class: "tablerow tdpanumber"
	};

	var spnponumber = document.createElement('span');
	colarray_po_list['customer'] = { 
		header_title: "Customer",
		edit: [spnponumber],
		disp: [spnponumber],
		td_class: "tablerow tdcustomer"
	};

	var spndate = document.createElement('span');
	colarray_po_list['date'] = { 
		header_title: "PA Date",
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
	var myjstbl_release_order_list;

	$('.txtqtyrecv').binder('setRule','numeric');

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);

	var root_po_list = document.getElementById("tbl_release_order");
	myjstbl_release_order_list = new my_table(tab_po_list, colarray_po_list, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root_po_list.appendChild(myjstbl_release_order_list.tab);

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", controller : 'release', isAddRow : false });

	var poListTableHelper = new TableHelper ({ tableObject : myjstbl_release_order_list, tableArray : colarray_po_list });

	tableHelper.detailContent.bindUpdateEvents(getRowDetailsBeforeSubmit);
	tableHelper.detailContent.bindEditEvents();

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date').datepicker();
		$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
		$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
		$('#date').datepicker("setDate", new Date());

		var arr = 	{ 
						fnc : 'get_release_details'
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
					recomputeTotalItem();
				};

				if (response.release_order_list_error == '') 
				{
					myjstbl_release_order_list.insert_multiplerow_with_value(1,response.release_order_lists);
					
					if ($('.chkdetails:checked').length > 0)
					{
						$('.chkdetails').attr('disabled','disabled');
						$('.chkdetails:checked').removeAttr('disabled');
					} 
						
				}

				if (!response.is_editable || (Boolean(<?= $permission_list['allow_to_edit']?>) == false && response.is_saved == true) || (Boolean(<?= $permission_list['allow_to_add']?>) == false && response.is_saved == false))
				{
					$('input, textarea, select').not('#print').attr('disabled','disabled');
					$('.tdupdate, .tddelete, #save').hide();
				}

				if (!response.is_saved)
					$('#print').hide();
				
				tableHelper.contentHelper.checkProductTypeDescription();
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
			$('.chkdetails').attr('disabled','disabled');

			var po_head_id_val = poListTableHelper.contentProvider.getData(rowIndex,'id');

			var arr = 	{ 
							fnc : 'get_pa_details',
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
						tableHelper.contentHelper.checkProductTypeDescription();
						checkReceivedDetails();
						recomputeTotalItem();
					}

					$(self).removeAttr('disabled');

					flag = 0;
				}       
			});
		}
		else
		{
			$('.chkdetails').removeAttr('disabled');

			var importPONumber = poListTableHelper.contentProvider.getData(rowIndex,'panumber');

			for(var i = myjstbl.get_row_count() - 1; i > 0; i--)
			{
				var tableDetailRowPONumber = tableHelper.contentProvider.getData(i,'ponumber');
				if (importPONumber == tableDetailRowPONumber) 
					myjstbl.delete_row(i);
			};

			recomputeTotalItem();

			flag = 0;
		}
	});
	
	$('.chkreceiveall').live('click',function(){
		transferRemainingToReceive($(this));

		if ($(this).is(':checked')) 
		{
			if ($('.chkreceiveall:checked').length == $('.chkreceiveall').length)
				$('#chkcheckall').attr('checked','checked');
		}
		else
			$('#chkcheckall').removeAttr('checked');
	});

	$('.imgdel').live('click',function(){
		var rowIndex = $(this).parent().parent().index();
		var rowUniqueId = tableHelper.contentProvider.getData(rowIndex,'id');

		if (Number(rowUniqueId) === 0)
		{
			myjstbl.delete_row(rowIndex);
			checkRemainingPODetails();
			recomputeTotalItem();
		}
		else
		{
			clear_message_box();
			$('#deleteReleasedModal').find('.message-content').show();
			$('#deleteReleasedModal').find('.btn-default').html('Cancel');
			$('#delete').show();
			$('#deleteReleasedModal').modal('show');
			globalRowIndex = rowIndex;
			globalDetailId = rowUniqueId;
		}
	});

	$('#delete').live('click',function(){
		if (flag == 1) 
			return;

		flag = 1;

		var rowIndex     = globalRowIndex;
		var rowUniqueId  = globalDetailId;
		
		var arr =   { 
						fnc         : 'delete_release_detail', 
						detail_id   : rowUniqueId
					};
	   
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				clear_message_box();

				if (response.error != '')
				{ 
					$('#deleteReleasedModal').find('.message-content').hide();
					$('#deleteReleasedModal').find('.btn-default').html('OK');
					$('#delete').hide();
					build_message_box('messagebox_1',response.error,'danger');
				}
				else
				{
					myjstbl.delete_row(rowIndex);
					tableHelper.contentProvider.recomputeRowNumber();
					recomputeTotalItem();
					checkRemainingPODetails();
					$('#deleteReleasedModal').modal('hide');
				}

				flag = 0;
			}       
		});
	});

	$('.txtqtyrecv').live('change',function(){
		recomputeRemainingQuantity($(this));
	});

	$('#save').click(function(){
		if (flag == 1)
			return;

		if (myjstbl.get_row_count() - 1 == 0) 
		{
			alert('Please receive at least one product!');
			return;
		}

		for (var i = 1; i <= myjstbl.get_row_count() - 1; i++) 
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
						fnc 	 	: 'save_release_head', 
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
					goToPrintOut();

				flag = 0;
			}       
		});
	});
	
	$('#chkcheckall').live('click', function(){
		if ($(this).is(':checked')) 
			$('.chkreceiveall').attr('checked','checked');
		else
			$('.chkreceiveall').removeAttr('checked');
		
		$(".chkreceiveall").each(function(index, element){
			transferRemainingToReceive(element);
		});
	});

	$('#print').click(function(){
		goToPrintOut();
	});

	function transferRemainingToReceive(element)
	{
		var rowIndex = $(element).parent().parent().index();
		var totalQuantity = 0;
		var remainingQuantity = Number(tableHelper.contentProvider.getData(rowIndex, 'qtyremaining', 1));
		var receivedQuantity = Number(tableHelper.contentProvider.getData(rowIndex, 'qtyrecv',1));
		var receivedQuantityElement = tableHelper.contentProvider.getElement(rowIndex, 'qtyrecv');

		if ($(element).is(':checked'))
		{
			$(receivedQuantityElement).attr('disabled','disabled');
			totalQuantity = receivedQuantity + remainingQuantity;
		}
		else
		{
			$(receivedQuantityElement).removeAttr('disabled','disabled');
			totalQuantity = receivedQuantity;
		}

		tableHelper.contentProvider.setData(rowIndex,'qtyrecv',[totalQuantity,receivedQuantity]);

		recomputeRemainingQuantity($(element));
	}

	function checkRemainingPODetails()
	{
		$(".chkdetails:checked").each(function(index, element){
			var detailExist = false;
			var poRowIndex = $(element).parent().parent().index();

			var importPONumber = poListTableHelper.contentProvider.getData(poRowIndex, 'panumber');

			for (var i = 1; i < myjstbl.get_row_count(); i++) 
			{
				var tableDetailRowPONumber = tableHelper.contentProvider.getData(i,'ponumber');
				if (tableDetailRowPONumber == importPONumber)
				{
					detailExist = true;
					break;
				}
			};

			if (!detailExist)
			{
				$('.chkdetails').removeAttr('disabled','disabled');
				$(element).removeAttr('checked');
			}
		});
	}

	function recomputeRemainingQuantity(element)
	{
		var rowIndex = $(element).parent().parent().index();
		
		var quantityOrdered = Number(tableHelper.contentProvider.getData(rowIndex, 'qty'));
		var hiddenRemainingQuantity = Number(tableHelper.contentProvider.getData(rowIndex, 'qtyremaining', 1));
		var currentReceivedQuantity = Number(tableHelper.contentProvider.getData(rowIndex, 'qtyrecv'));
		var hiddenCurrentReceivedQuantity = Number(tableHelper.contentProvider.getData(rowIndex, 'qtyrecv', 1));

		var baseQuantity = quantityOrdered;

		if (hiddenCurrentReceivedQuantity == 0) 
			baseQuantity = hiddenRemainingQuantity;

		var newQuantityRemaining = baseQuantity - currentReceivedQuantity;

		tableHelper.contentProvider.setData(rowIndex,'qtyremaining',[newQuantityRemaining, hiddenRemainingQuantity]);
	}

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
					window.open('<?= base_url() ?>printout/release/Release');
				}
			}
		});
	}

	function getRowDetailsBeforeSubmit(element)
	{
		var rowIndex = $(element).parent().parent().index();

		var receiveDetailId 	= tableHelper.contentProvider.getData(rowIndex,'id');
		var purchaseDetailId 	= tableHelper.contentProvider.getData(rowIndex,'podetailid');
		var receivedQty 		= tableHelper.contentProvider.getData(rowIndex,'qtyrecv');
		var productId 			= tableHelper.contentProvider.getData(rowIndex,'product',1);
		var actionFunction 		= receiveDetailId == 0 ? 'insert_release_detail' : 'update_release_detail';

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
						release_order_detail_id : purchaseDetailId,
						quantity : receivedQty,
						product_id : productId
					};

		return arr;
	}

	function checkReceivedDetails()
	{
		for (var i = 1; i < myjstbl.get_row_count(); i++) 
		{
			var rowReceiveDetailId = tableHelper.contentProvider.getData(i,'id');

			if (rowReceiveDetailId == 0) 
			{
				myjstbl.edit_row(i);
				tableHelper.contentHelper.descriptionAccessibilty(i);
			}
		};
	}

	function recomputeTotalItem()
	{
		var totalQty = myjstbl.get_row_count() - 1;
        $('#totalReleasedItem').html(totalQty);
	}
</script>