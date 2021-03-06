<script type="text/javascript">
	var token = '<?= $token ?>';

	var tab = document.createElement('table');
	tab.className = "tblstyle";
	tab.id = "tableid";
	tab.setAttribute("style","border-collapse:collapse;");
	tab.setAttribute("class","border-collapse:collapse;");
	
	var colarray = [];

	var tableColumns = [{ headerName : '', className : 'number' },
						{ headerName : 'Material Code', className : 'material' },
						{ headerName : 'Product', className : 'name' },
						{ headerName : 'Type', className : 'type' },
						{ headerName : 'Beginning Inventory', className : 'beginv' },
						{ headerName : 'Purchase Receive', className : 'purchasereceive' },
						{ headerName : 'Customer Return', className : 'customereturn' },
						{ headerName : 'Item Receive', className : 'stockreceive' },
						{ headerName : 'Adjust Increase', className : 'adjustinc' },
						{ headerName : 'Damage', className : 'damage' },
						{ headerName : 'Purchase Return', className : 'purchasereturn' },
						{ headerName : 'Item Delivery', className : 'stockdelivery' },
						{ headerName : 'Customer Delivery', className : 'customerdelivery' },
						{ headerName : 'Adjust Decrease', className : 'adjustdec' },
						{ headerName : 'Warehouse Release', className : 'release' },
						{ headerName : 'Total Inventory', className : 'totalinv' },
						{ headerName : 'Sales Reservation', className : 'reservation' }];

	for (var i = 0; i < tableColumns.length; i++) 
	{
		var spntransaction = document.createElement('span');
		colarray[tableColumns[i].className] = { 
			header_title: tableColumns[i].headerName,
			edit: [spntransaction],
			disp: [spntransaction],
			td_class: "tablerow column_click column_hover td" + tableColumns[i].className
		};
	};

	var myjstbl;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, { ispaging : true, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);
	root.appendChild(myjstbl.mypage.pagingtable);

	myjstbl.mypage.set_mysql_interval(100);
	myjstbl.mypage.isOldPaging = true;
	myjstbl.mypage.pass_refresh_filter_page(triggerSearchRequest);
				
	$('#tbl').hide();

	$('#date_from, #date_to').datepicker();
	$('#date_from, #date_to').datepicker("option","dateFormat", "mm-dd-yy");

	$("#show-adv-info").click(function(){
		var display = $("#show-info").css("display");	
		if(display == "block")
			$(this).attr('value', 'Show Advanced Info');
		else
			$(this).attr('value', 'Hide Advanced Info');
		
		$("#show-info").toggle("fast");
	});

	$('#is_include_date').click(function(){
		if ($(this).is(':checked')) 
			$('#date_from, #date_to').removeAttr('disabled');
		else
			$('#date_from, #date_to').attr('disabled','disabled');
	});

	$('.transaction').click(function(){
		var elementAllClass = $(this).attr('class');
		var specificClass = elementAllClass.split(' ');

		if ($(this).is(':checked'))
		{
			$('.' + specificClass[1]).removeAttr('checked');
			$(this).attr('checked','checked');	
		}
		
	});

	var tableHelper = new TableHelper({ tableObject : myjstbl, tableArray : colarray, notFoundMessage : 'No product found!' });
	tableHelper.headContent.bindSearchEvent(triggerSearchRequest);
	
	triggerSearchRequest();

	$('#export').click(function () {
		var filterValues = getSearchFilterValues();

		filterValues.fnc = "product_transaction";

		var queryString = $.objectToQueryString(filterValues);

		window.open("<?= base_url() ?>export?" + queryString);
	});

	function getSearchFilterValues()
	{
		var itemcode_val    = $('#itemcode').val();
		var product_val     = $('#product').val();
		var subgroup_val    = $('#subgroup').val();
		var type_val        = $('#type').val();
		var material_val    = $('#material').val();
		var branch_val      = $('#branch').val();
		var orderby_val     = $('#orderby').val();
		var is_include_date = $('#is_include_date').is(':checked') ? true : false;
		var date_from_val 	= $('#date_from').val() != '' ? moment($('#date_from').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var date_to_val 	= $('#date_to').val() != '' ? moment($('#date_to').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var purchase_receive_val 	= $('.purchase-receive-transaction:checked').val() === undefined ? 0 : $('.purchase-receive-transaction:checked').val();
		var customer_return_val 	= $('.customer-return-transaction:checked').val() === undefined ? 0 : $('.customer-return-transaction:checked').val();
		var stock_receive_val 		= $('.stock-receive-transaction:checked').val() === undefined ? 0 : $('.stock-receive-transaction:checked').val();
		var adjust_increase_val 	= $('.adjust-increase-transaction:checked').val() === undefined ? 0 : $('.adjust-increase-transaction:checked').val();
		var damage_val 				= $('.damage-transaction:checked').val() === undefined ? 0 : $('.damage-transaction:checked').val();
		var purchase_return_val 	= $('.purchase-return-transaction:checked').val() === undefined ? 0 : $('.purchase-return-transaction:checked').val();
		var stock_delivery_val 		= $('.stock-delivery-transaction:checked').val() === undefined ? 0 : $('.stock-delivery-transaction:checked').val();
		var customer_delivery_val 	= $('.customer-delivery-transaction:checked').val() === undefined ? 0 : $('.customer-delivery-transaction:checked').val();
		var adjust_decrease_val 	= $('.adjust-decrease-transaction:checked').val() === undefined ? 0 : $('.adjust-decrease-transaction:checked').val();
		var release_val 			= $('.release-transaction:checked').val() === undefined ? 0 : $('.release-transaction:checked').val();

		var filterValues =   { 
								fnc      : 'get_transaction_list', 
								code     : itemcode_val,
								product  : product_val,
								subgroup : subgroup_val,
								type     : type_val,
								material : material_val,
								branch   : branch_val,
								orderby  : orderby_val,
								is_include_date : is_include_date,
								date_from : date_from_val,
								date_to : date_to_val,
								purchase_receive : purchase_receive_val,
								customer_return : customer_return_val,
								stock_receive : stock_receive_val,
								adjust_increase : adjust_increase_val,
								damage : damage_val,
								purchase_return : purchase_return_val,
								stock_delivery : stock_delivery_val,
								customer_delivery : customer_delivery_val,
								adjust_decrease : adjust_decrease_val,
								release : release_val
							};

		return filterValues;
	}

	function triggerSearchRequest(rowStart, rowEnd)
	{
		if((typeof rowStart === 'undefined') && (typeof rowEnd === 'undefined'))
			myjstbl.clear_table();
		else
			myjstbl.clean_table();

		var filterResetValue = (typeof rowStart === 'undefined') ? 1 : 0;
		var rowStartValue = (typeof rowStart === 'undefined') ? 0 : rowStart;
		var rowEndValue = (typeof rowEnd === 'undefined') ? (myjstbl.mypage.mysql_interval-1) : rowEnd;

		var paginationRowValues =	{
										filter_reset : filterResetValue,
										row_start : rowStartValue,
										row_end : rowEndValue
									}

		var filterValues = getSearchFilterValues();

		$.extend(filterValues, paginationRowValues);

		tableHelper.contentHelper.refreshTableWithLimit(filterValues);
	}
</script>