<script type="text/javascript">
	/**
	 * Initialization of global variables
	 * @flag {Number} - To prevent spam request
	 * @global_detail_id {Number} - Holder of stock delivery id for delete modal
	 * @global_row_index {Number} - Holder of stock delivery row index for delete modal
	 * @token_val {String} - Token for CSRF Protection
	 */
	
	var flag = 0;
	var global_detail_id = 0;
	var global_row_index = 0;
	var token_val = '<?= $token ?>';

	/**
	 * Initialization for JS table stock delivery details
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

    var chkchecktransfer = document.createElement('input');
    chkchecktransfer.setAttribute('type','checkbox');
    chkchecktransfer.setAttribute('class','chktransfer');
    var chkchecktransferdisabled = document.createElement('input');
    chkchecktransferdisabled.setAttribute('type','checkbox');
    chkchecktransferdisabled.setAttribute('disabled','disabled');
    chkchecktransferdisabled.setAttribute('class','chktransfer');
	colarray['istransfer'] = { 
        header_title: "FT",
        edit: [chkchecktransfer],
        disp: [chkchecktransferdisabled],
        td_class: "tablerow tdistransfer",
		headertd_class : "tdistransfer"
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
		td_class: "tablerow column_hover tdupdate",
		headertd_class: "tdupdate"
	};

    var imgDelete = document.createElement('i');
	imgDelete.setAttribute("class","imgdel fa fa-trash");
	colarray['coldelete'] = { 
		header_title: "",
		edit: [imgDelete],
		disp: [imgDelete],
		td_class: "tablerow column_hover tddelete",
		headertd_class: "tddelete"
	};


	var myjstbl;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);

	$('#tbl').hide();

	var tableEvents = new TABLE.EventHelper();
	tableEvents.bindUpdateEvents(get_table_details);

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date').datepicker();
    	$('#date').datepicker("option","dateFormat", "yy-mm-dd" );
    	$('#date').datepicker("setDate", new Date());

		var arr = 	{ 
						fnc : 'get_stock_delivery_details'
					};
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				clear_message_box();

				if (response.head_error != '') 
					build_message_box('messagebox_1',response.error,'danger');
				else
				{
					$('#reference_no').val(response.reference_number);
					$('#memo').val(response.memo);
					$('#delivery_type').val(response.delivery_type);
					$('#to_branch').val(response.to_branchid);

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);	

					if (response.delivery_type == 2) 
						$('#delivery_to_list').hide();
				}
				
				if (response.detail_error == '') 
					myjstbl.insert_multiplerow_with_value(1,response.detail);

				if (response.is_editable == false)
				{
					$('input, textarea, button, select').not('#print').attr('disabled','disabled');
					$('.tdupdate, .tddelete').hide();
				}
				else
				{
					add_new_row(myjstbl,colarray);
					bind_product_autocomplete();
				}

				if (response.delivery_type != 1)
				{
					$('.tdistransfer').hide();
					insert_dynamic_css();
				} 
					

				recompute_total_qty(myjstbl,colarray,'total_qty');

				$('#tbl').show();
			}       
		});
	}
	else
		$('input, textarea').attr('disabled','disabled');

	$('.imgedit').live('click',function(){
		var row_index = $(this).parent().parent().index();
		myjstbl.edit_row(row_index);
	});

	$('.txtqty').live('blur',function(e){
		recompute_total_qty(myjstbl,colarray,'total_qty');
	});

	$('.tddelete').live('click',function(){
		global_row_index 	= $(this).parent().index();
		global_detail_id 	= table_get_column_data(global_row_index,'id');

		if (global_detail_id != 0) 
			$('#deleteStockDeliveryModal').modal('show');
	});

	$('#delivery_type').live('change',function(){
		if ($(this).val() == 2) 
		{
			$('#delivery_to_list').hide();
			$('.tdistransfer').hide();
			insert_dynamic_css();
		}
		else if ($(this).val() == 3) 
		{
			$('#delivery_to_list').show();
			$('.tdistransfer').hide();
			insert_dynamic_css();
		}
		else
		{
			$('#delivery_to_list').show();
			$('.tdistransfer').show();
			$('#dynamic-css').html('');
		}
	});

	$('#delete').click(function(){
		if (flag == 1) 
			return;

		flag = 1;

		var row_index 		= global_row_index;
		var detail_id_val 	= global_detail_id;

		var arr = 	{ 
						fnc 	 	: 'delete_stock_delivery_detail', 
						detail_id 	: detail_id_val
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
					recompute_total_qty(myjstbl,colarray,'total_qty');
					$('#deleteStockDeliveryModal').modal('hide');
				}

				flag = 0;
			}       
		});
	});

	$('#save').click(function(){
		if (flag == 1) 
			return;

		flag = 1;

		var date_val	= $('#date').val();
		var memo_val 	= $('#memo').val();
		var type_val 	= $('#delivery_type').val();
		var to_branch 	= type_val == 2 ? 0 : $('#to_branch').val();

		var arr = 	{ 
						fnc 	 	: 'save_stock_delivery_head', 
						entry_date 	: date_val,
						memo 		: memo_val,
						type 		: type_val,
						to_branch 	: to_branch
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
					window.location = "<?= base_url() ?>delivery/list";

				flag = 0;
			}       
		});
	});

	$('#deleteStockDeliveryModal').live('hidden.bs.modal', function (e) {
		global_row_index 	= 0;
		global_detail_id 	= 0;
	});
	
	function bind_product_autocomplete()
	{
		my_autocomplete_add("<?= $token ?>",".txtproduct",'<?= base_url() ?>delivery', {
			enable_add : false,
			fnc_callback : function(x, label, value, ret_datas, error){
				var row_index = $(x).parent().parent().index();
				if (error.length > 0) {
					table_set_column_data(row_index,'product',['',0]);
					table_set_column_data(row_index,'code',['']);
					table_set_column_data(row_index,'qty',['']);
					table_set_column_data(row_index,'inventory',['']);
					table_set_column_data(row_index,'memo',['']);
				}
				else
				{
					table_set_column_data(row_index,'product',[ret_datas[1],ret_datas[0]]);
					table_set_column_data(row_index,'code',[ret_datas[2]]);
					table_set_column_data(row_index,'inventory',[ret_datas[3]]);
				}
			},
			fnc_render : function(ul, item){
				return my_autocomplete_render_fnc(ul, item, "code_name", [2,1], 
					{ width : ["100px","auto"] });
			}
		});
	}

	function get_table_details(element)
	{
		var row_index 		= $(element).parent().parent().index();
		var product_id_val 	= table_get_column_data(row_index,'product',1);
		var qty_val 		= table_get_column_data(row_index,'qty');
		var memo_val 		= table_get_column_data(row_index,'memo');
		var id_val 			= table_get_column_data(row_index,'id');
		var is_transfer_val = Number(table_get_column_data(row_index,'istransfer'));
		var fnc_val 		= id_val != 0 ? "update_stock_delivery_detail" : "insert_stock_delivery_detail";

		var arr = 	{ 
						fnc 	 	: fnc_val, 
						product_id 	: product_id_val,
						qty     	: qty_val,
			     		memo 		: memo_val,
			     		detail_id 	: id_val,
			     		istransfer 	: is_transfer_val
					};

		return arr;
	}

	function insert_dynamic_css()
	{
		$('#dynamic-css').html('');
		var css = "<style>.tdistransfer { display:none; }</style>";
		$('#dynamic-css').html(css);
	}
</script>