/**
 * Version: 1.0
 * Added Helper functions for MYJSTABLE. Current method supports js table, and other methods were specifically 
 * created for Hi - Top Project.
 **********************************************************
 * Created by Lawrence Pena.
 * Date Created: 6/16/2015
 * Version: 1.0
 */

window.TABLE = {};

var _flag = 0;

var _jsTable;
var _jsTableArray;

/**
 * Settings
 * @type {Object}
 */
var _settings = { 
    updateClass : 'imgupdate',
    deleteClass : 'tddelete',
    editClass   : 'imgedit',
    memoClass   : 'txtmemo',
    deleteID    : 'delete',
    quantityClass : 'txtqty',
    totalQtyID  : 'total_qty',
    modalID     : 'deleteModal'
};

TABLE.EventHelper = function(tableOptions,options) { 
    // Overrides current settings
    if (options) {
        $.extend(settings,options);
    }

    // Bind table to be used for the helper
    if (tableOptions) {
        _jsTable = tableOptions.tableObject;
        _jsTableArray = tableOptions.tableArray;
    };
}


TABLE.EventHelper.prototype = {
    
    addRow : function(element,tableObject,tableObjectArray){
        _addRow(element,tableObject,tableObjectArray);
    },

    getData : function(rowIndex,tableObjectArrayColumn,arrayColumnIndex,tableObject,tableObjectArray){
        return _getColumnData(rowIndex,tableObjectArrayColumn,arrayColumnIndex,tableObject,tableObjectArray);
    },

    setData : function(rowIndex,tableObjectArrayColumn,values,tableObject,tableObjectArray){
        _setColumnData(rowIndex,tableObjectArrayColumn,values,tableObject,tableObjectArray);
    },

    getElement : function(rowIndex,tableObjectArrayColumn,tableObject,tableObjectArray){
        var jsTable = _jsTable;
        var jsTableArray = _jsTableArray;

        if (tableObject) 
        {
            jsTable = tableObject;
            jsTableArray = tableObjectArray;
        };

        return jsTable.getelem_by_rowindex_tdclass(rowIndex,jsTableArray[tableObjectArrayColumn].td_class);
    },

    recomputeRowNumber : function(tableObject,tableObjectArray){
        var jsTable = _jsTable;
        var jsTableArray = _jsTableArray;

        if (tableObject) 
        {
            jsTable = tableObject;
            jsTableArray = tableObjectArray
        };

        for (var i = 1; i < jsTable.get_row_count(); i++)
            jsTable.setvalue_to_rowindex_tdclass([i],i,jsTableArray["number"].td_class);
    },

    bindUpdateEvents : function(onBeforeSubmit,onAfterSubmit){
        $('.' + _settings.updateClass).live('click',function(){
            _callUpdate($(this),onBeforeSubmit,onAfterSubmit);
        });

        $('.' + _settings.memoClass).live('keydown',function(e){
            if (e.keyCode == 13) {
                _callUpdate($(this),onBeforeSubmit,onAfterSubmit);
                e.preventDefault();
            };
        });
    },

    bindAutoComplete : function (token,backstage,onAfterSubmit)
    {
        my_autocomplete_add(token,".txtproduct",backstage, {
            enable_add : false,
            fnc_callback : function(x, label, value, ret_datas, error){
                var row_index = $(x).parent().parent().index();
                if (onAfterSubmit) {
                    onAfterSubmit(row_index, error, ret_datas);
                }else{
                    if (error.length > 0) {
                        _setColumnData(row_index,'product',['',0]);
                        _setColumnData(row_index,'code',['']);
                        _setColumnData(row_index,'qty',['']);
                        _setColumnData(row_index,'inventory',['']);
                        _setColumnData(row_index,'memo',['']);
                    }
                    else
                    {
                        _setColumnData(row_index,'product',[ret_datas[1],ret_datas[0]]);
                        _setColumnData(row_index,'code',[ret_datas[2]]);
                        _setColumnData(row_index,'inventory',[ret_datas[3]]);
                    }
                }
                
            },
            fnc_render : function(ul, item){
                return my_autocomplete_render_fnc(ul, item, "code_name", [2,1], 
                    { width : ["100px","auto"] });
            }
        });
    }
}

function _callUpdate(element,onBeforeSubmit,onAfterSubmit)
{
    if (_flag == 1) 
        return;

    _flag = 1;

    var rowIndex = $(element).parent().parent().index();
    var arr = onBeforeSubmit(element);

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
                _jsTable.update_row(rowIndex);

                if (arr.detail_id == 0) {
                    _setColumnData(rowIndex,'id',[response.id]);
                    _addRow('txtproduct');
                }

                if (onAfterSubmit)
                    onAfterSubmit(rowIndex,response);
            }

            _flag = 0;
        }       
    });
}

function _addRow(element,tableObject,tableObjectArray)
{
    var ok = true;

    var jsTable = _jsTable;
    var jsTableArray = _jsTableArray;

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
}

function _getColumnData(rowIndex,tableObjectArrayColumn,arrayColumnIndex,tableObject,tableObjectArray)
{
    var value = "";
    var column_index = 0;
    var jsTable = _jsTable;
    var jsTableArray = _jsTableArray;

    if (arrayColumnIndex) 
        column_index = arrayColumnIndex;

    if (tableObject) 
    {
        jsTable = tableObject;
        jsTableArray = tableObjectArray;
    };

    value = jsTable.getvalue_by_rowindex_tdclass(rowIndex, jsTableArray[tableObjectArrayColumn].td_class)[column_index];

    return value;
}

function _setColumnData(rowIndex,tableObjectArrayColumn,values,tableObject,tableObjectArray)
{
    var jsTable = _jsTable;
    var jsTableArray = _jsTableArray;

    if (tableObject) 
    {
        jsTable = tableObject;
        jsTableArray = tableObjectArray;
    };

    jsTable.setvalue_to_rowindex_tdclass(values,rowIndex,jsTableArray[tableObjectArrayColumn].td_class);
}
