<?php

/**
 * Utility functions & Classes
 */
function number_array($from = 0, $to = 100, $steps = 1, $exclude = array(), $array = array()) {
    for ($i = $from; $i <= $to; $i += $steps) {
        if (!in_array($i, $exclude))
            $array[$i] = $i;
    }

    return $array;
}

function get_registered_image_sizes($exclude = array(), $wp_additional_image_sizes = false) {
    global $_wp_additional_image_sizes,$image_sizes;

    // Standard sizes
    $image_sizes = wp_parse_args($image_sizes, array(
        'no scaling' => array("width" => __(' Original Width', 'redux-page-builder'), "height" => __(' Original Height', 'redux-page-builder')),
        'thumbnail' => array("width" => get_option('thumbnail_size_w'), "height" => get_option('thumbnail_size_h')),
        'medium' => array("width" => get_option('medium_size_w'), "height" => get_option('medium_size_h')),
        'large' => array("width" => get_option('large_size_w'), "height" => get_option('large_size_h')),
    ));


    if (isset($_wp_additional_image_sizes) && count($_wp_additional_image_sizes))
        $image_sizes = array_merge($image_sizes, $_wp_additional_image_sizes);
    if ($wp_additional_image_sizes)
        return $image_sizes;
    $result = array();
    foreach ($image_sizes as $key => $image) {
        if ((is_array($exclude) && !in_array($key, $exclude)) || (is_numeric($exclude) && ($image['width'] > $exclude || $image['height'] > $exclude)) || !is_numeric($image['height'])) {
            $title = str_replace("_", ' ', $key) . " (" . $image['width'] . "x" . $image['height'] . ")";

            $result[$key] = ucwords($title);
        }
    }

    return $result;
}

function new_section($params = array()) {
    $defaults = array('class' => 'main_color', 'bg' => '', 'close' => true, 'open' => true, 'open_structure' => true, 'open_color_wrap' => true, 'data' => '', "style" => '', 'id' => "");
    extract(array_merge($defaults, $params));

    $post_class = "";
    $output = "";
    if ($id)
        $id = "id='{$id}'";

    //close old
    if ($close){
        if ( cadr_section_class( 'wrap' ) ) {
            //$output .= '</div></div></div></div></div></div>';
            $output .= '</div></div></div></div></div></div></div>';
        }else{
            $output .= '</div></div></div></div>';
        }
    }

    //start new
    if ($open) {

        global $post;
        if (isset($post->ID)) {
            $ID = $post->ID;
            $config['real_ID'] = $ID;
        } else {
            $post = get_post();
            $ID = $post->ID;
        }

        $post_class = "post-entry-" . $ID;

        if ($open_color_wrap) {
            $output .= "<div {$id} class='{$class}' {$bg} {$data} {$style}>";
        }

        if ($open_structure) {
            $main_class = cadr_section_class( 'main' );
            $output .= "<div class='wrap container'>";
            $output .= "<div class='content row'>";
            //$output .= "<div class='post-entry post-entry-type-page {$post_class}'>";
            $output .= "<div class='main {$main_class}'>";
        }
    }
    return $output;
}

function get_url($link, $post_id = false) {
    //$link = explode(',', $link);
    //print_r($link);
    //echo "ee";
    if (isset($link[0]) && $link[0] == 'lightbox') {
        $link = wp_get_attachment_image_src($post_id, 'large');
        return $link[0];
    }

    if (isset($link[1]) && empty($link[1]))
        return $link[0];
    if (isset($link[0]) && $link[0] == 'manually')
        return $link[1];
    if (post_type_exists($link[0]))
        return get_permalink($link[1]);
    if (taxonomy_exists($link[0]))
        return get_term_link(get_term($link[1], $link[0]));
}

function url_regex($string, $pattern = false, $start = "^", $end = "") {
    if (!$pattern)
        return false;

    if ($pattern == "url") {
        $pattern = "!$start((https?|ftp)://(-\.)?([^\s/?\.#-]+\.?)+(/[^\s]*)?)$end!";
    } else if ($pattern == "mail") {
        $pattern = "!$start\w[\w|\.|\-]+@\w[\w|\.|\-]+\.[a-zA-Z]{2,4}$end!";
    } else if ($pattern == "image") {
        $pattern = "!$start(https?(?://([^/?#]*))?([^?#]*?\.(?:jpg|gif|png)))$end!";
    } else if (strpos($pattern, "<") === 0) {
        $pattern = str_replace('<', "", $pattern);
        $pattern = str_replace('>', "", $pattern);

        if (strpos($pattern, "/") !== 0) {
            $close = "\/>";
            $pattern = str_replace('/', "", $pattern);
        }
        $pattern = trim($pattern);
        if (!isset($close))
            $close = "<\/" . $pattern . ">";

        $pattern = "!$start\<$pattern.+?$close!";
    }

    preg_match($pattern, $string, $result);

    if (empty($result[0])) {
        return false;
    } else {
        return $result;
    }
}

function modifier_truncate($string, $length = 80, $etc = '...',$break_words = false, $middle = false){
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length) . $etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}

/*
 * Returns the schema.org markup based on the context value.
 * $args: context (string), echo (boolean) and post_type (string)
 */

function schema_markup($args) {
    if (!empty($args))
        $args = array_merge(array('context' => '', 'echo' => true, 'post_type' => ''), $args);

    $args = apply_filters('schema_markup_args', $args);
    if (empty($args['context']))
        return;

    // markup string - stores markup output
    $markup = ' ';
    $attributes = array();

    //try to fetch the right markup
    switch ($args['context']) {
        case 'body':
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/WebPage';
            break;

        case 'header':
            $attributes['role'] = 'banner';
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/WPHeader';
            break;

        case 'title':
            $attributes['itemprop'] = 'headline';
            break;

        case 'description':
            $attributes['itemprop'] = 'description';
            break;

        case 'nav':
            $attributes['role'] = 'navigation';
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/SiteNavigationElement';
            break;

        case 'content':
            $attributes['role'] = 'main';
            $attributes['itemprop'] = 'mainContentOfPage';

            //* Blog microdata
            if (is_singular('post') || is_archive() || is_home()) {
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype'] = 'http://schema.org/Blog';
            }

            if (is_archive() && $args['post_type'] == 'products') {
                $attributes['itemtype'] = 'http://schema.org/SomeProducts';
            }

            //* Search results pages
            if (is_search()) {
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype'] = 'http://schema.org/SearchResultsPage';
            }
            break;

        case 'entry':
            global $post;
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/CreativeWork';

            //* Blog posts microdata
            if ('post' === $post->post_type) {
                $attributes['itemtype'] = 'http://schema.org/BlogPosting';

                //* If main query,
                if (is_main_query())
                    $attributes['itemprop'] = 'blogPost';
            }
            break;

        case 'phone':
            $attributes['itemprop'] = 'telephone';
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/LocalBusiness';
            break;

        case 'image':
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/ImageObject';
            break;

        case 'image_url':
            $attributes['itemprop'] = 'contentURL';
            break;

        case 'name':
            $attributes['itemprop'] = 'name';
            break;

        case 'email':
            $attributes['itemprop'] = 'email';
            break;

        case 'job':
            $attributes['itemprop'] = 'jobTitle';
            break;

        case 'url':
            $attributes['itemprop'] = 'url';
            break;

        case 'affiliation':
            $attributes['itemprop'] = 'affiliation';
            break;

        case 'author':
            $attributes['itemprop'] = 'author';
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/Person';
            break;

        case 'person':
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/Person';
            break;

        case 'single_image':
            $attributes['itemprop'] = 'image';
            break;

        case 'author_link':
            $attributes['itemprop'] = 'url';
            break;

        case 'author_name':
            $attributes['itemprop'] = 'name';
            break;

        case 'entry_time':
            $attributes['itemprop'] = 'datePublished';
            $attributes['datetime'] = get_the_time('c');
            break;

        case 'entry_title':
            $attributes['itemprop'] = 'headline';
            break;

        case 'entry_content':
            $attributes['itemprop'] = 'text';
            break;

        case 'comment':
            $attributes['itemprop'] = 'comment';
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/UserComments';
            break;

        case 'comment_author':
            $attributes['itemprop'] = 'creator';
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/Person';
            break;

        case 'comment_author_link':
            $attributes['itemprop'] = 'creator';
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/Person';
            $attributes['rel'] = 'external nofollow';
            break;

        case 'comment_time':
            $attributes['itemprop'] = 'commentTime';
            $attributes['itemscope'] = 'itemscope';
            $attributes['datetime'] = get_the_time('c');
            break;

        case 'comment_text':
            $attributes['itemprop'] = 'commentText';
            break;

        case 'author_box':
            $attributes['itemprop'] = 'author';
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/Person';
            break;

        case 'table':
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/Table';
            break;

        case 'video':
            $attributes['itemprop'] = 'video';
            $attributes['itemtype'] = 'http://schema.org/VideoObject';
            break;

        case 'audio':
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/AudioObject';
            break;

        case 'blog':
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/Blog';
            break;

        case 'sidebar':
            $attributes['role'] = 'complementary';
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/WPSideBar';
            break;

        case 'footer':
            $attributes['role'] = 'contentinfo';
            $attributes['itemscope'] = 'itemscope';
            $attributes['itemtype'] = 'http://schema.org/WPFooter';
            break;
    }


    $attributes = apply_filters('schema_markup_attributes', $attributes, $args);

    //we failed to fetch the attributes - let's stop
    if (empty($attributes))
        return;

    foreach ($attributes as $key => $value) {
        $markup .= $key . '="' . $value . '" ';
    }

    $markup = apply_filters('schema_markup_output', $markup, $args);

    if ($args['echo']) {
        echo $markup;
    } else {
        return $markup;
    }
}

/**
 * Modifies WordPress's built-in comments_popup_link() function to return a string instead of echo comment results
 */
function get_comments_popup_link( $zero = false, $one = false, $more = false, $css_class = '', $none = false ) {
    global $wpcommentspopupfile, $wpcommentsjavascript;

    $id = get_the_ID();

    if ( false === $zero ) $zero = __( 'No Comments', 'redux-page-builder' );
    if ( false === $one ) $one = __( '1 Comment', 'redux-page-builder' );
    if ( false === $more ) $more = __( '% Comments', 'redux-page-builder' );
    if ( false === $none ) $none = __( 'Comments Off' , 'redux-page-builder');

    $number = get_comments_number( $id );

    $str = '';

    if ( 0 == $number && !comments_open() && !pings_open() ) {
        $str = '<span' . ((!empty($css_class)) ? ' class="' . esc_attr( $css_class ) . '"' : '') . '>' . $none . '</span>';
        return $str;
    }

    if ( post_password_required() ) {
        $str = __('Enter your password to view comments.','redux-page-builder');
        return $str;
    }

    $str = '<a href="';
    if ( $wpcommentsjavascript ) {
        if ( empty( $wpcommentspopupfile ) )
            $home = home_url();
        else
            $home = get_option('siteurl');
        $str .= $home . '/' . $wpcommentspopupfile . '?comments_popup=' . $id;
        $str .= '" onclick="wpopen(this.href); return false"';
    } else { // if comments_popup_script() is not in the template, display simple comment link
        if ( 0 == $number )
            $str .= get_permalink() . '#respond';
        else
            $str .= get_comments_link();
        $str .= '"';
    }

    if ( !empty( $css_class ) ) {
        $str .= ' class="'.$css_class.'" ';
    }
    $title = the_title_attribute( array('echo' => 0 ) );

    $str .= apply_filters( 'comments_popup_link_attributes', '' );

    $str .= ' title="' . esc_attr( sprintf( __('Comment on %s', 'redux-page-builder'), $title ) ) . '">';
    $str .= get_comments_number_str( $zero, $one, $more );
    $str .= '</a>';

    return $str;
}

/**
 * Modifies WordPress's built-in comments_number() function to return string instead of echo
 */
function get_comments_number_str( $zero = false, $one = false, $more = false, $deprecated = '' ) {
    if ( !empty( $deprecated ) )
        _deprecated_argument( __FUNCTION__, '1.3' );

    $number = get_comments_number();

    if ( $number > 1 )
        $output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments', 'redux-page-builder') : $more);
    elseif ( $number == 0 )
        $output = ( false === $zero ) ? __('No Comments', 'redux-page-builder') : $zero;
    else // must be one
        $output = ( false === $one ) ? __('1 Comment', 'redux-page-builder') : $one;

    return apply_filters('comments_number', $output, $number);
}

/**
 * Display an HTML link to the author page of the author of the current post.
 *
 * Does just echo get_author_posts_url() function, like the others do. The
 * reason for this, is that another function is used to help in printing the
 * link to the author's posts.
 *
 * @link http://codex.wordpress.org/Template_Tags/the_author_posts_link
 * @since 1.2.0
 * @uses $authordata The current author's DB object.
 * @uses get_author_posts_url()
 * @uses get_the_author()
 * @param string $deprecated Deprecated.
 */
function get_the_author_posts_link($deprecated = '') {
	if ( !empty( $deprecated ) )
		_deprecated_argument( __FUNCTION__, '2.1' );

	global $authordata;
	if ( !is_object( $authordata ) )
		return false;
	$link = sprintf(
		'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
		esc_url( get_author_posts_url( $authordata->ID, $authordata->user_nicename ) ),
		esc_attr( sprintf( __( 'Posts by %s', 'redux-page-builder' ), get_the_author() ) ),
		get_the_author()
	);

	/**
	 * Filter the link to the author page of the author of the current post.
	 *
	 * @since 2.9.0
	 *
	 * @param string $link HTML link.
	 */
	return apply_filters( 'the_author_posts_link', $link );
}

class post_grid {

    static $grid = 0;
    static $preview_template = array();
    protected $atts;
    protected $entries;

    function __construct($atts = array()) {
        $this->atts = wp_parse_args($atts, array(
            'linking' => '',
            'columns' => '4',
            'items' => '16',
            'contents' => 'title',
            'sort' => 'yes',
            'paginate' => 'yes',
            'categories' => '',
            'preview_mode' => 'auto',
            'image_size' => '4_column',
            'post_type' => 'portfolio',
            'taxonomy' => 'portfolio-category',

            'style' => '',
        ));

    }

    //generates the html of the post grid
    public function html() {
        if (empty($this->entries) || empty($this->entries->posts))
            return;

        post_grid::$grid++;
        extract($this->atts);
        $class = '';
        $container_id = post_grid::$grid;
        $extraClass = 'first';
        $grid = 'col-md-3';
        if ($preview_mode == 'auto')
            $image_size = 'portfolio';
        $post_loop_count = 1;
        $loop_counter = 1;
        $output = "";
        $total = $this->entries->post_count % 2 ? "odd" : "even";


        switch ($columns) {
            case "1": $grid = 'col-md-12';
                if ($preview_mode == 'auto')
                    $image_size = 'large'; break;
            case "2": $grid = 'col-md-6';
                break;
            case "3": $grid = 'col-md-4';
                break;
            case "4": $grid = 'col-md-3';
                if ($preview_mode == 'auto')
                    $image_size = 'portfolio_small'; break;
            case "6": $grid = 'col-md-2';
                if ($preview_mode == 'auto')
                    $image_size = 'portfolio_small'; break;
        }

        $output .= $sort == "yes" ? $this->sort_buttons($this->entries->posts, $this->atts) : "";


        $output .= "<div class='{$class} portfolio-archive mode-{$style} grid-sort-container' >";
        $id = rand();
        foreach ($this->entries->posts as $entry) {
            $the_id = $entry->ID;
            $parity = $post_loop_count % 2 ? 'odd' : 'even';
            $last = $this->entries->post_count == $post_loop_count ? " post-entry-last " : "";
            $post_class = "post-entry portfolio  post-entry-{$the_id} {$last}";
            $sort_class = $this->sort_cat_string($the_id, $this->atts);

            switch ($linking) {
                case "fancybox": $link = wp_get_attachment_image_src(get_post_thumbnail_id($the_id), 'large');
                    $link = $link[0];
                    break;
                default: $link = get_permalink($the_id);
                    break;
            }

            $title_link = get_permalink($the_id);
            $custom_link = get_post_meta($the_id, '_portfolio_custom_link', true) != "" ? get_post_meta($the_id, '_portfolio_custom_link_url', true) : false;

            if ($custom_link) {
                $title_link = $link = $custom_link;
            }

            $excerpt = '';
            $title = '';

            switch ($contents) {
                case "excerpt":
                    $excerpt = $entry->post_excerpt;
                    $title = $entry->post_title;
                    break;
                case "title": $excerpt = '';
                    $title = $entry->post_title;
                    break;
                case "only_excerpt": $excerpt = $entry->post_excerpt;
                    $title = '';
                    break;
                case "no": $excerpt = '';
                    $title = '';
                    break;
            }

            $custom_overlay = apply_filters('portfolio_custom_overlay', "", $entry);
            $link_markup = apply_filters('portfolio_custom_image_container', array("a rel='{$id}' class='{$linking}  portfolio-thumbnail portfolio-wrapper' href='{$link}' title='" . esc_attr(strip_tags($title)) . "' ", 'a'), $entry);

            $title = apply_filters('portfolio_title', $title, $entry);
            $title_link = apply_filters('portfolio_title_link', $title_link, $entry);

            $output .= "<article class=' grid-entry mix all_sort {$post_class} col-sm-6 {$sort_class} {$grid} {$extraClass}'>";
            $output .= "<div class='main_color inner-entry portfolio-loop'>";
            $output .= apply_filters('portfolio_extra', "", $entry);
            //href start
            $output .= "<" . $link_markup[0] . " data-rel='grid-" . post_grid::$grid . "' class='grid-image  hover-fx'><div class='thumbnails'>" . $custom_overlay . get_the_post_thumbnail($the_id, $image_size) ."</div>";
            $output .=!empty($title) || !empty($excerpt) ? "<div class='info'><div class='arrow'></div><div class='info-wrapper'>" : '';
            $output .=!empty($title) ? "<h3 class='grid-entry-title entry-title'>". $title . "</h3>" : '';
            $output .=!empty($excerpt) ? "<div class='grid-entry-excerpt entry-content'>" . $excerpt . "</div>" : '';
            $output .=!empty($title) || !empty($excerpt) ? "</div></div>" : '';
            $output .= "</" . $link_markup[1] . ">";
            //end href
            $output .= "</div>";
            $output .= "</article>";

            $loop_counter++;
            $post_loop_count++;
            $extraClass = "";

            if ($loop_counter > $columns) {
                $loop_counter = 1;
                $extraClass = 'first';
            }
        }

        $output .= "<div class='clearfix'></div></div>";

        //append pagination
        if ($paginate == "yes" && $pagination = pagination($this->entries, false)) {
            $output .= "<div class='pagination-wrap pagination-{$post_type}'>{$pagination}</div>";
        }


        return $output;
    }

    //generates the html for the sort buttons
    protected function sort_buttons($entries, $params) {
        //get all categories that are actually listed on the page
        $categories = get_categories(array(
            'taxonomy' => $params['taxonomy'],
            'hide_empty' => 0
        ));

        $current_page_cats = array();
        $cat_count = array();
        $display_cats = is_array($params['categories']) ? $params['categories'] : array_filter(explode(',', $params['categories']));

        foreach ($entries as $entry) {
            if ($current_item_cats = get_the_terms($entry->ID, $params['taxonomy'])) {
                if (!empty($current_item_cats)) {
                    foreach ($current_item_cats as $current_item_cat) {
                        if (empty($display_cats) || in_array($current_item_cat->term_id, $display_cats)) {
                            $current_page_cats[$current_item_cat->term_id] = $current_item_cat->term_id;

                            if (!isset($cat_count[$current_item_cat->term_id])) {
                                $cat_count[$current_item_cat->term_id] = 0;
                            }

                            $cat_count[$current_item_cat->term_id] ++;
                        }
                    }
                }
            }
        }

        $output = "";

        //$output = "<div class='sort_width_container' data-portfolio-id='" . post_grid::$grid . "' ><div id='js_sort_items' >";
        $hide = count($current_page_cats) <= 1 ? "hidden" : "";


        $first_item_name = apply_filters('portfolio_sort_first_label', __('All', 'redux-page-builder'), $params);
        $output .= apply_filters('portfolio_sort_heading', "", $params);

        $output .= "<center><div class='btn-group sort_by_cat {$hide} '>";
        $output .= "<button type='button' data-filter='all_sort' class='filter btn btn-default active'>{$first_item_name} <span class='badge'>" . count($entries) . "</span></button>";

        foreach ($categories as $category) {
            if (in_array($category->term_id, $current_page_cats, true)) {
                $output .= "<button type='button' data-filter='" . $category->category_nicename . "_sort" . "' class='filter btn btn-default'>{$category->cat_name}  <span class='badge'>{$cat_count[$category->term_id]}</span></button>";
            }
        }

        //$output .= "</div></div></div>";
        $output .= "</div></center><br />";

        return $output;
    }

    //get the categories for each post and create a string that serves as classes so the javascript can sort by those classes
    protected function sort_cat_string($the_id, $params) {
        $sort_classes = "";
        $item_categories = get_the_terms($the_id, $params['taxonomy']);

        if (is_object($item_categories) || is_array($item_categories)) {
            foreach ($item_categories as $cat) {
                $sort_classes .= $cat->slug . "_sort ";
            }
        }

        return $sort_classes;
    }

    //fetch new entries
    public function query_entries($params = array()) {
        $query = array();
        if (empty($params))
            $params = $this->atts;


        $page = get_query_var('paged') ? get_query_var('paged') : get_query_var('page');
        if (!$page)
            $page = 1;

        //if we find categories perform complex query, otherwise simple one
        if (isset($params['categories'][0]) && !empty($params['categories'][0]) && !is_null($params['categories'][0]) && $params['categories'][0] != "null") {
            $query = array('orderby' => 'post_date',
                'order' => 'DESC',
                'paged' => $page,
                'posts_per_page' => $params['items'],
                'post_type' => $params['post_type'],
                'tax_query' => array(array('taxonomy' => $params['taxonomy'],
                        'field' => 'id',
                        'terms' => $params['categories'],
                        'operator' => 'IN')));
        } else {
            $query = array('paged' => $page, 'posts_per_page' => $params['items'], 'post_type' => $params['post_type']);
        }

        $query = apply_filters('post_grid_query', $query, $params);

        $this->entries = new WP_Query($query);
    }

}
