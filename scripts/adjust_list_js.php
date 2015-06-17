<script type="text/javascript">
	/**
	 * Initialization of global variables
	 * @flag {Number} - To prevent spam request
	 * @token_val {String} - Token for CSRF Protection
	 */
	
	var flag = 0;
	var global_adjust_id = 0;
	var global_product_id = 0;
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

    var spnmaterial = document.createElement('span');
	colarray['material'] = { 
        header_title: "Material Type",
        edit: [spnmaterial],
        disp: [spnmaterial],
        td_class: "tablerow column_click column_hover tdmaterial_type"
    };

    var spnsubgroup = document.createElement('span');
	colarray['subgroup'] = { 
        header_title: "Sub Group",
        edit: [spnsubgroup],
        disp: [spnsubgroup],
        td_class: "tablerow column_click column_hover tdsubgroup"
    };

    var spninv = document.createElement('span');
	colarray['inv'] = { 
        header_title: "Inv",
        edit: [spninv],
        disp: [spninv],
        td_class: "tablerow column_click column_hover tdinv"
    };

    var spnrequest = document.createElement('span');
    var spnrequesthidden = document.createElement('span');
    spnrequesthidden.setAttribute('style','display:none');
	colarray['request'] = { 
        header_title: "Req Inv",
        edit: [spnrequest,spnrequesthidden],
        disp: [spnrequest,spnrequesthidden],
        td_class: "tablerow column_click column_hover tdrequest"
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

	var tableHelper = new TABLE.EventHelper({tableObject : myjstbl, tableArray : colarray});

	$('#tbl').hide();
	//Call refresh function after loading the page
	refresh_table();

	$('#date_from, #date_to').datepicker();
    $('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );

    //Event for calling search function
    $('#search').click(function(){
    	refresh_table();
    });

    $('.column_click').live('click',function(){
    	if (flag == 1)
			return;

    	global_row_index = $(this).parent().index();
    	global_product_id = tableHelper.getData(global_row_index,'id');
    	global_adjust_id = tableHelper.getData(global_row_index,'request',1);
    	
    	var arr = 	{ 
						fnc 	 : 'get_adjust_details', 
						product_id : global_product_id,
						adjust_id  : global_adjust_id,
					};

		flag = 1;

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				if (response.error != '') 
					build_message_box('messagebox_2',response.error,'danger');
				else
				{
					if (global_adjust_id == 0) 
						$('#div-pending').hide();
					else
						$('#div-pending').show();

					$('#product_name').html(response.product_name);
					$('#product_code').html(response.material_code);
					$('#old_inventory').html(response.old_inventory);
					$('#new_inventory').val(response.new_inventory);
				}

				$('#requestAdjustModal').modal('show');
				flag = 0;
			}       
		});
    });

	$('#save').click(function(){
		if (flag == 1)
			return;

		var fnc_val 			= global_adjust_id == 0 ? 'insert_inventory_adjust' : 'update_inventory_adjust';
		var new_inventory_val 	= $('#new_inventory').val();
		var old_inventory_val 	= $('#old_inventory').html();
		var memo_val 			= $('#memo').val();

		var arr = 	{ 
						fnc 	 : fnc_val, 
						product_id : global_product_id,
						adjust_id  : global_adjust_id,
						old_inventory : old_inventory_val,
						new_inventory : new_inventory_val,
						memo : memo_val
					};

		flag = 1;

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				if (response.error != '') 
					build_message_box('messagebox_2',response.error,'danger');
				else
				{
					var message = '';
					if (response.status == 1) 
					{
						var adjust_id = Number(global_adjust_id) == 0 ? response.id : global_adjust_id;
						message = 'Inventory adjust request successfully submitted!';
						tableHelper.setData(global_row_index,'request',[new_inventory_val,adjust_id]);
					}
					else if (response.status == 2)
					{
						message = 'Inventory successfully adjusted!';
						tableHelper.setData(global_row_index,'inv',[new_inventory_val]);
					} 
						
					$('#requestAdjustModal').modal('hide');
					build_message_box('messagebox_1',message,'success');
				}
				flag = 0;
			}       
		});
	});

    //Callback function for modals. Will reset values to default
    $('#requestAdjustModal').live('hidden.bs.modal', function (e) {
		global_row_index 	= 0;
		global_adjust_id 	= 0;
		global_product_id 	= 0;
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
		var inv_val			= $('#invstatus').val();
		var orderby_val		= $('#orderby').val();


		var arr = 	{ 
						fnc 	 : 'get_product_and_adjust_list', 
						code 	 : itemcode_val,
						product  : product_val,
						subgroup : subgroup_val,
						type 	 : type_val,
						material : material_val,
						datefrom : datefrom_val,
						dateto 	 : dateto_val,
						invstat  : inv_val,
						orderby  : orderby_val
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
					build_message_box('messagebox_1','No product found!','info');
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