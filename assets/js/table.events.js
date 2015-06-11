window.TABLE = {};

var _flag = 0;

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

TABLE.EventHelper = function(options) { 
    if (options) {
        $.extend(settings,options);
    }
}


TABLE.EventHelper.prototype = {
    bindUpdateEvents : function(callback){
        $('.' + _settings.updateClass).live('click',function(){
            _callUpdate($(this),callback);
        });

        $('.' + _settings.memoClass).live('keydown',function(e){
            if (e.keyCode == 13) {
                _callUpdate($(this),callback);
                e.preventDefault();
            };
        });
    },

    addRow : function(elementFocus,object,objectArray){
        _addRow(elementFocus,object,objectArray);
    },

    getData : function(rowIndex,objectArrayColumn,arrayColumnIndex,object,objectArray){
        return _getColumnData(rowIndex,objectArrayColumn,arrayColumnIndex,object,objectArray);
    },

    setData : function(rowIndex,objectArrayColumn,values,object,objectArray){
        _setColumnData(rowIndex,objectArrayColumn,values,object,objectArray);
    },

    getElement : function(rowIndex,objectArrayColumn,object,objectArray){
        var jsTable = myjstbl;
        var jsTableArray = colarray;

        if (object) 
        {
            jsTable = object;
            jsTableArray = objectArray;
        };

        return jsTable.getelem_by_rowindex_tdclass(rowIndex,jsTableArray[objectArrayColumn].td_class);
    },

    recomputeRowNumber : function(object,objectArray){
        var jsTable = myjstbl;
        var jsTableArray = colarray;

        if (object) 
        {
            jsTable = object;
            jsTableArray = objectArray
        };

        for (var i = 1; i < jsTable.get_row_count(); i++)
            jsTable.setvalue_to_rowindex_tdclass([i],i,jsTableArray["number"].td_class);
    }
}

function _callUpdate(element,callback)
{
    if (_flag == 1) 
        return;

    _flag = 1;

    var rowIndex = $(element).parent().parent().index();
    var arr = callback(element);

    $.ajax({
        type: "POST",
        dataType : 'JSON',
        data: 'data=' + JSON.stringify(arr) + token_val,
        success: function(response) {
            clear_message_box();

            if (response.error != '') 
                build_message_box('messagebox_1',response.error,'danger');
            else
            {
                myjstbl.update_row(rowIndex);

                if (arr.detail_id == 0) {
                    _setColumnData(rowIndex,'id',[response.id]);
                    _addRow(myjstbl,colarray,'txtproduct');
                }

            }

            _flag = 0;
        }       
    });
}

function _addRow(elementFocus,object,objectArray)
{
    var ok = true;

    var jsTable = myjstbl;
    var jsTableArray = colarray;

    if (object) 
    {
        jsTable = object;
        jsTableArray = objectArray;
    };

    /*var count = jsTable.get_row_count() - 1;

    if (count > 0) {
        var id =  jsTable.getvalue_by_rowindex_tdclass(count,jsTableArray["id"].td_class);
        if (id == 0)
            ok = false;
    }

    if (ok == false) 
        return;*/

    var lastRowIndex = jsTable.get_row_count();
    jsTable.add_new_row();
    jsTable.setvalue_to_rowindex_tdclass([lastRowIndex],lastRowIndex,jsTableArray['number'].td_class);

    if (elementFocus) 
        $('.' + element + ':last').focus();
}

function _getColumnData(rowIndex,objectArrayColumn,arrayColumnIndex,object,objectArray)
{
    var value = "";
    var column_index = 0;
    var jsTable = myjstbl;
    var jsTableArray = colarray;

    if (arrayColumnIndex) 
        column_index = arrayColumnIndex;

    if (object) 
    {
        jsTable = object;
        jsTableArray = objectArray;
    };

    value = jsTable.getvalue_by_rowindex_tdclass(rowIndex, jsTableArray[objectArrayColumn].td_class)[column_index];

    return value;
}

function _setColumnData(rowIndex,objectArrayColumn,values,object,objectArray)
{
    var jsTable = myjstbl;
    var jsTableArray = colarray;

    if (object) 
    {
        jsTable = object;
        jsTableArray = objectArray;
    };

    jsTable.setvalue_to_rowindex_tdclass(values,rowIndex,jsTableArray[objectArrayColumn].td_class);
}
