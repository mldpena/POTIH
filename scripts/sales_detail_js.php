<script type="text/javascript">
	var CustomerType = {
		Regular : 1,
		Walkin : 2
	};

	var Tax = {
		Nonvat : 1,
		Vatable :2 
	};

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

	var spnreservationid = document.createElement('span');
	colarray['reservationid'] = { 
		header_title: "",
		edit: [spnreservationid],
		disp: [spnreservationid],
		td_class: "tablerow hide-elem tdreservationid",
		headertd_class : "hide-elem tdreservationid"
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

	var spnprice = document.createElement('span');
	var txtprice = document.createElement('input');
	txtprice.setAttribute('class','form-control txtprice');
	colarray['price'] = { 
		header_title: "Price",
		edit: [txtprice],
		disp: [spnprice],
		td_class: "tablerow column_click column_hover tdprice"
	};

	var spndeliveredqty = document.createElement('span');
	colarray['delivered'] = { 
		header_title: "Delivered Qty",
		edit: [spndeliveredqty],
		disp: [spndeliveredqty],
		td_class: "tablerow column_click column_hover tddeliveredqty",
		headertd_class : "tddeliveredqty"
	};

	var spnmemo = document.createElement('span');
	var txtmemo = document.createElement('input');
	txtmemo.setAttribute('class','form-control txtmemo');
	colarray['memo'] = { 
		header_title: "Memo",
		edit: [txtmemo],
		disp: [spnmemo],
		td_class: "tablerow column_click column_hover tdmemo"
	};

	var spnamount = document.createElement('span');
	colarray['amount'] = { 
		header_title: "Amount",
		edit: [spnamount],
		disp: [spnamount],
		td_class: "tablerow column_click column_hover tdamount"
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
										  controller : 'sales',
										  token : notificationToken,
										  recentNameElementId : 'supplier' } );

	tableHelper.detailContent.bindAllEvents({ 
												saveEventsBeforeCallback : getHeadDetailsBeforeSubmit,
												updateEventsBeforeCallback : getRowDetailsBeforeSubmit,
												addInventoryChecker : false
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

		$('#customer, #salesman').chosen();
		$('#date, #due-date').datepicker();
		$('#date, #due-date').datepicker("option","dateFormat", "mm-dd-yy");
		$('#date').datepicker("setDate", new Date());
		$('#due-date').datepicker("option", "minDate", $('#date').val());

    	$('#date').change(function(){
    		$('#due-date').datepicker("option", "minDate", $('#date').val());
    	});

		var arr = 	{ 
						fnc : 'get_sales_details'
					};

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + notificationToken,
			success: function(response) 
			{
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_1', response.error, 'danger');
				else
				{
					$('#reference_no').val(response.reference_number);
					$('#memo').val(response.memo);
					$('#customer').val(response.customer_id).trigger('liszt:updated');
					$('#walkin-customer').val(response.walkin_customer_name);
					$('#salesman').val(response.salesman_id).trigger('liszt:updated');
					$('#address').val(response.address);
					$('#orderfor').val(response.for_branch);
					$('#po-number').val(response.ponumber);
					$('#dr-number').val(response.drnumber);
					$('#is-vatable').val(response.is_vatable);

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
					{
						$('#date').val(response.entry_date);
						$('#due-date').datepicker("option", "minDate", $('#date').val());
					}

					if (!response.is_saved)
					{
						$('#print').hide();
						toggleColumn();
					}
				}
				
				if (response.detail_error == '') 
				{
					myjstbl.insert_multiplerow_with_value(1, response.detail);
					computeSummary();
				}

				if (!response.is_editable || (Boolean(<?= $permission_list['allow_to_edit']?>) == false && response.is_saved == true) || (Boolean(<?= $permission_list['allow_to_add']?>) == false && response.is_saved == false))
				{
					$('input, textarea, select').not('#print').attr('disabled','disabled');
					$('.tdupdate, .tddelete, #save').hide();

					if (response.is_saved && response.is_incomplete && (response.own_branch == response.transaction_branch))
					{
						$('input, textarea, select').not('#print').removeAttr('disabled');
						$('.tdupdate, #save').show();
					}
				}	
				else
					tableHelper.contentProvider.addRow();

				tableHelper.contentProvider.recomputeTotalQuantity();
				tableHelper.contentHelper.checkProductInfo();
			}       
		});
	}

	$('#customer').change(function(){

		var customer_id = $(this).val();

		var arr = 	{ 
						fnc : 'get_customer_details',
						customer_id : customer_id
					};

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + notificationToken,
			success: function(response) 
			{
				$('#address').val(response.office_address);
				$('#is-vatable').val(response.tax);
				computeSummary();
			}
		});
	});

	$("input[name='customer-type'][value=2]").click(function(){
		$('#is-vatable').val(Tax.Vatable);
		computeSummary();
	});

	$('.txtprice').live('change', function(){
		var rowIndex = $(this).parent().parent().index();
		var quantity = Number(tableHelper.contentProvider.getData(rowIndex, 'qty').replace(/\,/gi,""));
		var price 	 = Number(tableHelper.contentProvider.getData(rowIndex, 'price').replace(/\,/gi,""));
		var amount   = Number(quantity * price);

		tableHelper.contentProvider.setData(rowIndex, 'amount', [amount.formatMoney(2)]);

		computeSummary();
	});

	function getHeadDetailsBeforeSubmit()
	{
		var customer_type 	= $("input[name='customer-type']:checked").val();
		var customer_id_val = customer_type == CustomerType.Regular ? $('#customer').val() : 0;
		var walkin_customer_name_val = customer_type == CustomerType.Regular ? '' : $('#walkin-customer').val();
		var address_val 	= customer_type == CustomerType.Regular ? '' : $('#address').val();
		var salesman_val 	= $('#salesman').val();
		var date_val		=  moment($('#date').val(),'MM-DD-YYYY').format('YYYY-MM-DD');
		var memo_val 		= $('#memo').val();
		var for_branch_val 	= $('#orderfor').val();
		var is_vatable 		= $('#is-vatable').val();
		var ponumber 		= $('#po-number').val();
		var drnumber 		= $('#dr-number').val();

		if (
				(customer_type == CustomerType.Regular && customer_id_val == 0) ||
				(customer_type == CustomerType.Walkin && walkin_customer_name_val == '')
			) 
		{
			alert('Customer should not be empty!');
			return false;
		};
		
		var arr = 	{ 
						fnc 	 	: 'save_sales_head', 
						customer_id : customer_id_val,
						walkin_customer_name : walkin_customer_name_val,
						address 	: address_val,
						salesman 	: salesman_val,
						entry_date 	: date_val,
						memo 		: memo_val,
						orderfor    : for_branch_val,
						is_vatable 	: is_vatable,
						ponumber 	: ponumber,
						drnumber 	: drnumber
					};

		return arr;
	}

	function getRowDetailsBeforeSubmit(element)
	{
		var rowIndex 		= $(element).parent().parent().index();
		var productId 		= tableHelper.contentProvider.getData(rowIndex, 'product', 1);
		var qty 			= Number(tableHelper.contentProvider.getData(rowIndex, 'qty').replace(/\,/gi,""));
		var memo 			= $.sanitize(tableHelper.contentProvider.getData(rowIndex, 'memo'));
		var price 			= Number(tableHelper.contentProvider.getData(rowIndex, 'price').replace(/\,/gi,""));
		var sales_detail_id = tableHelper.contentProvider.getData(rowIndex, 'id');
		var reservation_detail_id = Number(tableHelper.contentProvider.getData(rowIndex, 'reservationid'));
		var description 	= (tableHelper.contentProvider.getData(rowIndex, 'product', 4));
		var actionFunction 	= sales_detail_id != 0 ? "update_sales_detail" : "insert_sales_detail";

		var arr = 	{ 
						fnc 	 	: actionFunction, 
						product_id 	: productId,
						qty     	: qty,
						memo 		: memo,
						detail_id 	: sales_detail_id,
						reservation_detail_id : reservation_detail_id,
						price 		: price,
						description : description
					};

		return arr;
	}

	function computeSummary()
	{
		var isVatable = $('#is-vatable').val() == Tax.Nonvat ? false : true;
		var totalAmount = 0;
		var vatAmount = 0;
		var vatableAmount = 0;

		for (var i = 1; i < myjstbl.get_row_count(); i++) 
			totalAmount += Number(tableHelper.contentProvider.getData(i, 'amount').replace(/\,/gi,""));

		vatAmount 		= (isVatable) ? ((totalAmount * 0.12) / 1.12) : 0;
		vatableAmount 	= (isVatable) ? (totalAmount - vatAmount) : 0;

		$('#vatable').html(vatableAmount.formatMoney(2));
		$('#vat-amount').html(vatAmount.formatMoney(2));
		$('#vat-inclusive').html(totalAmount.formatMoney(2));
		$('#less-vat').html(vatAmount.formatMoney(2));
		$('#total').html(vatableAmount.formatMoney(2));
		$('#amount-due').html(totalAmount.formatMoney(2));
	}

	function toggleColumn()
	{
		$('#dynamic-css').html('');
		$('#dynamic-css').html("<style> .tddeliveredqty{ display:none; } </style>");
	}
</script>