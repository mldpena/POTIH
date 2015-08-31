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

    var spnlocation = document.createElement('span');
	colarray['location'] = { 
        header_title: "Location",
        edit: [spnlocation],
        disp: [spnlocation],
        td_class: "tablerow column_click column_hover tdlocation"
    };

    var spnforbranch = document.createElement('span');
	colarray['forbranch'] = { 
        header_title: "For Branch",
        edit: [spnforbranch],
        disp: [spnforbranch],
        td_class: "tablerow column_click column_hover tdforbranch"
    };

    var spnreferencenumber = document.createElement('span');
	colarray['referencenumber'] = { 
        header_title: "Reference #",
        edit: [spnreferencenumber],
        disp: [spnreferencenumber],
        td_class: "tablerow column_click column_hover tdreference"
    };
    
    var spntype = document.createElement('span');
	colarray['type'] = { 
        header_title: "Type",
        edit: [spntype],
        disp: [spntype],
        td_class: "tablerow column_click column_hover tdtype"
    };
   	
   	var spndate = document.createElement('span');
	colarray['date'] = { 
        header_title: "PO Date",
        edit: [spndate],
        disp: [spndate],
        td_class: "tablerow column_click column_hover tddate"
    };

    var spnsupplier= document.createElement('span');
	colarray['supplier'] = { 
        header_title: "Supplier",
        edit: [spnsupplier],
        disp: [spnsupplier],
        td_class: "tablerow column_click column_hover tdsupplier"
    };

    var spnmemo = document.createElement('span');
	colarray['memo'] = { 
        header_title: "Memo",
        edit: [spnmemo],
        disp: [spnmemo],
        td_class: "tablerow column_click column_hover tdmemo"
    };
    
    var spntotalqty = document.createElement('span');
	colarray['total_qty'] = { 
        header_title: "Total Qty",
        edit: [spntotalqty],
        disp: [spntotalqty],
        td_class: "tablerow column_click column_hover tdtotalqty"
    };

    var spnstatus = document.createElement('span');
	colarray['status'] = { 
        header_title: "Status",
        edit: [spnstatus],
        disp: [spnstatus],
        td_class: "tablerow column_click column_hover status"
    };

    var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");

	<?php if (!$permission_list['allow_to_delete']) : ?>

	imgDelete.setAttribute("style","display:none;");

	<?php endif; ?>

	colarray['coldelete'] = { 
		header_title: "",
		edit: [imgDelete],
		disp: [imgDelete],
		td_class: "tablerow column_hover tddelete"
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
	$('#branch_list, #for_branch').chosen();
	$('#date_from, #date_to').datepicker();
	$('#date_from, #date_to').datepicker("option","dateFormat", "mm-dd-yy" );
	$('#date_from, #date_to').datepicker("setDate", new Date());

	bind_asc_desc('order_type');

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", 
										  controller : 'purchase',
										  notFoundMessage : 'No purchase order entry found!',
										  permissions : { allow_to_view : Boolean(<?= $permission_list['allow_to_view_detail'] ?>) } 
										});

	tableHelper.headContent.bindAllEvents( { searchEventsBeforeCallback : triggerSearchRequest, 
											deleteEventsAfterCallback : actionAfterDelete } );

	triggerSearchRequest();
	

	$('#export').click(function () {
		var filterValues = getSearchFilterValues();

		filterValues.fnc = "purchase_transaction";

		var queryString = $.objectToQueryString(filterValues);

		window.open("<?= base_url() ?>export?" + queryString);
	});
		
	function getSearchFilterValues()
	{
		var search_val 		= $('#search_string').val();
		var order_val 		= $('#order_by').val();
		var orde_type_val 	= $('#order_type').val();
		var date_from_val 	= moment($('#date_from').val(),'MM-DD-YYYY').format('YYYY-MM-DD');
		var date_to_val 	= moment($('#date_to').val(),'MM-DD-YYYY').format('YYYY-MM-DD');
		var branch_val 		= $('#branch_list').val();
		var status_val 		= $('#status').val();
		var type_val 		= $('#type').val();
		var for_branch_val  = $('#for_branch').val();

		var filterValues = 	{ 
								fnc 	 		: 'search_purchaseorder_list', 
								search_string 	: search_val,
								order_by  		: order_val,
								order_type 		: orde_type_val,
								date_from		: date_from_val,
								date_to 		: date_to_val,
								branch 			: branch_val,
								for_branch 		: for_branch_val,
								status 			: status_val,
								type 			: type_val
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

		var paginationRowValues = 	{ 
										filter_reset : filterResetValue,
										row_start : rowStartValue,
										row_end : rowEndValue
									};

		var filterValues = getSearchFilterValues();

		$.extend(filterValues, paginationRowValues);

		tableHelper.contentHelper.refreshTableWithLimit(filterValues);
	}

	function actionAfterDelete()
	{
		triggerSearchRequest();
		build_message_box('messagebox_1','Purchase Order entry successfully deleted!','success');
	}

</script>