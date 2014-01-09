<?php
class Redux_Widget_iconlist extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract(wp_parse_args($instance,array(
            'test' => '',
        )));
        
        $output = "<!-- icon list -->";
        $output .= "<ul class='iconlist'>";

        foreach ($iconlist as $key => $value):
            $use_content = isset($_content[$key]) && !empty($_content[$key])?"<p>{$_content[$key]}</p>":"";
            $use_content = "";//temporary
            $output .= "
            <li>
                <i class='{$icon[$key]}'></i>  {$list_title[$key]}{$use_content}
            </li>
            ";
        endforeach;
        $output .= "</ul>";
        $output .= "<!-- /Icon list -->";

        return $output;
    }

}