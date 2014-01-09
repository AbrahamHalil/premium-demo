(function($){
  'use strict';

  $.builder = $.builder || {};

  var big_loading = '<center><i style="margin: 20px auto;" class="icon-refresh icon-spin icon-4x icon-muted"></i></center>';

  var loading = '<i class="icon-refresh icon-spin"></i>';

  

  $(document).ready(function(){

    //Display Widget settings & saving it
    $.builder.widget_settings();  

    //Display section settings
    $.builder.section_settings();

    //$.builder.widgets();

  });

  /**
   * display widget settings & saving it
   */
  $.builder.widget_settings = function(){

    var insertto = "", module_index = "", msf_mid = "", msf_title = "";
        //Select Module
    $('.btnAddMoudule').live('click',function(e){
      e.preventDefault();
        insertto = '#'+this.rel+' .module';          
        module_index = this.rel;
        $( "#dialog" ).html( '<center><i style="margin: 20px auto;" class="icon-refresh icon-spin icon-4x icon-muted"></i></center>');
        //tb_show("Insert Module","admin-ajax.php?page=redux&action=insert_module&modal=1&width=510&height=500")
        //$( "#dialog" ).dialog( 'option', 'position',{my:"top+50px,center"});
        $( "#dialog" ).dialog( 'option', 'width',940);
        //$( "#dialog" ).dialog( 'option', 'height',600);
        $( "#dialog" ).dialog( 'option', 'title',redux_opr.modules);
        $( "#dialog" ).dialog( "open" ).load("admin-ajax.php?page=redux&action=insert_module&modal=1&width=770&height=600");
        return false;
    });

    //display widget settings
    $('.insert').live('click',function(e){
      e.preventDefault();
        msf_mid = $(this).attr('rel');
        msf_title = this.title;
        var data = $(this).attr('data')==undefined?"":"&instance="+jQuery(this).attr('data');
        var datafield = jQuery(this).attr('datafield')==undefined?"":"&datafield="+jQuery(this).attr('datafield');          
        var post = "&post="+pageid;
        var data_inst = jQuery('#'+jQuery(this).attr('datafield')).val();
        var title = jQuery(this).closest('h3').find('.title').html();

        $( "#dialog" ).dialog( 'option', 'title',title);
        $( "#dialog" ).html(big_loading);
        $( "#dialog" ).dialog( 'option', 'width',900);
        $( "#dialog" ).dialog('open')
        .load("admin-ajax.php?page=redux&action=module_settings&modal=1&width=510&height=500&module="+msf_mid+data+datafield+post,{data_inst:data_inst} ,function() {
              $(document).trigger('dialog_finished');
         });

        return false;
    });

    //Saving new widget settings 
    $('#module-settings-form').live('submit',function(e){
      e.preventDefault();
        $("#submit_module").html(loading);
        $(this).ajaxSubmit({              
            url:ajaxurl+'?page=redux&action=module_settings_data',
            success:function(res){
                var d = new Date();
                var z = d.getTime();
                //TODO
                $(insertto).append('<li id="module_'+module_index+'_'+z+'" rel="'+module_index+'"><input type="hidden" id="modid_module_'+module_index+'_'+z+'" name="modules['+module_index+'][]" value="'+msf_mid+'" /><input type="hidden" name="modules_settings['+module_index+'][]" id="modset_module_'+module_index+'_'+z+'" value="'+res+'" /><h3><nobr class="title">'+msf_title+'</nobr><nobr class="ctl"><i class="handle icon-move"></i>&nbsp;<i class="icon-copy duplicate_module"></i>&nbsp;<i class="icon-trash icon-large delete_module" rel="#module_'+module_index+'_'+z+'"></i>&nbsp;<i class="insert icon-pencil icon-large" rel="'+msf_mid+'" datafield="modset_module_'+module_index+'_'+z+'" data="'+module_index+'|0"></i></nobr></h3><div class="module-preview w3eden">'+big_loading+'</div><div class="clear"</div></li>');
                $( insertto ).sortable({handle : '.handle', connectWith: "ul.module",placeholder: "ui-state-highlight",forcePlaceholderSize: true});
                $( insertto ).disableSelection({handle : '.handle'});   
                $("#dialog").html(big_loading);               
                $('#dialog').dialog('close');
                $('#module_'+module_index+'_'+z+' .module-preview').load(ajaxurl+'?page=redux&action=get_module_preview',{mod:msf_mid, modinfo:res});

                //jQuery('.module').trigger('sortupdate');

               
            }   
        });
        
        return false;
    });

    //updating widget settings
    $('#update-module-settings-form').live('submit',function(e){
      e.preventDefault();
      var datafield = $(this).attr('datafield');
      $("#submit_module").html(loading);
      $(this).ajaxSubmit({              
          url:ajaxurl+'?page=redux&action=module_settings_data',
          success:function(res){
              //alert('#'+datafield);
              $('#'+datafield).val(res);
              //$('#'+datafield+"_icon").attr('data',res);
              $("#dialog").html(loading);
              $('#dialog').dialog('close');
              var mod = datafield.replace("modset_","");
              var msf_mid =   datafield.replace("modset_","modid_");
              msf_mid = $('#'+msf_mid).val();
              $('#'+mod+' .module-preview').html(big_loading);
              $('#'+mod+' .module-preview').load(ajaxurl+'?page=redux&action=get_module_preview',{mod:msf_mid, modinfo:res});
          }   
      });
        
      return false;
    });

  }

  /**
   * Display Section Settings & saving 
   */
  $.builder.section_settings = function(){
    
    //display section settings
    $('.rsettings').live('click',function(e){
      e.preventDefault();
        var section_settings_id = "",section_settings_data="";
        section_settings_id = $(this).attr('rel');          
        section_settings_data = $('#'+section_settings_id).val();
        $( "#dialog" ).dialog( 'option', 'title','Section Settings');
        $( "#dialog" ).dialog( 'option', 'width',900);
        $( "#dialog" ).html( '<center><i style="margin: 20px auto;" class="icon-refresh icon-spin icon-4x icon-muted"></i></center>');
        $( "#dialog" ).dialog( "open" )
        .load("admin-ajax.php",
            { page: "redux", action: "section_settings",section_settings_id: section_settings_id, section_settings_data: section_settings_data, modal: "1", width: "400", height: "200"  },function(){
          $(document).trigger('dialog_finished');
        });
        return false;
    });

    //saving section settings
    $('#section-settings-form').live('submit',function(e){
      e.preventDefault();
      var section_settings_id = $(this).attr('rel');
      //$(this).append('<i class="icon-refresh icon-spin"></i>');
      $("#submit_module").html('<i class="icon-refresh icon-spin"></i>');
      $(this).ajaxSubmit({                        
          url:ajaxurl+'?page=redux&action=section_settings_data',
          success:function(res){
              $('#'+section_settings_id).val(res);
              $("#dialog").dialog("close");
          }   
      });
      
      return false;
    });
  }

  $.builder.widgets = function(){

  }

})(jQuery);

jQuery(function(){
            jQuery("#content-html").before('<a onclick="switchtoredux();" class="hide-if-no-js wp-switch-editor switch-redux" id="content-redux">'+redux_opr.builder+'</a>');
            //if(pageid!='')
            //var mxnb = "<div style='float:right;' class='ghbutton-group'><a class='ghbutton icon arrowdown' href='"+userSettings.url+"wp-admin/?reduxexport="+pageid+"' >Export Layout</a><a class='ghbutton icon arrowup import-layout' rel='"+pageid+"' href='#' >Import Layout</a><a class='ghbutton icon loop' href='"+userSettings.url+"wp-admin/?reduxclone="+pageid+"' >Clone</a></div>";
            //else
            //var mxnb = "<div style='float:right;' class='ghbutton-group'><a class='ghbutton icon arrowdown' href='#' onclick='alert(\""+post_type+" is not published or saved yet!\");return false;' >Export Layout</a><a class='ghbutton icon arrowup' href='#' onclick='alert(\""+post_type+" is not published or saved yet!\");return false;' >Import Layout</a><a class='ghbutton icon loop' href='#' onclick='alert(\""+post_type+" is not published or saved yet!\");return false;' >Clone</a></div>";
            var mxnb = '';
            jQuery("#wp-content-wrap").append("<div id='redux-builder' class='wp-editor-container' style='display:none'><div class='quicktags-toolbar redux-toolbar'><div class='ghbutton-group'><a class='insert-layout ghbutton ' holder='#layout_"+post_type+"' rel='col-1' href='#' >1 "+redux_opr.col+"</a><a class='insert-layout ghbutton ' holder='#layout_"+post_type+"' rel='col-2' href='#' >2 "+redux_opr.cols+"</a><a class='insert-layout ghbutton ' holder='#layout_"+post_type+"' rel='col-3' href='#' >3 "+redux_opr.cols+"</a><a class='insert-layout ghbutton ' holder='#layout_"+post_type+"' rel='col-4' href='#' >4 "+redux_opr.cols+"</a><a class='insert-layout ghbutton ' holder='#layout_"+post_type+"' rel='col-5' href='#' >5 "+redux_opr.cols+"</a><a class='insert-layout ghbutton ' holder='#layout_"+post_type+"' rel='col-6' href='#' >6 "+redux_opr.cols+"</a></div>"+mxnb+"</div><div id='lbrd'></div></div>");
            jQuery('#lbrd').html(jQuery('#'+post_type+'-redux-layout-builder .inside').html());
            jQuery('#'+post_type+'-redux-layout-builder .inside').html('');            
            jQuery('#content-html,#content-tmce').click(function(){             
            jQuery('.wp-switch-editor').removeClass('tactive');     
            if(this.id=='content-tmce') {jQuery('#ed_toolbar').hide(); jQuery('#content-tmce').addClass('tactive');   }
            if(this.id=='content-html') jQuery('#ed_toolbar').show();   
            jQuery('#post-status-info').show();     
            jQuery('#wp-content-editor-container').show();
            jQuery('#redux-builder').hide();            
            jQuery('.export,.import,.clone').css('visibility','hidden');
            jQuery('#content-redux').removeClass('tactive');
            jQuery.cookie('active_mx_'+pageid,'0');
            });
            
            jQuery(window).resize(function(){
                reset_layout_width();                
            });
    
      jQuery('.module-preview.w3eden *').live('click',function(e){
          e.preventDefault();
      });
      
      jQuery('.mwdth').live('click',function(e){
        e.preventDefault();
              var prts = this.id.split("_");
              var grid = jQuery('#'+prts[1]+'_'+prts[2]).val();
              var tgrid = grid;
              var col = prts[2];
              var rowid = prts[1];
              var cols = jQuery(this).attr('cols');
              grid = parseInt(grid);
              col = parseInt(col);
              cols = parseInt(cols);
              if(cols>col) var nxtc = col+1;
              else if(cols==col&&cols!=1) var nxtc = col-1;
              var ngrid = parseInt(jQuery('#'+rowid+'_'+nxtc).val());
              if(this.rel=='inc') {
                  if(ngrid==1) return false;
                  jQuery('#'+rowid+'_'+col).val(grid+1);
                  jQuery('#grid_'+col+'_'+rowid).attr('class','grid_'+(grid+1));
                  jQuery('#'+rowid+'_'+nxtc).val(ngrid-1);
                  jQuery('#grid_'+nxtc+'_'+rowid).attr('class','grid_'+(ngrid-1));
                  
                  
              }   else   {
                  if(grid==1) return false;
                  jQuery('#'+rowid+'_'+col).val(grid-1);
                  jQuery('#grid_'+col+'_'+rowid).attr('class','grid_'+(grid-1));
                  jQuery('#'+rowid+'_'+nxtc).val(ngrid+1);
                  jQuery('#grid_'+nxtc+'_'+rowid).attr('class','grid_'+(ngrid+1));
                  
              }
              
              return false;
               
              
      });
      
      jQuery('.admin-cont').css('min-height',(jQuery('body').height()-120)+'px');
      
      jQuery('.insert-layout').live('click',function(e){       
        e.preventDefault();
       //jQuery(this).find('img').fadeTo('slow',0.3);       
       if(holder=='') { 
           holder = jQuery(this).attr('holder')+" .layout-data";           
           holder_id = jQuery(this).attr('holder').replace("#layout_","");                       
       }       
       load_layout(this.rel);       
       return false;
       });
      
      jQuery('#theme-admin-menu a').click(function(e){
        e.preventDefault();
          
          jQuery('.settings').hide();
          jQuery(jQuery(this).attr('href')).show();
          jQuery('#theme-admin-menu li a').removeClass('active');
          jQuery(this).addClass('active');
          var sn = jQuery(this).attr('href').replace('#','').replace('-',' ');
          jQuery('#admin-title span').html(sn).css('text-transform','capitalize');
          return false;
      });
      
      //Insert Layout
      jQuery('.select-layout').live('click',function(e){
        e.preventDefault();
          holder = this.rel+" .layout-data";
          holder_id = this.rel.replace("#layout_","");           
          //tb_show("Insert Layout","themes.php?page=redux&task=select_layout&TB_iframe=1&width=400&height=270");
          //jQuery('#ui-dialog-title-dialog').html('Available Modules');
          jQuery('#ui-dialog-title-dialog').html('Select Layout');
          jQuery( "#dialog" ).dialog( "open" ).load("themes.php?page=redux&task=select_layout&width=400&height=270");
          return false;
      });
      
      
      //Delete Layout
      jQuery('.rdel').live('click',function(){           
          jQuery(this).after("<div class='besure' style='display:none;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;z-index:99999999;position:absolute;color:#000;border:5px solid rgba(0,0,0,0.4);'><div style='padding:10px;background:#fff;font-family:verdana;font-size:10px'>Are you sure? <a style='-webkit-border-radius: 15px;-moz-border-radius: 15px;border-radius: 15px;background:#800;padding:4px 8px 6px 8px;color:#fff;text-decoration:none;' href='#' onclick='jQuery(\".besure\").fadeOut(function(){jQuery(this).remove();jQuery(\"#"+jQuery(this).attr("rel")+"\").slideUp(function(){jQuery(this).remove();});});return false;'>y</a> <a href='' style='-webkit-border-radius: 15px;-moz-border-radius: 15px;border-radius: 15px;background:#080;padding:4px 8px 6px 8px;color:#fff;text-decoration:none;' onclick='jQuery(\".besure\").fadeOut(function(){jQuery(this).remove();mxdm=null;});return false;'>n</a></div></div>");
          jQuery('.besure').fadeIn();                     
      });
      
      //Duplicate Layout
      jQuery('.duplicate').live('click',function(){
        var newRow = jQuery(this).parents().parents().clone();
        var oldID = newRow.find('li:first').attr('id').substr(7);
        var layout;
        newRow.find('input').each(function() {
          if (jQuery(this).val().indexOf("col-") >= 0) {
            layout = jQuery(this).val();
          }
        });
        
        jQuery.get("admin-ajax.php?page=redux&action=insert_layout&holder="+holder_id+"&layout="+layout,function(res){          
           console.log(newRow.prop('outerHTML'));
           var doc = document.createElement('html');
           doc.innerHTML = res;
           var newID = jQuery("li:first", doc).attr('id').substr(7);
           var res = jQuery(newRow).prop('outerHTML').replace(oldID, newID);
            console.log(oldID);
            console.log(newID);
            console.log(res);
           jQuery(holder).append(res);
           
           //we rebind here so we can drag any exists widget to new section
           jQuery('.module').sortable({handle : '.handle', connectWith: "ul.module",placeholder: "ui-state-highlight",forcePlaceholderSize: true});        

           jQuery( '.layout-data' ).sortable({handle : '.row-handler',placeholder: "ui-state-highlight",forcePlaceholderSize: true});
           jQuery( '.layout-data' ).disableSelection();
           jQuery("#dialog").html("Loading...");
           jQuery('#dialog').dialog('close');
           reset_layout_width();
           
        });

        
       
          
      });
      
      

      
      //Import Layout
      var insertto = "", module_index = "", msf_mid = "", msf_title = "";
      jQuery('.import-layout').live('click',function(e){
        e.preventDefault();
          jQuery( "#dialog" ).dialog( 'option', 'title','Import Layout');
          jQuery( "#dialog" ).dialog( 'option', 'width',540);
          jQuery( "#dialog" ).dialog( "open" ).load("admin-ajax.php?page=redux&action=import_layout&modal=1&width=370&height=300");
          return false;
      });
      

      
      
      //Delete Module              
      jQuery('.delete_module').live('click',function(){        
          jQuery(this).after("<div class='besure' style='display:none;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;z-index:99999999;position:absolute;color:#000;border:5px solid rgba(0,0,0,0.4);'><div style='padding:10px;background:#fff;font-family:verdana;font-size:10px'>Are you sure? <a style='-webkit-border-radius: 15px;-moz-border-radius: 15px;border-radius: 15px;background:#800;padding:4px 8px 6px 8px;color:#fff;text-decoration:none;' href='#' onclick='jQuery(\".besure\").fadeOut(function(){jQuery(this).remove();jQuery(\""+jQuery(this).attr("rel")+"\").slideUp(function(){jQuery(this).remove();});});return false;'>y</a> <a href='' style='-webkit-border-radius: 15px;-moz-border-radius: 15px;border-radius: 15px;background:#080;padding:4px 8px 6px 8px;color:#fff;text-decoration:none;' onclick='jQuery(\".besure\").fadeOut(function(){jQuery(this).remove();mxdm=null;});return false;'>n</a></div></div>");
          jQuery('.besure').fadeIn();
      });

      //Duplicate Module              
      jQuery('.duplicate_module').live('click',function(){          
            
          var module = jQuery(this).closest('li').clone();

          jQuery(this).closest('li').after(module); 

          var d          = new Date(),
              z          = d.getTime(),
              parent     = module.parent(),
              id         = parent.attr('rel')+'_'+z,
              rplc       = module.attr('rel'), //column_1_525a9690d519d
              parent_rel = parent.attr('rel'), //column_1_525a9690d519d
              li_id      = module.attr('id').replace('module_',''); //column_1_525a9690d519d_1
          
          module.attr('id','module_'+id).attr('rel',parent_rel);
          module.html(module.html().replace(new RegExp(li_id,"g"),id ));
          //module.html(module.html().replace(new RegExp(li_id,"g"),id ));          
          //module.html(module.html().replace(new RegExp(rplc,"g"),rplcw));
          //module.html(module.html().replace(new RegExp(rplc+'_([\d]*)',"g"),rplcw+'_'+z));

          jQuery('.module').sortable({handle : '.handle', connectWith: "ul.module",placeholder: "ui-state-highlight",forcePlaceholderSize: true});
          jQuery('.module').disableSelection({handle : '.handle'});  
          
      });
      
      
      // Form Submit
      jQuery('#redux-form').submit(function(e){
        e.preventDefault();
          jQuery('#mxinfo').html('Please Wait...')
          jQuery('#mxinfo').slideDown();
          jQuery(this).ajaxSubmit({              
              url:ajaxurl,
              success:function(res){
                   jQuery('#mxinfo').html('Setting Saved Successfully!')
                   setTimeout("jQuery('#mxinfo').slideUp();",2000);
              }   
          });
          
          return false;
          
      });            
      

      
      jQuery('.module').sortable({handle : '.handle', connectWith: "ul.module",placeholder: "ui-state-highlight",forcePlaceholderSize: true});
      
      jQuery( '.layout-data' ).sortable({handle : '.row-handler',placeholder: "ui-state-highlight",forcePlaceholderSize: true});
      jQuery( '.layout-data' ).disableSelection();      
      if(jQuery.cookie('active_mx_'+pageid)==1) switchtoredux();
      //jQuery('.module').bind('sortupdate',function(event, ui){
      jQuery(document).on('sortupdate','.module',function(event, ui){
          var d = new Date();
          var z = d.getTime();
          var id = jQuery(jQuery(ui.item).parent()).attr('rel')+'_'+z;
          var rplc = jQuery(ui.item).attr('rel');
          var rplcw = jQuery(jQuery(ui.item).parent()).attr('rel');
          var li_id = jQuery(ui.item).attr('id').replace('module_','');
          jQuery(ui.item).attr('id','module_'+id).attr('rel',jQuery(jQuery(ui.item).parent()).attr('rel'));          
          jQuery(ui.item).html(jQuery(ui.item).html().replace(new RegExp(li_id,"g"),id ));
          jQuery(ui.item).html(jQuery(ui.item).html().replace(new RegExp(rplc,"g"),rplcw ));
          //jQuery(ui.item).html(jQuery(ui.item).html().replace(new RegExp(rplc,"g"),rplcw));
          //jQuery(ui.item).html(jQuery(ui.item).html().replace(new RegExp(rplc+'_([\d]*)',"g"),rplcw+'_'+z));
           
      });
      //jQuery('.ghbutton').addClass('button button-small button-secondary').removeClass('ghbutton').css('border-radius','0px').css('padding','4px');
  });
  
  var holder = "", holder_id = "";
  function load_layout(layout){          
     jQuery.get("admin-ajax.php?page=redux&action=insert_layout&holder="+holder_id+"&layout="+layout,function(res){          
         jQuery(holder).append(res);
         
         //we rebind here so we can drag any exists widget to new section
         jQuery('.module').sortable({handle : '.handle', connectWith: "ul.module",placeholder: "ui-state-highlight",forcePlaceholderSize: true});        

         jQuery( '.layout-data' ).sortable({handle : '.row-handler',placeholder: "ui-state-highlight",forcePlaceholderSize: true});
         jQuery( '.layout-data' ).disableSelection();
         jQuery("#dialog").html("Loading...");
         jQuery('#dialog').dialog('close');
         reset_layout_width();
     });        
  }
  
  function mediaupload(id){
      var id = '#'+id;
      tb_show('Upload Image','media-upload.php?TB_iframe=1&width=640&height=624');
      window.send_to_editor = function(html) {           
              var imgurl = jQuery('img',"<p>"+html+"</p>").attr('src');                                    
              jQuery(id).val(imgurl);
              tb_remove();
              }
      
  }
  
  function switchtoredux(){          
            jQuery('.wp-switch-editor').removeClass('tactive'); 
            jQuery('#wp-content-wrap').removeClass('tmce-active').removeClass('html-active');
            jQuery('#wp-content-editor-container').hide();
            jQuery('#post-status-info').hide();
            jQuery('#redux-builder').show();
            jQuery('.export,.import,.clone').css('visibility','visible');
            jQuery('.redux-toolbar').show();
            jQuery('#content-redux').addClass('tactive');
            reset_layout_width();
            jQuery.cookie('active_mx_'+pageid,'1');            
        }
        
  function reset_layout_width(){
            var mw = jQuery('.layout-data li').width()-35;
            if(mw>0);                
            jQuery('.row-container').css('width',mw+'px');
        }
                