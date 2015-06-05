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

TABLE.EventHelper = function() { }


TABLE.EventHelper.prototype = {
    init: function(options) {
        if (options) {
            $.extend(settings,options);
        }
    },
    bindUpdateEvents: function(callback){
        $('.' + _settings.updateClass).live('click',function(){
            _callAjaxUpdate($(this),callback);
        });

        $('.' + _settings.memoClass).live('keydown',function(e){
            if (e.keyCode == 13) {
                _callAjaxUpdate($(this),callback);
                e.preventDefault();
            };
        });
    }
}

function _callAjaxUpdate(element,callback)
{
    if (_flag == 1) 
        return;

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

                if (arr.id_val == 0) {
                    table_set_column_data(row_index,'id',[response.id]);
                    add_new_row(myjstbl,colarray,'txtproduct');
                }

            }

            flag = 0;
        }       
    });
}
