(function($){
    "use strict";

    $.redux = $.redux || {};
	var the_body = $(document);
    var activeTinyEditor = '';

    $(document).ready(function () {
        
        
        the_body.on('dialog_finished', function(event, window){
            //render all methods
            $.each($.redux, function( method, value ) {
                 $.redux[method]();  // call the function
            });
        });

        the_body.on('check_dependencies', function(event,variable){    
            $.check_dependencies(event,variable);
        });
       
    
    });

    $.ui.dialog.prototype._allowInteraction = function(e) {
        return !!$(e.target).closest('.ui-dialog, .ui-datepicker, .select2-drop').length;
    };

    
    $.redux.editor = function(){
        jQuery("textarea.redux-editor").each(function(){
           
            var el_id   = this.id,
            current     = jQuery(this), 
            parent      = current.parents('.wp-editor-wrap:eq(0)'),
            textarea    = parent.find('textarea.redux-editor'),
            switch_btn  = parent.find('.wp-switch-editor').removeAttr("onclick"),
            settings    = {
                id: this.id , 
                buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close"
            };
            
            // add quicktags for text editor
            quicktags(settings);
            QTags._buttonsInit(); //workaround since dom ready was triggered already and there would be no initialization

            // modify behavior for html editor
            switch_btn.bind('click', function(){
                var button = jQuery(this);
                
                if(button.is('.switch-tmce')){
                    parent.removeClass('html-active').addClass('tmce-active');
                    window.tinyMCE.execCommand("mceAddControl", true, el_id);
                    //work around to fix inserting media into the same editor
                    wpActiveEditor = tinymce.activeEditor.id;
                    window.tinyMCE.get(el_id).setContent(window.switchEditors.wpautop(textarea.val()), {format:'raw'});
//                    parent.removeClass('html-active').addClass('tmce-active');
//                    tinyMCE.init({
//                        skin : "wp_theme",
//                        mode : "exact",
//                        elements :  el_id,
//                        theme: "advanced",
//                        //theme_advanced_buttons1  : "bold,italic,underline,blockquote,strikethrough,bullist,numlist,justifyleft, justifycenter, justifyright, undo, redo,link,unlink,fullscreen",
//                        theme_advanced_buttons1  : "bold, italic, strikethrough, bullist, numlist, blockquote, justifyleft, justifycenter, justifyright, link, unlink, wp_more, spellchecker, fullscreen, wp_adv",
//                        theme_advanced_buttons2  : "formatselect, underline, justifyfull, forecolor, pastetext, pasteword, removeformat, charmap, outdent, indent, undo, redo, wp_help",
//                    });
//
                    //work around to fix inserting media into the same editor
                    wpActiveEditor = tinymce.activeEditor.id;
//
//                    window.tinyMCE.get(el_id).setContent(window.switchEditors.wpautop(textarea.val()), {
//                        format:'raw'
//                    });
                }
                else
                {
                    parent.removeClass('tmce-active').addClass('html-active');
                    window.tinyMCE.execCommand("mceRemoveControl", true, el_id);
                }
            }).trigger('click');

            
            //make sure that when the save button is pressed the textarea gets updated and sent to the editor
            $("#dialog").find('.ui-button').bind('click', function(){
                switch_btn.filter('.switch-html').trigger('click');
            });

        });
    }


    $.redux.required = function(){

        // Hide the fold elements on load ,
        // It's better to do this by PHP but there is no filter in tr tag , so is not possible
        // we going to move each attributes we may need for folding to tr tag
        $('.hiddenFold , .showFold').each(function() {
            var current     = $(this), 
            scope           = current.parents('tr:eq(0)'),
            check_data      = current.data();

            if(current.hasClass('hiddenFold')){
                scope.addClass('hiddenFold').attr('data-check-field' , check_data.checkField)
                    .attr('data-check-comparison' , check_data.checkComparison)
                    .attr('data-check-value' , check_data.checkValue)
                    .attr('data-check-id' , check_data.id).hide();
                //we clean here, so we won't get confuse    
                current.removeClass('hiddenFold').removeAttr('data-check-field')
                    .removeAttr('data-check-comparison')
                    .removeAttr('data-check-value');    
            }else{
                scope.attr('data-check-field' , check_data.checkField)
                    .attr('data-check-comparison' , check_data.checkComparison)
                    .attr('data-check-value' , check_data.checkValue)
                    .attr('data-check-id' , check_data.id);
                //we clean here, so we won't get confuse    
                current.removeClass('showFold').removeAttr('data-check-field')
                    .removeAttr('data-check-comparison')
                    .removeAttr('data-check-value');    
            }
        });

        $( ".fold" ).promise().done(function() {
            // Hide the fold elements on load
            $('.foldParent').each(function() {
                var id = $(this).parents('.redux-field:first').data('id');
                if ( redux_opts.folds[ id ] ) {
                    if ( !redux_opts.folds[ id ].parent  ) {
                        
                        $.verify_fold($(this));
                    }
                }
            });
        });

        the_body.on('change', '#redux-main select, #redux-main radio, #redux-main input[type=checkbox], #redux-main input[type=hidden]', function(e){

            $.check_dependencies(e,this);
            
        });
    }

    $.check_dependencies = function(e,variable){
        
        var current     = $(variable),
            scope   = current.parents('.redux-group-tab:eq(0)');
 
        if(!scope.length) scope = the_body;

        var id      = current.parents('.redux-field:first').data('id'),
        dependent   = scope.find('tr[data-check-field="'+id+'"]'), 
        value1      = variable.value,
        is_hidden   = current.parents('tr:eq(0)').is('.hiddenFold');

        if(!dependent.length) return;

        dependent.each(function(){
            var current     = $(this), 
            check_data  = current.data(), 
            value2      = check_data.checkValue, 
            show        = false;
            
            if(!is_hidden){
                switch(check_data.checkComparison){
                    case '=':
                    case 'equals':
                        //if value was array
                        if (value2.toString().indexOf('|') !== -1){
                            var value2_array = value2.split('|');
                            if($.inArray( value1, value2_array ) != -1){
                                show = true;
                            }
                        }else{
                            if(value1 == value2) 
                                show = true;
                        }
                        break;
                    case '!=':    
                    case 'not':
                        //if value was array
                        if (value2.indexOf('|') !== -1){
                            var value2_array = value2.split('|');
                            if($.inArray( value1, value2_array ) == -1){
                                show = true;
                            }
                        }else{
                            if(value1 != value2) 
                                show = true;
                        }
                        break;
                    case '>':    
                    case 'greater':    
                    case 'is_larger':
                        if(value1 >  value2) 
                            show = true;
                        break;
                    case '<':
                    case 'less':    
                    case 'is_smaller':
                        if(value1 <  value2) 
                            show = true;
                        break;
                    case 'contains':
                        if(value1.indexOf(value2) != -1) 
                            show = true;
                        break;
                    case 'doesnt_contain':
                        if(value1.indexOf(value2) == -1) 
                            show = true;
                        break;
                    case 'is_empty_or':
                        if(value1 == "" || value1 == value2) 
                            show = true;
                        break;
                    case 'not_empty_and':
                        if(value1 != "" && value1 != value2) 
                            show = true;
                        break;
                        
                        
                }
            }
                
            /*if(show == true && current.is('.hiddenFold')){
                current.css({
                    display:'none'
                }).removeClass('hiddenFold').find('select, radio, input[type=checkbox]').trigger('change');
                current.slideDown(300);
            }else if(show == false  && !current.is('.hiddenFold')){
                current.css({
                    display:''
                }).addClass('hiddenFold').find('select, radio, input[type=checkbox]').trigger('change');
                current.slideUp(300);
            }*/
            $.verify_fold($(variable)); 
        });
    }

    $.verify_fold = function(item){
        var id = item.parents('.redux-field:first').data('id');
        var itemVal = item.val();
        var scope = (item.parents('.redux-groups-accordion-group:first').length > 0)?item.parents('.redux-groups-accordion-group:first'):item.parents('.redux-group-tab:eq(0)');

        if ( redux_opts.folds[ id ] ) {

            if ( redux_opts.folds[ id ].children ) {

                var theChildren = {};
                $.each(redux_opts.folds[ id ].children, function(index, value) {
                    $.each(value, function(index2, value2) { // Each of the children for this value
                        if ( ! theChildren[value2] ) { // Create an object if it's not there
                            theChildren[value2] = { show:false, hidden:false };
                        }
                        
                        if ( index == itemVal || theChildren[value2] === true ) { // Check to see if it's in the criteria
                            theChildren[value2].show = true;
                        }

                        if ( theChildren[value2].show === true && scope.find('tr[data-check-id="'+id+'"]').hasClass("hiddenFold") ) {
                            theChildren[value2].show = false; // If this item is hidden, hide this child
                        }

                        if ( theChildren[value2].show === true && scope.find('tr[data-check-id="'+redux_opts.folds[ id ].parent+'"]').hasClass('hiddenFold') ) {
                            theChildren[value2].show = false; // If the parent of the item is hidden, hide this child
                        }
                        // Current visibility of this child node
                        theChildren[value2].hidden = scope.find('tr[data-check-id="'+value2+'"]').hasClass("hiddenFold");
                    });
                });

                $.each(theChildren, function(index) {

                    var parent = scope.find('tr[data-check-id="'+index+'"]');
                    
                    if ( theChildren[index].show === true ) {

                        parent.fadeIn('medium', function() {
                            parent.removeClass('hiddenFold');
                            if ( redux_opts.folds[ index ] && redux_opts.folds[ index ].children ) {
                                // Now iterate the children
                                $.verify_fold(parent.find('select, radio, input[type=checkbox], input[type=hidden]'));
                            }
                        });

                    } else if ( theChildren[index].hidden === false ) {
                        
                        parent.fadeOut('medium', function() {
                            parent.addClass('hiddenFold');
                            if ( redux_opts.folds[ index ].children ) {
                                // Now iterate the children
                                $.verify_fold(parent.find('select, radio, input[type=checkbox], input[type=hidden]'));
                            }
                        });
                    }
                });
            }
        }   
    }

})(jQuery);



function redux_change(variable) {
    //We need this for switch and image select fields , jquery dosn't catch it on fly
    if(variable.is('input[type=hidden]') || jQuery(variable).parents('fieldset:eq(0)').is('.redux-container-image_select') )
        jQuery('body').trigger('check_dependencies' , variable);
}