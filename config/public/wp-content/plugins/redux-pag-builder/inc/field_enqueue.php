<?php
function compile_redux_dependencies() {
    //this is only in developing
    $complie_every_load = WP_DEBUG?true:false;
    $minify = WP_DEBUG?false:true;
    $widgets = apply_filters( 'redux-widgets-options', array() );
    $_js = array();
    $_js_frontend = array();
    $_less = array();

        global $wp_filesystem;

    // Initialize the Wordpress filesystem, no more using file_put_contents function
    if ( empty( $wp_filesystem ) ) :
        require_once( ABSPATH . '/wp-admin/includes/file.php' );
        WP_Filesystem();
    endif;

    //we need to include color and spaces fields for sections
    $widgets[] = array('fields' => array(array('type'=>'color'),array('type'=>'spacing')));

    foreach ($widgets as $sections) {
        foreach ($sections['fields'] as $field) {
            if(file_exists(ReduxPageBuilder::$_dir . '/inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.js')){
                $_js[$field['type']] = ReduxPageBuilder::$_dir . '/inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.js';
            }elseif(file_exists(ReduxFramework::$_dir. 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.js')){
                $_js[$field['type']] = ReduxFramework::$_dir. 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.js';
            }

            if(file_exists(ReduxPageBuilder::$_dir . '/inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.less')){
                $_less[$field['type']] = ReduxPageBuilder::$_dir . '/inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.less';
            }elseif(file_exists(ReduxFramework::$_dir. 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.less')){
                $_less[$field['type']] = ReduxFramework::$_dir. 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.less';
            }
        }
    }

    //JS
    if(!get_option('cached_redux_js'))
        update_option( 'cached_redux_js', serialize($_js) );

    if(get_option('cached_redux_js') != serialize($_js) || $complie_every_load){
        //we recomplie here
        $_js['numeric'] = ReduxPageBuilder::$_dir . 'assets/js/jquery.numeric.min.js';
        $_js['select2'] = ReduxPageBuilder::$_dir . 'assets/js/select2.min.js';
        $_js['redux'] = ReduxPageBuilder::$_dir . 'assets/js/redux.js';

        require_once('minify/jsmin-1.1.1.php');
        $js = '';
        foreach ($_js as $file) 
            $js .= $minify?JSMin::minify(file_get_contents($file)):file_get_contents($file);
        $js= str_replace('#redux-main', '#dialog', $js);
        file_put_contents(ReduxPageBuilder::$_dir . "assets/js/builder.min.js", $js);
    }

    //JS frontend
    if(!get_option('cached_redux_js_frontend'))
        update_option( 'cached_redux_js_frontend', serialize($_js_frontend) );

    if(get_option('cached_redux_js_frontend') != serialize($_js_frontend) || $complie_every_load){
        $include_bs = apply_filters('builder-include-bs',true);
        if($include_bs){
            $_js_frontend['bootstrap'] = ReduxPageBuilder::$_dir . 'assets/js/bootstrap.min.js';
        }
        //we recomplie here
        $_js_frontend['fancybox'] = ReduxPageBuilder::$_dir . 'assets/js/jquery.fancybox-1.3.4.js';
        $_js_frontend['mousewheel'] = ReduxPageBuilder::$_dir . 'assets/js/jquery.mousewheel-3.0.4.pack.js';
        $_js_frontend['mixitup'] = ReduxPageBuilder::$_dir . 'assets/js/jquery.mixitup.js';
        $_js_frontend['frontend'] = ReduxPageBuilder::$_dir . 'assets/js/builder-frontend.js';
        

        require_once('minify/jsmin-1.1.1.php');
        $js = '';
        foreach ($_js_frontend as $file) 
            $js .= $minify?JSMin::minify(file_get_contents($file)):file_get_contents($file);
        file_put_contents(ReduxPageBuilder::$_dir . "assets/js/builder-frontend.min.js", $js);
    }

    //LESS
    if(!get_option('cached_redux_less'))
        update_option( 'cached_redux_less', serialize($_less) );

    if(get_option('cached_redux_less') != serialize($_less) || $complie_every_load){
        $_less['general'] = ReduxPageBuilder::$_dir . 'assets/less/style.less';
        $_less['select2'] = ReduxPageBuilder::$_dir . 'assets/less/select2.less';
        $_less['BS-ui'] = ReduxPageBuilder::$_dir . 'assets/less/jquery-ui-1.10.0.custom.less';
        //we recomplie here
        if(!class_exists('lessc'))
            require_once(ReduxPageBuilder::$_dir . '/inc/lessc.inc.php');
        $less = new lessc;
        if($minify)
            $less->setFormatter("compressed");     
        $css = '';
        foreach ($_less as $file) 
            $css .= $less->compile(file_get_contents($file));
        $css = str_replace('#redux-main', '#dialog', $css);
        file_put_contents(ReduxPageBuilder::$_dir . "assets/css/builder.min.css", $css);   
    }

    if($complie_every_load){
        if(!class_exists('lessc'))
            require_once FRAMEWORK_DIR.'/lessc.inc.php';
        $less = new lessc;
        //$less->setFormatter("compressed");
        $less->setImportDir(array(ReduxPageBuilder::$_dir . "assets/less/"));
        if ( function_exists( 'cadr_variables_less' ) ) :
            $_less = cadr_variables_less();
            $_less .= file_get_contents(ReduxPageBuilder::$_dir . "assets/less/frontend-builder.less"); 
            $_less .= get_builder_css();
            $content = $less->compile( $_less );
            $file = ReduxPageBuilder::$_dir . "assets/css/frontend-builder.css";
            
            if ( is_writeable( $file ) || ( !file_exists( $file ) && is_writeable( dirname( $file ) ) ) ) :
                $wp_filesystem->put_contents( $file, $content, FS_CHMOD_FILE ); 
                
            endif;
        endif;
        $less->checkedCompile(ReduxPageBuilder::$_dir . "assets/less/frontend-builder.less", ReduxPageBuilder::$_dir . "assets/css/frontend-builder.css");
    }        
    
}

function get_builder_css(){
    return "
// Button variants
// -------------------------
// Easily pump out default styles, as well as :hover, :focus, :active,
// and disabled options for all buttons
.button-variant(@color; @background; @border) {
  color: @color;
  background-color: @background;
  border-color: @border;

  &:hover,
  &:focus,
  &:active,
  &.active,
  .open .dropdown-toggle& {
    color: @color;
    background-color: darken(@background, 8%);
        border-color: darken(@border, 12%);
  }
  &:active,
  &.active,
  .open .dropdown-toggle& {
    background-image: none;
  }
  &.disabled,
  &[disabled],
  fieldset[disabled] & {
    &,
    &:hover,
    &:focus,
    &:active,
    &.active {
      background-color: @background;
          border-color: @border;
    }
  }
}

// Alternate buttons
// --------------------------------------------------

.btn-default.rounded.btn-with-icon i {
  .button-variant(@btn-default-color; @btn-default-bg; @btn-default-border);
}
// .btn-primary.rounded.btn-with-icon i {
//   .button-variant(@btn-primary-color; @btn-primary-bg; @btn-primary-border);
// }
// Warning appears as orange
.btn-warning.rounded.btn-with-icon i {
  .button-variant(@btn-warning-color; @btn-warning-bg; @btn-warning-border);
}
// Danger and error appear as red
.btn-danger.rounded.btn-with-icon i {
  .button-variant(@btn-danger-color; @btn-danger-bg; @btn-danger-border);
}
// Success appears as green
.btn-success.rounded.btn-with-icon i {
  .button-variant(@btn-success-color; @btn-success-bg; @btn-success-border);
}
// Info appears as blue-green
.btn-info.rounded.btn-with-icon i {
  .button-variant(@btn-info-color; @btn-info-bg; @btn-info-border);
}
    ";
}

function fields_helper_enqueue($prefix) {
    
    if ($prefix == 'post.php' ||$prefix ==  'post-new.php') {

        if( WP_DEBUG ) {
            compile_redux_dependencies();
        }
        
        // Media Field
        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        } else {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
        }
        
        global $wp_styles;
        wp_register_style(
            'redux-awesome-icon',
            ReduxPageBuilder::$_url . 'assets/css/font-awesome.min.css',
            array(),
            time(),
            'all'
        );

        wp_register_style(
            'redux-awesome-icon-ie7',
            ReduxPageBuilder::$_url . 'assets/css/font-awesome-ie7.min.css',
            array(),
            time(),
            'all'
        );
        
        $wp_styles->add_data( 'redux-awesome-icon-ie7', 'conditional', 'lte IE 7' );
        wp_enqueue_style( 'redux-awesome-icon' );
        wp_enqueue_style( 'redux-awesome-icon-ie7' );
        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_script(
                'builder-js', ReduxPageBuilder::$_url . 'assets/js/builder.min.js', array('jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'wp-color-picker'), time(), true
        );

        wp_enqueue_style('builder-css', ReduxPageBuilder::$_url . 'assets/css/builder.min.css', array( 'dashicons' ) );
        
        //wp_enqueue_script('origin-widgets-admin-script', ReduxPageBuilder::$_url . 'widgets/js/admin.min.js', array('jquery'), SITEORIGIN_PANELS_VERSION);
    }
}

add_action('admin_enqueue_scripts', 'fields_helper_enqueue');


