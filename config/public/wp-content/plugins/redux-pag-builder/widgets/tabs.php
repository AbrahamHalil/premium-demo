<?php
class Redux_Widget_tabs extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        $atts =  wp_parse_args($instance,array('initial' => '1', 'position' => 'top_tab', 'boxed'=>'border_tabs'));
        extract($atts);

        //$output  = "<div class='tabbable  {$position} '>";//'.$boxed.'
        $counter = 1;
        $id = rand();

        $tabs_heading = "
        <!-- tabs -->
        <ul class='nav nav-tabs'>
        ";
        foreach ($tabs as $key => $value):
            $use_active_class = ($initial == ($key+1) )?"class='active'":"";
            $use_icon = ($icon_select[$key] && $icon[$key] != '')?"<i class='{$icon[$key]}'></i>":"";
            $tabs_heading .= "
            <li {$use_active_class}>
                <a href='#{$id}-{$key}' data-toggle='tab'>{$use_icon} {$tab_title[$key]}</a>
            </li>
            ";
        endforeach;
        $tabs_heading .= "
        </ul>";
        $tab_contents = "<div class='tab-content'>
        ";
        foreach ($tabs as $key => $value):
            $use_active_class = ($initial == ($key+1) )?"active":"";
            $_content[$key] = stripcslashes($_content[$key]);
            $tab_contents .= "
            <div class='tab-pane {$use_active_class}' id='{$id}-{$key}'>{$_content[$key]}</div>";
        endforeach;
        $tab_contents .= "
        </div>
        <!-- /tabs -->
        ";

        if($position == 'tabs-right'){
            $output = "<div class='tabbable {$position}'>{$tab_contents} {$tabs_heading}</div>";
        }else{
            $output = "<div class='tabbable {$position}'>{$tabs_heading} {$tab_contents}</div>";
        }

        //$output .= '</div>';

        return $output;
    }

}