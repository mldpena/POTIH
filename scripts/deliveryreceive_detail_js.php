<script type="text/javascript">
	var token = '<?= $token ?>';

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

    var disabledDescription = document.createElement('textarea');
    disabledDescription.setAttribute('class','nonStackDescription');
    disabledDescription.setAttribute('style','display:none;');
    disabledDescription.setAttribute('disabled','disabled');

    var newline = document.createElement('span');

	colarray['product'] = { 
        header_title: "Product",
        edit: [spnproduct,spnproductid,newline,disabledDescription],
        disp: [spnproduct,spnproductid,newline,disabledDescription],
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

	var tableHelper = new TableHelper(	{ tableObject : myjstbl, tableArray : colarray }, 
										{ baseURL : "<?= base_url() ?>", controller : 'delreceive' });

	tableHelper.detailContent.bindAllEvents( { 	saveEventsBeforeCallback : getHeadDetailsBeforeSubmit, 
												updateEventsBeforeCallback : getRowDetailsBeforeSubmit } );


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
				
				$('input, textarea, button, select').not('#print, #receive_date, #save').attr('disabled','disabled');

				if (response.detail_error == '') 
					myjstbl.insert_multiplerow_with_value(1,response.detail);

				for (var i = 1; i < myjstbl.get_row_count(); i++) 
				{
					var receive_qty = tableHelper.contentProvider.getData(i,'receiveqty');
					if (receive_qty == 0) 
						myjstbl.edit_row(i);
				};

				tableHelper.contentProvider.recomputeTotalQuantity();
				tableHelper.contentHelper.showDescriptionFields();

				$('#tbl').show();
			}       
		});
	}
	else
		$('input, textarea').attr('disabled','disabled');

	function getHeadDetailsBeforeSubmit()
	{
		var receiveDate	= $('#receive_date').val();

		var arr = 	{ 
						fnc 	 		: 'update_delivery_receive_head', 
						receive_date 	: receiveDate
					};

		return arr;
	}

	function getRowDetailsBeforeSubmit(element)
	{
		var rowIndex 		= $(element).parent().parent().index();
		var rowId 			= tableHelper.contentProvider.getData(rowIndex,'id');
		var receivedQty 	= tableHelper.contentProvider.getData(rowIndex,'receiveqty');

		var arr = 	{ 
						fnc 	 	: 'update_receive_detail', 
			     		detail_id 	: rowId,
			     		receiveqty 	: receivedQty
					};

		return arr;
	}
</script>