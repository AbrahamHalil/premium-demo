<?php
class Redux_Widget_slide extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract( wp_parse_args($instance,array(
            'link' => '',
            'link_target' => '',
            'caption_title' => '',
            'caption_text' => '',
            'size' => '',
            'size_option' => '1',
            'width' => '',
//            'class' => '',
            'interval' => '5000',
            )));
        if(is_admin()){
            return "<center><i class='icon-youtube-play  icon-4x'></i></center>";
        }
        $output = "";
        $class = "";
        if($size_option == '1'){
            if ( cadr_section_class( 'wrap' ) ) {
                $output .= "</div></div></div></div></div></div></div></div></div></div>";
            }else{
                $output .= "</div></div></div></div></div></div>";
            }
        }

        $use_width = '';
        if($size_option == '0'){
            //check how large the slider is and change the classname accordingly
            global $_wp_additional_image_sizes;
            $_wp_additional_image_sizes = get_registered_image_sizes(array('thumbnail','logo','widget','slider_thumb'),true);
            $width = 1500;
            
            if(isset($_wp_additional_image_sizes[$size]['width']))        
                $width = $_wp_additional_image_sizes[$size]['width'];        
            else if($size = get_option( $size.'_size_w' ))        
                $width = $size; 

            if($width < 600)        
                $class = " small-width-slider";    
            
            if($width < 305)
                $class = " super-small-width-slider"; 

            //$use_width = "style='width:{$width}px'";
            $use_width = "";         
        }
        //$size = $size_option == '1'?'full':$size;
        $id = "carousel-slide-".rand();
        $output .= "
        <div id='{$id}' class='carousel slide slideshow-{$size} {$class}' {$use_width}>
            <!-- Indicators -->
            <ol class='carousel-indicators'>
            ";
        $i = 0;            
        foreach ($slides as $key => $value):
            $active = $i == 0?"class='active'":'';
            $use_link_target = isset($link_target[$key]) && !empty($link_target[$key])?"target='".$link_target[$key]."'":'';
            //$use_link = isset($link[$key]) && is_array($link[$key])?get_url($link[$key]):'#';

            $output .= "<li data-target='#{$id}' data-slide-to='{$i}' {$use_link_target} {$active}></li>";
            $i++;
        endforeach; 
        $output .= "
            </ol>

            <!-- Wrapper for slides -->
            <div class='carousel-inner'>
        ";
        $i = 0;
        //print_r($size);
        foreach ($slides as $key => $value):
            $active = $i == 0?"active":'';
            $use_image = "";
            if(isset($image[$key]) && is_array($image[$key])){
                
                $img   = wp_get_attachment_image_src($image[$key]['id'], $size);
                $img_title = isset($caption_title[$key]) && !empty($caption_title[$key])?"{$caption_title[$key]}":"";
                $use_image = "<img src='{$img[0]}' alt='{$img_title}' width='{$img[1]}' height='{$img[2]}' />";
            }
            //$use_image = isset($image[$key]['url']) && !empty($image[$key]['url'])?"<img alt='' src='".$image[$key]['url']."' />":'';
            $use_caption_title = isset($caption_title[$key]) && !empty($caption_title[$key])?"<h3>{$caption_title[$key]}</h3>":"";
            $use_caption_text = isset($caption_text[$key]) && !empty($caption_text[$key])?"<p>{$caption_text[$key]}</p>":"";
            $output .= "
                <div class='item {$active}'>
                    {$use_image}
                    <div class='carousel-caption'>
                        {$use_caption_title}
                        {$use_caption_text}
                    </div>
                </div>
            ";
            $i++;
        endforeach;
        $output .= "
            </div>

            <!-- Controls -->
            <a class='left carousel-control' href='#{$id}' data-slide='prev'>
                <span class='icon-prev'></span>
            </a>
            <a class='right carousel-control' href='#{$id}' data-slide='next'>
                <span class='icon-next'></span>
            </a>
        </div>    
        ";   

        $use_interval = $interval == 'false'?'false':$interval*1000;
        $output .= "
        <script>
            !function ($) {
                $(function(){
                    $('#{$id}').carousel({interval: {$use_interval} });
                });
            }(window.jQuery)
        </script>
        ";
        if($size_option == '1'){
            $output .= new_section(array('open_structure' => true,'open_color_wrap' => false,'close'=>false, 'id' => "after_full_slider"));
            $output .= "<div><div>";
            ?>
            <script type="text/javascript">
                jQuery( document ).ready(function(){
                    
                    jQuery('.slideshow-full_width').css({'width':jQuery( 'body' ).width()});
                });
            </script>
            <?php
        }

        return $output;
    }

}