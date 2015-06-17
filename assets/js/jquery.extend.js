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

})(jQuery);