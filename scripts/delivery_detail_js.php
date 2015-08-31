<script type="text/javascript">
	var DeliveryType = {
		Unsaved 	: 0,
		Both 		: 1,
		Sales 		: 2,
		Transfer 	: 3
	}

	var TransferState = {
		ForTransfer : 1,
		ForSales : 0
	}
	
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
		edit: [txtproduct,spnproductid,productType,newline,description],
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

	var myjstbl;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);

	$('#tbl').hide();

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", controller : 'delivery' });

	tableHelper.detailContent.bindAllEvents( { 	saveEventsBeforeCallback : getHeadDetailsBeforeSubmit,
												updateEventsBeforeCallback : getRowDetailsBeforeSubmit,
												addInventoryChecker : true,
												saveEventsAfterCallback : goToPrintOut } );

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
						$("#to_branch option[value="+response.own_branch+"]").remove();

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

				var isHideTransfer = false;

				if (response.delivery_type == DeliveryType.Transfer) 
					isHideTransfer = true;
				
				hideTransferAndReceived(isHideTransfer);

				if (!$.inArray(response.delivery_type,[DeliveryType.Both,DeliveryType.Unsaved]))
				{
					$('.tdistransfer').hide();
					hideTransferAndReceived(true);
				}
					
				tableHelper.contentProvider.recomputeTotalQuantity();
				tableHelper.contentHelper.checkProductTypeDescription();

				$('#tbl').show();
			}       
		});
	}
	else
		$('input, textarea').attr('disabled','disabled');

	$('#delivery_type').live('change',function(){
		if ($(this).val() == DeliveryType.Sales) 
		{
			$('#delivery_to_list').hide();
			$('.tdistransfer').hide();
			hideTransferAndReceived(true);
		}
		else if ($(this).val() == DeliveryType.Transfer) 
		{
			$('#delivery_to_list').show();
			$('.tdistransfer').hide();
			hideTransferAndReceived(true);
		}
		else if ($(this).val() == DeliveryType.Both)
		{
			for (var i = 1; i < myjstbl.get_row_count(); i++) 
			{
				myjstbl.edit_row(i);
				tableHelper.contentHelper.descriptionAccessibilty(i);
			}

			$('#delivery_to_list').show();
			$('.tdistransfer').show();
			$('.chktransfer').removeAttr('checked');
			$('#dynamic-css').html('');
			hideTransferAndReceived();

		}
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

	function getHeadDetailsBeforeSubmit()
	{
		var date_val	= moment($('#date').val(),'MM-DD-YYYY').format('YYYY-MM-DD');
		var memo_val 	= $('#memo').val();
		var type_val 	= $('#delivery_type').val();
		var to_branch 	= type_val == DeliveryType.Sales ? 0 : $('#to_branch').val();

		for (var i = 1; i < myjstbl.get_row_count() - 1; i++) 
		{
			var isTransfer 	= Number(tableHelper.contentProvider.getData(i,'istransfer'));
			var memo 		= tableHelper.contentProvider.getData(i,'memo');

			if ((type_val == DeliveryType.Both && isTransfer == TransferState.ForSales && memo == '') || (type_val == DeliveryType.Sales && memo == '')) 
			{
				alert('Please add a customer name in remarks!');
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
		var memo 			= encodeURIComponent(tableHelper.contentProvider.getData(rowIndex,'memo'));
		var invoice 		= encodeURIComponent(tableHelper.contentProvider.getData(rowIndex,'invoice'));
		var rowId 			= tableHelper.contentProvider.getData(rowIndex,'id');
		var isTransfer 		= Number(tableHelper.contentProvider.getData(rowIndex,'istransfer'));
		var description 	= tableHelper.contentProvider.getData(rowIndex,'product',4);
		var actionFunction 	= rowId != 0 ? "update_stock_delivery_detail" : "insert_stock_delivery_detail";

		if ((type_val == DeliveryType.Both && isTransfer == TransferState.ForSales && memo == '') || (type_val == DeliveryType.Sales && memo == '')) 
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
						invoice 	: invoice
					};

		return arr;
	}

	function hideTransferAndReceived(isTransfer)
	{
		$('#dynamic-css').html('');

		var css = "<style>";
		css += ".tdsample" + isUsed;

		if (isTransfer) 
			css += ", .tdistransfer";

		css += " { display:none; } </style>";

		$('#dynamic-css').html(css);
	}
</script>