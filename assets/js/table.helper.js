/**
 * Version: 1.0
 * Added Helper functions for MYJSTABLE. Current method supports js table, and other methods were specifically 
 * created for Hi - Top Project.
 **********************************************************
 * Created by Lawrence Pena.
 * Date Created: 6/16/2015
 * Version: 1.0
 */



var TableHelper = function(tableOptions,options) {
    
    this._flag = 0;

    this._jsTable;
    this._jsTableArray;
    this.globalRownIndex = 0;
    this.globaId = 0;

    /**
     * Settings
     * @type {Object}
     */
    this.settings = { 
        updateClass : 'imgupdate',
        deleteClass : 'tddelete',
        editClass   : 'imgedit',
        memoClass   : 'txtmemo',
        deleteID    : 'delete',
        saveID      : 'save',
        quantityClass : 'txtqty',
        totalQtyID  : 'total_qty',
        deleteModalID     : 'deleteModal',
        insertDetailName : 'insert_detail',
        updateDetailName : 'update_detail',
        deleteDetailName : 'delete_detail',
        deleteHeadName : 'delete_head',
        totalQtySpan : 'total_qty',
        productClass : 'txtproduct',
        createButtonId : 'create_new',
        tableID : 'tbl',
        loadingImgID : 'loadingimg',
        searchButtonID : 'search',
        columnClass : 'column_click'
    };

    // Overrides current settings
    if (options) {
        $.extend(this._settings,options);
    }

    // Bind table to be used for the helper
    if (tableOptions) {
        this._jsTable = tableOptions.tableObject;
        this._jsTableArray = tableOptions.tableArray;
    };
}

TableHelper.prototype = {
    self : function() {
        return this;
    },
    contentProvider : {
        addRow : function(element,tableObject,tableObjectArray)
        {
            _addRow(element,tableObject,tableObjectArray);
        },

        getData : function(rowIndex,tableObjectArrayColumn,arrayColumnIndex,tableObject,tableObjectArray)
        {
            return _getColumnData(rowIndex,tableObjectArrayColumn,arrayColumnIndex,tableObject,tableObjectArray);
        },

        setData : function(rowIndex,tableObjectArrayColumn,values,tableObject,tableObjectArray)
        {
            _setColumnData(rowIndex,tableObjectArrayColumn,values,tableObject,tableObjectArray);
        },

        getElement : function(rowIndex,tableObjectArrayColumn,tableObject,tableObjectArray)
        {
            var jsTable = _jsTable;
            var jsTableArray = _jsTableArray;

            if (tableObject) 
            {
                jsTable = tableObject;
                jsTableArray = tableObjectArray;
            };

            return jsTable.getelem_by_rowindex_tdclass(rowIndex,jsTableArray[tableObjectArrayColumn].td_class);
        },

        recomputeTotalQuantity : function(tableObject,tableObjectArray)
        {
            var jsTable = _jsTable;
            var jsTableArray = _jsTableArray;

            if (tableObject) 
            {
                jsTable = tableObject;
                jsTableArray = tableObjectArray;
            };

            var totalQty = 0;

            for (var i = 1; i < jsTable.get_row_count(); i++) {
                var currentQty = _getColumnData(i,'qty');
                totalQty += Number(currentQty);
            };

            $('#' + _settings.totalQtySpan).html(totalQty);
        },

        recomputeRowNumber : function(tableObject,tableObjectArray)
        {
            var jsTable = _jsTable;
            var jsTableArray = _jsTableArray;

            if (tableObject) 
            {
                jsTable = tableObject;
                jsTableArray = tableObjectArray
            };

            for (var i = 1; i < jsTable.get_row_count(); i++)
                jsTable.setvalue_to_rowindex_tdclass([i],i,jsTableArray["number"].td_class);
        }
    },

    detailContent : {
        bindUpdateEvents : function(onBeforeSubmit,onAfterSubmit)
        {
            var tableHelper = TableHelper.prototype.self();

            $('.' + _settings.updateClass).live('click',function(){
                tableHelper.helpers._callUpdate($(this),onBeforeSubmit,onAfterSubmit);
            });

            $('.' + _settings.memoClass).live('keydown',function(e){
                if (e.keyCode == 13) {
                    tableHelper.helpers._callUpdate($(this),onBeforeSubmit,onAfterSubmit);
                    e.preventDefault();
                };
            });
        },

        bindEditEvents : function()
        {
            var tableHelper = TableHelper.prototype.self();
            
            $('.' + _settings.editClass).live('click',function(){
                var rowIndex = $(this).parent().parent().index();
                _jsTable.edit_row(rowIndex);
            });

            $('.' + _settings.quantityClass).live('blur',function(){
                tableHelper.contentProvider.recomputeTotalQuantity();
            });
        },

        bindDeleteEvents : function(onBeforeSubmit)
        {
            var tableHelper = TableHelper.prototype.self();

            $('.' + _settings.deleteClass).live('click',function(){
                globalRownIndex    = $(this).parent().index();
                globaId             = _getColumnData(globalRownIndex,'id');

                if (globaId != 0) 
                    $('#' + _settings.deleteModalID).modal('show');
            });

            $('#' + _settings.deleteID).click(function(){
                if (_flag == 1) 
                    return;

                _flag = 1;

                var rowIndex       = globalRownIndex;

                if (onBeforeSubmit) 
                    var arr = onBeforeSubmit($(this));
                else
                {
                    var detail_id_val  = globaId;
                    var arr =   { 
                                    fnc         : _settings.deleteDetailName, 
                                    detail_id   : detail_id_val
                                };
                }
               
                $.ajax({
                    type: "POST",
                    dataType : 'JSON',
                    data: 'data=' + JSON.stringify(arr) + token,
                    success: function(response) {
                        clear_message_box();

                        if (response.error != '') 
                            build_message_box('messagebox_2',response.error,'danger');
                        else
                        {
                            _jsTable.delete_row(rowIndex);
                            tableHelper.contentProvider.recomputeRowNumber();
                            tableHelper.contentProvider.recomputeTotalQuantity();
                            $('#' + _settings.deleteModalID).modal('hide');
                        }

                        _flag = 0;
                    }       
                });
            });

            $('#' + _settings.deleteModalID).live('hidden.bs.modal', function (e) 
            {
                globalRownIndex = 0;
                globaId         = 0;
            });
        },

        bindAutoComplete : function (onAfterSubmit)
        {
            my_autocomplete_add(token,"." + _settings.productClass,_settings.controller, {
                enable_add : false,
                fnc_callback : function(x, label, value, ret_datas, error){
                    var rowIndex = $(x).parent().parent().index();
                    if (onAfterSubmit) {
                        onAfterSubmit(rowIndex, error, ret_datas);
                    }else{
                        if (error.length > 0) {
                            _setColumnData(rowIndex,'product',['',0]);
                            _setColumnData(rowIndex,'code',['']);
                            _setColumnData(rowIndex,'qty',['']);
                            _setColumnData(rowIndex,'memo',['']);
                        }
                        else
                        {
                            _setColumnData(rowIndex,'product',[ret_datas[1],ret_datas[0]]);
                            _setColumnData(rowIndex,'code',[ret_datas[2]]);
                        }
                    }
                    
                },
                fnc_render : function(ul, item){
                    return my_autocomplete_render_fnc(ul, item, "code_name", [2,1], 
                        { width : ["100px","auto"] });
                }
            });
        },

        bindSaveTransactionEvent : function(onBeforeSubmit)
        {
            $('#' + _settings.saveID).click(function(){
                if (_flag == 1)
                    return;

                _flag = 1;

                var arr = onBeforeSubmit();

                $.ajax({
                    type: "POST",
                    dataType : 'JSON',
                    data: 'data=' + JSON.stringify(arr) + token,
                    success: function(response) {
                        clear_message_box();

                        if (response.error != '') 
                            build_message_box('messagebox_1',response.error,'danger');
                        else
                            window.location = _settings.baseURL + _settings.controller + "/list";

                        _flag = 0;
                    }       
                });
            });
        },
        bindAllEvents : function(callbackOptions)
        {
            var tableHelper = TableHelper.prototype.self();

            tableHelper.detailContent.bindUpdateEvents(callbackOptions.updateEventsBeforeCallback,callbackOptions.updateEventsAfterCallback);
            tableHelper.detailContent.bindEditEvents();
            tableHelper.detailContent.bindDeleteEvents(callbackOptions.deleteEventsBeforeCallback);
            tableHelper.detailContent.bindAutoComplete();
            tableHelper.detailContent.bindSaveTransactionEvent(callbackOptions.saveEventsBeforeCallback);
        }
    },

    headContent : {
        bindDeleteEvents : function(onBeforeSubmit, onAfterDelete)
        {
            var tableHelper = TableHelper.prototype.self();

            $('.' + tableHelper._settings.deleteClass).live('click',function(){
                globalRownIndex    = $(this).parent().index();
                globaId             = tableHelper.contentProvider._getColumnData(globalRownIndex,'id');

                if (globaId != 0) 
                    $('#' + tableHelper._settings.deleteModalID).modal('show');
            });

            $('#' + tableHelper._settings.deleteID).click(function(){
                if (tableHelper._flag == 1)
                    return;

                tableHelper._flag = 1;

                var rowIndex       = globalRownIndex;

                if (onBeforeSubmit) 
                    var arr = onBeforeSubmit($(this));
                else
                {
                    var head_id_val  = globaId;
                    var arr =   { 
                                    fnc       : tableHelper._settings.deleteHeadName, 
                                    head_id   : head_id_val
                                };
                }
               
                $.ajax({
                    type: "POST",
                    dataType : 'JSON',
                    data: 'data=' + JSON.stringify(arr) + token,
                    success: function(response) {
                        clear_message_box();

                        if (response.error != '') 
                            build_message_box('messagebox_2',response.error,'danger');
                        else
                        {
                            tableHelper._jsTable.delete_row(rowIndex);
                            tableHelper.contentProvider.recomputeRowNumber();

                            if (tableHelper._jsTable.get_row_count() == 1) 
                                $('#' + tableHelper._settings.tableID).hide();

                            $('#' + tableHelper._settings.deleteModalID).modal('hide');

                            if (onAfterDelete)
                                onAfterDelete();
                        }

                        _flag = 0;
                    }       
                });
            });

            $('#' + tableHelper._settings.deleteModalID).live('hidden.bs.modal', function (e) {
                globalRownIndex = 0;
                globaId         = 0;
            });
        },

        bindSearchEvent : function(onBeforeSubmit)
        {
            $('#' + tableHelper._settings.searchButtonID).click(function(){
                tableHelper.helpers._refreshTable(onBeforeSubmit);
            });
        },

        bindViewEvent : function()
        {
            $('.' + tableHelper._settings.columnClass).live('click',function(){
                var rowIndex   = $(this).parent().index();
                var headId   = tableHelper.helpers._getColumnData(rowIndex,'id');

                window.open(tableHelper._settings.baseURL + tableHelper._settings.controller + '/view/' + headId);
            });
        },

        bindCreateReferenceEvent : function ()
        {
            $('#' + tableHelper._settings.createButtonId).click(function(){
                if (tableHelper._flag == 1) 
                    return;

                tableHelper._flag = 1;

                var arr = { fnc : 'create_reference_number' };

                $.ajax({
                    type: "POST",
                    dataType : 'JSON',
                    data: 'data=' + JSON.stringify(arr) + token,
                    success: function(response) {
                        clear_message_box();

                        if (response.error != '') 
                            build_message_box('messagebox_1',response.error,'danger');
                        else
                            window.location = tableHelper._settings.baseURL + tableHelper._settings.controller + '/view/' + response.id;

                        _flag = 0;
                    }       
                });
            });
        },
        bindAllEvents : function (callbackOptions)
        {
            var tableHelper = TableHelper.prototype.self();

            tableHelper.headContent.bindDeleteEvents(callbackOptions.deleteEventsBeforeCallback,callbackOptions.deleteEventsAfterCallback);
            tableHelper.headContent.bindSearchEvent(callbackOptions.searchEventsBeforeCallback);
            tableHelper.headContent.bindViewEvent();
            tableHelper.headContent.bindCreateReferenceEvent();
        }
    },

    helpers : {
        _callUpdate : function(element,onBeforeSubmit,onAfterSubmit)
        {
            var tableHelper = TableHelper.prototype.self();

            if (tableHelper._flag == 1) 
                return;

            tableHelper._flag = 1;

            var rowIndex = $(element).parent().parent().index();

            if (onBeforeSubmit) 
                var arr = onBeforeSubmit(element);
            else
            {
                var rowIndex       = $(element).parent().parent().index();
                var product_id_val  = tableHelper.helpers._getColumnData(rowIndex,'product',1);
                var qty_val         = tableHelper.helpers._getColumnData(rowIndex,'qty');
                var memo_val        = tableHelper.helpers._getColumnData(rowIndex,'memo');
                var id_val          = tableHelper.helpers._getColumnData(rowIndex,'id');
                var fnc_val         = id_val != 0 ? tableHelper._settings.updateDetailName : tableHelper._settings.insertDetailName;

                var arr =   { 
                                fnc         : fnc_val, 
                                product_id  : product_id_val,
                                qty         : qty_val,
                                memo        : memo_val,
                                detail_id   : id_val
                            };
            }
            
            $.ajax({
                type: "POST",
                dataType : 'JSON',
                data: 'data=' + JSON.stringify(arr) + token,
                success: function(response) {
                    clear_message_box();

                    if (response.error != '') 
                        build_message_box('messagebox_1',response.error,'danger');
                    else
                    {
                        tableHelper._jsTable.update_row(rowIndex);

                        if (arr.detail_id == 0) {
                            tableHelper.helpers._setColumnData(rowIndex,'id',[response.id]);
                            tableHelper.helpers._addRow(_settings.productClass);
                        }

                        if (onAfterSubmit)
                            onAfterSubmit(rowIndex,response);
                    }

                    _flag = 0;
                }       
            });
        },

        _addRow : function(element,tableObject,tableObjectArray)
        {
            var tableHelper = TableHelper.prototype.self();

            var ok = true;

            var jsTable = tableHelper._jsTable;
            var jsTableArray = tableHelper._jsTableArray;

            if (tableObject) 
            {
                jsTable = tableObject;
                jsTableArray = tableObjectArray;
            };

            var lastRowIndex = jsTable.get_row_count();
            jsTable.add_new_row();
            jsTable.setvalue_to_rowindex_tdclass([lastRowIndex],lastRowIndex,jsTableArray['number'].td_class);

            if (element) 
                $('.' + element + ':last').focus();
        },

        _getColumnData : function(rowIndex,tableObjectArrayColumn,arrayColumnIndex,tableObject,tableObjectArray)
        {
            var tableHelper = TableHelper.prototype.self();

            var value = "";
            var column_index = 0;
            var jsTable = tableHelper._jsTable;
            var jsTableArray = tableHelper._jsTableArray;

            if (arrayColumnIndex) 
                column_index = arrayColumnIndex;

            if (tableObject) 
            {
                jsTable = tableObject;
                jsTableArray = tableObjectArray;
            };

            value = jsTable.getvalue_by_rowindex_tdclass(rowIndex, jsTableArray[tableObjectArrayColumn].td_class)[column_index];

            return value;
        },

        _setColumnData : function(rowIndex,tableObjectArrayColumn,values,tableObject,tableObjectArray)
        {
            var tableHelper = TableHelper.prototype.self();

            var jsTable = tableHelper._jsTable;
            var jsTableArray = tableHelper._jsTableArray;

            if (tableObject) 
            {
                jsTable = tableObject;
                jsTableArray = tableObjectArray;
            };

            jsTable.setvalue_to_rowindex_tdclass(values,rowIndex,jsTableArray[tableObjectArrayColumn].td_class);
        },

        _refreshTable : function(onBeforeSubmit)
        {
            var tableHelper = TableHelper.prototype.self();

            if (tableHelper._flag == 1)
                return;

            var arr = onBeforeSubmit();

            _flag = 1;

            $('#' + tableHelper._settings.loadingImgID).show();

            $.ajax({
                type: "POST",
                dataType : 'JSON',
                data: 'data=' + JSON.stringify(arr) + token,
                success: function(response) {
                    tableHelper._jsTable.clear_table();
                    clear_message_box();

                    if (response.rowcnt == 0) 
                    {
                        $('#' + tableHelper._settings.tableID).hide();
                        build_message_box('messagebox_1','No entry found!','info');
                    }
                    else
                    {
                        if(response.rowcnt <= 10)
                            tableHelper._jsTable.mypage.set_last_page(1);
                        else
                            tableHelper._jsTable.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(_jsTable.mypage.filter_number)));

                        tableHelper._jsTable.insert_multiplerow_with_value(1,response.data);

                        $('#' + tableHelper._settings.tableID).show();
                    }

                    $('#' + tableHelper._settings.loadingImgID).hide();
                    
                    tableHelper._flag = 0;
                }       
            });
        }
    }
}

