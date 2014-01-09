<?php

class Redux_Widget_testimonial extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract( wp_parse_args($instance,array(
            'columns' => '',
            'grid' => '',
            'interval' => '5000',
            'output' => '',
            'image_url' => '',
            )));

        if(is_admin())
            return "<center><i class='icon-comments  icon-4x'></i></center>";



        //Grid style
        if($columns == '1'){
            switch($grid){
                case 1: $columnClass = "col-md-12"; break;
                case 2: $columnClass = "col-md-6"; break;
                case 3: $columnClass = "col-md-4"; break;
                case 4: $columnClass = "col-md-3"; break;
            }
            foreach ($slides as $key => $value):
                if(isset($src[$key]['id']) && $src[$key]['id'] > 0){
                    $img = wp_get_attachment_image_src($src[$key]['id'], 'thumbnail');
                    $image_url = $img[0];
                }
                $output .= "
                <div class='{$columnClass}'>
                    <blockquote>
                        <img class='testimonial-image animate_when_visible img-circle' src='{$image_url}' alt='{$name[$key]}' />
                        <p>
                            {$_content[$key]}
                            <strong>{$name[$key]}</strong>
                            <small>{$subtitle[$key]} - <a href='{$link[$key]}'>{$linktext[$key]}</a></small>
                        </p>
                        
                    </blockquote>
                </div>
                ";
            endforeach; 

        //slide style
        }else{
            $id = "carousel-testimonial-".rand();
            $output .= "
            <div id='{$id}' class='carousel slide'>
                <!-- Indicators -->
                <ol class='carousel-indicators'>
                ";
            $i = 0;            
            foreach ($slides as $key => $value):
                $active = $i == 0?"class='active'":'';
                $output .= "<li data-target='#{$id}' data-slide-to='{$i}'  {$active}></li>";
                $i++;
            endforeach; 
            $output .= "
                </ol>
                <!-- Wrapper for slides -->
                <div class='carousel-inner'>
            ";
            $i = 0;
            foreach ($slides as $key => $value):
                $active = $i == 0?"active":'';
                if(isset($src[$key]['id']) && $src[$key]['id'] > 0){
                    $img = wp_get_attachment_image_src($src[$key]['id'], 'thumbnail');
                    $image_url = $img[0];
                }

                $small = "";
                $subtitle[$key] = trim($subtitle[$key]);
                $linktext[$key] = trim($linktext[$key]);
                $_content[$key] = stripcslashes($_content[$key]);
                if( !empty($subtitle[$key]) && !empty($linktext[$key]) ){
                    $small = "<small>{$subtitle[$key]} - <a href='{$link[$key]}'>{$linktext[$key]}</a></small>";
                }

                $img = "";
                $no_margin = " class='no-margin'";
                if(!empty($image_url)){
                    $img = "<img class='testimonial-image animated start_animation img-circle' src='{$image_url}' alt='{$name[$key]}' />";
                    $no_margin = '';
                }
                $output .= "
                    <div class='item {$active}'>
                        <blockquote>
                            {$img}
                            <p {$no_margin}>
                                {$_content[$key]}
                                <strong>{$name[$key]}</strong>
                                {$small}
                            </p>
                        </blockquote>
                        
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
                        $('#<?php echo $id; ?>').carousel({interval: <?php echo $use_interval; ?>});
                    });
                }(window.jQuery)
            </script>
            ";

           
        }
        
        return $output;

    }

}