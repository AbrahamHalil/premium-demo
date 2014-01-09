(function($){
    "use strict";

    $.redux = $.redux || {};
    
    $(document).ready(function () {
        $.redux.linkpicker();
        $(document).on('dialog_finished', function(event, window){
            $("#dialog").find(".attach-templating select, .attach-templating radio, .attach-templating input[type=checkbox]").trigger('change');
        });
    });

    $.redux.linkpicker = function(){

        $("body").on('change','.attach-templating select, .attach-templating radio, .attach-templating input[type=checkbox]' , function(){

            var current = $(this),
            target  = current.next('.template-container');
            
            //if(!scope.length) scope = the_body;
            if(!target.length) return;
            
            var new_value   = this.value,
            template    = $("#tmpl-"+new_value);
            if(!template.length)
            {           
                template = $('<div />');
            }
                
            target.html(template.html());
            /*$('.template-container .redux-select-item').select2({
                width: 'resolve',
                triggerChange: true,
                allowClear: true
            });*/
        });
    }

})(jQuery);     