<?php

$redux_opt_name = "redux_demo";

// The loader will load all of the extensions automatically.
// Alternatively you can run the include/init statements below.
require_once(dirname(__FILE__).'/loader.php');




if ( !function_exists( "redux_add_widget_areas" ) ):
    function redux_add_widget_areas($widget_areas) {

        $widget_areas[] = "Custom Sidebar 1";
        $widget_areas[] = "Custom Sidebar 2";

        return $widget_areas;
    }
    add_action('redux/widget_areas', 'redux_add_widget_areas');
endif;



if ( !function_exists( "redux_add_metaboxes" ) ):
  function redux_add_metaboxes($metaboxes) {
      
      $boxSections[] = array(
        'title' => __('Home Settings', 'redux-framework-demo'),
        'header' => __('Welcome to the Simple Options Framework Demo', 'redux-framework-demo'),
        'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'redux-framework-demo'),
        'icon_class' => 'icon-large',
          'icon' => 'el-icon-home',
          // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
        'fields' => array(
          array(
            'title'     => __( 'Layout', 'redux-framework-demo' ),
            'desc'      => __( 'Select main content and sidebar arrangement. Choose between 1, 2 or 3 column layout.', 'redux-framework-demo' ),
            'id'        => 'layout',
            'default'   => 1,
            'type'      => 'image_select',
            'customizer'=> array(),
            'options'   => array( 
              0         => ReduxFramework::$_url . 'assets/img/1c.png',
              1         => ReduxFramework::$_url . 'assets/img/2cr.png',
              2         => ReduxFramework::$_url . 'assets/img/2cl.png',
              3         => ReduxFramework::$_url . 'assets/img/3cl.png',
              4         => ReduxFramework::$_url . 'assets/img/3cr.png',
              5         => ReduxFramework::$_url . 'assets/img/3cm.png',
            )
          )
        )
      );
  	  $metaboxes = array();
      $metaboxes[] = array(
        'id' => 'demo-layout',
        //'title' => __('Cool Options', 'redux-framework-demo'),
        'post_types' => array('page','post'),
        'position' => 'side', // normal, advanced, side
        'priority' => 'high', // high, core, default, low
        'sections' => $boxSections
      );

      // Kind of overkill, but ahh well.  ;)
      //$metaboxes = apply_filters( 'your_custom_redux_metabox_filter_here', $metaboxes );

  	return $metaboxes;
  }
  add_action('redux/metaboxes/'.$redux_opt_name.'/boxes', 'redux_add_metaboxes');
endif;