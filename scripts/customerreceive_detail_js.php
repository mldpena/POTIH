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
   	
   	var spnuom = document.createElement('span');
	colarray['uom'] = { 
		header_title: "UOM",
		edit: [spnuom],
		disp: [spnuom],
		td_class: "tablerow column_click column_hover tduom"
	};
	
   	var spnqty = document.createElement('span');
	colarray['qty'] = { 
        header_title: "Qty",
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

    var chkreceiveall = document.createElement('input');
	chkreceiveall.className = "chkreceiveall";
	chkreceiveall.type = "checkbox";

	var chkreceiveallDis = document.createElement('input');
	chkreceiveallDis.setAttribute('type','checkbox');
	chkreceiveallDis.setAttribute('disabled','disabled');
	
	var chkcheckall = document.createElement('input');
	chkcheckall.setAttribute('type','checkbox');
	chkcheckall.setAttribute('id','chkcheckall');

	colarray['receiveall'] = { 
		header_title: chkcheckall,
		edit: [chkreceiveall],
		disp: [chkreceiveallDis],
		td_class: "tablerow tdreceiveall",
		header_elem: chkcheckall
	};

    var spnreceiveqty = document.createElement('span');
    var txtreceiveqty = document.createElement('input');
    txtreceiveqty.setAttribute('class','receiveqty form-control');
	colarray['receiveqty'] = { 
        header_title: "Received Qty",
        edit: [txtreceiveqty],
        disp: [spnreceiveqty],
        td_class: "tablerow column_click column_hover tdreceiveqty"
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

	var myjstbl;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);

	$('#tbl').hide();
	$('.receiveqty').binder('setRule','numeric');

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", controller : 'custreceive' });

	tableHelper.detailContent.bindAllEvents( { saveEventsBeforeCallback : getHeadDetailsBeforeSubmit, 
											   updateEventsBeforeCallback : getRowDetailsBeforeSubmit,
											   saveEventsAfterCallback : goToPrintOut } );


	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date, #receive_date').datepicker();
    	$('#date, #receive_date').datepicker("option","dateFormat", "mm-dd-yy");
    	$('#date, #receive_date').datepicker("setDate", new Date());

		var arr = 	{ 
						fnc : 'get_customer_receive_details'
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
					$('#delivery_type').val(response.delivery_type);
					$('#receive_date').val(response.receive_date);	

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);	
				}
				
				if (response.detail_error == '') 
					myjstbl.insert_multiplerow_with_value(1,response.detail);

				if (!response.is_editable || (Boolean(<?= $permission_list['allow_to_edit']?>) == false))
				{
					$('input, textarea, select').attr('disabled','disabled');
					$('.imgedit, .imgupdate').hide();
				}
				else
				{
					$('input, textarea, select').not('#print, #receive_date, #save, input[type=checkbox]').attr('disabled','disabled');
					for (var i = 1; i < myjstbl.get_row_count(); i++) 
					{
						var receive_qty = tableHelper.contentProvider.getData(i,'receiveqty');
						if (receive_qty == 0) 
							myjstbl.edit_row(i);
					};
				}

				//if ((response.is_incomplete) && (response.own_branch == response.transaction_branch) && (Boolean(<?= $permission_list['allow_to_transfer_remaining']?>) == true))
				if ((response.own_branch == response.transaction_branch) && (Boolean(<?= $permission_list['allow_to_transfer_remaining']?>) == true))
					$('#transfer').show();

				tableHelper.contentProvider.recomputeTotalQuantity();
				tableHelper.contentHelper.checkProductTypeDescription();

				$('#tbl').show();
			}       
		});
	}
	else
		$('input, textarea').attr('disabled','disabled');

	$('.chkreceiveall').live('click',function(){
		receiveAll($(this));

		if ($(this).is(':checked')) 
		{
			if ($('.chkreceiveall:checked').length == $('.chkreceiveall').length)
				$('#chkcheckall').attr('checked','checked');
		}
		else
			$('#chkcheckall').removeAttr('checked');
	});

	$('#chkcheckall').live('click',function(){
		if ($(this).is(':checked')) 
			$('.chkreceiveall').attr('checked','checked');
		else
			$('.chkreceiveall').removeAttr('checked');
		
		$(".chkreceiveall").each(function(index, element){
			receiveAll(element);
		});
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

	$('#print').click(function(){
		goToPrintOut(true);
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
						fnc : 'transfer_to_return',
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
					for (var i = 0; i < response.id.length; i++) 
						window.open('<?= base_url() ?>return/view/' + response.id[i]);
				
					window.location = '<?= base_url() ?>custreceive/view/<?= $this->uri->segment(3) ?>';
				}
			}
		});
	});

	function receiveAll(element)
	{
		var rowIndex = $(element).parent().parent().index();
		var receiveQuantityElement = tableHelper.contentProvider.getElement(rowIndex, 'receiveqty');

		var totalQuantity = 0;

		if ($(element).is(':checked')) 
		{
			$(receiveQuantityElement).attr('disabled','disabled');
			totalQuantity = Number(tableHelper.contentProvider.getData(rowIndex,'qty'));
		}
		else
			$(receiveQuantityElement).removeAttr('disabled');
		
		tableHelper.contentProvider.setData(rowIndex,'receiveqty',[totalQuantity]);
	}

	function goToPrintOut(isPrintButtonClicked)
	{
		var printType = 'customer';

		var arr = 	{ 
						fnc : 'set_session_receive',
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
					if (!isPrintButtonClicked)
					{
						if (response.is_incomplete == true) 
						{
							if ((Boolean(<?= $permission_list['allow_to_transfer_remaining']?>) == true))
								$('#transfer').show();
						}
						else
							$('#transfer').hide();	
					}
					
                	window.open('<?= base_url() ?>printout/customer_receive/Receive');
				}
            }
        });
	}

	function getHeadDetailsBeforeSubmit()
	{
		var receiveDate	= moment($('#receive_date').val(),'MM-DD-YYYY').format('YYYY-MM-DD');

		var arr = 	{ 
						fnc 	 		: 'update_customer_receive_head', 
						receive_date 	: receiveDate
					};

		return arr;
	}

	function getRowDetailsBeforeSubmit(element)
	{
		var rowIndex 		= $(element).parent().parent().index();
		var rowId 			= tableHelper.contentProvider.getData(rowIndex,'id');
		var receivedQty 	= tableHelper.contentProvider.getData(rowIndex,'receiveqty');

		var errorList = $.dataValidation([{
                                            value : receivedQty,
                                            fieldName : 'Received Quantity',
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
						fnc 	 	: 'update_receive_detail', 
			     		detail_id 	: rowId,
			     		receiveqty 	: receivedQty
					};

		return arr;
	}
</script>