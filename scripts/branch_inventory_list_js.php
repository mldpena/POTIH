<script type="text/javascript">
	
	var flag    = 0;
	var token   = '<?= $token ?>';

	var arr =  { fnc : 'get_branch_list' };
	var myjstbl;
	var tableHelper;

	$.ajax({
		type: "POST",
		dataType : 'JSON',
		data: 'data=' + JSON.stringify(arr) + token,
		success: function(response) {
		  
			if ((Object.keys(response.branches).length) > 0) 
			{
				var tab = document.createElement('table');
				tab.className = "tblstyle";
				tab.id = "tableid";
				tab.setAttribute("style","border-collapse:collapse;");
				tab.setAttribute("class","border-collapse:collapse;");

				var colarray = [];

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
					td_class: "tablerow column_click column_hover tdproduct",
					headertd_class : "tdheader_p"

				};

				var spntype = document.createElement('span');
				colarray['type'] = { 
					header_title: "Type",
					edit: [spntype],
					disp: [spntype],
					td_class: "tablerow column_click column_hover tdtype"
				};

				var spnbranch = document.createElement('span');

				$.each(response.branches, function(key,value){
					colarray[value] = { 
						header_title: value,
						edit: [spnbranch],
						disp: [spnbranch],
						td_class: "tablerow column_click column_hover tdbranches tdbranch" + value,
						headertd_class : "tdbranches tdbranch" + value
					};
				});

				var spntotalinv = document.createElement('span');
				colarray['totalinv'] = { 
					header_title: "Total Inventory",
					edit: [spntotalinv],
					disp: [spntotalinv],
					td_class: "tablerow column_click column_hover tdtotalinv"
				};

				var root = document.getElementById("tbl");
				myjstbl = new my_table(tab, colarray, { ispaging : true, 
														tdhighlight_when_hover : "tablerow",
														iscursorchange_when_hover : true  
										});

				root.appendChild(myjstbl.tab);
				root.appendChild(myjstbl.mypage.pagingtable);

				myjstbl.mypage.set_mysql_interval(100);
				myjstbl.mypage.isOldPaging = true;
				myjstbl.mypage.pass_refresh_filter_page(triggerSearchRequest);
	
				tableHelper = new TableHelper(  { tableObject : myjstbl, tableArray : colarray }, 
													{ baseURL : "<?= base_url() ?>", 
													  controller : 'product',
													  notFoundMessage : 'No product found!' });

				tableHelper.headContent.bindSearchEvent(triggerSearchRequest);
			}         
		}
	});
								   
	$('#tbl').hide();    
	$('#loadingimg').hide();    
	$("#branch").select2();

	$('#export').click(function () {

		var filterValues = getSearchFilterValues();

		if (!arr) 
			return;

		filterValues.fnc = "branch_inventory";

		var queryString = $.objectToQueryString(filterValues);

		window.open("<?= base_url() ?>export?" + queryString);
	});

	function getSearchFilterValues()
	{
		var token_val       = '<?= $token ?>';
		var itemcode_val    = $('#itemcode').val();
		var product_val     = $('#product').val();
		var subgroup_val    = $('#subgroup').val();
		var type_val        = $('#type').val();
		var material_val    = $('#material').val();
		var datefrom_val    = $('#date_from').val();
		var dateto_val      = $('#date_to').val();
		var branch_val      = $('#branch').val() == null ? '' : $('#branch').val();
		var orderby_val     = $('#orderby').val();

		if(branch_val.length == 0)
		{
			alert('No branch selected!'); 
			return false;
		}

		if ($.inArray('0',branch_val) != -1) 
		{
			branch_val = [];
			$('#branch > option').each(function(key,element){
				var value = $(element).val();
				
				if (value != 0) 
					branch_val.push(value);
			});
		}
		
		var filterValues =  {
								fnc      : 'get_branch_inventory_list', 
								code     : itemcode_val,
								product  : product_val,
								subgroup : subgroup_val,
								type     : type_val,
								material : material_val,
								datefrom : datefrom_val,
								dateto   : dateto_val,
								branch   : branch_val,
								orderby  : orderby_val
							}  

		return filterValues;
	}

	function triggerSearchRequest(rowStart, rowEnd)
	{
		if((typeof rowStart === 'undefined') && (typeof rowEnd === 'undefined'))
			myjstbl.clear_table();
		else
			myjstbl.clean_table();

		var filterResetValue = (typeof rowStart === 'undefined') ? 1 : 0;
		var rowStartValue = (typeof rowStart === 'undefined') ? 0 : rowStart;
		var rowEndValue = (typeof rowEnd === 'undefined') ? (myjstbl.mypage.mysql_interval-1) : rowEnd;

		var paginationRowValues =   {
										filter_reset : filterResetValue,
										row_start : rowStartValue,
										row_end : rowEndValue
									}

		var filterValues = getSearchFilterValues();

		if (!filterValues)
			return;

		$.extend(filterValues, paginationRowValues);

		tableHelper.contentHelper.refreshTableWithLimit(filterValues, showSelectedBranches);
	}

	function showSelectedBranches(response)
	{
		if (response.rowcnt > 0)
		{
			var input = getSearchFilterValues();

			$('.tdbranches').hide();
			for (var i = 0; i < input.branch.length; i++)
			{
				var branch_name = $('#branch [value='+input.branch[i]+']').text();
				$('.tdbranch' + branch_name).show();
			}
		} 
	}
</script>
