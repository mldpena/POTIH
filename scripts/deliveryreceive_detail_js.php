<script type="text/javascript">
	/**
	 * Initialization of global variables
	 * @flag {Number} - To prevent spam request
	 * @global_detail_id {Number} - Holder of stock receive id for delete modal
	 * @global_row_index {Number} - Holder of stock receive row index for delete modal
	 * @token {String} - Token for CSRF Protection
	 */
	
	var flag = 0;
	var global_detail_id = 0;
	var global_row_index = 0;
	var token = '<?= $token ?>';

	/**
	 * Initialization for JS table stock receive details
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

    var spnreceiveqty = document.createElement('span');
    var txtreceiveqty = document.createElement('input');
    txtreceiveqty.setAttribute('class','form-control');
	colarray['receiveqty'] = { 
        header_title: "Received Qty",
        edit: [txtreceiveqty],
        disp: [spnreceiveqty],
        td_class: "tablerow column_click column_hover tdreceiveqty"
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

	var myjstbl;

	var root = document.getElementById("tbl");
	myjstbl = new my_table(tab, colarray, {	ispaging : false, 
											tdhighlight_when_hover : "tablerow",
											iscursorchange_when_hover : true,
											isdeleteicon_when_hover : false
							});

	root.appendChild(myjstbl.tab);

	$('#tbl').hide();

	var tableHelper = new TABLE.EventHelper({ tableObject : myjstbl, tableArray : colarray });
	tableHelper.bindUpdateEvents(get_table_details);

	if ("<?= $this->uri->segment(3) ?>" != '') 
	{
		$('#date, #receive_date').datepicker();
    	$('#date, #receive_date').datepicker("option","dateFormat", "yy-mm-dd" );
    	$('#date, #receive_date').datepicker("setDate", new Date());

		var arr = 	{ 
						fnc : 'get_stock_receive_details'
					};
		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
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
					$('#receive_date').val(response.receive_date);	

					if (response.entry_date != '') 
						$('#date').val(response.entry_date);	

					if (response.delivery_type == 2) 
						$('#delivery_to_list').hide();
				}
				
				$('input, textarea, button, select').not('#print, #receive_date').attr('disabled','disabled');

				if (response.detail_error == '') 
					myjstbl.insert_multiplerow_with_value(1,response.detail);

				for (var i = 1; i < myjstbl.get_row_count(); i++) 
				{
					var receive_qty = tableHelper.getData(i,'receiveqty');
					if (receive_qty == 0) 
						myjstbl.edit_row(i);
				};

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

	/*$('#save').click(function(){
		if (flag == 1) 
			return;

		flag = 1;

		var receive_date_val	= $('#receive_date').val();
		var date_column_val 	= 'delivery_receive_date';

		var arr = 	{ 
						fnc 	 	: 'save_receive_head', 
						entry_date 	: date_val,
						memo 		: memo_val,
						type 		: type_val,
						to_branch 	: to_branch
					};

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token,
			success: function(response) {
				clear_message_box();

				if (response.error != '') 
					build_message_box('messagebox_1',response.error,'danger');
				else
					window.location = "<?= base_url() ?>delivery/list";

				flag = 0;
			}       
		});
	});*/

	function get_table_details(element)
	{
		var row_index 		= $(element).parent().parent().index();
		var id_val 			= tableHelper.getData(row_index,'id');
		var receiveqty_val 	= tableHelper.getData(row_index,'receiveqty');

		var arr = 	{ 
						fnc 	 	: 'update_stock_receive_detail', 
			     		detail_id 	: id_val,
			     		receiveqty 	: receiveqty_val
					};

		return arr;
	}
</script>