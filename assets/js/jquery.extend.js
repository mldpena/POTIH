(function($) {

    $.ucfirst = function(str) {

        var text = str;


        var parts = text.split(' '),
            len = parts.length,
            i, words = [];
        for (i = 0; i < len; i++) {
            var part = parts[i];
            var first = part[0].toUpperCase();
            var rest = part.substring(1, part.length);
            var word = first + rest;
            words.push(word);

        }

        return words.join(' ');
    };

    $.getEnumString = function(enumObject, numericValue){
        var description = '';
        
        $.each(enumObject, function(key,value) {
            if (value.value) {
                if (value.value == numericValue) {
                    description = value.name;
                };
            }else{
                if (value == numericValue) {
                    description = key;
                };
            }
        });

        return description;
    };

    $.getEnumValue = function(enumObject, stringValue){
        var equivalentValue = 0;
        
        $.each(enumObject, function(key,value) {
            if (value.name) {
                if (value.name == stringValue) {
                    equivalentValue = value.value;
                };
            }else{
                if (key == stringValue) {
                    equivalentValue = value;
                };
            }
        });

        return equivalentValue;
    };

    $.dataValidation = function(data){
        var numericReg = /[^0-9]/;
        /*var arr = [{ value : ss,
                    fieldName : '',
                    required : false,
                    rules : 'numeric' }];*/

        var error = [];

        for (var i = 0; i < data.length; i++) {
            var result;

            if (data[i].required && data[i].required == true) {
                if (data[i].value == '' || $.trim(data[i].value) == '')
                    error.push(data[i].fieldName + ' should not be empty!');
            }else{
                if (data[i].rules) {
                    switch(data[i].rules){
                        case 'numeric':
                            result = numericReg.test(data[i].value);
                            break;
                    }

                    if (result) 
                        error.push(data[i].fieldName + ' should only contain numbers!');  
                };
            }
        };

        return error;
    };
})(jQuery);