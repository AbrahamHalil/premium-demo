<?php

class ReduxFields extends ReduxFramework {

  //Current widget class
  public $widget;

  public function __construct($_sections = array(),$args = array(), $widget = null) {

    $args['dev_mode'] = false;
    $args['global_variable'] = false;

    if($widget != null){
      $this->widget = $widget;
      $sections[] = array(
        'title' => '',
        'fields' => $_sections,
      );
      
      add_filter('redux-support-group' , array($this,'field_support_group'),1,3);
    }else{
      $this->widget = null;
      $sections = $_sections;
    }

    parent::__construct( $sections, $args, $extra_tabs = array() );

    //check here if the field is availabe in ReduxPageBuilder fields folder
    //otherwise load it from ReduxFramework fields directory
    //remove_filter('redux-typeclass-load', 'add_extended_fields');
    //add_filter('redux-typeclass-load' , array($this,'fields_dir'));

    add_filter('redux/'.$args['opt_name'].'/field/class/linkpicker', array($this,'fields_dir'));

    //add_filter('redux-field-'.$this->args['opt_name'] , array($this,'field_support_widget'),10,2);
    add_filter('redux/field/'.$this->args['opt_name'].'/render/after' , array($this,'field_support_widget'),10,2);

    // Set option with defaults
    $this->_set_default_options();

    // Options page
    $this->_options_page();

    // Register setting
    $this->_register_settings();
  }


    /**
     * ->get_options(); This is used to get options from the database
     *
     * @since ReduxFramework 3.0.0
     */
    function get_options() {
      if($this->widget != null){
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
        $data_inst = $data_inst["widget-" . $mod->id_base][$mod->number];
        /* <input type='hidden' name='datafile' value='{$datafield}' /> */

        if (isset($instance) && $instance):
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

        if (!isset($iinstance))
            $iinstance = array();

        return $iinstance;
      }else{
        $defaults = false;
        if ( !empty( $this->defaults ) ) {
          $defaults = $this->defaults;
        }
        $results = unserialize(base64_decode($_REQUEST['section_settings_data']));

        if ( empty( $result ) && !empty( $defaults ) ) {
          $results = $defaults;
        }
        return $results;
      }
    }

    /**
     * ->set_options(); This is used to set an arbitrary option in the options array
     * but here we going to override the parent set_options function and ignore 
     * save options in database
     * @since ReduxFramework 3.0.0
     * @param mixed $value the value of the option being added
     */
    function set_options( $value = '' ) {
      //nothing to do here , just ignore
    }

    /**
     * Class Options Page Function, creates main options page.
     *
     * @since       1.0.0
     * @access      public
     * @return
     */
    function _options_page() {
        //nothing to do here , just ignore
    }

    /**
     * Register Option for use
     *
     * @since       1.0.0
     * @access      public
     * @return      void
     */
    public function _register_settings() {
      parent::_register_settings();
      $this->_enqueue();
      $this->_options_page_html();
    }

    /**
     * check here if the field is availabe in ReduxPageBuilder fields folder
     * otherwise load it from ReduxFramework fields directory
     *
     * @since       1.0.0
     * @access      public
     * @param       string $class_file the field directory
     * @return      string $class_file
     */
    public function fields_dir($class_file){
      $_file = pathinfo($class_file);
      $type = str_replace('field_', '', $_file['filename']);
      if(file_exists( ReduxPageBuilder::$_dir . '/inc/fields/' . $type . '/field_' . $type . '.php')){
        return ReduxPageBuilder::$_dir . '/inc/fields/' . $type . '/field_' . $type . '.php';
      }
      return $class_file;
    }

    /**
     * HTML OUTPUT.
     *
     * @since       1.0.0
     * @access      public
     * @return      void
     */
    public function _options_page_html() {
        $localize = array(
          'save_pending'      => __( 'You have changes that are not saved. Would you like to save them now?', 'redux-page-builder' ), 
          'reset_confirm'     => __( 'Are you sure? Resetting will loose all custom values.', 'redux-page-builder' ), 
          'preset_confirm'    => __( 'Your current options will be replaced with the values of this preset. Would you like to proceed?', 'redux-page-builder' ), 
          'opt_name'          => $this->args['opt_name'],
          'folds'       => $this->folds,
          'options'     => $this->options,
          'defaults'      => $this->options_defaults,
        );
        // Values used by the javascript
        ?>
        <script type="text/javascript">
            var redux_opts = <?php echo json_encode($localize); ?>;
        </script>
        <?php   
        foreach( $this->sections as $k => $section ) {
          echo '<div id="' . $k . '_section_group' . '" class="redux-group-tab">';
          if ( !empty( $section['sections'] ) ) {
            //$tabs = "";
          echo '<div id="' . $k . '_section_tabs' . '" class="redux-section-tabs">';
          echo '<ul>';                  
            foreach ($section['sections'] as $subkey => $subsection) {
              echo '<li><a href="#'.$k.'_section-tab-'.$subkey.'">'.$subsection['title'].'</a></li>';
            }
          echo '</ul>';
            foreach ($section['sections'] as $subkey => $subsection) {
              echo '<div id="' . $k .'sub-'.$subkey. '_section_group' . '" class="redux-group-tab">';
              echo '<div id="'.$k.'_section-tab-'.$subkey.'">';
              echo "hello".$subkey;
              do_settings_sections( $this->args['opt_name'] . $k . '_tab_'.$subkey.'_section_group' );  
              echo "</div>";
            }
            echo "</div>";
          } else {
            do_settings_sections( $this->args['opt_name'] . $k . '_section_group' );  
          }

          echo '</div>';
      }
    }


    /**
     * Section HTML OUTPUT.
     *
     * @since       1.0.0
     * @access      public
     * @param       array $section
     * @return      void
     */
    public function _section_desc($section) {
        //nothing to do here
    }

    public function field_support_widget($render,$field){

      if($this->widget == null)
        return $render;

      if(!isset($field['type']) || empty($field['type']))
        return $render;
      
      if(!file_exists( ReduxPageBuilder::$_dir . '/inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php'))
        return str_replace($this->args['opt_name'].'['.$field['id'].']', $this->widget->get_field_name($field['id']), $render);

      return $render;
    }

    public function field_support_group($content, $field, $sort) {
        remove_filter('redux-support-group' , array('ReduxFramework_group','support_multi'),1);
        //convert name
        $name = $this->widget->get_field_name($field['id']);
        //$content = str_replace($name, $name . '[' . $sort . ']', $content);
        $content = str_replace($name, $name . '[' . $sort . ']', $content);

        //we should add $sort to id to fix problem with select field
        //$content = str_replace('id="'.$field['id'].'"', 'id="'.$field['id'].'-'.$sort.'"', $content);
        return $content;
    } 

}
