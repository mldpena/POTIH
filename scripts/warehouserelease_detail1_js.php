
<script type="text/javascript">
	/**
	 * Initialization of global variables
	 * @flag {Number} - To prevent spam request
	 * @global_detail_id {Number} - Holder of return detail id for delete modal
	 * @global_row_index {Number} - Holder of return detail row index for delete modal
	 * @token_val {String} - Token for CSRF Protection
	 */
	
	var flag = 0;
	var global_detail_id = 0;
	var global_row_index = 0;
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


    var spnnumber = document.createElement('span');
	colarray['number'] = { 
        header_title: "",
        edit: [spnnumber],
        disp: [spnnumber],
        td_class: "tablerow tdnumber"
    };

    var spnproduct = document.createElement('span');
    var spnproductid = document.createElement('span');
    //var txtproduct = document.createElement('input');
   // txtproduct.setAttribute('class','form-control txtproduct');
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

   
    var spnqtyrelease = document.createElement('span');
    var txtqtyrelease = document.createElement('input');
    txtqtyrelease.setAttribute('class','form-control txtqtyrelease');
	colarray['qty_release'] = { 
        header_title: "Qty Release",
        edit: [txtqtyrelease],
        disp: [spnqtyrelease],
        td_class: "tablerow column_click column_hover tdqty_release",
        headertd_class : "tdheader_qtyrelease"
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
		td_class: "tablerow column_hover tddelete",
		headertd_class : "tdheader_delete"
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

		var arr = 	{ 
						fnc : 'get_warehouserelease_details'
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
					$('#customer_name').val(response.customer_name);

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);
				}

				
			$('textarea, text, #customer_name, #date').attr('disabled','disabled');

				if (response.delivery_type != 1)
				{
					$('.tddelete').hide();
					$('.tdheader_delete').hide();
					insert_dynamic_css();
				} 

				if (response.detail_error == '') {
					myjstbl.insert_multiplerow_with_value(1,response.detail);
					check_detail_received();
			};

					$('.tddelete').hide();
					$('.tdheader_delete').hide();
					insert_dynamic_css();
				recompute_total_qty(myjstbl,colarray,'total_qty');
				bind_product_autocomplete();
			}       
		});
	}
	else{
		$('input, textarea').attr('disabled','disabled');
	}

	$('.imgupdate').live('click',function(){
		insert_and_update_warehouserelease_detail($(this));
	});

	$('.imgedit').live('click',function(){
		var row_index = $(this).parent().parent().index();
		myjstbl.edit_row(row_index);

	});
	
	
function bind_product_autocomplete()
	{
		my_autocomplete_add("<?= $token ?>",".txtproduct",'<?= base_url() ?>return', {
			enable_add : false,
			fnc_callback : function(x, label, value, ret_datas, error){
		        
				var row_index = $(x).parent().parent().index();
				if (error.length > 0) {
					table_set_column_data(row_index,'product',['',0]);
					table_set_column_data(row_index,'code',['']);
					table_set_column_data(row_index,'qty',['']);
					table_set_column_data(row_index,'inventory',['']);
					table_set_column_data(row_index,'memo',['']);
					table_set_column_data(row_index,'qty_release',['']);
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

		function insert_and_update_warehouserelease_detail(element)
	{
		if (flag == 1) 
			return;

		flag = 1;

		var row_index 		= $(element).parent().parent().index();
		var product_id_val 	= table_get_column_data(row_index,'product',1);
		var qty_val 		= table_get_column_data(row_index,'qty');
		var memo_val 		= table_get_column_data(row_index,'memo');
		var qtyrelease_val 	= table_get_column_data(row_index,'qty_release');
		var id_val 			= table_get_column_data(row_index,'id');
		var fnc_val 		= id_val != 0 ? "update_warehouserelease_detail" : "insert_warehouserelease_detail";

		var arr = 	{ 
						fnc 	 	: fnc_val, 
						product_id 	: product_id_val,
						qty     	: qty_val,
			     		memo 		: memo_val,
			     		released	:qtyrelease_val,
			     		detail_id 	: id_val
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
				{
					
					myjstbl.update_row(row_index);

					if (id_val == 0) {

						table_set_column_data(row_index,'id',[response.id]);
            	
							$('.tddelete').hide();
					$('.tdheader_delete').hide();
						insert_dynamic_css();
            		}

				}

				$('input, textarea').not('#print, #save').attr('disabled','disabled');
				flag = 0;
			}       
		});

	}
	function insert_dynamic_css()
	{
		$('#dynamic-css').html('');

		var css = "<style>.tddelete { display:none; }</style>";
		$('#dynamic-css').html(css);

		$('#dynamic-css').html('');
		
		var css1 = "<style>.tdheader_delete { display:none; }</style>";
		$('#dynamic-css').html(css1);
	}
function check_detail_received()
	{
		for (var i = 1; i < myjstbl.get_row_count(); i++) 
		{
			var row_qtyrelease_number = table_get_column_data(i,'qty_release');
			var row_receive_detail_id = table_get_column_data(i,'id');

			if (row_qtyrelease_number == 0) 
				myjstbl.edit_row(i);
		};
	}
</script>