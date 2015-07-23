<script type="text/javascript">
	var token = '<?= $token ?>';

	var tab = document.createElement('table');
	tab.className = "tblstyle";
	tab.id = "tableid";
	tab.setAttribute("style","border-collapse:collapse;");
	tab.setAttribute("class","border-collapse:collapse;");
    
    var colarray = [];
	
	var chkchecktransfer = document.createElement('input');
    chkchecktransfer.setAttribute('type','checkbox');
    chkchecktransfer.setAttribute('class','chkforprint');

	colarray['print'] = { 
        header_title: "",
        edit: [chkchecktransfer],
        disp: [chkchecktransfer],
        td_class: "tablerow tdview tdistransfer",
		headertd_class : "tdview tdistransfer"
    };

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

    var spncustomer = document.createElement('span');
	colarray['customer'] = { 
        header_title: "Customer",
        edit: [spncustomer],
        disp: [spncustomer],
        td_class: "tablerow column_click column_hover tdcustomer"
    };

    var spnmemo = document.createElement('span');
	colarray['memo'] = { 
        header_title: "Memo",
        edit: [spnmemo],
        disp: [spnmemo],
        td_class: "tablerow column_click column_hover tddate"
    };
  	
  	var spnstatus = document.createElement('span');
	colarray['status'] = { 
        header_title: "Status",
        edit: [spnstatus],
        disp: [spnstatus],
        td_class: "tablerow column_click column_hover tdstatus"
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
	$('#branch_list').chosen();
	$('#date_from, #date_to').datepicker();
	$('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );
	$('#date_from, #date_to').datepicker("setDate", new Date());

	bind_asc_desc('order_type');

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ notFoundMessage : 'No pick-up summary entry found!' });

	tableHelper.headContent.bindSearchEvent(getSearchFilter);
	tableHelper.contentHelper.refreshTable(getSearchFilter);

	$('#print').click(function(){
		var idList = [];

		$('.chkforprint:checked').each(function(index,element){
			var rowIndex = $(this).parent().parent().index();
			var rowId = tableHelper.contentProvider.getData(rowIndex,'id');
			idList.push(rowId);
		});

		if (idList.length == 0)
		{
			alert('Please select at least one entry!');
			return;
		}

		var arr = 	{ 
						fnc : 'set_session_pickup',
					 	release_id : idList
					}

		$.ajax({
            type: "POST",
            dataType : 'JSON',
            data: 'data=' + JSON.stringify(arr) + token,
            success: function(data) {
                window.open('<?= base_url() ?>printout/pickup/Pickup');
            }
        });

	});

	function getSearchFilter()
	{
		var search_val 		= $('#search_string').val();
		var order_val 		= $('#order_by').val();
		var orde_type_val 	= $('#order_type').val();
		var date_from_val 	= $('#date_from').val();
		var date_to_val 	= $('#date_to').val();
		var branch_val 		= $('#branch_list').val();

		var arr = 	{ 
						fnc 	 		: 'get_pickup_summary', 
						search_string 	: search_val,
						order_by  		: order_val,
						order_type 		: orde_type_val,
						date_from		: date_from_val,
						date_to 		: date_to_val,
						branch 			: branch_val
					};

		return arr;
	}

</script>