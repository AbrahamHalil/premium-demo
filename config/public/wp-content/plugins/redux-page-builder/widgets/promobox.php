<?php
class Redux_Widget_promobox extends Redux_Widget {

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
            'border' => '',
            'custom_bg' => '#444444',
            'custom_font' => '#ffffff',
            'size' => 'large',
            'dismiss' => '',
            'icon_select' => '1',
            'icon' => '',
            'icon_open' => '',
            'icon_close' => '',
            'button' => '0',
            'label' => '',
            'link' => '',
        )));
        
        if($icon_select){
            $icon_open = "<div class='col-md-1 col-xs-2'><i class='{$icon} icon-3x'></i></div><div class='col-md-11 col-xs-10'>";
            $icon_close = "</div><div class='clearfix'></div>";
        }
        $dismiss = ($dismiss)? "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>":'';
        

        $use_href = (is_array($link) && count($link)>0)?get_url($link):'#';
        $use_button = "<a class='btn-lg btn btn-{$color}' href='{$use_href}'>{$label}</a>";

        $output = "
        <div class='bs-callout bs-callout-{$color}'>
            {$icon_open}
            {$dismiss}
            <div class='col-md-10'>
                <h4> {$title}</h4>
                <p>{$_content}</p>
            </div>
            <div class='col-md-2'>
                {$use_button}
            </div>
            <div class='clearfix'></div>
            
            {$icon_close}
            
        </div>";
        return $output;


    }

}
