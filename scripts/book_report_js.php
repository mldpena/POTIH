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

    var spndate = document.createElement('span');
	colarray['date'] = { 
        header_title: "Date",
        edit: [spndate],
        disp: [spndate],
        td_class: "tablerow column_click column_hover tddate",
        headertd_class : "tddate"
    };

    var spnreferencenumber = document.createElement('span');
	colarray['referencenumber'] = { 
        header_title: "Invoice No.",
        edit: [spnreferencenumber],
        disp: [spnreferencenumber],
        td_class: "tablerow column_click column_hover tdreference"
    };
    
    var spncustomer = document.createElement('span');
	colarray['customer'] = { 
        header_title: "Company Name",
        edit: [spncustomer],
        disp: [spncustomer],
        td_class: "tablerow column_click column_hover tdcustomer",
        headertd_class : "tdcustomer"
    };
   	
   	var spninvoiceamount = document.createElement('span');
	colarray['invoice_amount'] = { 
        header_title: "Invoice Amount",
        edit: [spninvoiceamount],
        disp: [spninvoiceamount],
        td_class: "tablerow column_click column_hover td-right td-invoice-amount"
    };

    var spnvatableamount = document.createElement('span');
	colarray['vatable_amount'] = { 
        header_title: "VATable Amount",
        edit: [spnvatableamount],
        disp: [spnvatableamount],
        td_class: "tablerow column_click column_hover td-right td-vatable-amount"
    };

    var spnvatamount = document.createElement('span');
	colarray['vat_amount'] = { 
        header_title: "VAT Amount",
        edit: [spnvatamount],
        disp: [spnvatamount],
        td_class: "tablerow column_click column_hover td-right td-vat-amount"
    };

    var spnvatexemptamount = document.createElement('span');
	colarray['vat_exempt_amount'] = { 
        header_title: "VAT Exempt Amount",
        edit: [spnvatexemptamount],
        disp: [spnvatexemptamount],
        td_class: "tablerow column_click column_hover td-right td-vat-exempt-amount"
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
	$('#for_branch, #customer, #salesman').chosen();
	$('#date_from, #date_to').datepicker();
	$('#date_from, #date_to').datepicker("option","dateFormat", "mm-dd-yy" );
	$('#date_from, #date_to').datepicker("setDate", new Date());

	bind_asc_desc('order_type');

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", 
										  controller : 'sales',
										  token : notificationToken,
										  notFoundMessage : 'No sales book report found!'
										});

	tableHelper.headContent.bindAllEvents({ searchEventsBeforeCallback : triggerSearchRequest });

	triggerSearchRequest();
	
	$('#export').click(function(){

		var filterValues = getSearchFilterValues();

		filterValues.fnc = "book_report";

		var queryString = $.objectToQueryString(filterValues);

		window.open("<?= base_url() ?>export?" + queryString);
	});

	function getSearchFilterValues()
	{
		var date_from_val 	= $('#date_from').val() != '' ? moment($('#date_from').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var date_to_val 	= $('#date_to').val() != '' ? moment($('#date_to').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var customer_val 	= $('#customer').val();
		var for_branch_val  = $('#for_branch').val();
		var branch_name_val = $('#for_branch option:selected').text();
		var salesman_val  	= $('#salesman').val();
		var order_val 		= 1;
		var order_type_val 	= 'ASC';
		var transaction_status_val = 1;

		var filterValues = 	{ 
								fnc 	 		: 'generate_book_report', 
								order_by  		: order_val,
								order_type 		: order_type_val,
								date_from		: date_from_val,
								date_to 		: date_to_val,
								for_branch 		: for_branch_val,
								branch_name 	: branch_name_val,
								customer 		: customer_val,
								salesman 		: salesman_val,
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

		$.extend(filterValues, paginationRowValues);

		tableHelper.contentHelper.refreshTableWithLimit(filterValues, showSummary);
	}

	function showSummary(response)
	{
		if (response.rowcnt == 0)
		{
			$('#tbl-total').hide();
			return;
		}

		$('#tbl-total').show();

		$('#total-invoice-amount').html(response.total_amount);
		$('#total-vatable-amount').html(response.total_vatable_amount);
		$('#total-vat-amount').html(response.total_vat_amount);
		$('#total-vat-exempt-amount').html(response.total_vat_exempt_amount);
	}
</script>