<script type="text/javascript">
	var TransactionState = {
		Saved : 1,
		Unsaved : 0
	}

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
										  controller : 'assort',
										  recentNameElementId : 'customer' } );

	tableHelper.detailContent.bindAllEvents( { saveEventsBeforeCallback : getHeadDetailsBeforeSubmit,
											   addInventoryChecker : false,
											   saveEventsAfterCallback : goToPrintOut } );

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
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
					$('#customer').val(response.customer_name);

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);	

					if (response.is_saved == TransactionState.Unsaved)
						hideQuantityReleasedColumn();
				}
				
				if (response.detail_error == '') 
					myjstbl.insert_multiplerow_with_value(1,response.detail);

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
				tableHelper.contentHelper.checkProductTypeDescription();

			}       
		});
	}
	else
		$('input, textarea').attr('disabled','disabled');

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
		var customer_name_val = $('#customer').val();

		if (customer_name_val == '') 
		{
			alert('Customer Name should not be empty!');
			return false;
		};
		
		var arr = 	{ 
						fnc 	 	: 'save_assortment_head', 
						entry_date 	: date_val,
						memo 		: memo_val,
						customer_name : customer_name_val
					};

		return arr;
	}

	function hideQuantityReleasedColumn()
	{
		$('#dynamic-css').html('');
		$('#dynamic-css').html("<style> .tdreleased, .tdremaining{ display:none; } </style>");
	}
</script>