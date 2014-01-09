<?php
class Redux_Widget_horizontal extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract( wp_parse_args($instance,array(
            'class' => 'default', 
            'height' => '50',
            'position'=>'center', 
            'shadow'=>'no-shadow'
            )));
        $output  = "";
        $height  = trim($height, 'px% ');
        $style   = $class == 'invisible' ? "style='height:{$height}px'" : "";
        $class  .= $class == 'short' ? " hr-{$position}" : "";
        $class  .= $class == 'full'  ? " hr-{$shadow}" : "";

        $output .= "<div {$style} class='hr hr-{$class}'><span class='hr-inner'><span class='hr-inner-style'></span></span></div>";
        
        return $output;
    }

}