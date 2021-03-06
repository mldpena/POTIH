<script type="text/javascript">
    var token = '<?= $token ?>';

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
    var spnmininv = document.createElement('span');
    colarray['min_inv'] = { 
        header_title: "Min Inventory",
        edit: [spnmininv],
        disp: [spnmininv],
        td_class: "tablerow column_click column_hover tdmininv"
    };
    var spnmaxinv = document.createElement('span');
    colarray['max_inv'] = { 
        header_title: "Max Inventory",
        edit: [spnmaxinv],
        disp: [spnmaxinv],
        td_class: "tablerow column_click column_hover tdmaxinv"
    };
    var spninv = document.createElement('span');
    colarray['inv'] = { 
        header_title: "Inventory",
        edit: [spninv],
        disp: [spninv],
        td_class: "tablerow column_click column_hover tdinv"
    };
     var spnstatus = document.createElement('span');
    colarray['status'] = { 
        header_title: "Status",
        edit: [spnstatus],
        disp: [spnstatus],
        td_class: "tablerow column_click column_hover tdstatus"
    };

    var myjstbl;

    var root = document.getElementById("tbl");
    myjstbl = new my_table(tab, colarray, { ispaging : true, 
                                            tdhighlight_when_hover : "tablerow",
                                            iscursorchange_when_hover : true,
                                            isdeleteicon_when_hover : false
                            });

    root.appendChild(myjstbl.tab);
    root.appendChild(myjstbl.mypage.pagingtable);

    myjstbl.mypage.set_mysql_interval(100);
    myjstbl.mypage.isOldPaging = true;
    myjstbl.mypage.pass_refresh_filter_page(triggerSearchRequest);

    if ('<?= $this->uri->segment(3) ?>' != '') 
    {
        var notificationStatus = Number('<?= $this->uri->segment(3) ?>');
        $('#invstatus').val(notificationStatus);
    }

    $('#tbl').hide();

    $('#date_from, #date_to').datepicker();
    $('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );

    var tableHelper = new TableHelper({ tableObject : myjstbl, tableArray : colarray });
    
    tableHelper.headContent.bindSearchEvent(triggerSearchRequest);
    
    triggerSearchRequest();

    $('#export').click(function () {
        var filterValues = getSearchFilterValues();

        filterValues.fnc = "inventory_warning";

        var queryString = $.objectToQueryString(filterValues);

        window.open("<?= base_url() ?>export?" + queryString);
    });

    function getSearchFilterValues()
    {
        var itemcode_val    = $('#itemcode').val();
        var product_val     = $('#product').val();
        var subgroup_val    = $('#subgroup').val();
        var type_val        = $('#type').val();
        var material_val    = $('#material').val();
        var datefrom_val    = $('#date_from').val();
        var dateto_val      = $('#date_to').val();
        var branch_val      = $('#branch').val();
        var invstatus_val   = $('#invstatus').val();
        var orderby_val     = $('#orderby').val();


        var filterValues =  { 
                                fnc      : 'get_inventory_warning_list', 
                                code     : itemcode_val,
                                product  : product_val,
                                subgroup : subgroup_val,
                                type     : type_val,
                                material : material_val,
                                datefrom : datefrom_val,
                                dateto   : dateto_val,
                                branch   : branch_val,
                                orderby  : orderby_val,
                                invstatus : invstatus_val
                            };

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

        $.extend(filterValues, paginationRowValues);

        tableHelper.contentHelper.refreshTableWithLimit(filterValues);
    }
</script>