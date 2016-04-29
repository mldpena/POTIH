<script type="text/javascript">

	var tab = document.createElement('table');
	tab.className = "tblstyle";
	tab.id = "tableid";
	tab.setAttribute("style","border-collapse:collapse;");
	tab.setAttribute("class","border-collapse:collapse;");
    
    var colarray = [];

    var spnnumber = document.createElement('span');
	colarray['number'] = { 
        header_title: "",
        edit: [spnnumber],
        disp: [spnnumber],
        td_class: "tablerow tdnumber"
    };

    var spnreferencenumber = document.createElement('span');
	colarray['referencenumber'] = { 
        header_title: "Reference #",
        edit: [spnreferencenumber],
        disp: [spnreferencenumber],
        td_class: "tablerow column_click column_hover tdreference"
    };
    
    var spndate = document.createElement('span');
	colarray['date'] = { 
        header_title: "Date",
        edit: [spndate],
        disp: [spndate],
        td_class: "tablerow column_click column_hover tddate",
        headertd_class : "tddate"
    };

    var spncustomer = document.createElement('span');
	colarray['customer'] = { 
        header_title: "Customer",
        edit: [spncustomer],
        disp: [spncustomer],
        td_class: "tablerow column_click column_hover tdcustomer",
        headertd_class : "tdcustomer"
    };
   	
   	var spnsalesman = document.createElement('span');
	colarray['salesman'] = { 
        header_title: "Salesman",
        edit: [spnsalesman],
        disp: [spnsalesman],
        td_class: "tablerow column_click column_hover tdsalesman"
    };

    var spnamount = document.createElement('span');
	colarray['amount'] = { 
        header_title: "Amount",
        edit: [spnamount],
        disp: [spnamount],
        td_class: "tablerow column_click column_hover tdamount"
    };

	var myjstbl;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : true, 
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
	$('#for_branch').select2();

	$('#customer').select2({
		minimumInputLength: 1,
		maximumSelectionLength: 10
	});
	
	$('#date_from, #date_to').datepicker();
	$('#date_from, #date_to').datepicker("option","dateFormat", "mm-dd-yy" );
	$('#date_from, #date_to').datepicker("setDate", new Date());

	bind_asc_desc('order_type');

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", 
										  controller : 'sales',
										  token : notificationToken,
										  notFoundMessage : 'No sales report found!'
										});

	tableHelper.headContent.bindAllEvents({ searchEventsBeforeCallback : triggerSearchRequest });

	triggerSearchRequest();
	
	$('.export').click(function(){

		var filterValues = getSearchFilterValues();
		var reportType = $('#report-type').val();
		var excelFile = $(this).attr('id');

		filterValues.fnc = "sales_report";
		filterValues.report_type = reportType;
		filterValues.page = excelFile;

		var queryString = $.objectToQueryString(filterValues);

		if (reportType == SalesReportType.Customer && filterValues.customer == 0) 
		{
			alert('Please select a specific customer!');
			return false;
		}

		window.open("<?= base_url() ?>export?" + queryString);
	});

	$('#report-type').change(function(){

		var value = $(this).val();

		$('.export').hide();
		$('.export-option-' + value).show();

	});

	function getSearchFilterValues()
	{
		var date_from_val 	= $('#date_from').val() != '' ? moment($('#date_from').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var date_to_val 	= $('#date_to').val() != '' ? moment($('#date_to').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var customer_val 	= $('#customer').val();
		var for_branch_val  = $('#for_branch').val();
		var order_val 		= 1;
		var order_type_val 	= 'ASC';
		var transaction_status_val = 1;

		var filterValues = 	{ 
								fnc 	 		: 'generate_sales_report', 
								order_by  		: order_val,
								order_type 		: order_type_val,
								date_from		: date_from_val,
								date_to 		: date_to_val,
								for_branch 		: for_branch_val,
								customer 		: customer_val,
								transaction_status : transaction_status_val
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
		var reportType = $('#report-type').val();

		var paginationRowValues = 	{ 
										filter_reset : filterResetValue,
										row_start : rowStartValue,
										row_end : rowEndValue
									};

		var filterValues = getSearchFilterValues();

		if (reportType == SalesReportType.Customer && filterValues.customer == 0) 
		{
			alert('Please select a specific customer!');
			$('#tbl').hide();
			return false;
		}

		$.extend(filterValues, paginationRowValues);

		tableHelper.contentHelper.refreshTableWithLimit(filterValues, showSummary);
	}

	function showSummary(response)
	{
		var reportType = $('#report-type').val();

		if (reportType == SalesReportType.DailySales)
		{
			$('.tdcustomer').show();
			$('.tddate').hide();
		}	
		else if (reportType == SalesReportType.PeriodicSales) 
			$('.tdcustomer, .tddate').show();
		else if (reportType == SalesReportType.Customer) 
		{
			$('.tddate').show();
			$('.tdcustomer').hide();
		}

		if (response.rowcnt > 0) 
		{
			$('#total_amount').html(response.total_amount);
			$('.tbl-total').show();
		}
		else
			$('.tbl-total').hide();
	}
</script>