<script type="text/javascript">
	var flag = 0;
	var global_detail_id = 0;
	var global_row_index = 0;
	var token_val = '<?= $token ?>';

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

    var spnpodetailid = document.createElement('span');
	colarray['podetailid'] = { 
        header_title: "",
        edit: [spnpodetailid],
        disp: [spnpodetailid],
        td_class: "tablerow tdid tdpodetailid",
		headertd_class : "tdheader_id"
    };

    var spnnumber = document.createElement('span');
	colarray['number'] = { 
        header_title: "",
        edit: [spnnumber],
        disp: [spnnumber],
        td_class: "tablerow tdnumber"
    };

    var spnponumber = document.createElement('span');
	colarray['ponumber'] = { 
        header_title: "PO Number",
        edit: [spnponumber],
        disp: [spnponumber],
        td_class: "tablerow tdponumber"
    };

    var spnproduct = document.createElement('span');
    var spnproductid = document.createElement('span');
    spnproductid.setAttribute('style','display:none;');
	colarray['product'] = { 
        header_title: "Product",
        edit: [spnproduct,spnproductid],
        disp: [spnproduct,spnproductid],
        td_class: "tablerow column_click column_hover tdproduct"
    };

	var spnmaterialcode = document.createElement('span');
	colarray['code'] = { 
        header_title: "Material Code",
        edit: [spnmaterialcode],
        disp: [spnmaterialcode],
        td_class: "tablerow column_click column_hover tdcode"
    };
   	
   	var spnqty = document.createElement('span');
	colarray['qty'] = { 
        header_title: "Qty",
        edit: [spnqty],
        disp: [spnqty],
        td_class: "tablerow column_click column_hover tdqty"
    };

    var spninventory = document.createElement('span');
	colarray['inventory'] = { 
        header_title: "Inventory",
        edit: [spninventory],
        disp: [spninventory],
        td_class: "tablerow column_click column_hover tdinv"
    };

    var spnmemo = document.createElement('span');
	colarray['memo'] = { 
        header_title: "Remarks",
        edit: [spnmemo],
        disp: [spnmemo],
        td_class: "tablerow column_click column_hover tdmemo"
    };

    var spnqtyrecv = document.createElement('span');
    var txtqtyrecv = document.createElement('input');
    txtqtyrecv.setAttribute('class','form-control txtqtyrecv');
	colarray['qtyrecv'] = { 
        header_title: "Qty Recv",
        edit: [txtqtyrecv],
        disp: [spnqtyrecv],
        td_class: "tablerow column_click column_hover tdqtyrecv"
    };

    var imgUpdate = document.createElement('i');
	imgUpdate.setAttribute("class","imgupdate fa fa-check");
	var imgEdit = document.createElement('i');
	imgEdit.setAttribute("class","imgedit fa fa-pencil");
	colarray['colupdate'] = { 
		header_title: "",
		edit: [imgUpdate],
		disp: [imgEdit],
		td_class: "tablerow column_hover tdupdate"
	};

    var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");
	colarray['coldelete'] = { 
		header_title: "",
		edit: [imgDelete],
		disp: [imgDelete],
		td_class: "tablerow column_hover tddelete"
	};

	var tab_po_list = document.createElement('table');
	tab_po_list.className = "tblstyle";
	tab_po_list.id = "table_polist_id";
	tab_po_list.setAttribute("style","border-collapse:collapse;");
	tab_po_list.setAttribute("class","border-collapse:collapse;");
    
    var colarray_po_list = [];

	var spnpoid = document.createElement('span');
	colarray_po_list['id'] = { 
        header_title: "",
        edit: [spnpoid],
        disp: [spnpoid],
        td_class: "tablerow tdid",
		headertd_class : "tdheader_id"
    };

    var chkdetails = document.createElement('input');
    chkdetails.setAttribute('type','checkbox');
    chkdetails.setAttribute('class','form-control chkdetails');
	colarray_po_list['check'] = { 
        header_title: "",
        edit: [chkdetails],
        disp: [chkdetails],
        td_class: "tablerow tddetails"
    };

    var spnponumber = document.createElement('span');
	colarray_po_list['ponumber'] = { 
        header_title: "PO Number",
        edit: [spnponumber],
        disp: [spnponumber],
        td_class: "tablerow tdponumber"
    };

    var spndate = document.createElement('span');
	colarray_po_list['date'] = { 
        header_title: "PO Date",
        edit: [spndate],
        disp: [spndate],
        td_class: "tablerow tddate"
    };

    var spntotalqty = document.createElement('span');
	colarray_po_list['totalqty'] = { 
        header_title: "Total Qty",
        edit: [spntotalqty],
        disp: [spntotalqty],
        td_class: "tablerow tdtotalqty"
    };

	var myjstbl;
	var myjstbl_po_list;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);

	var root_po_list = document.getElementById("tbl_po");
	myjstbl_po_list = new my_table(tab_po_list, colarray_po_list, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root_po_list.appendChild(myjstbl_po_list.tab);

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date').datepicker();
    	$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
    	$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
    	$('#date').datepicker("setDate", new Date());

		var arr = 	{ 
						fnc : 'get_purchase_receive_details'
					};
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.head_error != '') 
				{
					build_message_box('messagebox_1',response.error,'danger');
				}
				else
				{
					$('#reference_no').val(response.reference_number);
					$('#memo').val(response.memo);

					if (response.entry_date != '') 
					{
						$('#date').val(response.entry_date);
					};
				}
				
				if (response.detail_error == '') 
				{
					myjstbl.insert_multiplerow_with_value(1,response.detail);
				};

				if (response.po_list_error == '') 
				{
					myjstbl_po_list.insert_multiplerow_with_value(1,response.po_lists);
				};
			}       
		});
	}
	else
	{
		$('input, textarea').attr('disabled','disabled');
	}

	$('.chkdetails').live('click',function(){
		if (flag == 1) { return };
		flag = 1;

		var row_index = $(this).parent().parent().index();
		var self = $(this);

		if ($(self).is(':checked')) 
		{
			$(self).attr('disabled','disabled');

			var po_head_id_val = table_get_column_data(row_index,'id',0,myjstbl_po_list,colarray_po_list);
			var po_number = table_get_column_data(row_index,'ponumber',0,myjstbl_po_list,colarray_po_list);

			var arr = 	{ 
							fnc : 'get_po_details',
							po_head_id : po_head_id_val
						};

			$.ajax({
				type: "POST",
				dataType : 'JSON',
				data: 'data=' + JSON.stringify(arr) + token_val,
				success: function(response) {
					clear_message_box();

					if (response.error != '') 
					{
						build_message_box('messagebox_1',response.error,'danger');
					}
					else
					{
						myjstbl.insert_multiplerow_with_value(1,response.detail);

						for (var i = 1; i < myjstbl.get_row_count(); i++) 
						{
							var row_po_number = table_get_column_data(i,'ponumber');
							if (row_po_number == po_number) 
							{
								myjstbl.edit_row(i);
							};
						};

						recompute_row_count(myjstbl,colarray);
					}

					$(self).removeAttr('disabled');

					flag = 0;
				}       
			});
		}
		else
		{

		}
	});
	
	$('.imgupdate').live('click',function(){
		if (flag ==1 ) { return; };
		flag = 1;

		var row_index = $(this).parent().parent().index();

		var receive_detail_id_val 	= table_get_column_data(row_index,'id');
		var purchase_detail_id_val 	= table_get_column_data(row_index,'podetailid');
		var quantity_val 			= table_get_column_data(row_index,'qtyrecv');
		var product_id_val 			= table_get_column_data(row_index,'product',1);
		var fnc_val 				= receive_detail_id_val == 0 ? 'insert_receive_detail' : 'update_receive_detail';

		var arr = 	{ 
						fnc : fnc_val,
						receive_detail_id : receive_detail_id_val,
						purchase_detail_id : purchase_detail_id_val,
						quantity : quantity_val,
						product_id : product_id_val
					};

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
				{
					build_message_box('messagebox_1',response.error,'danger');
				}
				else
				{
					myjstbl.update_row(row_index);
					table_set_column_data(row_index,'id',[response.id]);
				}
			}       
		});
	});

</script>