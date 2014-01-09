<?php

class Redux_Widget_blog extends Redux_Widget {

    var $excerpt_length;

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        if (is_admin()) {
            return "<center><i class='icon-list-alt  icon-4x'></i></center>";
        }
        $output = '';
        if (empty($instance['categories']))
            $instance['categories'] = "";
        if (isset($instance['link']) && isset($instance['blog_type']) && $instance['blog_type'] == 'taxonomy') {
            //$instance['link'] = explode(',', $instance['link'], 2);
            $instance['taxonomy'] = $instance['link'][0];

            if (!empty($instance['link'][1])) {
                $instance['categories'] = $instance['link'][1];
            } else if (!empty($instance['taxonomy'])) {
                $taxonomy_terms_obj = get_terms($instance['taxonomy']);
                foreach ($taxonomy_terms_obj as $taxonomy_term) {
                    $instance['categories'] .= $taxonomy_term->term_id . ',';
                }
            }
        }

        $instance = wp_parse_args($instance, array(
            'blog_style' => '',
            'columns' => 3,
            'blog_type' => 'posts',
            'items' => '16',
            'paginate' => 'yes',
            'categories' => '',
            'preview_mode' => 'auto',
            'image_size' => '6_column',
            'post_type' => 'post',
            'taxonomy' => 'category',
            'contents' => 'excerpt',
            'content_length' => 'content',
            'offset' => '0',
            'excerpt_length' => '55',
            'post_meta' => '1',
            'widget_title' => ''
        ));
        $this->excerpt_length = $instance['excerpt_length'];
        add_filter( 'excerpt_length', array($this,'custom_excerpt_length'), 999 );

        $this->query_entries($instance);

        $instance['blog_content'] = $instance['content_length'];

        $instance['remove_pagination'] = $instance['paginate'] === "yes" ? false : true;
        //print_r($instance);
        $more = 0;
        //ob_start(); //start buffering the output instead of echoing it
        //include_once ReduxPageBuilder::$_dir . '/inc/loop-index.php';


        global $options, $post_loop_count, $wp_query;

        //default for temprory
        $options['blog_style'] = 'small-featured';
        $options['single_post_style'] = 'small-featured';

        if (empty($post_loop_count))
            $post_loop_count = 1;
        $blog_style = !empty($instance['blog_style']) ? $instance['blog_style'] : $options['blog_style'];
        //if (is_single())
        //    $blog_style = $options['single_post_style'];
        $blog_content = !empty($instance['blog_content']) ? $instance['blog_content'] : "content";
//        $output = "
//            <style type='text/css'>
//                .Redux_Widget_video{
//                    display: none;
//                }
//            </style>
//         ";
        ?>

        <?php

        if(!empty($instance['widget_title'])){
            $output .= "<h3 class='widget-title blog-widget-title'>{$instance['widget_title']}</h3>";
        }

        // check if we got posts to display:
        if (have_posts()) :

            while (have_posts()) : the_post();


                /*
                 * get the current post id, the current post class and current post format
                 */
                $column_width = "";
                switch ($blog_style) {
                    case 'list-large':
                    case 'list-medium':
                    case 'list-small':
                        $column_width = "col-md-12";
                        break;
                    case 'grid-medium':
                        $column_width = "col-md-4";
                        break;
                    case 'grid-small':
                        $column_width = "col-md-3 col-xs-4";
                        break;
                    case 'grid-mini':
                        $column_width = "col-md-2 col-xs-3";
                        break;
                }
                $the_id = get_the_ID();
                $parity = $post_loop_count % 2 ? 'odd' : 'even';
                $last = count($wp_query->posts) == $post_loop_count ? " post-entry-last " : "";
                $post_class = "post-entry-" . $the_id . " post-loop-" . $post_loop_count . " post-parity-" . $parity . $last . " ".$column_width." ";
                $post_format = get_post_format() ? get_post_format() : 'standard';

                /*
                 * retrieve slider, title and content for this post,...
                 */
                
                $size = '9_culomn';
                if (!empty($instance['preview_mode']) && !empty($instance['image_size']) && $instance['preview_mode'] == 'custom')
                    $size = $instance['image_size'];
  
                $current_post['slider'] = get_the_post_thumbnail($the_id, $size);
                $current_post['title'] = get_the_title();

                $current_post['content'] = $blog_content == "content" ? get_the_content(__('Read more', 'redux-page-builder') . '<span class="more-link-arrow">  &rarr;</span>') : get_the_excerpt();

                $current_post['content'] = $blog_content == "excerpt_read_more" ? $current_post['content'] . '<div class="read-more-link"><a href="' . get_permalink() . '" class="more-link">' . __('Read more', 'redux-page-builder') . '<span class="more-link-arrow">  &rarr;</span></a></div>' : $current_post['content'];

                $current_post['before_content'] = "";

                /*
                 * ...now apply a filter, based on the post type... (filter function is located in includes/helper-post-format.php)
                 */
                $current_post = apply_filters('post-format-' . $post_format, $current_post);
                $with_slider = empty($current_post['slider']) ? "" : "with-slider";
                
                /*
                 * ... last apply the default wordpress filters to the content
                 */
                $current_post['content'] = str_replace(']]>', ']]&gt;', apply_filters('the_content', $current_post['content']));



                /*
                 * Now extract the variables so that $current_post['slider'] becomes $slider, $current_post['title'] becomes $title, etc
                 */
                extract($current_post);



                /*
                 * render the html:
                 */

                $output .= "<article class=' " . implode(" ", get_post_class('post-entry post-entry-type-' . $post_format . " " . $post_class . " " . $with_slider)) . "' " . schema_markup(array('context' => 'entry', 'echo' => false)) . "><div class='inner-entry post-loop'>";

                //default link for preview images
                $link = get_permalink();


                //echo preview image
                //if (strpos($blog_style, 'big') !== false) {
                    if ($slider)
                        $slider = '<a href="' . $link . '">' . $slider . '</a>';
                    if ($slider)
                        $output .= '<div class="big-preview ' . $blog_style . '">' . $slider . ' </div>';
                //}

                if (!empty($before_content))
                    $output .= '<div class="big-preview ' . $blog_style . '">' . $before_content . '</div>';

                $output .= "<div class='entry-content-wrapper clearfix {$post_format}-content'><div class='arrow'></div>";
                $output .= '<header class="entry-content-header">';
//                if($blog_style == 'grid-mini')
//                    $output .= modifier_truncate($title,100);
//                elseif($blog_style == 'grid-small')
//                    $output .= modifier_truncate($title,20);
//                else
                    $output .= $title;

                if($instance['post_meta']):  
                    $output .= "<span class='post-meta-infos'>";
                    $markup = schema_markup(array('context' => 'entry_time', 'echo' => false));
                    $output .= "<time class='date-container minor-meta updated' $markup><i class='icon-time'></i> " . get_the_time(get_option('date_format')) . "</time>";
                    //$output .= "<span class='text-sep text-sep-date'>/</span>";



                    if (get_comments_number() != "0" || comments_open()) {

                        $output .= "<span class='comment-container minor-meta'><i class='icon-comment'></i> ";
                        $output .= get_comments_popup_link("0 " . __('Comments', 'redux-page-builder'), "1 " . __('Comment', 'redux-page-builder'), "% " . __('Comments', 'redux-page-builder'), 'comments-link', "" . __('Comments Disabled', 'redux-page-builder'));
                        $output .= "</span>";
                        //$output .= "<span class='text-sep text-sep-comment'>/</span>";
                    }


                    $taxonomies = get_object_taxonomies(get_post_type($the_id));
                    $cats = '';
                    $excluded_taxonomies = apply_filters('exclude_taxonomies', array('post_tag', 'post_format'), get_post_type($the_id), $the_id);

                    if (!empty($taxonomies)) {
                        foreach ($taxonomies as $taxonomy) {
                            if (!in_array($taxonomy, $excluded_taxonomies)) {
                                $cats .= get_the_term_list($the_id, $taxonomy, '', ', ', '') . ' ';
                            }
                        }
                    }

                    if (!empty($cats)) {
                        $output .= '<span class="blog-categories minor-meta"><i class="icon-tags"></i> ' . __('in', 'redux-page-builder') . " ";
                        $output .= $cats;
                        $output .= '</span>';
                    }


                    $output .= '<span class="blog-author minor-meta"><i class="icon-user"></i> ' . __('by', 'redux-page-builder') . " ";
                    $output .= '<span class="entry-author-link" ' . schema_markup(array('context' => 'author_name', 'echo' => false)) . '>';
                    $output .= '<span class="vcard author"><span class="fn">';
                    $output .= get_the_author_posts_link();
                    $output .= '</span></span>';
                    $output .= '</span>';
                    $output .= '</span>';
                    $output .= '</span>';
                endif;

                $output .= '</header>';


                // echo the post content
                $output .= '<div class="entry-content" ' . schema_markup(array('context' => 'entry_content', 'echo' => false)) . '>';
                $output .= $content;
                $output .= '</div>';

                $output .= '<footer class="entry-footer">';

                wp_link_pages(array('before' => '<div class="pagination_split_post">',
                    'after' => '</div>',
                    'pagelink' => '<span>%</span>'
                ));

                if (has_tag() && is_single() && !post_password_required()) {
                    $output .= '<span class="blog-tags minor-meta">';
                    the_tags('<strong>' . __('Tags:', 'redux-page-builder') . '</strong><span> ');
                    $output .= '</span></span>';
                }

                $output .= '</footer>';

                $output .= "<div class='post_delimiter'></div>";
                $output .= "</div><div class='clearfix'></div>";
                $output .= "</div></article>";

                $post_loop_count++;
            endwhile;
        else:
            ?>

            <article class="entry">
                <header class="entry-content-header">
                    <h1 class='post-title entry-title'><?php _e('Nothing Found', 'redux-page-builder'); ?></h1>
                </header>

                <p class="entry-content" <?php schema_markup(array('context' => 'entry_content')); ?>><?php _e('Sorry, no posts matched your criteria', 'redux-page-builder'); ?></p>

                <footer class="entry-footer"></footer>
            </article>

        <?php
        endif;

        $output .= "<div class='clearfix'></div>";
        if (isset($instance['remove_pagination']) && $instance['remove_pagination'] === false) {
            $output .= "<div class='{$blog_style}'>" . pagination($wp_query, false) . "</div>";
        }


        //$output = ob_get_clean();
        wp_reset_query();

        if ($output) {
            $markup = schema_markup(array('context' => 'blog', 'echo' => false));
            $output = "<div class='{$blog_style} template-blog' {$markup}>{$output}</div>";
        }

        return $output;
    }

    function query_entries($params) {

        $query = array();

        if (!empty($params['categories']) && is_string($params['categories'])) {
            //get the categories
            $terms = explode(',', $params['categories']);
        }

        $page = get_query_var('paged') ? get_query_var('paged') : get_query_var('page');
        if (!$page)
            $page = 1;

        if ($params['offset'] == 'no_duplicates') {
            $params['offset'] = 0;
            $no_duplicates = true;
        }

        //if we find categories perform complex query, otherwise simple one
        if (isset($terms[0]) && !empty($terms[0]) && !is_null($terms[0]) && $terms[0] != "null" && !empty($params['taxonomy'])) {
            $query = array('paged' => $page,
                'posts_per_page' => $params['items'],
                'offset' => $params['offset'],
                'post__not_in' => (!empty($no_duplicates)) ? $params['posts_on_current_page'] : array(),
                'tax_query' => array(array('taxonomy' => $params['taxonomy'],
                        'field' => 'id',
                        'terms' => $terms,
                        'operator' => 'IN')));
        } else {
            $query = array('paged' => $page,
                'posts_per_page' => $params['items'],
                'offset' => $params['offset'],
                'post__not_in' => (!empty($no_duplicates)) ? $params['posts_on_current_page'] : array(),
                'post_type' => $params['post_type']);
        }

        $query = apply_filters('builder_blog_post_query', $query, $params);

        query_posts($query);

        // store the queried post ids in
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                $params['posts_on_current_page'][] = get_the_ID();
            }
        }
    }

    function custom_excerpt_length( $length ) {
        return $this->excerpt_length;
    }

}
