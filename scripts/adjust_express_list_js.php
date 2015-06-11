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
	colarray['material_code'] = { 
        header_title: "Material Code",
        edit: [spnmaterialcode],
        disp: [spnmaterialcode],
        td_class: "tablerow column_click column_hover tdmaterial"
    };

    var spninventory = document.createElement('span');
	colarray['oldinventory'] = { 
        header_title: "Old Inv",
        edit: [spninventory],
        disp: [spninventory],
        td_class: "tablerow column_click column_hover tdinv"
    };

    var spnqty = document.createElement('span');
   	var txtqty = document.createElement('input');
    txtqty.setAttribute('class','form-control txtqty');
	colarray['newinventory'] = { 
        header_title: "New Inv",
        edit: [txtqty],
        disp: [spnqty],
        td_class: "tablerow column_click column_hover tdqty"
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

    var spnstatus = document.createElement('span');
	colarray['status'] = { 
        header_title: "Status",
        edit: [spnstatus],
        disp: [spnstatus],
        td_class: "tablerow column_click column_hover tdstatus"
    };

    var imgUpdate = document.createElement('i');
	imgUpdate.setAttribute("class","imgupdate fa fa-check");
	var imgEdit = document.createElement('i');
	imgEdit.setAttribute("class","imgedit fa fa-pencil");
	colarray['update'] = { 
		header_title: "",
		edit: [imgUpdate],
		disp: [imgEdit],
		td_class: "tablerow column_hover tdupdate",
		headertd_class: "tdupdate"
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

	var tableHelper = new TABLE.EventHelper();

	tableHelper.bindUpdateEvents(getRowDetails);

	$('#tbl').hide();
	//Call refresh function after loading the page

	$('#date_from, #date_to').datepicker();
    $('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );
    $('#date_from, #date_to').datepicker("setDate",new Date());

    refreshTable();

    //Event for calling search function
    $('#search').click(function(){
    	refreshTable();
    });

    //Function for refreshing table content
	function refreshTable()
	{
		if (flag == 1)
			return;

		flag = 1;

		var dateFrom	= $('#date_from').val();
		var dateTo		= $('#date_to').val();
		var searchString = $('#search_string').val();
		var orderBy 	= $('#order_by').val();
		var orderType 	= $('#order_type').val();

		var arr = 	{ 
						fnc 	 : 'get_adjust_express_list', 
						search_string 	: searchString,
						order_by  		: orderBy,
						order_type 		: orderType,
						date_from		: dateFrom,
						date_to 		: dateTo
					};

		$('#tbl').hide();
		$('#loadingimg').show();

		$.ajax({
			type: "POST",
			dataType : 'JSON',
			data: 'data=' + JSON.stringify(arr) + token_val,
			success: function(response) {
				myjstbl.clear_table();
				clear_message_box();

				if (response.rowcnt == 0) 
					build_message_box('messagebox_1','No inventory adjust found!','info');
				else
				{
					if(response.rowcnt <= 10)
						myjstbl.mypage.set_last_page(1);
					else
						myjstbl.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(myjstbl.mypage.filter_number)));

					myjstbl.insert_multiplerow_with_value(1,response.data);
				}

				for (var i = 1; i < myjstbl.get_row_count(); i++) {
					if (status != 'Pending')
					{
						var element = tableHelper.getElement(i,'update');
						$(element).hide();
					}
				};

				tableHelper.addRow();

				$('#loadingimg').hide();
				$('#tbl').show();
				flag = 0;
			}       
		});
	}

	function getRowDetails(element)
	{
		var rowIndex 	= $(element).parent().parent().index();
		var productId 	= tableHelper.getData(row_index,'product',1);
		var newInventory = tableHelper.getData(row_index,'newinventory');
		var oldInventory = tableHelper.getData(row_index,'oldinventory');
		var memo 		= tableHelper.getData(row_index,'memo');
		var adjustId 	= tableHelper.getData(row_index,'id');
		var fnc 	= adjustId != 0 ? "update_adjust_detail" : "insert_adjust_detail";

		var arr = 	{
						fnc 	 	: fnc, 
						product_id 	: productId,
						odl_inventory : oldInventory,
						new_inventory : newInventory,
			     		memo 		: memo,
			     		id 			: adjustId
					};

		return arr;
	}
</script>