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

    $.objectToQueryString = function(object){
        var queryString = "";

        $.each(object, function(index, value){
            queryString += "&" + index + "=" + value;
        });

        return queryString.substring(1);
    };

    $.dataValidation = function(data){
        var numericReg = /[^0-9-]/;
        var alphaNumericCharReg = /[^A-Za-z0-9 '".\/]/;
        var alphaNumericReg = /[^A-Za-z0-9 ]/;
        var codeReg = /[^A-Za-z0-9]/;
        var letterReg = /[^A-Za-z ]/;
        var letterWthCharReg = /[^A-Za-z '".\/]/;
        var credentialReg = /[^A-Za-z0-9_@.!]/;

        var error = [];

        for (var i = 0; i < data.length; i++) {
            var result;

            if (data[i].required && data[i].required == true) {
                if ($.trim(data[i].value) == '')
                {
                    error.push(data[i].fieldName + ' should not be empty!');
                    continue;
                }
            }

            if (data[i].rules && $.trim(data[i].value) != '') {
                switch(data[i].rules){
                    case 'numeric':
                        result = isNaN(data[i].value);
                        if (result) 
                            error.push(data[i].fieldName + ' should only contain numbers!'); 
                        break;

                    case 'alphaNumericChar':
                        result = alphaNumericCharReg.test(data[i].value);
                        if (result) 
                            error.push(data[i].fieldName + ' should only contain numbers, letters and characters!');
                        break;

                    case 'alphaNumeric':
                        result = alphaNumericReg.test(data[i].value);
                        if (result) 
                            error.push(data[i].fieldName + ' should only contain numbers and letters!');
                        break;

                    case 'letter':
                        result = letterReg.test(data[i].value);
                        if (result) 
                            error.push(data[i].fieldName + ' should only contain letters!');
                        break;

                    case 'letterChar':
                        result = letterWthCharReg.test(data[i].value);
                        if (result) 
                            error.push(data[i].fieldName + ' should only contain letters and characters!');
                        break;

                    case 'code':
                        result = codeReg.test(data[i].value);
                        if (result) 
                            error.push(data[i].fieldName + ' should only contain numbers and letters!');
                        break;

                    case 'credential':
                        result = credentialReg.test(data[i].value);
                        if (result) 
                            error.push(data[i].fieldName + ' should only contain letters, numbers and characters!');
                        break;
                }       
            };

            if (data[i].minLength)
            {
                if (data[i].value.length < data[i].minLength)
                    error.push(data[i].fieldName + ' should at least contain ' + data[i].minLength + ' characters!');
            }

            if (data[i].isNotEqual) 
            {
                if (data[i].value == data[i].isNotEqual.value)
                    error.push(data[i].isNotEqual.errorMessage);
            };
        };

        return error;
    };
})(jQuery);