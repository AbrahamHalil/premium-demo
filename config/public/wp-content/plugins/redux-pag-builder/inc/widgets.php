<?php

include plugin_dir_path(__FILE__) . '/field_enqueue.php';

/**
 * Class Redux_Widget
 */
abstract class Redux_Widget extends WP_Widget {

    public $form_args;
    protected $demo;
    protected $origin_id;
    public $sub_widgets;
    private $styles;

    /**
     * Create the widget
     *
     * @param string $name Name for the widget displayed on the configuration page.
     * @param array $widget_options Optional Passed to wp_register_sidebar_widget()
     *     - description: shown on the configuration page
     *     - classname
     * @param array $control_options Optional Passed to wp_register_widget_control()
     *     - width: required if more than 250px
     *     - height: currently not used but may be needed in the future
     * @param array $form Form arguments.
     * @param array $demo Values for the demo of the page builder widget.
     * @internal param string $id_base
     */
    function __construct() {
        $id_base = str_replace('Redux_Widget_', '', get_class($this));
        $widgets = apply_filters( 'redux-widgets-options', array() );
        $this->_widget = $widget = $widgets[$id_base];

        $id_base = strtolower(str_replace('_', '-', $id_base));
        parent::__construct('redux_' . $id_base, $widget['title'], array('description'=>$widget['desc']), $control_options = array());

        $this->origin_id = $id_base;
        $this->form_args = $widget['fields'];
        $this->demo = array();
        $this->styles = array();
        $this->sub_widgets = array();

        //icons
        $this->icon_type = isset($widget['icon_type'])?$widget['icon_type']:'icon'; //image or icon
        $this->icon      = isset($widget['icon'])?$widget['icon']:'puzzle-piece'; // image url or font awesome class name
        $this->icon_class= isset($widget['icon'])?$widget['icon']:'icon-4x'; 
    }

    /**
     * Update the widget and save the new CSS.
     *
     * @param array $old
     * @param array $new
     * @return array
     */
    function update($new, $old) {

        foreach ($this->form_args as $field_id => $field_args) {
            if ($field_args['type'] == 'checkbox') {
                $new[$field_id] = !empty($new[$field_id]);
            }
        }
        return $new;
    }

    /**
     * Display the form for the widget. Auto generated from form array.
     *
     * @param array $instance
     * @return string|void
     */
    public function form($instance) {
        $args['opt_name'] = 'cadr_widget';
        require_once ( dirname( __FILE__ ) . '/redux.php' );
        new ReduxFields( $this->form_args, $args, $this );
    }

    /**
     * Render the widget.
     *
     * @param array $args
     * @param array $instance
     * @return bool|void
     */
    function widget($args, $instance) {

        // Set up defaults for all the widget args
        foreach ($this->form_args as $field_id => $field_args) {
            if (isset($field_args['default']) && !isset($instance[$field_id])) {
                $instance[$field_args['id']] = $field_args['default'];
            }
            if (!isset($instance[$field_args['id']]))
                $instance[$field_args['id']] = false;
        }

        // Filter the title
        if (!empty($instance['title'])) {
            $instance['title'] = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        }

        if (method_exists($this, 'enqueue_scripts')) {
            $this->enqueue_scripts();
        }

        if(!isset($args['before_widget']))
            $args['before_widget'] = '';
        if(!isset($args['after_widget']))
            $args['after_widget'] = '';

        //return array('args' => $args, 'instance' => $instance);
    }

    /**
     * Get all the paths where we'll look for widgets.
     *
     * @return array
     */
    function get_widget_paths() {
        static $paths = array();

        if (empty($paths)) {
            $paths = array_keys($this->get_widget_folders());
        }

        return $paths;
    }

    /**
     * Get all the folders where we'll look for widgets
     *
     * @return mixed|void
     */
    static function get_widget_folders() {
        static $folders = array();

        if (empty($folders)) {
            $folders = array(
                get_stylesheet_directory() . '/widgets' => get_stylesheet_directory_uri() . '/widgets/widgets',
                get_template_directory() . '/widgets' => get_template_directory_uri() . '/widgets',
                plugin_dir_path(SITEORIGIN_PANELS_BASE_FILE) . 'widgets/widgets' => plugin_dir_url(SITEORIGIN_PANELS_BASE_FILE) . 'widgets/widgets',
            );
            $folders = apply_filters('siteorigin_widget_folders', $folders);
        }

        return $folders;
    }

    /**
     * Get all the folders where we'll look for widget images
     *
     * @return mixed|void
     */
    static function get_image_folders() {
        static $folders = array();
        if (empty($folders)) {
            $folders = array(
                get_stylesheet_directory() . '/widgets/img' => get_stylesheet_directory_uri() . '/widgets/img',
                get_template_directory() . '/widgets/img' => get_template_directory_uri() . '/widgets/img',
                plugin_dir_path(SITEORIGIN_PANELS_BASE_FILE) . 'widgets/img' => plugin_dir_url(SITEORIGIN_PANELS_BASE_FILE) . 'widgets/img',
            );
            $folders = apply_filters('siteorigin_widget_image_folders', $folders);
        }

        return $folders;
    }

    /**
     * Get all the styles for this widget.
     *
     * @return array
     */
    public function get_styles() {
        if (empty($this->styles)) {
            // We can add extra paths here
            foreach ($this->get_widget_paths() as $path) {
                if (!is_dir($path))
                    continue;

                $files = glob($path . '/' . $this->origin_id . '/styles/*.less');
                if (!empty($files)) {
                    foreach (glob($path . '/' . $this->origin_id . '/styles/*.less') as $file) {
                        $p = pathinfo($file);
                        $this->styles[$p['filename']] = $this->get_style_data($p['filename']);
                    }
                }
            }
        }

        return $this->styles;
    }

    /**
     * Get the presets for a given style
     *
     * @param $style_id
     * @return mixed|void
     */
    public function get_style_presets($style_id) {
        $filename = plugin_dir_path(__FILE__) . 'widgets/' . $this->origin_id . '/presets/' . sanitize_file_name($style_id) . '.php';
        if (file_exists($filename)) {
            // This file should register a filter that adds the presets
            $presets = include($filename);
        }

        return apply_filters('origin_widget_presets_' . $this->origin_id . '_' . $style_id, $presets);
    }

    /**
     * Get data for the style.
     *
     * @param $name
     * @return array
     */
    public function get_style_data($name) {
        $paths = $this->get_widget_paths();

        foreach ($paths as $path) {
            $filename = $path . '/' . $this->origin_id . '/styles/' . sanitize_file_name($name) . '.less';
            if (!file_exists($filename))
                continue;

            $data = get_file_data($filename, array(
                'Name' => 'Name',
                'Template' => 'Template',
                'Author' => 'Author',
                'Author URI' => 'Author URI',
                    ), 'origin_widget');
            return $data;
        }
        return false;
    }

    /**
     * Render a demo of the widget.
     *
     * @param array $args
     */
    function render_demo($args = array()) {
        $this->widget($args, $this->demo);
    }

    /**
     * Register a widget that we'll be using inside this widget.
     *
     * @param $id
     * @param $name
     * @param $class
     */
    function add_sub_widget($id, $name, $class) {
        $this->sub_widgets[$id] = array($name, $class);
    }

    /**
     * Add the fields required to query the posts.
     */
    function add_post_query_fields() {
        // Add the posts type field
        $post_types = get_post_types(array('public' => true));
        $post_types = array_values($post_types);
        $this->form_args['query_post_type'] = array(
            'type' => 'select',
            'options' => $post_types,
            'label' => __('Post Type', 'redux-page-builder')
        );

        // Add the posts per page field
        $this->form_args['query_posts_per_page'] = array(
            'type' => 'number',
            'default' => 10,
            'label' => __('Posts Per Page', 'redux-page-builder'),
        );

        $this->form_args['query_orderby'] = array(
            'type' => 'select',
            'label' => __('Order By', 'redux-page-builder'),
            'options' => array(
                'none' => __('None', 'redux-page-builder'),
                'ID' => __('Post ID', 'redux-page-builder'),
                'author' => __('Author', 'redux-page-builder'),
                'name' => __('Name', 'redux-page-builder'),
                'name' => __('Name', 'redux-page-builder'),
                'date' => __('Date', 'redux-page-builder'),
                'modified' => __('Modified', 'redux-page-builder'),
                'parent' => __('Parent', 'redux-page-builder'),
                'rand' => __('Random', 'redux-page-builder'),
                'comment_count' => __('Comment Count', 'redux-page-builder'),
                'menu_order' => __('Menu Order', 'redux-page-builder'),
            )
        );

        $this->form_args['query_order'] = array(
            'type' => 'select',
            'label' => __('Order', 'redux-page-builder'),
            'options' => array(
                'ASC' => __('Ascending', 'redux-page-builder'),
                'DESC' => __('Descending', 'redux-page-builder'),
            )
        );

        $this->form_args['query_sticky'] = array(
            'type' => 'select',
            'label' => __('Sticky Posts', 'redux-page-builder'),
            'options' => array(
                '' => __('Default', 'redux-page-builder'),
                'ignore' => __('Ignore Sticky', 'redux-page-builder'),
                'exclude' => __('Exclude Sticky', 'redux-page-builder'),
                'only' => __('Only Sticky', 'redux-page-builder'),
            )
        );

        $this->form_args['query_additional'] = array(
            'type' => 'text',
            'label' => __('Additional Arguments', 'redux-page-builder'),
            'description' => sprintf(__('Additional query arguments. See <a href="%s" target="_blank">query_posts</a>.', 'redux-page-builder'), 'http://codex.wordpress.org/Function_Reference/query_posts'),
        );
    }

    /**
     * Get all the posts for the current query
     *
     * @param $instance
     * @return WP_Query
     */
    static function get_query_posts($instance) {
        $query_args = array();
        foreach ($instance as $k => $v) {
            if (strpos($k, 'query_') === 0) {
                $query_args[preg_replace('/query_/', '', $k, 1)] = $v;
            }
        }
        $query = $query_args;
        unset($query['additional']);
        unset($query['sticky']);

        // Add the additional arguments
        $query = wp_parse_args($query_args['additional'], $query);

        // Add the sticky posts if required
        switch ($query_args['sticky']) {
            case 'ignore' :
                $query['ignore_sticky_posts'] = 1;
                break;
            case 'only' :
                $query['post__in'] = get_option('sticky_posts');
                break;
            case 'exclude' :
                $query['post__not_in'] = get_option('sticky_posts');
                break;
        }

        // Add the current page
        global $wp_query;
        $query['paged'] = $wp_query->get('paged');

        return new WP_Query($query);
    }

}