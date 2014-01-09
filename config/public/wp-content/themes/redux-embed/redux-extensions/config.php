<?

$redux_opt_name = "redux_demo";

// The loader will load all of the extensions automatically.
// Alternatively you can run the include/init statements below.
require_once(dirname(__FILE__).'/loader.php');




if ( !function_exists( "redux_add_widget_areas" ) ) {
    function redux_add_widget_areas($widget_areas) {

        $widget_areas[] = "Custom Sidebar 1";
        $widget_areas[] = "Custom Sidebar 2";

        return $widget_areas;
    }
    add_action('redux/widget_areas', 'redux_add_widget_areas');
}