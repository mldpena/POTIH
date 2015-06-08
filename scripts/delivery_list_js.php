<script type="text/javascript">
	/**
	 * Initialization of global variables
	 * @flag {Number} - To prevent spam request
	 * @global_delivery_id {Number} - Holder of stock delivery detail id for delete modal
	 * @global_row_index {Number} - Holder of stock delivery detail row index for delete modal
	 * @token_val {String} - Token for CSRF Protection
	 */
	
	var flag = 0;
	var global_delivery_id = 0;
	var global_row_index = 0;
	var token_val = '<?= $token ?>';

	/**
	 * Initialization for JS table stock delivery list
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

    var spntype = document.createElement('span');
	colarray['type'] = { 
        header_title: "Type",
        edit: [spntype],
        disp: [spntype],
        td_class: "tablerow column_click column_hover tdtype"
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
	$('#from_branch, #to_branch').chosen();
	$('#date_from, #date_to').datepicker();
	$('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );
	$('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );
	$('#date_from, #date_to').datepicker("setDate", new Date());

	refresh_table();

	bind_asc_desc('order_type');

	$('.tddelete').live('click',function(){
		global_row_index 	= $(this).parent().index();
		global_delivery_id 	= table_get_column_data(global_row_index,'id');

		$('#deleteStockDeliveryModal').modal('show');
	});

	$('#search').click(function(){
    	refresh_table();
    });

 	$('#delete').click(function(){
		if (flag == 1) 
			return;

		flag = 1;

		var row_index 		= global_row_index;
		var delivery_id_val = global_delivery_id;

		var arr = 	{ 
						fnc 	 	: 'delete_stock_delivery_head', 
						delivery_head_id : delivery_id_val
					};
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_2',response.error,'danger');
				else
				{
					myjstbl.delete_row(row_index);
					recompute_row_count(myjstbl,colarray);

					if (myjstbl.get_row_count() == 1) 
						$('#tbl').hide();

					$('#deleteStockDeliveryModal').modal('hide');

					build_message_box('messagebox_1','Stock Delivery entry successfully deleted!','success');
				}

				flag = 0;
			}       
		});
	});

	$('.column_click').live('click',function(){
		
		var row_index 	= $(this).parent().index();
		var delivery_id = table_get_column_data(row_index,'id');

		window.open('<?= base_url() ?>delivery/view/' + delivery_id);
	});

	$('#create_new').click(function(){
		if (flag == 1) 
			return;

		flag = 1;

		var arr = 	{ 
						fnc : 'create_reference_number'
					};
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_1',response.error,'danger');
				else
					window.location = '<?= base_url() ?>delivery/view/'+response.id;

				flag = 0;
			}       
		});
	});

	$('#deleteStockDeliveryModal').live('hidden.bs.modal', function (e) {
		global_row_index 	= 0;
		global_delivery_id 	= 0;
	});

	function refresh_table()
	{
		if (flag == 1) 
			return;

		flag = 1;

		var search_val 		= $('#search_string').val();
		var order_val 		= $('#order_by').val();
		var orde_type_val 	= $('#order_type').val();
		var date_from_val 	= $('#date_from').val();
		var date_to_val 	= $('#date_to').val();
		var from_branch_val = $('#from_branch').val();
		var to_branch_val 	= $('#to_branch').val();
		var status_val 		= $('#status').val();
		var type_val 		= $('#delivery_type').val();

		var arr = 	{ 
						fnc 	 		: 'search_stock_delivery_list', 
						search_string 	: search_val,
						order_by  		: order_val,
						order_type 		: orde_type_val,
						date_from		: date_from_val,
						date_to 		: date_to_val,
						from_branch 	: from_branch_val,
						to_branch 		: to_branch_val,
						status 			: status_val,
						type 			: type_val
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
					$('#tbl').hide();
					build_message_box('messagebox_1','No stock delivery found!','info');
				}
				else
				{
					if(response.rowcnt <= 10)
						myjstbl.mypage.set_last_page(1);
					else
						myjstbl.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(myjstbl.mypage.filter_number)));

					myjstbl.insert_multiplerow_with_value(1,response.data);

					$('#tbl').show();
				}

				$('#loadingimg').hide();
				
				flag = 0;
			}       
		});
	}
</script>