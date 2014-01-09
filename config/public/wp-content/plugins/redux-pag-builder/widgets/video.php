<?php
class Redux_Widget_video extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract( wp_parse_args($instance,array(
            'src' => '', 
            'format' => '', 
            'height' => '9',
            'width' => '16',
            'style' => '',
        )));

        if(is_admin()){
            return "<center><i class='icon-play-sign  icon-4x'></i></center>";
        }

        global $wp_embed;
        $output = $wp_embed->run_shortcode("[embed]".trim($src)."[/embed]");
        if($format == 'custom'){
            $height = intval($height);
            $width  = intval($width);
            $ratio  = (100 / $width) * $height;
            $style = "style='padding-bottom:{$ratio}%;'";
        }
        $output = "<div {$style} class='video video-{$format}'>{$output}</div>";
        return $output;
    }

}