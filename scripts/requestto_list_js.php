<script type="text/javascript">

	var token = '<?= $token ?>';
	var fromNotication = Number('<?= $this->uri->segment(3) ?>');

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

    var spnreferencenumber = document.createElement('span');
	colarray['referencenumber'] = { 
        header_title: "Reference #",
        edit: [spnreferencenumber],
        disp: [spnreferencenumber],
        td_class: "tablerow column_click column_hover tdreference"
    };

    var spnfrombranch = document.createElement('span');
	colarray['frombranch'] = { 
        header_title: "From Branch",
        edit: [spnfrombranch],
        disp: [spnfrombranch],
        td_class: "tablerow column_click column_hover tdfrombranch"
    };

    var spntobranch = document.createElement('span');
	colarray['tobranch'] = { 
        header_title: "To Branch",
        edit: [spntobranch],
        disp: [spntobranch],
        td_class: "tablerow column_click column_hover tdtobranch"
    };
   	
   	var spndate = document.createElement('span');
	colarray['date'] = { 
        header_title: "Entry Date",
        edit: [spndate],
        disp: [spndate],
        td_class: "tablerow column_click column_hover tddate"
    };

    var spnduedate = document.createElement('span');
	colarray['duedate'] = { 
        header_title: "Due Date",
        edit: [spnduedate],
        disp: [spnduedate],
        td_class: "tablerow column_click column_hover tdduedate"
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
        td_class: "tablerow column_click column_hover tdstatus"
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
	$('#from_branch, #to_branch').select2();
	$('#date_from, #date_to').datepicker();
	$('#date_from, #date_to').datepicker("option","dateFormat", "mm-dd-yy");
	$('#date_from, #date_to').datepicker("setDate", new Date());

	if (fromNotication != 0) 
	{
		var status = fromNotication == NotificationType.NoDelivery ? 3 : 1;
		$('#date_from').val('');
		$('#status').val(status);
	}
	
	bind_asc_desc('order_type');

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", 
										  controller : 'requestto',
										  notFoundMessage : 'No item request entry found!',
										  permissions : { allow_to_view : Boolean(<?= $permission_list['allow_to_view_detail'] ?>) } 
										});

	tableHelper.headContent.bindAllEvents( { searchEventsBeforeCallback : triggerSearchRequest, 
											deleteEventsAfterCallback : actionAfterDelete } );

	triggerSearchRequest();

	function triggerSearchRequest(rowStart, rowEnd)
	{
		var search_val 		= $('#search_string').val();
		var order_val 		= $('#order_by').val();
		var orde_type_val 	= $('#order_type').val();
		var date_from_val 	= $('#date_from').val() != '' ? moment($('#date_from').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var date_to_val 	= $('#date_to').val() != '' ? moment($('#date_to').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var from_branch_val = $('#from_branch').val();
		var to_branch_val 	= $('#to_branch').val();
		var status_val 		= $('#status').val();

		if((typeof rowStart === 'undefined') && (typeof rowEnd === 'undefined'))
			myjstbl.clear_table();
		else
			myjstbl.clean_table();

		var filterResetValue = (typeof rowStart === 'undefined') ? 1 : 0;
		var rowStartValue = (typeof rowStart === 'undefined') ? 0 : rowStart;
		var rowEndValue = (typeof rowEnd === 'undefined') ? (myjstbl.mypage.mysql_interval-1) : rowEnd;
		
		var objectValues = 	{ 
								fnc 	 		: 'search_request_to_list', 
								search_string 	: search_val,
								order_by  		: order_val,
								order_type 		: orde_type_val,
								date_from		: date_from_val,
								date_to 		: date_to_val,
								from_branch 	: from_branch_val,
								to_branch 		: to_branch_val,
								status 			: status_val,
								notification 	: fromNotication,
								filter_reset 	: filterResetValue,
								row_start 		: rowStartValue,
								row_end 		: rowEndValue
							};

		tableHelper.contentHelper.refreshTableWithLimit(objectValues);
	}

	function actionAfterDelete()
	{
		triggerSearchRequest();
		build_message_box('messagebox_1','Item Request successfully deleted!','success');
	}

</script>