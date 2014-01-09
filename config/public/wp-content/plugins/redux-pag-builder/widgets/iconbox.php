<?php
class Redux_Widget_iconbox extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract(wp_parse_args($instance,array(
            'title' => 'Title',
            'icon' => '1', 
            'position' => 'left', 
            'link' =>'', 
            'linktarget' => 'no',
            '_content' => 'Content of icon Box'
            )));

                
        if($position == 'top') $position .= " main_color flex_column ";

        $linktarget = ($linktarget == 'no') ? '' : 'target="_blank"';
        $link = get_url($link);
        if(!empty($link)){
            $title = "<a href='$link' title='".esc_attr($title)."' $linktarget>$title</a>";
        }

        // add blockquotes to the content
        $output  = '<div class="iconbox iconbox_'.$position.' ">';
        $output .= '<div class="iconbox_content">';
        $output .= '<div class="iconbox_icon heading-color"><i class="'.$icon.'"></i></div>';
        $output .= '<h3 class="iconbox_content_title">'.$title."</h3>";
        $output .= wpautop($_content );
        $output .= '</div></div>';
        return $output;

    }

}