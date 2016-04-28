<script type="text/javascript">
	
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

	var chkchecktransfer = document.createElement('input');
	chkchecktransfer.setAttribute('type','checkbox');
	chkchecktransfer.setAttribute('class','chktransfer');

	var chktransferall = document.createElement('input');
	chktransferall.setAttribute('type','checkbox');
	chktransferall.setAttribute('id','chktransferall');

	colarray['istransfer'] = { 
		header_title: chktransferall,
		edit: [chkchecktransfer],
		disp: [chkchecktransfer],
		td_class: "tablerow tdistransfer",
		headertd_class : "tdistransfer",
		header_elem: chktransferall
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
	colarray['receive'] = { 
		header_title: "Received Qty",
		edit: [spnreceive],
		disp: [spnreceive],
		td_class: "tablerow column_click column_hover tdreceive",
		headertd_class : "tdreceive"
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

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray},
										{ baseURL : "<?= base_url() ?>", 
										  controller : 'purchase',
										  recentNameElementId : 'supplier' } );

	tableHelper.detailContent.bindAllEvents( { saveEventsBeforeCallback : getHeadDetailsBeforeSubmit,
											   addInventoryChecker : true,
											   saveEventsAfterCallback : goToPrintOut } );

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date').datepicker();
		$('#date').datepicker("option","dateFormat", "mm-dd-yy");
		$('#date').datepicker("setDate", new Date());

		var arr = 	{ 
						fnc : 'get_purchaseorder_details'
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
					$('#supplier').val(response.supplier_name);
					$('#orderfor').val(response.orderfor);
					
					if (response.is_imported == PurchaseType.Imported) 
						$('#is_imported').attr('checked','checked');

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);	

					if (!response.is_saved)
					{
						$('#print').hide();
						hideQuantityReceivedColumn();
					}
				}
				
				if (response.detail_error == '') 
					myjstbl.insert_multiplerow_with_value(1,response.detail);

				if (!response.is_editable || (Boolean(<?= $permission_list['allow_to_edit']?>) == false && response.is_saved == true) || (Boolean(<?= $permission_list['allow_to_add']?>) == false && response.is_saved == false))
				{
					$('input, textarea, select').not('#print').attr('disabled','disabled');
					$('.tdupdate, .tddelete, #save, #transfer').hide();

					if (response.is_saved && response.is_incomplete && (response.own_branch == response.transaction_branch) && (Boolean(<?= $permission_list['allow_to_edit_transfer']?>) == true))
					{
						$('input, textarea, select').not('#print').removeAttr('disabled');
						$('.tdupdate, #save, #transfer').show();
					}
				}	
				else
					tableHelper.contentProvider.addRow();

				if (!response.is_saved || !response.is_incomplete)
				{
					hideTransferColumn();
					$('#transfer').hide();
				}
					

				tableHelper.contentProvider.recomputeTotalQuantity();
				tableHelper.contentHelper.checkProductInfo();

			}       
		});
	}
	else
		$('input, textarea').attr('disabled','disabled');

	$('#print').click(function(){
		goToPrintOut();
	});

	$('#chktransferall').live('click', function(){
		if ($(this).is(':checked')) 
			$('.chktransfer').attr('checked','checked');
		else
			$('.chktransfer').removeAttr('checked');
	});

	$('.chktransfer').live('click', function(){
		if ($(this).is(':checked'))
		{
			if ($('.chktransfer').length == $('.chktransfer:checked').length) 
				$('#chktransferall').attr('checked','checked');
		} 
		else
			$('#chktransferall').removeAttr('checked');
	});

	$('#transfer').click(function(){

		var selectedDetailId = [];

		$('.chktransfer:checked').each(function(index, element){
			var rowIndex = $(element).parent().parent().index();
			var currentId = tableHelper.contentProvider.getData(rowIndex, 'id');
			selectedDetailId.push(currentId);
		});

		if (selectedDetailId.length == 0 )
		{
			alert('Please select at least one product!');
			return;
		}

		var arr = 	{ 
						fnc : 'transfer_remaining',
						selected_detail_id : selectedDetailId 
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
					window.open('<?= base_url() ?>purchase/view/' + response.id);
					window.location = '<?= base_url() ?>purchase/view/<?= $this->uri->segment(3) ?>';
				}
			}
		});
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
					window.open('<?= base_url() ?>printout/purchase_order/Order');
				}
			}
		});
	}

	function getHeadDetailsBeforeSubmit()
	{
		var date_val	=  moment($('#date').val(),'MM-DD-YYYY').format('YYYY-MM-DD');
		var memo_val 	= $('#memo').val();
		var supplier_name_val = $('#supplier').val();
		var orderfor_val 	= $('#orderfor').val();
		var is_imported_val = $('#is_imported').is(':checked') ? 1 : 2;

		if (supplier_name_val == '') 
		{
			alert('Supplier Name should not be empty!');
			return false;
		};
		
		var arr = 	{ 
						fnc 	 	: 'save_purchaseorder_head', 
						entry_date 	: date_val,
						memo 		: memo_val,
						supplier_name : supplier_name_val,
						orderfor     : orderfor_val,
						is_imported : is_imported_val
					};

		return arr;
	}

	function hideQuantityReceivedColumn()
	{
		$('#dynamic-css').html('');
		$('#dynamic-css').html("<style> .tdreceive{ display:none; } </style>");
	}

	function hideTransferColumn()
	{
		var currentInnerHTML = $('#dynamic-css').html();
		$('#dynamic-css').html(currentInnerHTML + "<style> .tdistransfer{ display:none; } </style>");
	}
</script>