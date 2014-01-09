<?php

class Redux_Widget_portfolio extends Redux_Widget {

    /**
     * Render the widget in frontend
     *
     * @param array $args
     * @param array $instance
     * @return string
     */
    function get_widget($args, $instance) {
        if (is_admin()) {
            return "<center><i class='icon-th  icon-4x'></i></center>";
        }
        $atts['class'] = !empty($meta['custom_class']) ? $meta['custom_class'] : "";

        $grid = new post_grid($instance);
        $grid->query_entries();
        return $grid->html();
    }

}