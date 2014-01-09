<?php
class Redux_Widget_layerslider extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        if(is_admin())
            return "<center><i class='icon-th-large  icon-4x'></i></center>";
        $output = "";
        $instance['id'] = $instance['layerslider'];
        $skipSecond = false;

        //check if we got a layerslider
        global $wpdb;

        // Table name
        $table_name = $wpdb->prefix . "layerslider";

        // Get slider
        $slider = $wpdb->get_row("SELECT * FROM $table_name
                                    WHERE id = " . (int) $instance['layerslider'] . " AND flag_hidden = '0'
                                    AND flag_deleted = '0'
                                    ORDER BY date_c DESC LIMIT 1", ARRAY_A);


        if (!empty($slider)) {
            $slides = json_decode($slider['data'], true);
            $height = isset($slides['properties']['height'])?$slides['properties']['height']:"";
            $width = isset($slides['properties']['width'])?$slides['properties']['width']:"";
            $responsive = isset($slides['properties']['responsive'])?$slides['properties']['responsive']:"";
            $responsiveunder = isset($slides['properties']['responsiveunder'])?$slides['properties']['responsiveunder']:"";

            $params['style'] = " style='height: " . ($height + 1) . "px;' ";
        }


        $params['class'] = "layerslider main_color shadow ";
        $params['open_structure'] = false;
        $params['id'] = "layer_slider" ;

        $output .= "</div></div></div></div></div></div>";

        //$output .= new_section($params);
        $output .= layerslider_init($instance);
        //$output .= "</div>"; //close section


        //if (empty($skipSecond)) {
            $output .= new_section(array('open_structure' => true,'open_color_wrap' => false,'close'=>false, 'id' => "after_layerslider"));
            //$output .= new_section(array('close' => false, 'id' => "after_layer_slider"));
            $output .= '<div><div>';
        //}
        return $output;
        
    }

}