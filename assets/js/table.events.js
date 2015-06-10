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
            _callAjaxUpdate($(this),callback);
        });

        $('.' + _settings.memoClass).live('keydown',function(e){
            if (e.keyCode == 13) {
                _callAjaxUpdate($(this),callback);
                e.preventDefault();
            };
        });
    },

    addRow : function(object,object_array,element_focus){
        _addRow(object,object_array,element_focus);
    },

    getData : function(row_index,object_array_column,array_column_index,object,object_array){
        return _getColumnData(row_index,object_array_column,array_column_index,object,object_array);
    },

    setData : function(row_index,object_array_column,values,object,object_array){
        _setColumnData(row_index,object_array_column,values,object,object_array);
    }
}

function _callAjaxUpdate(element,callback)
{
    if (_flag == 1) 
        return;

    _flag = 1;

    var row_index = $(element).parent().parent().index();
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
                myjstbl.update_row(row_index);

                if (arr.detail_id == 0) {
                    _setColumnData(row_index,'id',[response.id]);
                    _addRow(myjstbl,colarray,'txtproduct');
                }

            }

            _flag = 0;
        }       
    });
}

function _addRow(object,object_array,element_focus)
{
    var ok = true;
    var count = object.get_row_count() - 1;

    if (count > 0) {
        var id =  object.getvalue_by_rowindex_tdclass(count,object_array["id"].td_class);
        if (id == 0)
            ok = false;
    }

    if (ok == false) 
        return;

    var last_row_index = object.get_row_count();
    object.add_new_row();
    object.setvalue_to_rowindex_tdclass([last_row_index],last_row_index,object_array['number'].td_class);

    if (element_focus) 
        $('.' + element_focus + ':last').focus();
}

function _getColumnData(row_index,object_array_column,array_column_index,object,object_array)
{
    var value = "";
    var column_index = 0;
    var js_table = myjstbl;
    var js_table_array = colarray;

    if (array_column_index) 
        column_index = array_column_index;

    if (object) 
    {
        js_table = object;
        js_table_array = object_array;
    };

    value = js_table.getvalue_by_rowindex_tdclass(row_index, js_table_array[object_array_column].td_class)[column_index];

    return value;
}

function _setColumnData(row_index,object_array_column,values,object,object_array)
{
    var js_table = myjstbl;
    var js_table_array = colarray;

    if (object) 
    {
        js_table = object;
        js_table_array = object_array;
    };

    js_table.setvalue_to_rowindex_tdclass(values,row_index,js_table_array[object_array_column].td_class);
}
