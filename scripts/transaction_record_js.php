<script type="text/javascript">
    var token = '<?= $token ?>';
    var flag = 0;

    var tab = document.createElement('table');
    tab.className = "tblstyle";
    tab.id = "tableid";
    tab.setAttribute("style","border-collapse:collapse;");
    tab.setAttribute("class","border-collapse:collapse;");
    
    var colarray = [];

    var tableColumns = [{ headerName : 'Beg Inv.', className : 'beginv' },
                        { headerName : 'Purchase Receive', className : 'purchasereceive' },
                        { headerName : 'Customer Return', className : 'customereturn' },
                        { headerName : 'Stock Receive', className : 'stockreceive' },
                        { headerName : 'Adjust Increase', className : 'adjustinc' },
                        { headerName : 'Damage', className : 'damage' },
                        { headerName : 'Purchase Return', className : 'purchasereturn' },
                        { headerName : 'Stock Delivery', className : 'stockdelivery' },
                        { headerName : 'Customer Delivery', className : 'customerdelivery' },
                        { headerName : 'Adjust Decrease', className : 'adjustdec' },
                        { headerName : 'Warehouse Release', className : 'release' },
                        { headerName : 'Total Inv', className : 'totalinv' }];

    for (var i = 0; i < tableColumns.length; i++) 
    {
        var spntransaction = document.createElement('a');
        colarray[tableColumns[i].className] = { 
            header_title: tableColumns[i].headerName,
            edit: [spntransaction],
            disp: [spntransaction],
            td_class: "tablerow column_click column_hover td" + tableColumns[i].className
        };
    };

    var myjstbl;

    var root = document.getElementById("tbl");
    myjstbl = new my_table(tab, colarray, { ispaging : false, 
                                            tdhighlight_when_hover : "tablerow",
                                            iscursorchange_when_hover : true,
                                            isdeleteicon_when_hover : false
                            });

    root.appendChild(myjstbl.tab);

    $('#date_from, #date_to').datepicker();
    $('#date_from, #date_to').datepicker("option","dateFormat", "yy-mm-dd" );

    var tableHelper = new TableHelper(  { tableObject : myjstbl, tableArray : colarray},
                                        { baseURL : "<?= base_url() ?>", controller : 'product' });

    tableHelper.detailContent.bindAutoComplete(getProductId);

    if ("<?= $this->uri->segment(3) ?>" != "") 
    {
        var arr =   { fnc : 'get_product_name' };

        $.ajax({
            type: "POST",
            dataType : 'JSON',
            data: 'data=' + JSON.stringify(arr) + token,
            success: function(response) {
                if (response.error != '') 
                    build_message_box('messagebox_1',response.error,'danger');
                else
                {
                    $('#branch').val('<?= $this->uri->segment(4) ?>');
                    $('#product_search').val(response.product_name);
                    $('#product_id').val(response.product_id);
                   refreshTable();
                }
            }
        });
    }
    else
        refreshTable();
    
    function refreshTable()
    {
        if (flag == 1)
            return;
        
        var date_from_val   = $('#date_from').val();
        var date_to_val     = $('#date_to').val();
        var branch_val      = $('#branch').val();
        var product_id_val  = $('#product_id').val();

        var arr = { fnc     : 'get_transaction_record',
                    date_from : date_from_val,
                    date_to : date_to_val,
                    branch : branch_val,
                    product_id : product_id_val }

        $('#loadingimg').show();

        flag = 1;

        $.ajax({
            type: "POST",
            dataType : 'JSON',
            data: 'data=' + JSON.stringify(arr) + token,
            success: function(response) {
                myjstbl.clear_table();
                clear_message_box();

                if (response.error != '') 
                    build_message_box('messagebox_1',response.error,'danger');
                else
                {
                    myjstbl.insert_multiplerow_with_value(1,response.data);
                    $('#tbl').show();
                }

                $('#loadingimg').hide();
                flag = 0;
            }       
        });
    }

    function getProductId(rowIndex, error, ret_datas)
    {
        var productSearchValue = error.length > 0 ? '' : ret_datas[1];
        var productIdValue = error.length > 0 ? 0 : ret_datas[0];

        $('#product_search').val(productSearchValue);
        $('#product_id').val(productIdValue);
    }
</script>