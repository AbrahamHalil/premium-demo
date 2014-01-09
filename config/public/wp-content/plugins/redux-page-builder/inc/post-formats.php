<?php

add_filter('post-format-standard', 'standard_post_formats', 10, 1); // Stander post format
add_filter('post-format-video', 'video_post_formats', 10, 1); //video post format
add_filter('post-format-image', 'image_post_formats', 10, 1); //image post format
add_filter('post-format-link', 'link_post_formats', 10, 1); //link post format

/**
 *   The standard_post_formats creates the default title for your posts.
 *   This function is used by most post types
 */
function standard_post_formats($current_post) {
    if (!empty($current_post['title'])) {
        $heading = is_singular() ? "h1" : "h2";

        $output = "";
        $output .= "<{$heading} class='post-title entry-title' ".schema_markup(array('context' => 'entry_title','echo'=>false)).">";
        $output .= "	<a href='" . get_permalink() . "' rel='bookmark' title='" . __('Permanent Link:', 'redux-page-builder') . " " . $current_post['title'] . "'>" . $current_post['title'];
        $output .= "			<span class='post-format-icon minor-meta'></span>";
        $output .= "	</a>";
        $output .= "</{$heading}>";

        $current_post['title'] = $output;
    }

    return $current_post;
}


function video_post_formats($current_post) {
    
    //replace empty url strings with an embed code
    $current_post['content'] = preg_replace( '|^\s*(https?://[^\s"]+)\s*$|im', "[embed]$1[/embed]", $current_post['content'] );
    
    //extrect embed and video codes from the content. if any were found execute them and prepend them to the post
    preg_match("!\[embed.+?\]|\[video.+?\]!", $current_post['content'], $match_video);

    if(!empty($match_video)){
            global $wp_embed;
            $video = $match_video[0];
            $current_post['before_content'] = "<div class='iframe-wrap'>".do_shortcode($wp_embed->run_shortcode($video))."</div>";
            $current_post['content'] = str_replace($match_video[0], "", $current_post['content']);
            $current_post['slider'] = "";
    }


//    $video = get_media_embedded_in_content($current_post['content']);
//
//    //remove the the first embed from the content so we can add it before the title
//    if(isset($video[0]) && !empty($video)){
//        $current_post['before_content'] = '<div class="video video-16-9">'.$video[0].'</div>';
//        $current_post['content'] = str_replace($video[0], '' , $current_post['content']);
//        $current_post['slider'] = "";
//    }

    return standard_post_formats($current_post);
}


function image_post_formats($current_post) {

    $prepend_image =$current_post['slider'];
    $image = "";

    if (!$prepend_image) {
        $image = url_regex($current_post['content'], 'image');
        if (is_array($image)) {
            $image = $image[0];
            $prepend_image = '<img src="' . $image . '" alt="" title ="" />';
        } else {
            $image = url_regex($current_post['content'], '<img />', "");
            if (is_array($image)) {
                $prepend_image =  $image[0] ;
            }
        }
    } else {
        $prepend_image = $prepend_image;
    }


    if (!empty($prepend_image) && is_string($prepend_image)) {
        if ($image)
            $current_post['content'] = str_replace($image, "", $current_post['content']);
        $current_post['before_content'] = "<a href='".get_permalink()."'>{$prepend_image}</a>";
        $current_post['slider'] = "";
    }


    return standard_post_formats($current_post);
}

/**
 *  The link_post_formats checks if the beginning of the post is a url. If thats the case this url will be aplied to the title.
 *  Otherwise the theme will search for the first URL within the content and apply this URL
 */
function link_post_formats($current_post) {
    //retrieve the link for the post
    $link = "";

    $pattern1 = '!^(https?|ftp)://(-\.)?([^\s/?\.#-]+\.?)+(/[^\s]*)?!';
    $pattern2 = "!^\<a.+?<\/a>!";
    $pattern3 = "!\<a.+?<\/a>!";

    //if the url is at the begnning of the content extract it
    preg_match($pattern1, $current_post['content'], $link);
    if (!empty($link[0])) {
        $link = $link[0];
        $markup = schema_markup(array('context' => 'entry_title','echo'=>false));
        $current_post['title'] = "<a href='$link' rel='bookmark' title='" . __('Link to:', 'redux-page-builder') . " " . the_title_attribute('echo=0') . "' $markup>" . get_the_title() . "</a>";
        $current_post['content'] = str_replace($link, "", $current_post['content']);
    } else {
        preg_match($pattern2, $current_post['content'], $link);
        if (isset($link[0]) && !empty($link[0])) {
            $current_post['title'] = $link[0];
            $current_post['content'] = str_replace($link, "", $current_post['content']);
        } else {
            preg_match($pattern3, $current_post['content'], $link);
            if (!empty($link[0])) {
                $current_post['title'] = $link[0];
            }
        }
    }

    if ($link) {
        if (is_array($link))
            $link = $link[0];

        $heading = is_singular() ? "h1" : "h2";
        $current_post['title'] = "<{$heading} class='post-title entry-title' " . schema_markup(array('context' => 'entry_title', 'echo' => false)) . ">" . $current_post['title'] . "</{$heading}>";

        //needs to be set for masonry
        $current_post['url'] = $link;
    }
    else {
        $current_post = standard_post_formats($current_post);
    }

    return $current_post;
}
