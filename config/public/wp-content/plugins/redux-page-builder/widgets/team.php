<?php
class Redux_Widget_team extends Redux_Widget {

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
            'image' => array(),
            'name' => '',
            'subtitle' => '',
            '_content' => '',
            'output' => '',
            )));

        if (is_admin()) {
            return "<center><i class='icon-picture  icon-4x'></i></center>";
        }
        
        if(isset($image['id']) && !empty($image['id'])):
            $img = wp_get_attachment_image_src($image['id'], 'medium');
            $use_image = " src='{$img[0]}' width='{$img[1]}' height='{$img[2]}' ";


            //button link
            if(is_array($link) && count($link) > 0 ){
                $link = get_url($link);
            }else{
                $link = '#';
            }
            $target = $link_target == '_blank'?'target="_blank"':'';
            $class = $color." ".$size." ".$style;

            if($icon_select == '1'){
                $font_icon = '<i class="'.$icon.'"></i> ';
                $class = $class." btn-with-icon";
            }

            $link = '<a href="'.$link.'" class="btn '.$class.'" '.$target.'>'.$font_icon.$label.'</a>';

            $output = "
            <div class='shape'>
                <a href='#' class='team-overlay hexagon'></a>
                <div class='details'>
                    <span class='heading'>{$name}</span>

                    <p>
                        {$subtitle} <br />
                        {$_content}
                    </p> 
                    <br />       
                    {$link}      
                </div>
                <div class='bg'></div>
                <div class='base'>
                    <img class='img-responsive' {$use_image} alt='' />
                </div>
            </div>
            ";
        endif;

        return $output;
    }


}