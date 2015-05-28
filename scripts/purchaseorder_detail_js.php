<script type="text/javascript">
	var flag = 0;
	var global_detail_id = 0;
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

    var spnproduct = document.createElement('span');
    var spnproductid = document.createElement('span');
    var txtproduct = document.createElement('input');
    txtproduct.setAttribute('class','form-control txtproduct');
    spnproductid.setAttribute('style','display:none;');
	colarray['product'] = { 
        header_title: "Product",
        edit: [txtproduct,spnproductid],
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
   	var txtqty = document.createElement('input');
    txtqty.setAttribute('class','form-control txtqty');
	colarray['qty'] = { 
        header_title: "Qty",
        edit: [txtqty],
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
    var txtmemo = document.createElement('input');
    txtmemo.setAttribute('class','form-control txtmemo');
	colarray['memo'] = { 
        header_title: "Remarks",
        edit: [txtmemo],
        disp: [spnmemo],
        td_class: "tablerow column_click column_hover tdmemo"
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


	var myjstbl;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date').datepicker();
    	$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
    	$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
    	$('#date').datepicker("setDate", new Date());

		var token_val		= '<?= $token ?>';

		var arr = 	{ 
						fnc 	 	: 'get_purchaseorder_details'
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
					$('#supplier').val(response.supplier_name);
					$('#orderfor').val(response.orderfor);
					if (response.entry_date != '') 
					{
						$('#date').val(response.entry_date);
					};
				
				}
				
				if (response.detail_error == '') 
				{
					myjstbl.insert_multiplerow_with_value(1,response.detail);
				};

				add_new_row(myjstbl,colarray);
				recompute_total_qty(myjstbl,colarray,'total_qty');
				bind_product_autocomplete();
			}       
		});
	}
	else
	{
		$('input, textarea').attr('disabled','disabled');
		
	}

	$('.txtmemo').live('keydown',function(e){
		if (e.keyCode == 13) {
			insert_and_update_purchaseorder_detail($(this));
			e.preventDefault();
		};
	});

	$('.imgupdate').live('click',function(){
		insert_and_update_purchaseorder_detail($(this));
	});

	$('.imgedit').live('click',function(){
		var row_index = $(this).parent().parent().index();
		myjstbl.edit_row(row_index);
	});

	$('.txtqty').live('blur',function(e){
		recompute_total_qty(myjstbl,colarray,'total_qty');
	});

	$('.tddelete').live('click',function(){
		global_row_index 	= $(this).parent().index();
		global_detail_id 	= myjstbl.getvalue_by_rowindex_tdclass(global_row_index, colarray["id"].td_class)[0];

		if (global_detail_id != 0) 
		{
			$('#deletePurchaseOrderModal').modal('show');
		};
	});

	$('#delete').click(function(){
		if (flag == 1) { return; };
		flag = 1;

		var token_val		= '<?= $token ?>';
		var row_index 		= global_row_index;
		var detail_id_val 	= global_detail_id;

		var arr = 	{ 
						fnc 	 	: 'delete_purchaseorder_detail', 
						detail_id 	: detail_id_val
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
					recompute_total_qty(myjstbl,colarray,'total_qty');
					$('#deletePurchaseOrderModal').modal('hide');
				}

				flag = 0;
			}       
		});
	});

	$('#save').click(function(){
		if (flag == 1) { return; };
		flag = 1;

		var token_val	= '<?= $token ?>';
		var date_val	= $('#date').val();
		var memo_val 	= $('#memo').val();
		var supplier_name_val = $('#supplier').val();
		var orderfor_val = $('#orderfor').val();

		var arr = 	{ 
						fnc 	 	: 'save_purchaseorder_head', 
						entry_date 	: date_val,
						memo 		: memo_val,
						supplier_name : supplier_name_val,
						orderfor     : orderfor_val
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
					window.location = "<?= base_url() ?>purchase/list";
				}

				flag = 0;
			}       
		});
	});

	$('#deletePurchaseOrderModal').live('hidden.bs.modal', function (e) {
		global_row_index 	= 0;
		global_detail_id 	= 0;
	});
	
	function bind_product_autocomplete()
	{
		my_autocomplete_add("<?= $token ?>",".txtproduct",'<?= base_url() ?>damage', {
			enable_add : false,
			fnc_callback : function(x, label, value, ret_datas, error){
				var row_index = $(x).parent().parent().index();
				if (error.length > 0) {
					myjstbl.setvalue_to_rowindex_tdclass(['',0],row_index,colarray["product"].td_class);
					myjstbl.setvalue_to_rowindex_tdclass([''],row_index,colarray["code"].td_class);
					myjstbl.setvalue_to_rowindex_tdclass([''],row_index,colarray["qty"].td_class);
					myjstbl.setvalue_to_rowindex_tdclass([''],row_index,colarray["inventory"].td_class);
					myjstbl.setvalue_to_rowindex_tdclass([''],row_index,colarray["memo"].td_class);
				}
				else
				{
					myjstbl.setvalue_to_rowindex_tdclass([ret_datas[1],ret_datas[0]],row_index,colarray["product"].td_class);
					myjstbl.setvalue_to_rowindex_tdclass([ret_datas[2]],row_index,colarray["code"].td_class);
					myjstbl.setvalue_to_rowindex_tdclass([ret_datas[3]],row_index,colarray["inventory"].td_class);
				}
			},
			fnc_render : function(ul, item){
				return my_autocomplete_render_fnc(ul, item, "code_name", [2,1], 
					{ width : ["100px","auto"] });
			}
		});
	}

	function insert_and_update_purchaseorder_detail(element)
	{
		if (flag == 1) { return; };
		flag = 1;

		var token_val 		= "<?= $token ?>";
		var row_index 		= $(element).parent().parent().index();
		var product_id_val 	= myjstbl.getvalue_by_rowindex_tdclass(row_index,colarray["product"].td_class)[1];
		var qty_val 		= myjstbl.getvalue_by_rowindex_tdclass(row_index,colarray["qty"].td_class)[0];
		var memo_val 		= myjstbl.getvalue_by_rowindex_tdclass(row_index,colarray["memo"].td_class)[0];
		var id_val 			= myjstbl.getvalue_by_rowindex_tdclass(row_index,colarray["id"].td_class)[0];
		var fnc_val 		= id_val != 0 ? "update_purchaseorder_detail" : "insert_purchaseorder_detail";

		var arr = 	{ 
						fnc 	 	: fnc_val, 
						product_id 	: product_id_val,
						qty     	: qty_val,
			     		memo 		: memo_val,
			     		detail_id 	: id_val
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

					if (id_val == 0) {
						myjstbl.setvalue_to_rowindex_tdclass([response.id],row_index,colarray["id"].td_class);
            			add_new_row(myjstbl,colarray,'txtproduct');
            		}

				}

				flag = 0;
			}       
		});
	}

</script>