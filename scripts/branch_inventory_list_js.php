<script type="text/javascript">
	
    var flag    = 0;
    var token   = '<?= $token ?>';

    var arr =  { fnc : 'get_branch_list' };
    var myjstbl;
              
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


                var root = document.getElementById("tbl");
                myjstbl = new my_table(tab, colarray, { ispaging : true, 
                                                        tdhighlight_when_hover : "tablerow",
                                                        iscursorchange_when_hover : true  
                                        });

                root.appendChild(myjstbl.tab);
                root.appendChild(myjstbl.mypage.pagingtable);
            }         
        }
    });
                                   
    $('#tbl').hide();
    
    $("#branch").chosen();

    $('#search').click(function(){
        refresh_table();
    });
   
  
    function refresh_table()
    {
        
        if (flag == 1) 
            return;

        var token_val       = '<?= $token ?>';
        var itemcode_val    = $('#itemcode').val();
        var product_val     = $('#product').val();
        var subgroup_val    = $('#subgroup').val();
        var type_val        = $('#type').val();
        var material_val    = $('#material').val();
        var datefrom_val    = $('#date_from').val();
        var dateto_val      = $('#date_to').val();
        var branch_val      = $('#branch').chosen().val() == null ? '' : $('#branch').chosen().val();
        var orderby_val     = $('#orderby').val();
        
        $('#tbl').hide();

        if(branch_val.length == 0)
        {
            alert('No branch selected!'); 
            return;
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
        
        var arr =  {
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

                
        flag = 1;
      
        $('#loadingimg').show();

        $.ajax({
            type: "POST",
            dataType : 'JSON',
            data: 'data=' + JSON.stringify(arr) + token,
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
                        myjstbl.mypage.set_last_page(3);
                    else
                        myjstbl.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(myjstbl.mypage.filter_number)));
                     
                    myjstbl.insert_multiplerow_with_value(1,response.data);
                    
                    $('.tdbranches').hide();

                    for (var i = 0; i < branch_val.length; i++)
                    {
                        var branch_name = $('#branch [value='+branch_val[i]+']').text();
                        $('.tdbranch' + branch_name).show();
                    }

                    $('#tbl').show();
                }

                $('#loadingimg').hide();

                flag = 0;
            }       
        });
    }
</script>
