<script type="text/javascript">
    /**
     * Initialization of global variables
     * @flag {Number} - To prevent spam request
     * @token_val {String} - Token for CSRF Protection
     */
    
    var flag = 0;
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
        header_title: "Min Inv",
        edit: [spnmininv],
        disp: [spnmininv],
        td_class: "tablerow column_click column_hover tdmininv"
    };
    var spnmaxinv = document.createElement('span');
    colarray['max_inv'] = { 
        header_title: "Max Inv",
        edit: [spnmaxinv],
        disp: [spnmaxinv],
        td_class: "tablerow column_click column_hover tdmaxinv"
    };
    var spninv = document.createElement('span');
    colarray['inv'] = { 
        header_title: "Inv",
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


    $('#tbl').hide();

    /**
     * Call refresh function after loading the page
     */
    refresh_table();

    $('#date_from, #date_to').datepicker();
    $('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );

    //Event for calling search function
    $('#search').click(function(){
        refresh_table();
    });

    //Function for refreshing table content
    function refresh_table()
    {
        if (flag == 1) 
            return;

        flag = 1;

        var itemcode_val    = $('#itemcode').val();
        var product_val     = $('#product').val();
        var subgroup_val    = $('#subgroup').val();
        var type_val        = $('#type').val();
        var material_val    = $('#material').val();
        var datefrom_val    = $('#date_from').val();
        var dateto_val      = $('#date_to').val();
        var branch_val      = $('#branch').val();
        var orderby_val     = $('#orderby').val();


        var arr =   { 
                        fnc      : 'get_inventory_warning_list', 
                        code     : itemcode_val,
                        product  : product_val,
                        subgroup : subgroup_val,
                        type     : type_val,
                        material : material_val,
                        datefrom : datefrom_val,
                        dateto   : dateto_val,
                        branch   : branch_val,
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