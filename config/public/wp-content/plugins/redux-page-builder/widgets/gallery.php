<?php
class Redux_Widget_gallery extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        extract( wp_parse_args($instance,array(
            'ids' => '', 
            'thumb_size' => '',
            'columns' => '',
            'imagelink'=>'', 
            'order' => 'ASC',
            'lightbox_size' => 'full',
            'preview_size' => '6_column',
            'columnClass' => '',
            'animation'  => 'fade',
            'animate_class' => '',
            'widget_title' => '',
            'output' => '',
        )));
        if (is_admin()) {
            return "<center><i class='icon-th-large  icon-4x'></i></center>";
        }

        $attachments = get_posts(array(
            'include' => $ids,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'order' => $order,
            'orderby' => 'post__in',
            )
        );

        if (!empty($attachments) && is_array($attachments)) {
            $id = rand();
            $thumb_width = round(100 / $columns, 4);
            if($animation != "no-animation"){
                $animate_class = 'animate_when_visible';
            }
            if(!empty($widget_title)){
                $output .= "<h3 class='widget-title gallery-widget-title'>{$widget_title}</h3>";
            }
            $output .= "<div class='gallery'>";
            $thumbs = "";
            $counter = 0;

            switch($columns){
                case 1: $columnClass = "col-md-12"; break;
                case 2: $columnClass = "col-md-6"; break;
                case 3: $columnClass = "col-md-4"; break;
                case 4: $columnClass = "col-md-3"; break;
                case 6: $columnClass = "col-md-2"; break;
            }

            foreach ($attachments as $attachment) {
                $class = "class='thumbnail $imagelink'";

                $img = wp_get_attachment_image_src($attachment->ID, $thumb_size);
                $link = wp_get_attachment_image_src($attachment->ID, 'full');
 

                $caption = trim($attachment->post_excerpt) ? wptexturize($attachment->post_excerpt) : "";
                $tooltip = $caption ? "title='" . $caption . "'" : "";

                $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
                $alt = !empty($alt) ? esc_attr($alt) : '';
                $title = trim($attachment->post_title) ? esc_attr($attachment->post_title) : "";
                $description = trim($attachment->post_content) ? esc_attr($attachment->post_content) : "";



                $thumbs .= " 
                    <div class='{$columnClass}'>
                        <a href='" . $link[0] . "' rel='{$id}'  {$class}  data-onclick='{$counter}' {$tooltip} ><img src='" . $img[0] . "' title='" . $title . "' data-animation='{$animation}' class='{$animate_class}' width='{$img[1]}' height='{$img[0]}' alt='" . $alt . "' /></a>
                    </div>
                ";
                $first = false;
            }

            //remove .fade to keep displaying the gallery
            if($animation != "no-animation"){
                $animation = '';
            }

            $output .= "<div class='gallery-thumb {$animation}'>{$thumbs}<div class='clearfix'></div></div>";
            $output .= "</div>";

            //generate thumb width based on columns
            //$output .= "<style type='text/css'>";
            //$output .= ".gallery-thumb a{width:{$thumb_width}%;}";
            //$output .= "</style>";
        }
        return $output;
    }

}