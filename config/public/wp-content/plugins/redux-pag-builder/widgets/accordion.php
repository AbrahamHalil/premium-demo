<?php
class Redux_Widget_accordion extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
    	print_r($instance);
        $atts =  wp_parse_args($instance,array('initial' => '0'));
        extract($atts);
        //print_r($instance);die();
        //echo $output  = '<div class="tabbable  '.$position.'  ">'."\n";//'.$boxed.'
        $counter = 1;
        $id = rand();
        $output = "
        <div class='panel-group' id='accordion'>
        ";

        foreach ($accordions as $key => $value):
            $use_initial = ($initial == ($key+1) )?'in':"";
            $output .= "
            <div class='panel panel-default'>
                <div class='panel-heading'>
                    <h4 class='panel-title'>
                        <a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#{$id}-{$key}'>
                            {$acc_title[$key]}
                        </a>
                    </h4>
                </div>
                <div id='{$id}-{$key}' class='panel-collapse collapse {$use_initial}'>
                    <div class='panel-body'>
                        {$_content[$key]}
                    </div>
                </div>
            </div>
            ";
        endforeach;
        $output .= "
        </div>
        ";

        return $output;
    }

}