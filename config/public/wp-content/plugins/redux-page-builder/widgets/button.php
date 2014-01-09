<?php

class Redux_Widget_button extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
    	
        extract( wp_parse_args($instance,array(
            'label' => '', 
            'link_target' => '',
            //'link' => '#',
            'color'=>'', 
            'size'=>'',
            'position' => '', 
            'icon_select' => '',
            'font_icon' => '',
            'icon'=>'',
            'style' => 'rounded', 
            )));

        if(is_array($link) && count($link) > 0 ){
            $link = get_url($link);
        }else{
            $link = '#';
        }
        $target = $link_target == '_blank'?'target="_blank"':'';
        $class = $color." ".$size." ".$position." ".$style;

        if($icon_select == '1'){
            $font_icon = '<i class="'.$icon.'"></i> ';
            $class = $class." btn-with-icon";
        }

        $output = '<a href="'.$link.'" class="btn '.$class.'" '.$target.'>'.$font_icon.$label.'</a>';
        if($style == 'circle')
            $output = "<h2>".$output."</h2>";
        if($position == 'pull-center')
            return '<center>'.$output.'</center>';
        else
            return $output;
    }

}