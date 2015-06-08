<script type="text/javascript">
	
    var flag = 0;
    var global_product_id = 0;
    var global_row_index = 0;


     var token_val       = '<?= $token ?>';
  //  var branch_val      = $('#branch').chosen().val() == null ? '' : $('#branch').chosen().val();
    
    var arr =   { 
                        fnc      : 'create_table'
                       
                 };

              
     $.ajax({

            type: "POST",
            dataType : 'JSON',
            data: 'data=' + JSON.stringify(arr) + token_val,
            success: function(response) {
              
             if ((Object.keys(response).length) != null) 
                {
                
            

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

               

                for(var n = 1; n <= (Object.keys(response).length) ; n++)
                
                { 
                  var spnn = document.createElement('span');
                  colarray[response[n]] = { 
                  header_title: response[n],
                  edit: [spnn],
                  disp: [spnn],
                  td_class: "tablerow column_click column_hover td".concat(response[n]),
                  headertd_class :"tdheader".concat(response[n])
             

                             
            };

 
                    
            }
   
                                 
                    var global_myjstbl;

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
        
        if (flag == 1) { return; };
        

     

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
    

        if(branch_val != 0){ flag= 1;}
        else{ alert('no branch selected!');};
      
                   
         var arr =    {
                        fnc      : 'get_productbranchinventory_list', 
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

                    {
                       
                        myjstbl.mypage.set_last_page(3);
                    }
                    else
                    {
                        myjstbl.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(myjstbl.mypage.filter_number)));
                      

                    }
                     
                         myjstbl.insert_multiplerow_with_value(1,response.data);

                                $('.tdheaderManila').hide();
                                $('.tdManila').hide();
                                $('.tdheaderMakati').hide();
                                
                                $('.tdMakati').hide();    

                                $('.tdheaderPasay').hide();
                                
                                $('.tdPasay').hide();
                                $('.tdheaderValenzuela').hide();
                                
                                $('.tdValenzuela').hide();   
                                $('.tdheaderBambang').hide();
                                
                                $('.tdBambang').hide();
                                
                      for (var i = 0; i < (Object.keys(branch_val).length); i++)
                        {


                            if (branch_val[i] == 1 )

                            {
                              
                                 $('.tdheaderManila').show();
                                
                                 $('.tdManila').show();

                            }
                           
                            if (branch_val[i] == 2)
                            {
                              
                                 $('.tdheaderMakati').show();
                    
                                 $('.tdMakati').show();
                             
                            }
                              if (branch_val[i] == 3)
                            {
                              
                                
                                 $('.tdheaderPasay').show();
                                 
                                 $('.tdPasay').show();
                                
                            }
                              if (branch_val[i] == 4)
                            {
                              
                                 
                                 $('.tdheaderValenzuela').show();
                                 
                                 $('.tdValenzuela').show();
                               
                            }
                              if (branch_val[i] == 5)
                            {
                              
                                
                                 $('.tdheaderBambang').show();
                                
                                 $('.tdBambang').show();
                              
                            }
                            
                
                        }
                       
                        
  
                
                    $('#tbl').show();
                }

                $('#loadingimg').hide();

                flag = 0;
            }       
        });
    }
</script>
