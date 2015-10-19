<script type="text/javascript">
	var token = "<?= $token ?>";

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
    description.setAttribute('placeholder', 'Description');
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

    var spnreceivedby = document.createElement('span');
    var txtreceivedby = document.createElement('input');
    txtreceivedby.setAttribute('class','form-control');
	colarray['receivedby'] = { 
        header_title: "Received By",
        edit: [txtreceivedby],
        disp: [spnreceivedby],
        td_class: "tablerow column_click column_hover tdrecvdby"
    };

    var spnmemo = document.createElement('span');
    var txtmemo = document.createElement('input');
    txtmemo.setAttribute('class','form-control txtmemo');
	colarray['memo'] = { 
        header_title: "Note",
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

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", 
										  controller : 'return',
										  recentNameElementId : 'customer_name' });

	tableHelper.detailContent.bindAllEvents( { 	saveEventsBeforeCallback : getHeadDetailsBeforeSubmit, 
												updateEventsBeforeCallback : getRowDetailsBeforeSubmit,
												saveEventsAfterCallback : goToPrintOut } );

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date').datepicker();
    	$('#date').datepicker("option","dateFormat", "mm-dd-yy");
    	$('#date').datepicker("setDate", new Date());

		var arr = 	{ fnc : 'get_return_details' };
		
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
					$('#received_by').val(response.received_by);

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);
				}
				
				if (response.detail_error == '') 
					myjstbl.insert_multiplerow_with_value(1,response.detail);

				if (!response.is_editable || (Boolean(<?= $permission_list['allow_to_edit']?>) == false && response.is_saved == true) || (Boolean(<?= $permission_list['allow_to_add']?>) == false && response.is_saved == false))
				{
					$('input, textarea, select').not('#print').attr('disabled','disabled');
					$('.tdupdate, .tddelete, #save').hide();
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

	$('.imgdel').live('click',function(){
		var rowIndex = $(this).parent().parent().index(); 
		var rowUniqueId = tableHelper.contentProvider.getData(rowIndex,'id');

		if (rowUniqueId == 0)
			tableHelper.contentProvider.setData(rowIndex,'receivedby',['']);
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
                	window.open('<?= base_url() ?>printout/customer_return/Receive');
				}
            }
        });
	}

	function getHeadDetailsBeforeSubmit()
	{
		var date_val	= moment($('#date').val(),'MM-DD-YYYY').format('YYYY-MM-DD');
		var memo_val 	= $('#memo').val();
		var customer_name_val = $('#customer_name').val();
		var received_by_val = $('#received_by').val();

		if (customer_name_val == '') 
		{
			alert('Customer Name should no be empty!');
			return false;
		};

		var arr = 	{ 
						fnc 	 	: 'save_return_head', 
						entry_date 	: date_val,
						memo 		: memo_val,
						customer_name : customer_name_val,
						received_by : received_by_val
					};

		return arr;
	}

	function getRowDetailsBeforeSubmit(element)
	{
		var rowIndex 		= $(element).parent().parent().index();
		var productId       = tableHelper.contentProvider.getData(rowIndex,'product',1);
	    var qty             = tableHelper.contentProvider.getData(rowIndex,'qty');
	    var memo            = $.sanitize(tableHelper.contentProvider.getData(rowIndex,'memo'));
	    var rowUniqueId     = tableHelper.contentProvider.getData(rowIndex,'id');
	    var nonStackDescription  = tableHelper.contentProvider.getData(rowIndex,'product',4);
	    var receivedBy 		= $.sanitize(tableHelper.contentProvider.getData(rowIndex,'receivedby'));
	    var actionFunction  = rowUniqueId != 0 ? tableHelper._settings.updateDetailName : tableHelper._settings.insertDetailName;

	    var errorList = $.dataValidation([  {   
	                                            value : productId,
	                                            fieldName : 'Product',
	                                            required : true,
	                                            isNotEqual : { value : 0, errorMessage : 'Please select a valid product!'}
	                                        },
	                                        {
	                                            value : qty,
	                                            fieldName : 'Quantity',
	                                            required : true,
	                                            rules : 'numeric',
	                                            isNotEqual : { value : 0, errorMessage : 'Quantity must be greater than 0!'}
	                                        }
	                                        ]);

	    if (errorList.length > 0) {
	        clear_message_box();
	        build_message_box('messagebox_1',build_error_message(errorList),'danger');
	        return;
	    };

	    var arr =   { 
                        fnc         : actionFunction, 
                        product_id  : productId,
                        qty         : qty,
                        memo        : memo,
                        detail_id   : rowUniqueId,
                        description : nonStackDescription,
                        received_by : receivedBy
                    };

		return arr;
	}
</script>