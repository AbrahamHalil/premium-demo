<?php
class Redux_Widget_pricetable extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract( wp_parse_args($instance,array(
            'title' => '', 
            'color' => '', 
            'price' => '',
            'duration' => '',
            'button' => '0',
            'label' => '',
            'link' => '',
            'output' => '',
        )));
        
        $use_href = (is_array($link) && count($link)>0)?get_url($link):'#';
        
        $use_features = "";
        foreach ($features as $key => $value) {                   
            $use_features .= "
                <li class='list-group-item'><i class='{$icon[$key]} text-{$color}'></i> {$feature_title[$key]}</li>
            ";
        }

        $output .= "
        <div class='panel price panel-{$color}'>
            <div class='panel-heading  text-center'>
                <h3>{$title}</h3>
            </div>
            <div class='panel-body text-center'>
                <div class='lead'><div class='amount pull-left'>{$price}</div> <div class='duration pull-left'>{$duration}</div></div>
            </div>
            <ul class='list-group list-group-flush text-center'>
                {$use_features}
            </ul>
            <div class='panel-footer'>
                <a class='btn btn-lg btn-block btn-{$color}' href='{$use_href}'>{$label}</a>
            </div>
        </div>
        ";
        return $output;

    }

}
