<script type="text/javascript">
	/**
	 * Initialization of global variables
	 * @flag {Number} - To prevent spam request
	 * @token_val {String} - Token for CSRF Protection
	 */
	
	var flag = 0;
	var token_val = '<?= $token ?>';

	/**
	 * Initialization for JS table details
	 */

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

	var chkadjust = document.createElement('input');
	chkadjust.setAttribute('type','checkbox');
	chkadjust.setAttribute('class','chkadjust');
	colarray['checkbox'] = { 
        header_title: "",
        edit: [chkadjust],
        disp: [chkadjust],
        td_class: "tablerow tdchkadjust"
    };

    var spnnumber = document.createElement('span');
	colarray['number'] = { 
        header_title: "",
        edit: [spnnumber],
        disp: [spnnumber],
        td_class: "tablerow tdnumber"
    };

    var spnmaterialcode = document.createElement('span');
	colarray['material_code'] = { 
        header_title: "Material Code",
        edit: [spnmaterialcode],
        disp: [spnmaterialcode],
        td_class: "tablerow column_click column_hover tdmaterial"
    };

	var spnproduct = document.createElement('span');
	colarray['name'] = { 
        header_title: "Product",
        edit: [spnproduct],
        disp: [spnproduct],
        td_class: "tablerow column_click column_hover tdproduct"
    };

	var spntype = document.createElement('span');
	colarray['type'] = { 
        header_title: "Type",
        edit: [spntype],
        disp: [spntype],
        td_class: "tablerow column_click column_hover tdtype"
    };

    var spnfrombranch = document.createElement('span');
	colarray['frombranch'] = { 
        header_title: "From Branch",
        edit: [spnfrombranch],
        disp: [spnfrombranch],
        td_class: "tablerow column_click column_hover tdfrombranch"
    };

    var spndate = document.createElement('span');
	colarray['date'] = { 
        header_title: "Date",
        edit: [spndate],
        disp: [spndate],
        td_class: "tablerow column_click column_hover tddate"
    };

    var spnoldinv = document.createElement('span');
	colarray['oldinv'] = { 
        header_title: "Old Inv",
        edit: [spnoldinv],
        disp: [spnoldinv],
        td_class: "tablerow column_click column_hover tdoldinv"
    };

    var spninv = document.createElement('span');
	colarray['inv'] = { 
        header_title: "Inv",
        edit: [spninv],
        disp: [spninv],
        td_class: "tablerow column_click column_hover tdinv"
    };

    var spnnewinv = document.createElement('span');
	colarray['newinv'] = { 
        header_title: "Req Inv",
        edit: [spnnewinv],
        disp: [spnnewinv],
        td_class: "tablerow column_click column_hover tdnewinv"
    };

    var spnstatus = document.createElement('span');
	colarray['status'] = { 
        header_title: "Status",
        edit: [spnstatus],
        disp: [spnstatus],
        td_class: "tablerow column_click column_hover tdstatus"
    };

    var spnmemo = document.createElement('span');
	colarray['memo'] = { 
        header_title: "Memo",
        edit: [spnmemo],
        disp: [spnmemo],
        td_class: "tablerow column_click column_hover tdmemo"
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

	var tableHelper = new TABLE.EventHelper({ tableObject : myjstbl, tableArray : colarray});

	$('#tbl, #action-button').hide();
	//Call refresh function after loading the page
	

	$('#date_from, #date_to').datepicker();
    $('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );
    $('#date_from, #date_to').datepicker("setDate", new Date());

    refresh_table();

    //Event for calling search function
    $('#search').click(function(){
    	refresh_table();
    });

    $('#approve, #decline').click(function(){
    	if (flag == 1) 
    		return;

    	var action_val = $(this).attr('id');
    	var adjust_id_list_val = [];

    	$(".chkadjust:checked").each(function () {
	        var self = $(this);
	        var row_index =  $(this).parent().parent().index();

	        var adjust_id = tableHelper.getData(row_index,'id');

	        tableHelper.setData(row_index,'status',[$.ucfirst(action_val) + 'd']);
	        
	        adjust_id_list_val.push(adjust_id);
    	});

    	var arr = 	{ 
						fnc 	: 'update_request_status', 
						action  : action_val,
						adjust_id_list  : adjust_id_list_val
					};

		flag = 1;

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				if (response.error != '') 
					build_message_box('messagebox_1',response.error,'danger');
				else
				{
					flag = 0;
					build_message_box('messagebox_1','Inventory adjust request successfully ' + action_val + 'd!','success');
				}

				flag = 0;
			}       
		});
    });

    //Function for refreshing table content
	function refresh_table()
	{
		if (flag == 1)
			return;

		flag = 1;

		var itemcode_val 	= $('#itemcode').val();
		var product_val 	= $('#product').val();
		var subgroup_val 	= $('#subgroup').val();
		var type_val 		= $('#type').val();
		var material_val	= $('#material').val();
		var datefrom_val	= $('#date_from').val();
		var dateto_val		= $('#date_to').val();
		var branch_val		= $('#branch').val();
		var orderby_val		= $('#orderby').val();
		var status_val		= $('#status').val();

		var arr = 	{ 
						fnc 	 : 'get_pending_adjust_list', 
						code 	 : itemcode_val,
						product  : product_val,
						subgroup : subgroup_val,
						type 	 : type_val,
						material : material_val,
						datefrom : datefrom_val,
						dateto 	 : dateto_val,
						branch 	 : branch_val,
						orderby  : orderby_val,
						status 	 : status_val
					};

		$('#loadingimg').show();

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				myjstbl.clear_table();
				clear_message_box();

				if (response.rowcnt == 0) 
				{
					$('#tbl, #action-button').hide();
					build_message_box('messagebox_1','No inventory adjust found!','info');
				}
				else
				{
					if(response.rowcnt <= 10)
						myjstbl.mypage.set_last_page(1);
					else
						myjstbl.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(myjstbl.mypage.filter_number)));

					myjstbl.insert_multiplerow_with_value(1,response.data);

					$('#tbl, #action-button').show();
				}

				$('#loadingimg').hide();

				flag = 0;
			}       
		});
	}
</script>