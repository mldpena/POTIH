<script type="text/javascript">

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
	colarray['istransfer'] = { 
        header_title: "",
        edit: [chkchecktransfer],
        disp: [chkchecktransfer],
        td_class: "tablerow tdistransfer tdhide",
		headertd_class : "tdistransfer tdhide"
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
	colarray['delivered'] = { 
        header_title: "Delivered Qty",
        edit: [spnreceive],
        disp: [spnreceive],
        td_class: "tablerow column_click column_hover tddelivered",
        headertd_class : "tddelivered"
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
	$('#row_reference_number').hide();

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", controller : 'requestto' });

	tableHelper.detailContent.bindAllEvents( { 	saveEventsBeforeCallback : getHeadDetailsBeforeSubmit,
											 	addInventoryChecker : false,
											 	saveEventsAfterCallback : goToPrintOut } );

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date, #due-date').datepicker();
    	$('#date, #due-date').datepicker("option", "dateFormat", "mm-dd-yy");
    	$('#date').datepicker("setDate", new Date());
    	$('#due-date').datepicker("option", "minDate", $('#date').val());

    	$('#date').change(function(){
    		$('#due-date').datepicker("option", "minDate", $('#date').val());
    	});

		var arr = 	{ 
						fnc : 'get_request_details'
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
					$('#to_branch').val(response.to_branchid);

					if (response.to_branchid != response.own_branch)
						$("#to_branch option[value="+response.own_branch+"]").remove();

					if (response.entry_date != '')
					{
						$('#date').val(response.entry_date);
						$('#due-date').datepicker("option", "minDate", $('#date').val());	
					}

					if (response.due_date != '') 
						$('#due-date').val(response.due_date);	

					if (response.delivery_reference_numbers != '') 
					{
						$('#row_reference_number').show();
						$('#reference_numbers').html(response.delivery_reference_numbers);
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
				
				if (!response.is_saved)
				{
					$('#print').hide();
					hideDeliveredQuantity();
				} 
					
				tableHelper.contentProvider.recomputeTotalQuantity();
				tableHelper.contentHelper.checkProductTypeDescription();

				$('#tbl').show();
			}       
		});
	}
	else
		$('input, textarea').attr('disabled','disabled');

	$('#print').click(function(){
		goToPrintOut($(this));
	});

	function goToPrintOut(element)
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
					window.open('<?= base_url() ?>printout/request/Request');
				}
			}
		});
	}

	function getHeadDetailsBeforeSubmit()
	{
		var date_val	= moment($('#date').val(),'MM-DD-YYYY').format('YYYY-MM-DD');
		var due_date	= moment($('#due-date').val(),'MM-DD-YYYY').format('YYYY-MM-DD');
		var memo_val 	= $('#memo').val();
		var to_branch 	= $('#to_branch').val();

		var arr = 	{ 
						fnc 	 	: 'save_request_head', 
						entry_date 	: date_val,
						due_date 	: due_date,
						memo 		: memo_val,
						to_branch 	: to_branch
					};

		return arr;
	}

	function hideDeliveredQuantity()
	{
		$('#dynamic-css').html('');
		$('#dynamic-css').html('<style> .tddelivered { display: none; }</style>');
	}
</script>