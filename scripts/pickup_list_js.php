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

	var spnreferencenumber = document.createElement('span');
	colarray['referencenumber'] = { 
        header_title: "Doc #",
        edit: [spnreferencenumber],
        disp: [spnreferencenumber],
        td_class: "tablerow column_click column_hover tdreference"
    };
   	
   	var spndate = document.createElement('span');
	colarray['date'] = { 
        header_title: "Entry Date",
        edit: [spndate],
        disp: [spndate],
        td_class: "tablerow column_click column_hover tddate"
    };
  	
  	var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");
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

	$('#tbl').hide();
	$('#branch_list').select2();
	$('#date_from, #date_to, #date_created').datepicker();
	$('#date_from, #date_to, #date_created').datepicker("option","dateFormat", "mm-dd-yy");
	$('#date_created').datepicker("setDate", new Date());

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ notFoundMessage : 'No pick-up summary entry found!' });

	tableHelper.headContent.bindSearchEvent(triggerSearchRequest);

	tableHelper.headContent.bindDeleteEvents(actionAfterDelete);

	triggerSearchRequest();

	$('#create_summary').click(function(){
		var date_created = $('#date_created').val() != '' ? moment($('#date_created').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';

		var arr = 	{ 
						fnc : 'generate_summary',
						date_created : date_created
					};

		$.ajax({
            type: "POST",
            dataType : 'JSON',
            data: 'data=' + JSON.stringify(arr) + token,
            success: function(response) {
            	if(response.error != '') 
					alert(response.error);
				else
                	triggerSearchRequest();
            }
        });
	});

	$('.column_click').live('click', function(){

		var rowIndex = $(this).parent().index();
		var rowId = tableHelper.contentProvider.getData(rowIndex, 'id');

		var arr = 	{ 
						fnc : 'set_session',
						summary_head_id : rowId 
					}

		$.ajax({
            type: "POST",
            dataType : 'JSON',
            data: 'data=' + JSON.stringify(arr) + token,
            success: function(response) {
            	if(response.error != '') 
					alert(response.error);
				else
                	window.open('<?= base_url() ?>printout/pickup/Pickup');
            }
        });
	});

	function lockEntriesForPreviousDays()
	{
		for (var i = 1; i < myjstbl.get_row_count(); i++) 
		{
			var deleteIconElement = tableHelper.contentProvider.getElement(i,'coldelete');
			var rowEntryDate = tableHelper.contentProvider.getData(i,'date');
			var dateToday = $.datepicker.formatDate('mm-dd-yy', new Date());

			if (dateToday != rowEntryDate) 
				$(deleteIconElement).hide();
		};
	}

	function triggerSearchRequest()
	{
		var search_val 		= $('#search_string').val();
		var branch_val 		= $('#branch_list').val();
		var date_from_val 	= $('#date_from').val() != '' ? moment($('#date_from').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';
		var date_to_val 	= $('#date_to').val() != '' ? moment($('#date_to').val(),'MM-DD-YYYY').format('YYYY-MM-DD') : '';

		var filterValues = 	{ 
								fnc 	 		: 'get_pickup_summary', 
								search_string 	: search_val,
								branch 			: branch_val,
								date_from		: date_from_val,
								date_to 		: date_to_val
							};

		tableHelper.contentHelper.refreshTableWithLimit(filterValues, lockEntriesForPreviousDays);
	}

	function actionAfterDelete()
	{
		triggerSearchRequest();
		build_message_box('messagebox_1','Summary successfully deleted!','success');
	}
</script>