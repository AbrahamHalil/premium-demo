<?php
// Don't duplicate me!
if (!class_exists('ReduxBuilder')) {

    /**
     * Main ReduxPageBuilder class
     *
     * @since       1.0.0
     */
    class ReduxBuilder {

        // Public vars
        public $widgets;

        /**
         * Class Constructor.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct() {

            //Actions    
            add_action('init', array($this, 'wp_widgets'));
            add_action('init', array($this, 'select_layout'));
            //Common Actions (required for front and back)
            add_action('init', array($this, 'load_theme_option_data'));

            add_action('admin_init', array($this, 'export_page_layout'));
            add_action('admin_init', array($this, 'import_layout_data'));
            add_action('admin_init', array($this, 'clone_page'));

            //Ajax Actions
            add_action("wp_ajax_insert_layout", array($this, 'insert_layout'));
            add_action("wp_ajax_import_layout", array($this, 'import_layout'));
            add_action("wp_ajax_insert_module", array($this, 'insert_module'));
            add_action("wp_ajax_module_settings", array($this, 'module_settings'));
            add_action("wp_ajax_module_settings_data", array($this, 'module_settings_data'));
            add_action("wp_ajax_section_settings", array($this, 'section_settings'));
            add_action("wp_ajax_section_settings_data", array($this, 'section_settings_data'));
            add_action("wp_ajax_redux_generate_layout", array($this, 'generate_custom_layout'));
            add_action("wp_ajax_get_module_preview", array($this, 'get_module_preview'));

            add_action('media_buttons', array($this, 'new_meida_buttons'), 99);
            add_action('save_post', array($this, 'save_page_layout'));
            add_action('admin_head', array($this, 'header_js'));
            add_action("admin_enqueue_scripts", array($this, 'admin_enqueue_scripts'));
            add_action("wp_enqueue_scripts", array($this, 'enqueue_scripts'));
            add_filter("the_content", array($this, 'page_layout') );

            //Widgets
            add_action('widgets_init', array($this, 'widgets_init'));

            //support woocommerce
            add_filter( 'woocommerce_product_tabs', array($this,'woocommerce_support') );
        }

        /**
         * Load active wp widgets
         * 
         * @since       1.0.0
         * @access      public
         * @return      object
         */
        public function wp_widgets() {
            global $mxwidgets, $wp_widget_factory;
            $this->widgets = $mxwidgets = $wp_widget_factory->widgets;
            return $this->widgets;
        }

        //Select Layout (admin)
        function select_layout() {
            if (!isset($_GET['task']) || $_GET['task'] != 'select_layout')
                return;
            include("layout-selector.php");
            die();
        }

        function export_page_layout() {
            if (!is_admin() || !isset($_REQUEST['reduxexport']))
                return;
            $post_id = $_REQUEST['reduxexport'];
            $redux_page['squeeze_page'] = get_post_meta($post_id, "squeeze_page", true);
            $redux_page['bodybgcolor'] = get_post_meta($post_id, "bodybgcolor", true);
            $redux_page['bodybgimage'] = get_post_meta($post_id, "bodybgimage", true);
            $redux_page['redux_layout'] = get_post_meta($post_id, "redux_layout", true);
            $redux_page['redux_layout_settings'] = get_post_meta($post_id, "redux_layout_settings", true);
            $redux_page['redux_grid_settings'] = get_post_meta($post_id, "redux_grid_settings", true);
            $redux_page['redux_modules'] = get_post_meta($post_id, "redux_modules", true);
            $redux_page['redux_modules_settings'] = get_post_meta($post_id, "redux_modules_settings", true);
            $data = serialize($redux_page);
            header("Content-Description: File Transfer");
            header("Content-Type: text/plain");
            header("Content-Disposition: attachment; filename=\"redux-page-{$post_id}.txt\"");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . strlen($data));
            echo $data;
            die();
        }

        function import_layout_data() {
            if (is_admin() && isset($_FILES['importlayout']) && is_uploaded_file($_FILES['importlayout']['tmp_name'])) {
                $post_id = $_GET['post'];

                $data = file_get_contents($_FILES['importlayout']['tmp_name']);
                @unlink($_FILES['importlayout']['tmp_name']);
                $redux_layout = unserialize($data);

                update_post_meta($post_id, "squeeze_page", $redux_layout['squeeze_page']);
                update_post_meta($post_id, "bodybgcolor", $redux_layout['bodybgcolor']);
                update_post_meta($post_id, "bodybgimage", $redux_layout['bodybgimage']);
                update_post_meta($post_id, "redux_layout", $redux_layout['redux_layout']);
                update_post_meta($post_id, "redux_layout_settings", $redux_layout['redux_layout_settings']);
                update_post_meta($post_id, "redux_grid_settings",$redux_layout['redux_grid_settings']);
                update_post_meta($post_id, "redux_modules", $redux_layout['redux_modules']);
                update_post_meta($post_id, "redux_modules_settings", $redux_layout['redux_modules_settings']);
                header("location: " . $_SERVER['REQUEST_URI']);
                die();
            }
        }

        function clone_page() {
            $post_id = isset($_REQUEST['reduxclone']) ? $_REQUEST['reduxclone'] : '';
            if (is_admin() && $post_id != '') {
                $post_data = get_post($post_id, ARRAY_A);
                unset($post_data['ID']);
                $post_data['post_title'] = "Clone: " . $post_data['post_title'];
                $post = wp_insert_post($post_data);
                add_post_meta($post, "squeeze_page", get_post_meta($post_id, "squeeze_page", true));
                add_post_meta($post, "bodybgcolor", get_post_meta($post_id, "bodybgcolor", true));
                add_post_meta($post, "bodybgimage", get_post_meta($post_id, "bodybgimage", true));
                add_post_meta($post, "redux_layout", get_post_meta($post_id, "redux_layout", true));
                add_post_meta($post, "redux_layout_settings", get_post_meta($post_id, "redux_layout_settings", true));
                add_post_meta($post, "redux_grid_settings", get_post_meta($post_id, 'redux_grid_settings', true));
                add_post_meta($post, "redux_modules", get_post_meta($post_id, "redux_modules", true));
                add_post_meta($post, "redux_modules_settings", get_post_meta($post_id, "redux_modules_settings", true));
                header("location: post.php?post={$post}&action=edit");
                die();
            }
        }

        //Insert Layout (admin)
        function insert_layout() {
            $id = uniqid();
            $holder = $_REQUEST['holder'];
            echo '<li id="row_li_' . $id . '"><input id="row_settings_' . $id . '" type="hidden" name="layout_settings[' . $holder . '][' . $_GET['layout'] . '][' . $id . ']" value="" /><div class="row-handler"><i class="icon-exchange icon-2x icon-rotate-90 sort"></i><i class="icon-copy icon-2x duplicate"></i><i class="icon-trash icon-2x rdel delete" rel="row_li_' . $id . '"></i><i class="rsettings icon-pencil icon-2x" rel="row_settings_' . $id . '"></i></div><div class="row-container"><div class="container_12 clearfix wrapper row" id="row_' . $id . '"><input type="hidden" name="layouts[' . $holder . '][' . $id . ']" value="' . (isset($_REQUEST['layout']) ? $_REQUEST['layout'] : '') . '" />';
            //include(ReduxPageBuilder::$_dir."/frames/{$_REQUEST['layout']}.frame.php");
            $cols = (int) str_replace("col-", "", $_GET['layout']);
            include(ReduxPageBuilder::$_dir . "/inc/dynamic.frame.php");
            echo "</div></div><div class='clear'></div></li>";
            die();
        }

        //Import Layout (admin)
        function import_layout() {
            require_once("import-layout.php");
            die();
        }

        //Insert Module (admin)
        function insert_module() {
            require_once("insert-module.php");
            die();
        }

        //Module Settings Form (admin)
        function module_settings() {
            global $mxwidgets, $redux_modules_settings;
            $mod = $mxwidgets[$_GET['module']];
            $form_prefix = '';
            if (isset($_REQUEST['instance'])) {
                $ins = explode('|', $_REQUEST['instance']);

                if (isset($redux_modules_settings[$ins[0]]) && is_array($redux_modules_settings[$ins[0]]))
                    $instance = @unserialize(@base64_decode($redux_modules_settings[$ins[0]][$ins[1]]));
                //echo "<pre>";    
                //print_r($redux_modules_settings[$ins[0]][$ins[1]]);
                //if (isset($_REQUEST['instance'])) {
                $form_prefix = "update-";
                //}
                $instance = @array_shift($instance["widget-" . $mod->id_base]); //[$mod->number];    
            }

            $datafield = isset($_REQUEST['datafield']) ? $_REQUEST['datafield'] : '';


            $data_inst = @unserialize(@base64_decode($_POST['data_inst']));
            //print_r($data_inst);die();
            $data_inst = $data_inst["widget-" . $mod->id_base][$mod->number];
            /* <input type='hidden' name='datafile' value='{$datafield}' /> */
            echo "<form class='ui-form' datafield='{$datafield}' method='post' id='" . $form_prefix . "module-settings-form'>";
            if (isset($instance)):
                foreach ($instance as $k => $c) {
                    $iinstance[$k] = is_array($c) ? $c : stripcslashes($c);
                }
            endif;
            if ($data_inst):
                foreach ($data_inst as $k => $c) {
                    $data_inst[$k] = is_array($c) ? $c : stripcslashes($c);
                }
            endif;

            if (is_array($data_inst))
                $iinstance = $data_inst;

            $iinstance = isset($iinstance) ? $iinstance : array();
            $mxwidgets[$_GET['module']]->form($iinstance);
            //<button type='submit' id='submit_module' class='ui-button button button-primary button-large'>Save Settings</button>        
            echo "
        <div class='dialog-inner-footer'>
    <button type='submit' id='submit_module' class='ui-button button button-primary button-large'>Save Settings</button>        
    <input type='button' class='ui-button button button-primary button-large' onclick='jQuery(\"#dialog\").dialog(\"close\");jQuery(\"#dialog\").html(\"Loading...\");' value='Cancel' />
        </div>
    </form><script>jQuery('#ui-dialog-title-dialog').html('" . $mxwidgets[$_GET['module']]->name . " Options');jQuery('.ui-button,.ui-form input[type=button]').button();</script>";
            die();
        }

        //Format module instance data (admin)
        function module_settings_data() {
            $data = base64_encode(serialize($_POST));
            echo $data;
            die();
        }

        //Section Settings (admin)
        function section_settings() {
            $ls = unserialize(base64_decode($_REQUEST['section_settings_data']));
            //print_r($ls);
            include("section-settings.php");
            die();
        }

        //Format Section Settings Data (admin)
        function section_settings_data() {
            echo base64_encode(serialize($_POST['ls']));
            die();
        }

        //Generate custom layout (admin)
        function generate_custom_layout() {
            extract($_POST['layoutopt']);
            $id = uniqid();
            $admin_html = "";
            $phpid = '<?php echo $id; ?>';
            for ($i = 1; $i <= $cols; $i++) {
                $grd = $colgrid[$i - 1];
                $phpcallback = '<?php redux_render_module_frames("column_' . $i . '_{$id}"); ?>';
                $admin_html .=<<<CLY
                <div class="grid_{$grd}">
    <div class="column" id="column_{$i}_{$phpid}">
    <ul class="module">
             {$phpcallback}    
            </ul>
            <a class="btnAddMoudule" rel="column_{$i}_{$phpid}" href="#">Add Module</a>
    </div>
    </div>
CLY;
                $phpfcb = '<?php redux_render_mobules("column_' . $i . '_{$id}"); ?>';
                $front_html .=<<<CLY
            <div class="redux_grid_{$grd}">
    <div class="redux_column" id="column_{$i}_{$phpid}">
    
    {$phpfcb}
    
    </div>
    
    </div>
CLY;
            }

            file_put_contents(ReduxPageBuilder::$_dir . '/frames/' . $id . '.frame.php', $admin_html);
            file_put_contents(ReduxPageBuilder::$_dir . '/layouts/' . $id . '.layout.php', $front_html);
            echo $id;
            die();
        }

        function get_module_preview() {
            //global $mxwidgets, $redux_modules, $redux_modules_settings;
            $instance = @unserialize(base64_decode($_REQUEST['modinfo']));
            $instance = @array_shift(array_shift($instance));
            if ($instance):
                foreach ($instance as $k => $v):
                    $ins[$k] = is_array($v) ? $v : stripslashes($v);
                endforeach;
            endif;
            $module = $_REQUEST['mod'];

            if (method_exists($pmod = new $module(), 'preview'))
                echo $pmod->preview($ins);
            elseif (method_exists($pmod = new $module(), 'get_widget')){
                //die("ss");
                echo $pmod->get_widget($module, $ins);
            }else
                the_widget($module, $ins);

            die();
        }

        //Load redux active theme option data
        function load_theme_option_data() {
            global $redux_layout_data, $redux_modules, $redux_modules_settings, $redux_options;
            $redux_options = get_option("wpeden_admin");
            $redux_layout_data = get_option('redux_layout', array());
            $redux_layout_settings = get_option('redux_layout_settings', array());
            $redux_modules = get_option('redux_modules', array());
            $redux_modules_settings = get_option('redux_modules_settings', array());
            if (isset($_GET['post']) && $_GET['post'] != '') {
                $redux_layout_settings = get_post_meta($_GET['post'], 'redux_layout_settings', true);
                $redux_layout_data = get_post_meta($_GET['post'], 'redux_layout', true);
                $redux_modules = get_post_meta($_GET['post'], 'redux_modules', true);
                $redux_modules_settings = get_post_meta($_GET['post'], 'redux_modules_settings', true);
            }
        }

        function new_meida_buttons() {
            $post_type = get_post_type();
            $pageid = isset($_GET['post']) ? $_GET['post'] : '';
            if ($pageid != '')
                $mxnb = "<a class='button export' href='" . admin_url() . "?reduxexport=" . $pageid . "' ><span>Export Layout</span></a><a class='button import-layout import' rel='" . $pageid . "' href='#' ><span>Import Layout</span></a><a class='button clone' href='" . admin_url() . "?reduxclone=" . $pageid . "' ><span>Clone</span></a>";
            else
                $mxnb = "<a class='button export' href='#' onclick='alert(\"" . $post_type . " is not published or saved yet!\");return false;' ><span>Export Layout</span></a><a class='button import' href='#' onclick='alert(\"" . $post_type . " is not published or saved yet!\");return false;' ><span>Import Layout</span></a><a class='button clone' href='#' onclick='alert(\"" . $post_type . " is not published or saved yet!\");return false;' ><span>Clone</span></a>";

            echo $mxnb;
        }

        function save_page_layout($post_id) {

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return;
            if (!$_POST)
                return;
                
       
            //$squeeze_page = $_POST['squeeze_page'] == '1' ? 1 : 0;
            //update_post_meta($post_id, "squeeze_page", $squeeze_page);
            //update_post_meta($post_id, "sptemplate", $_POST['sptemplate']);
            if (isset($_POST['bodybgcolor']))
                update_post_meta($post_id, "bodybgcolor", $_POST['bodybgcolor']);
            if (isset($_POST['bodybgimage']))
                update_post_meta($post_id, "bodybgimage", $_POST['bodybgimage']);
            if (isset($_POST['layouts']))
                update_post_meta($post_id, "redux_layout", $_POST['layouts']);
            if (isset($_POST['layout_settings']))
                update_post_meta($post_id, "redux_layout_settings", $_POST['layout_settings']);
            if (isset($_POST['layout_grids']))
                update_post_meta($post_id, "redux_grid_settings", $_POST['layout_grids']);
            if (isset($_POST['modules']))
                update_post_meta($post_id, "redux_modules", $_POST['modules']);
            if (isset($_POST['modules_settings']))
                update_post_meta($post_id, "redux_modules_settings", $_POST['modules_settings']);

            @unlink(ReduxPageBuilder::$_dir . 'cache/' . $post_id);
        }




        //Raw JS Code
        function header_js() {
            ?>

            <script language="JavaScript">
                <!--
                var builder_uri = "<?php echo ReduxPageBuilder::$_url; ?>",builder_img_uri = "<?php echo ReduxPageBuilder::$_url . 'assets/img/'; ?>",pageid="<?php echo isset($_GET['post']) ? $_GET['post'] : ''; ?>",post_type="<?php echo get_post_type(); ?>";
                //-->
            </script>

            <?php
        }

        function admin_enqueue_scripts() {

            wp_enqueue_script("jquery");

            if ((isset($_GET['page']) && $_GET['page'] == 'redux') || (isset($_GET['post_type']) && $_GET['post_type'] != '') || (isset($_GET['post']) && in_array(get_post_type($_GET['post']), get_post_types())) || in_array(get_post_type(), get_post_types())) {
                //Styles
                if (isset($_GET['page']) && $_GET['page'] == 'redux')
                    wp_enqueue_style("admin-reset", ReduxPageBuilder::$_url . 'assets/css/reset.css');
                wp_enqueue_style("admin-grid", ReduxPageBuilder::$_url . 'assets/css/grid.css');
                wp_enqueue_style("admin-theme-style", ReduxPageBuilder::$_url . 'assets/css/admin-style.css');
                wp_enqueue_style("frame-style", ReduxPageBuilder::$_url . '/inc/frames/css/style.css');
                wp_enqueue_style("frame-grid", ReduxPageBuilder::$_url . '/inc/frames/css/grid.css');
                if(is_rtl()){
                  wp_enqueue_style("frame-grid-rtl", ReduxPageBuilder::$_url . '/inc/frames/css/grid-rtl.css');
                }
                wp_enqueue_style("gh-buttons", ReduxPageBuilder::$_url . 'assets/css/gh-buttons.css');
                wp_enqueue_style("thickbox");
                //wp_enqueue_style("jquery-ui-new",get_builder_uri('/page-layout-builder/css/aristo.css'));
                //wp_enqueue_style("jquery-ui-m", get_builder_uri() . '/css/jqui/theme/jquery-ui.css');
                //wp_enqueue_style("jquery-ui-new", get_builder_uri() . '/css/jqui/css/custom.css');
                //wp_enqueue_style("jquery-ui-new",get_builder_uri('/page-layout-builder/css/flickr.css'));  
                //Scripts    
                wp_enqueue_script("jquery-ui-all");
                wp_enqueue_script("jquery-ui-sortable");
                wp_enqueue_script("jquery-form");
                wp_enqueue_script("thickbox");
                wp_enqueue_script("bjquery-cookie", ReduxPageBuilder::$_url . 'assets/js/jquery.cookie.js', array('jquery'));
                wp_enqueue_script("redux-opr", ReduxPageBuilder::$_url . 'assets/js/operations.js', array('jquery'));
                $translation_array = array(
                    'builder' => __( 'Builder' ,'redux-page-builder'),
                    'col' => __( 'Col' ,'redux-page-builder'),
                    'cols' => __( 'Cols' ,'redux-page-builder'),
                    'modules' => __( 'Modules' ,'redux-page-builder'),
                );
                wp_localize_script( 'redux-opr', 'redux_opr', $translation_array );
            }
        }

        //Register front-end script
        function enqueue_scripts() {
            wp_enqueue_script("jquery");
            wp_enqueue_style("frontend-builder", ReduxPageBuilder::$_url . 'assets/css/frontend-builder.css',false, ReduxPageBuilder::$_version );
            wp_enqueue_script("frontend-builder-js", ReduxPageBuilder::$_url . 'assets/js/builder-frontend.min.js',array('cadr_main'),ReduxPageBuilder::$_version,true);  
            $include_bs = apply_filters('builder-include-bs',true);
            if($include_bs){
                wp_enqueue_style("bootstrap", ReduxPageBuilder::$_url . 'assets/css/bootstrap.min.css');
                //wp_enqueue_script("bootstrap-js", ReduxPageBuilder::$_url . 'assets/js/bootstrap.min.js');  
            }    
        }

        /**
        * Filter the content of the page, adding all the widgets in the frontend.
        *
        * @param $content
        * @return string
        *
        * @filter the_content
        */
        function page_layout($content) {
            global $post;
            if ( empty( $post ) ) return $content;

            //workaround to fix builder in shop page for woocommerce
            if ( is_post_type_archive( 'product' ) && get_query_var( 'paged' ) == 0 && class_exists('woocommerce')){
                $shop_page   = get_post( woocommerce_get_page_id( 'shop' ) );
                $pid = $shop_page->ID;
            }else{
                $pid = $post->ID;
            }
            //if(get_post_type($pid)!='page') return $content;
            //if (file_exists(ReduxPageBuilder::$_dir . 'cache/' . $pid))
            //    return $content . file_get_contents(ReduxPageBuilder::$_dir . 'cache/' . $pid);
            //ob_start();
            $redux_layout_data = get_post_meta($pid, 'redux_layout', true);
            $redux_modules = get_post_meta($pid, 'redux_modules', true);
            $redux_modules_settings = get_post_meta($pid, 'redux_modules_settings', true);
            $data = '';
            //print_r($redux_layout_data);die();
            if (isset($redux_layout_data[get_post_type()]) && is_array($redux_layout_data[get_post_type()])):
                foreach ($redux_layout_data[get_post_type()] as $id => $layout):
                    $data .= redux_render_layout($layout, $id, get_post_type());
                endforeach;
            endif;
            //$data = ob_get_contents();
            //file_put_contents(ReduxPageBuilder::$_dir . 'cache/' . $pid, $data);
            //ob_clean();
            return $content . $data;
        }

        //Make builder work in single product
        function woocommerce_support($tabs = array()){
            global $product, $post;

            $redux_modules_settings = get_post_meta($post->ID, 'redux_modules_settings', true);

            // Description tab - shows product content
            if ( empty($post->post_content) && isset($redux_modules_settings) && is_array($redux_modules_settings) && count($redux_modules_settings) > 0 )
                $tabs['description'] = array(
                        'title'    => __( 'Description', 'woocommerce' ),
                        'priority' => 10,
                        'callback' => 'woocommerce_product_description_tab'
                );
            return $tabs;
        }

        /**
         * Include all the widget files and register their widgets
         */
        public function widgets_init(){
            $widgets = apply_filters( 'redux-widgets-options', array() );
            foreach(glob(ReduxPageBuilder::$_dir.'/widgets/*.php') as $file) {
                include_once ($file);

                $p = pathinfo($file);
                $class = $p['filename'];
                $class = strtolower($class);
                $class = str_replace('-', '_', $class);
                $class = str_replace(' ', '_', $class);

                $class = 'Redux_Widget_' . $class;
                if(class_exists($class)) register_widget($class);
            } 
        }

    }

}


global $mxwidgets, $redux_layout_data, $redux_modules, $wp_widget_factory, $redux_modules_settings, $redux_options, $redux_layout_settings;

//Layout builder metabox
function redux_layout_builder_meta_box() {
    add_meta_box( get_post_type().'-redux-layout-builder', 'redux Layout Builder  <a style="float:right;font-weight:bold;text-decoration:none" href="#" rel="#layout_page" class="insert-layout ghbutton big">+ Insert Layout</a>', 'redux_content_layout_builder', get_post_type(), 'normal','high' );   
}
 

//Layout builder metabox callback
function redux_content_layout_builder( $post ) {
    global $pt_plugin;     
    ?>
    <style type="text/css">
    #<?php echo get_post_type(); ?>-redux-layout-builder {
        display: none;
    }
    #<?php echo get_post_type(); ?>-redux-layout-builder h3{
        line-height: 30px !important;
        height:30px !important;
        font-size:14pt !important;
    }
    .row-container{
        width: 95%;         
    }
    #TB_ajaxContent{
        width: 95% !important;
        height: 90% !important;
        overflow: auto;
    }
    #widgets li{
        width: 183px !important;
    }
    .layout-data li{
     
    }
    .window-title{
    -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;
height: 35px;
background: #efefef;
background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2VmZWZlZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmOWY5ZjkiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
background: -moz-linear-gradient(top,  #efefef 0%, #f9f9f9 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#efefef), color-stop(100%,#f9f9f9));
background: -webkit-linear-gradient(top,  #efefef 0%,#f9f9f9 100%);
background: -o-linear-gradient(top,  #efefef 0%,#f9f9f9 100%);
background: -ms-linear-gradient(top,  #efefef 0%,#f9f9f9 100%);
background: linear-gradient(top,  #efefef 0%,#f9f9f9 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#efefef', endColorstr='#f9f9f9',GradientType=0 );
border-bottom:1px inset #dddddd;
    }
.window-content{
    border-top:1px solid #ffffff;
}
    .window{
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;
        border:1px solid #dddddd;
    }
    </style>
    
  <link rel="stylesheet" type="text/css" href="<?php echo ReduxPageBuilder::$_url . 'assets/css/tipTip.css';?>" /> 
  <script language="JavaScript" src="<?php echo ReduxPageBuilder::$_url . 'assets/js/jquery.tipTip.minified.js';?>"></script>
    
    <div id="alll">
    <div id="layout_<?php echo get_post_type(); ?>">                
                <?php 
                    global $redux_layout_settings;
                    if(isset($_GET['post']))
                    $redux_layout_settings = get_post_meta($_GET['post'],'redux_layout_settings',true);                                         
                    else 
                    $redux_layout_settings = array();
                ?>
                <ul class="layout-data">                
                <?php
                    redux_render_layout_frames(get_post_type()); 
                ?>                
                </ul>
    </div>
    </div>
     
    <div style="clear: both;"></div>
    
    <div class="clear"></div> 
    <div id="dialog" title="redux"><p>Loading...</p></div>
    <div id="childdialog" title="redux"><p>Loading...</p></div>
    <script type="text/javascript">
     
    jQuery(function() {
        jQuery( "#dialog" ).dialog({ zIndex: 100, position: 'top' , closeOnEscape: true, autoOpen: false,modal:true,width:'640px' });
        jQuery( ".dialog" ).dialog({ zIndex: 100, position: 'top' , closeOnEscape: true, autoOpen: false,modal:true,width:'640px' });
        jQuery( "#childdialog" ).dialog({ zIndex: 101, position: 'top' , closeOnEscape: true, autoOpen: false,width:'640px' });
        jQuery('.ui-dialog').css('margin-top','28px');
        jQuery(".tooltip").tipTip({defaultPosition:'top'});
    });
    </script>
    <?php
    
}

//Metabox action 
add_action( 'add_meta_boxes', 'redux_layout_builder_meta_box');


//Layout holder (site)
//function redux_layout_holder($name) {
//    global $redux_layout_data, $redux_modules;
//    if (is_array($redux_layout_data[$name])) {
//        foreach ($redux_layout_data[$name] as $id => $layout):
//            redux_render_layout($layout, $id, $name);
//        endforeach;
//    }
//}

//Render saved layout frames (admin)
function redux_render_layout_frames($holder) {
    global $redux_layout_data, $redux_layout_settings;
    if (isset($_GET['post'])):
        $gs = get_post_meta($_GET['post'], 'redux_grid_settings', true);
        if (isset($gs[$holder . "_rows"]))
            $gs = $gs[$holder . "_rows"];
        if (is_array($redux_layout_data) && isset($redux_layout_data[$holder]) && is_array($redux_layout_data[$holder])):
            foreach ($redux_layout_data[$holder] as $id => $layout):
                echo '<li id="row_li_' . $id . '"><input id="row_settings_' . $id . '" type="hidden" name="layout_settings[' . $holder . '][' . $layout . '][' . $id . ']" value="' . $redux_layout_settings[$holder][$layout][$id] . '" /><div class="row-handler"><i class="icon-exchange icon-2x icon-rotate-90 sort"></i> <i class="icon-copy icon-2x duplicate"></i> <i class="icon-trash icon-2x rdel delete" rel="row_li_' . $id . '"></i> <i class="rsettings icon-pencil icon-2x" rel="row_settings_' . $id . '"></i></div><div class="row-container"><div class="container_12 clearfix wrapper row" id="row_' . $id . '"><input type="hidden" name="layouts[' . $holder . '][' . $id . ']" value="' . $layout . '" />';
                //if (!isset($gs[$id]))
                //    include(ReduxPageBuilder::$_dir . "/frames/{$layout}.frame.php");
                //else {
                $cols = count($gs[$id]);
                include(ReduxPageBuilder::$_dir . "/inc/dynamic.frame.php");
                //}
                echo "</div></div><div class='clear'></div></li>";
            endforeach;
        endif;
    endif;
}

//Render layout (site)
function redux_render_layout($layout, $id, $holder = '') {
    $html = '';
    

    $redux_options = get_option("wpeden_admin");

    $gs = get_post_meta(get_the_ID(), 'redux_grid_settings', true);

    $gs = $gs[$holder . "_rows"];

    //$_testing = redux_render_layout_prepare($gs);

    //$container_css = "bs_row-fluid";
    //$layout_folder = "bootstrap";

    $container_css = "container_12";
    $layout_folder = "bootstrap";
    global $redux_layout_data, $redux_layout_settings;

    if (!$redux_layout_settings)
        $redux_layout_settings = get_option('redux_layout_settings', array());

    
    if (in_array($holder, get_post_types())) {
        $redux_page_layout_settings = get_post_meta(get_the_ID(), 'redux_layout_settings', true);
        $ls = unserialize(base64_decode($redux_page_layout_settings[$holder][$layout][$id]));
    } else {
        $ls = unserialize(base64_decode($redux_layout_settings[$holder][$layout][$id]));
    }

    //print_r($ls);die("hhh");

    //$rid = $ls['css_id'] ? $ls['css_id'] : "row_{$id}";
    $rid = "row_{$id}";
    $defaults = array(
        'custom_section' => '0',
        'custom_choice' => '',
        'custom_bg' => '',
        'position' => '',
        'repeat' => '',
        'attach' => '',
        'padding' => '',
        'margin'  => '',
        'shadow' => '',
        'close' => true,
        'open' => true,
    );
    $ls = wp_parse_args($ls, $defaults);
    if($ls['custom_section'] == '1'){
        $class       = "  section-".$ls['shadow'];
        $background  = "";
        $padding     = "";
        $margin      = "";
        $output      = "";

        if(isset($ls['src']['url']) && !empty($ls['src']['url'])){
             if($ls['repeat'] == 'stretch'){
                 $background .= "background-repeat: no-repeat; ";
                 $class .= " section-full-stretch";
             }else{
                 $background .= "background-repeat: ".$ls['repeat']."; ";
             }
             $img = wp_get_attachment_image_src($ls['src']['id'], 'extra_full_width');
             $background .= "background-image: url(".$img[0]."); ";
             $background .= "background-attachment: ".$ls['attach']."; ";
             $background .= "background-position: ".$ls['position']."; ";
        }

        if(isset($ls['margin']['units']) && !empty($ls['margin']['units'])){
            $margin .= "margin-top: ".$ls['margin']['margin-top']."; ";
            $margin .= "margin-right: ".$ls['margin']['margin-right']."; ";
            $margin .= "margin-bottom: ".$ls['margin']['margin-bottom']."; ";
            $margin .= "margin-left: ".$ls['margin']['margin-left']."; ";
        }
        
        if(isset($ls['padding']['units']) && !empty($ls['padding']['units'])  ){
            $padding .= "padding-top: ".$ls['padding']['padding-top']."; ";
            $padding .= "padding-right: ".$ls['padding']['padding-right']."; ";
            $padding .= "padding-bottom: ".$ls['padding']['padding-bottom']."; ";
            $padding .= "padding-left: ".$ls['padding']['padding-left']."; ";
        }
        
        //if custom bg color set && user enabled custom bg color , then ignore custom bg image
        if($ls['custom_bg'] != "" && $ls['custom_choice'] == '1'){
             $background = "background-color: ".$ls['custom_bg']."; ";
        }
        
        if($background || $padding || $margin) $background = "style = '{$background} {$padding} {$margin}'";
        //echo($background);
        $params['class'] = $class;
        $params['bg']    = $background;
        //echo "ssss";
        $html .= new_section($params);
        ///echo $html;
        //echo "ssss";
        //die();
    } 

    $html .= '<div class="row section_area">';
    $cols = count($gs[$id]);
    $cols = $cols?$cols:2;
    $grid = (int)(12/$cols);
    $rem = 12%$cols;       
    for($i=1; $i<=$cols;  $i++){
        if($i==$cols) $grid +=$rem;
        if($gs[$id]) $grid = $gs[$id]['grid_'.$i];
        $html .= "<div class='col-md-{$grid} redux_column' id='column_{$i}_{$id}'>";
        $html .= redux_render_mobules("column_{$i}_{$id}");
        $html .= "</div>";
    }
    $html .= '</div>';
    if($ls['custom_section'] == '1'){   
        $skipSecond = '';
        if(empty($skipSecond)) {
            $new_params['id'] = "after_section";
            $html .= new_section($new_params);
        }
    }

    return $html;
}

function redux_render_layout_prepare($grids){
    
    if(!isset($grids))
        return false;

    $redux_modules = get_post_meta(get_the_ID(), 'redux_modules', true);
    
    $i = 1;
    foreach($grids as $k => $v){
        if(is_array($v)){
             foreach($v as $k2 => $v2){
                 // $_grids[$i][$k2] = 'coo';
                 $_grids[$i][$k2] = find_grids_by_key($k,$redux_modules);
             }
        }
        $i++;
    }
    return $_grids;
}

function find_next_widget($grid,$index,$sibling = false){
    if($sibling > 0){
        return isset($grid[$index++]['grid_1'][0])?$grid[$index++]['grid_1'][0]:false;
    }
}

function find_grids_by_key($key , $redux_modules) {
    $grids = array();
    if(is_array($redux_modules) && count($redux_modules) > 0){
        foreach ($redux_modules as $k => $v) {
            if(strpos($k , $key) !== false){
                foreach ($v as $k2 => $v2) {
                    $grids[] = $v2;
                }

            }
        }
    }
    return $grids;
}


//Render Module Frames (admin)
function redux_render_module_frames($id) {
    global $mxwidgets, $redux_modules, $redux_modules_settings;
    $builder_uri = ReduxPageBuilder::$_url;
    $builder_img_uri = ReduxPageBuilder::$_url . 'assets/img/';
    $redux_modules_settings = get_option('redux_modules_settings', array());
    if (!is_array($redux_modules_settings))
        $redux_modules_settings = array();
    if (isset($_GET['post']) && !empty($_GET['post'])) {
        $post_modules_settings = get_post_meta($_GET['post'], 'redux_modules_settings', true);
        if (!is_array($post_modules_settings))
            $post_modules_settings = array();
        $redux_modules_settings += $post_modules_settings;
    }
    //echo "<div class='modules' id='$id' >";
    $module_frames = '';
    if (isset($redux_modules[$id]) && is_array($redux_modules[$id])):
        $z = 0;
        foreach ($redux_modules[$id] as $mid => $module):
            $mod = $mxwidgets[$module];
            $z++;
            $ms = $z - 1;

            //generate module preview

            $instance = @unserialize(base64_decode($redux_modules_settings[$id][$mid]));
            //echo "<pre>";
            //print_r($instance);
            //die();
            //echo $id."<br/>";
            $instance = @array_shift(array_shift($instance));
            if ($instance):
                foreach ($instance as $k => $v):
                    $ins[$k] = is_array($v) ? $v : stripslashes($v);
                endforeach;
            endif;


            if (method_exists($pmod = new $module(), 'preview'))
                $prevw = $pmod->preview($ins);
            elseif (method_exists($pmod = new $module(), 'get_widget')){
                $prevw = $pmod->get_widget(array(), $ins);
            }else{
                ob_start();
                $pmod->widget(array(), $ins);
                $prevw = ob_get_contents();
                ob_clean();
            }
            
            //end preview

            $module_frames .=<<<MOD
    <li id='module_{$id}_{$z}' rel='{$id}'>

        <input type="hidden" id="modid_module_{$id}_{$z}" name="modules[{$id}][]" value="{$module}" />
        <input id="modset_module_{$id}_{$z}" type="hidden" name="modules_settings[{$id}][]" value="{$redux_modules_settings[$id][$mid]}" />
        <h3><nobr class="title handle">{$mod->name}</nobr><nobr class="ctl"><i class="handle icon-move"></i>&nbsp;<i class="icon-copy duplicate_module"></i>&nbsp;<i class="icon-trash delete_module icon-large" rel='#module_{$id}_{$z}'></i>&nbsp;<i class="insert icon-pencil icon-large" id="modset_module_{$id}_{$z}_icon" rel="$module" data="{$id}|{$mid}" datafield="modset_module_{$id}_{$z}"></i></nobr></h3>
        <div class='module-preview w3eden'>
        {$prevw}
        </div>
        <div class="clear"</div></li>
MOD;
        endforeach;
    endif;
    echo $module_frames;
}

//Render Modules (site)
function redux_render_mobules($id) {
    global $redux_modules, $mxwidgets, $wp_widget_factory, $redux_modules_settings, $redux_layout_data;
    $output = "";
    //if(is_page()&&get_post_meta(get_the_ID(),'redux_modules',true)){           
    if (get_post_meta(get_the_ID(), 'redux_modules', true)) {
        $redux_layout_data_page = get_post_meta(get_the_ID(), 'redux_layout', true); 
        $postmod = get_post_meta(get_the_ID(), 'redux_modules', true);
        $postmodset = get_post_meta(get_the_ID(), 'redux_modules_settings', true);

        if (!is_array($redux_modules))
            $redux_modules = array();
        if (!is_array($redux_modules_settings))
            $redux_modules_settings = array();
        if (!is_array($postmod))
            $postmod = array();
        if (!is_array($postmodset))
            $postmodset = array();
        $redux_modules += $postmod;
        $redux_modules_settings += $postmodset;
    }
    if (isset($redux_modules[$id])):
        $mcount = 0;
        $output = "";
        foreach ($redux_modules[$id] as $index => $module):
            $instance = @unserialize(base64_decode($redux_modules_settings[$id][$index]));
            $instance = @array_shift(array_shift($instance));

            if ($instance):
                foreach ($instance as $k => $v):
                    $ins[$k] = is_array($v) ? $v : stripslashes($v);
                endforeach;
            endif;
            //print_r($module);die();
            $output .= "<div class='redux_module {$module}'>";
            $output .= get_the_widget($module, $ins);
            $output .= "</div>";
            $mcount++;
        endforeach;
    endif;

    return $output;
}

/**
 * Output an arbitrary widget as a template tag
 *
 * @since 2.8
 *
 * @param string $widget the widget's PHP class name (see default-widgets.php)
 * @param array $instance the widget's instance settings
 * @param array $args the widget's sidebar args
 * @return string
 **/
function get_the_widget($widget, $instance = array(), $args = array()) {
	global $wp_widget_factory;

	$widget_obj = $wp_widget_factory->widgets[$widget];
	if ( !is_a($widget_obj, 'WP_Widget') )
		return;

	$before_widget = sprintf('<div class="widget %s">', $widget_obj->widget_options['classname'] );
	$default_args = array( 'before_widget' => $before_widget, 'after_widget' => "</div>", 'before_title' => '<h2 class="widgettitle">', 'after_title' => '</h2>' );

	$args = wp_parse_args($args, $default_args);
	$instance = wp_parse_args($instance);

	do_action( 'the_widget', $widget, $instance, $args );

	$widget_obj->_set(-1);
        if(method_exists($widget_obj, 'get_widget')){
            $output = $widget_obj->get_widget($args, $instance);
        }else{
            ob_start();
            $widget_obj->widget($args, $instance);
            $output = ob_get_contents();
            ob_end_clean();

        }

        return $output;
}




