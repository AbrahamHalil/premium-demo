<?php
class Redux_Widget_progressbar extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract( wp_parse_args($instance,array(
            'pb_title' => '',
            'progressbar' => '', 
            'color' => '', 
            'striped' => '',
            'animated' => '',
            'icon_select' => '',
            'icon' => '',
        )));
        $output = "";
        foreach ($progressbar as $k => $value):
            $use_striped = (isset($striped[$k]) && $striped[$k] == '1')?'progress-striped':'';
            $use_animated = (isset($animated[$k]) && $animated[$k] == '1')?'active':'';
            $use_icon = ($icon_select[$k] == '1' && !empty($icon[$k]))?'<i class="'.$icon[$k].'"></i> ':'';

            $output .= "
            <div class='progress {$use_striped} {$use_animated} animate_when_visible' data-animation='expand'>
                <div class='progressbar-title-wrap'>{$use_icon}{$pb_title[$k]}</div>
                <div class='progress-bar {$color[$k]}' role='progressbar' aria-valuenow='{$progress[$k]}' aria-valuemin='0' aria-valuemax='100' style='width: {$progress[$k]}%;'>
                    <span class='sr-only'>{$progress[$k]}% Complete</span>
                </div>
            </div>
            ";
        endforeach;
        return $output;
    }

}
