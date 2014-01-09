<?php
class Redux_Widget_image extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract(wp_parse_args($instance,array(
            'align' => '',
            'animation' => '',
            'class' => '',
            'style' => '',
            'size'  => '',
            )));

        if (is_admin()) {
            return "<center><i class='icon-picture  icon-4x'></i></center>";
        }
        if (!empty($image['url'])) {
            if($animation != "no-animation"){
                $class = "image animated_image animate_when_visible ".$animation;
            }

            $link = get_url($link);
            if($image['id'] > 0 && $size != ''){
                $img = wp_get_attachment_image_src($image['id'], $size);
                $use_image = " src='{$img[0]}' width='{$img[1]}' height='{$img[2]}' ";
            }else{
                $img = $image['url'];;
                $use_image = " src='{$img}' ";
            }

            if ($link) {
                $output = "<a href='".$link."' ><img class='img-responsive {$align} {$class} {$style}'  data-animation='".esc_attr($animation)."' {$use_image} alt='' /></a>";
            } else {
                $output = "<img class='img-responsive {$align} {$class} {$style}' ' data-animation='".esc_attr($animation)."' alt='' {$use_image} />";
            }
        }
        return $output;
    }


}