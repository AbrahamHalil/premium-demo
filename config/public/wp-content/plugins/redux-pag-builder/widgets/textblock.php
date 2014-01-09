<?php
class Redux_Widget_textblock extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract( wp_parse_args($instance,array(
            '_content' => '',
            'widget_title' => '',
            'output' => '',
        )));

        if(!empty($widget_title)){
            $output .= "<h3 class='widget-title textblock-widget-title'>{$widget_title}</h3>";
        }
        $output .= $_content;
        return $output;
    }

}