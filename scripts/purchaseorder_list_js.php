<script type="text/javascript">
	var flag = 0;
	var global_purchase_id = 0;
	var global_row_index = 0;

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
   	
   	var spndate = document.createElement('span');
	colarray['date'] = { 
        header_title: "Entry Date",
        edit: [spndate],
        disp: [spndate],
        td_class: "tablerow column_click column_hover tddate"
    };

    var spnforbranch = document.createElement('span');
	colarray['supplier'] = { 
        header_title: "Supplier",
        edit: [spnforbranch],
        disp: [spnforbranch],
        td_class: "tablerow column_click column_hover tdforbranch"
    };

    var spnmemo = document.createElement('span');
	colarray['memo'] = { 
        header_title: "Memo",
        edit: [spnmemo],
        disp: [spnmemo],
        td_class: "tablerow column_click column_hover tddate"
    };
    var spntotalqty = document.createElement('span');
	colarray['total_qty'] = { 
        header_title: "Total qty",
        edit: [spntotalqty],
        disp: [spntotalqty],
        td_class: "tablerow column_click column_hover tdtotalqty"
    };

   	var spnqtyremain = document.createElement('span');
	colarray['qty_remaining'] = { 
        header_title: "Qty Remaining",
        edit: [spnqtyremain],
        disp: [spnqtyremain],
        td_class: "tablerow column_click column_hover tdqtyremain"
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
	$('#branch_list').chosen();
	$('#for_branch').chosen();
	$('#date_from, #date_to').datepicker();
	$('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );
	$('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );
	$('#date_from, #date_to').datepicker("setDate", new Date());

	refresh_table();
	bind_asc_desc('order_type');

	$('.tddelete').live('click',function(){
		global_row_index 	= $(this).parent().index();
		global_purchase_id 	= myjstbl.getvalue_by_rowindex_tdclass(global_row_index, colarray["id"].td_class)[0];

		$('#deletePurchaseOrderModal').modal('show');
	});

	$('#search').click(function(){
    	refresh_table();
    });

 	$('#delete').click(function(){
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
		var row_index 		= global_row_index;
		var purchase_id_val 	= global_purchase_id;

		var arr = 	{ 
						fnc 	 	: 'delete_purchaseorder_head', 
						purchase_id 	: purchase_id_val
					};
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
				{
					build_message_box('messagebox_2',response.error,'danger');
				}
				else
				{
					myjstbl.delete_row(row_index);
					recompute_row_count(myjstbl,colarray);

					if (myjstbl.get_row_count() == 1) 
					{
						$('#tbl').hide();
					}

					$('#deletePurchaseOrderModal').modal('hide');

					build_message_box('messagebox_1','Purchase Order entry successfully deleted!','success');
				}

				flag = 0;
			}       
		});
	});

	$('.column_click').live('click',function(){
		var row_index 	= $(this).parent().index();
		var purchase_id 	= myjstbl.getvalue_by_rowindex_tdclass(row_index,colarray['id'].td_class)[0];

		window.open('<?= base_url() ?>purchaseorder/view/' + purchase_id);
	});

	$('#deletePurchaseOrderModal').live('hidden.bs.modal', function (e) {
		global_row_index 	= 0;
		global_purchase_id 	= 0;
	});

	function refresh_table()
	{
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
		var search_val 		= $('#search_string').val();
		var order_val 		= $('#order_by').val();
		var orde_type_val 	= $('#order_type').val();
		var date_from_val 	= $('#date_from').val();
		var date_to_val 	= $('#date_to').val();
		var branch_val 		= $('#branch_list').val();
		var for_branch_val		= $('#for_branch').val();

		var arr = 	{ 
						fnc 	 		: 'search_purchaseorder_list', 
						search_string 	: search_val,
						order_by  		: order_val,
						order_type 		: orde_type_val,
						date_from		: date_from_val,
						date_to 		: date_to_val,
						branch 			: branch_val,
						for_branch 		:for_branch_val	
						
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
					build_message_box('messagebox_1','No purchase order found!','info');
				}
				else
				{
					if(response.rowcnt <= 10)
					{
						myjstbl.mypage.set_last_page(1);
					}
					else
					{
						myjstbl.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(myjstbl.mypage.filter_number)));
					}

					myjstbl.insert_multiplerow_with_value(1,response.data);

					$('#tbl').show();
				}

				$('#loadingimg').hide();
				
				flag = 0;
			}       
		});
	}
</script>