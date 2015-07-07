<script type="text/javascript">
	var TransactionState = {
		Saved : 1,
		Unsaved : 0
	}

	var tableHelper;
	var token = '<?= $token ?>';

	var tab = document.createElement('table');
	tab.className = "tblstyle";
	tab.id = "tableid";
	tab.setAttribute("style","border-collapse:collapse;");
	tab.setAttribute("class","border-collapse:collapse;");	

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date').datepicker();
		$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
		$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
		$('#date').datepicker("setDate", new Date());
		$('.txtqtyrelease').binder('setRule','numeric');

		var arr = 	{ fnc : 'get_release_details' };

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
					$('#customer_name').val(response.customer_name);

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);

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
					description.setAttribute('placeholder', 'Description')
					description.setAttribute('style','display:none;');

					var disabledDescription = document.createElement('textarea');
					disabledDescription.setAttribute('class','nonStackDescription form-control');
					disabledDescription.setAttribute('style','display:none;');
					disabledDescription.setAttribute('disabled','disabled');

					var productType = document.createElement('span');
					productType.setAttribute('style','display:none;');

					var newline = document.createElement('span');

					var editableProduct = response.is_saved == TransactionState.Unsaved ? txtproduct : spnproduct;
					var editableDescription = response.is_saved == TransactionState.Unsaved ? description : disabledDescription;

					colarray['product'] = { 
						header_title: "Product",
						edit: [editableProduct,spnproductid,productType,newline,editableDescription],
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
					var txtqty = document.createElement('input');
					txtqty.setAttribute('class','form-control txtqty');

					var editableQuantity = response.is_saved == TransactionState.Unsaved ? txtqty : spnqty;

					colarray['qty'] = { 
						header_title: "Qty",
						edit: [editableQuantity],
						disp: [spnqty],
						td_class: "tablerow column_click column_hover tdqty"
					};

					var spnmemo = document.createElement('span');
					var txtmemo = document.createElement('input');
					txtmemo.setAttribute('class','form-control txtmemo');

					var editableMemo = response.is_saved == TransactionState.Unsaved ? txtmemo : spnmemo;

					colarray['memo'] = { 
						header_title: "Remarks",
						edit: [editableMemo],
						disp: [spnmemo],
						td_class: "tablerow column_click column_hover tdmemo"
					};

					var spnqtyrelease = document.createElement('span');
					var txtqtyrelease = document.createElement('input');
					txtqtyrelease.setAttribute('class','form-control txtqtyrelease');

					var editableQtyRelease = response.is_saved == TransactionState.Unsaved ? spnqtyrelease : txtqtyrelease;

					colarray['qty_release'] = { 
						header_title: "Qty Release",
						edit: [editableQtyRelease],
						disp: [spnqtyrelease],
						td_class: "tablerow column_click column_hover tdqty_release",
						headertd_class : "tdqty_release"
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

					var myjstbl;

					var root = document.getElementById("tbl");
					myjstbl = new my_table(tab, colarray, {	ispaging : false, 
															tdhighlight_when_hover : "tablerow",
															iscursorchange_when_hover : true,
															isdeleteicon_when_hover : false
											});

					root.appendChild(myjstbl.tab);

				}
				
				if (response.detail_error == '') 
					myjstbl.insert_multiplerow_with_value(1,response.detail);

				var helperCallback = response.is_saved == TransactionState.Unsaved ? { saveEventsBeforeCallback : getHeadDetailsBeforeSubmit, addInventoryChecker : true, saveEventsAfterCallback : goToPrintOut } : { updateEventsBeforeCallback : getReleaseDetailsBeforeSubmit }

				tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
												{ baseURL : "<?= base_url() ?>", controller : 'release' });
				
				if (response.is_saved == TransactionState.Unsaved)
				{
					$('#dynamic-css').html('');
					var css = "<style>.tdqty_release{ display:none; } </style>";
					$('#dynamic-css').html(css);


					if (!response.is_editable || (Boolean(<?= $permission_list['allow_to_add']?>) == false)) 
					{
						$('input, textarea, select').not('#print').attr('disabled','disabled');
						$('.tdupdate, .tddelete, #save').hide();
					}
					else
						tableHelper.contentProvider.addRow();
				}
					
				if (response.is_saved == TransactionState.Saved)
				{
					if (!response.is_editable || (Boolean(<?= $permission_list['allow_to_edit']?>) == false))
					{
						$('input, textarea, select').not('#print').attr('disabled','disabled');
						$('.tdupdate, .tddelete, #save').hide();
					}
					else
					{
						$('.tddelete').hide();
						$('#save').hide();
						$('#customer_name, #memo, #date').attr('disabled','disabled');

						for (var i = 1; i < myjstbl.get_row_count(); i++) 
						{
							var releasedQty = Number(tableHelper.contentProvider.getData(i,'qty_release'));
							if (releasedQty == 0) 
								myjstbl.edit_row(i);
						};
					}
				}

				tableHelper.detailContent.bindAllEvents(helperCallback);
				tableHelper.contentHelper.checkProductTypeDescription();
				tableHelper.contentProvider.recomputeTotalQuantity();

				$('#tbl').show();
			}       
		});
	}
	else{
		$('input, textarea').attr('disabled','disabled');
	}

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
            success: function(data) {
                window.location = '<?= base_url() ?>printout/release/Release';
            }
        });
	}

	function getHeadDetailsBeforeSubmit()
	{
		var entryDate		= $('#date').val();
		var memo 			= $('#memo').val();
		var customerName 	= $('#customer_name').val();

		var arr = 	{ 
						fnc 	 	: 'save_release_head', 
						entry_date 	: entryDate,
						memo 		: memo,
						customer_name : customerName
					};

		return arr;
	}

	function getReleaseDetailsBeforeSubmit(element)
	{
		var rowIndex 		= $(element).parent().parent().index();
		var rowId 			= tableHelper.contentProvider.getData(rowIndex,'id');
		var releasedQty 	= tableHelper.contentProvider.getData(rowIndex,'qty_release');

		var errorList = $.dataValidation([{
											value : releasedQty,
											fieldName : 'Released Quantity',
											required : true,
											rules : 'numeric'
										 }]);

		if (errorList.length > 0) {
			clear_message_box();
			build_message_box('messagebox_1',build_error_message(errorList),'danger');
			return false;
		};

		var arr = 	{ 
						fnc 	 	: 'update_release_detail', 
						detail_id 	: rowId,
						released_qty : releasedQty
					};

		return arr;
	}
</script>