<?php

class ReduxFramework_linkpicker {

    static $cache = array();   //holds database requests or results of complex functions
    static $templates = array();  //an array that holds all the templates that should be created when the print_media_templates hook is called

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 1.0.0
     */

    function __construct($field = array(), $value = '', $parent) {

        //parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
        $this->field = $field;
        $this->value = $value;
        $this->args = $parent->args;
        $this->parent = $parent;
        //$this->render();


        if (!empty($this->field['data']) && empty($this->field['options'])) {
            if (empty($this->field['args'])) {
                $this->field['args'] = array();
            }
            $this->field['options'] = $parent->get_wordpress_data($this->field['data'], $this->field['args']);
        }
    }

//function

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since ReduxFramework 1.0.0
     */
    function render() {

        if(is_array($this->value))
           $this->field['default'] = join (',', $this->value); 
        //$this->field['default'] = $this->value;
        $original = $this->field;
        $new_default =  $this->value;
        
        $pt = self::public_post_types();
        $ta = self::public_taxonomies(false, true);
        
        if (isset($new_default[1]))
            $original['default'] = $new_default[1];

        $allowed_pts = isset($original['posttype']) ? $original['posttype'] : $pt;
        $allowed_tas = isset($original['taxtype']) ? $original['taxtype'] : $ta;

        if (array_key_exists('single', $this->field['options'])) {
            foreach ($pt as $key => $type) {
                if (in_array($type, $allowed_pts)) {
                    $original['options'] = $key;
                    $html = self::select($original);

                    if ($html) {
                        self::register_template($key, $html);
                    } else {
                        unset($pt[$key]);
                    }
                } else {
                    unset($pt[$key]);
                }
            }
        }

        if (array_key_exists('taxonomy', $this->field['options'])) {
            foreach ($ta as $key => $type) {
                if (in_array($type, $allowed_tas)) {
                    $original['options'] = 'cat';
                    $original['taxonomy'] = $key;

                    $html = self::select($original);
                    if ($html) {
                        self::register_template($key, $html);
                    } else {
                        unset($ta[$key]);
                    }
                } else {
                    unset($ta[$key]);
                }
            }
        }

        if (isset($new_default[1]))
            $this->field['default'] = $new_default[1];

        $original['options'] = "";
        //print_r($this->field['options']);die();
        foreach ($this->field['options'] as $key => $value) { //register templates
            switch ($key) {
                case "manually":

                    if (isset($new_default[0]) && $new_default[0] != $key)
                        $this->fieldt['default'] = "http://";

                    $original['options'][$key] = $value;
                    $html = $this->input($this->field);
                    self::register_template($key, $html);
                    break;

                case "single":
                    $original['options'][$key] = $pt;
                    break;

                case "taxonomy":
                    $original['options'][$value] = $ta;
                    break;

                default: $original['options'][$key] = $value;
                    break;
            }
        }
        //print_r($original['options']);die();
        //if (!empty($this->field['ajax'])) { // if we got an ajax request we also need to call the printing since the default wordpress hook is already executed
        self::print_templates();
        //}

        $original['default'] = isset($new_default[0])?$new_default[0]:'';
        unset($original['multiple']);
        $output = self::select($original);




        ///////////////////////////////////////////////////////////////////////////////
        //if (!empty($this->field['data']) && ( $this->field['data'] == "elusive-icons" || $this->field['data'] == "elusive-icon" || $this->field['data'] == "elusive" )) {
        //$this->field['class'] = " elusive-icons";
        //}//if
        if(!isset($class_string))
            $class_string = '';
        if (!empty($this->field['fetchTMPL']))
            $class_string .= " attach-templating ";
        echo '<fieldset id="' . $this->field['id'] . '" class="redux-select-container ' . $class_string . '">';

        if (!empty($this->field['options'])) {
            echo $output;

//            $placeholder = (isset($this->field['placeholder'])) ? esc_attr($this->field['placeholder']) : __('Select an item', 'redux-page-builder');
//
//            echo '<select id="' . $this->field['id'] . '-select" data-placeholder="' . $placeholder . '" name="' . $this->parent->widget->get_field_name($this->field['id'])  . '" class="redux-select-item' . $this->field['class'] . '" rows="6">';
//            echo '<option></option>';
//            foreach ($this->field['options'] as $k => $v) {
//                if (is_array($this->value)) {
//                    $selected = (is_array($this->value) && in_array($k, $this->value)) ? ' selected="selected"' : '';
//                } else {
//                    $selected = selected($this->value, $k, false);
//                }
//                echo '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
//            }//foreach
//            echo '</select>';
            if (!empty($this->field['fetchTMPL'])) {
                echo "<div class='template-container'></div>";
            }
        } else {
            echo '<strong>' . __('No items of this type were found.', 'redux-page-builder') . '</strong>';
        }

        echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? '<div class="description">' . $this->field['desc'] . '</div>' : '';

        echo '</fieldset>';
    }

//function

    /**
     * Enqueue Function.
     *
     * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
     *
     * @since ReduxFramework 1.0.0
     */
    function enqueue() {

//        wp_enqueue_script('select2-js');
//        wp_enqueue_style('select2-css');
//
//        wp_enqueue_script(
//                'field-select-js', REDUX_URL . 'inc/fields/select/field_select.min.js', array('jquery', 'select2-js'), time(), true
//        );
    }

    /**
     * Helper function that fetches all "public" post types.
     *
     * @return array $post_types example output: data-modal='true'
     */
    static function public_post_types() {
        $post_types = get_post_types(array('public' => false, 'name' => 'attachment', 'show_ui' => false, 'publicly_queryable' => false), 'names', 'NOT');
        $post_types['page'] = 'page';
        $post_types = array_map("ucfirst", $post_types);
        self::$cache['post_types'] = $post_types;
        return $post_types;
    }

    /**
     * Helper function that fetches all taxonomies attached to public post types.
     *
     * @return array $taxonomies
     */
    static function public_taxonomies($post_types = false, $merged = false) {
        $taxonomies = array();
        if (!$post_types)
            $post_types = empty(self::$cache['post_types']) ? self::public_post_types() : self::$cache['post_types'];

        if (!is_array($post_types))
            $post_types = array($post_types => ucfirst($post_types));

        foreach ($post_types as $type => $post) {
            $taxonomies[$type] = get_object_taxonomies($type);
        }


        self::$cache['taxonomies'] = $taxonomies;

        if ($merged) {
            $new = array();
            foreach ($taxonomies as $taxonomy) {
                foreach ($taxonomy as $tax) {
                    $new[$tax] = ucwords(str_replace("_", " ", $tax));
                }
            }

            $taxonomies = $new;
        }

        return $taxonomies;
    }

    /**
     * Helper function that creates a new javascript template to be called
     *
     * @return void
     */
    static function register_template($key, $html) {
        self::$templates[$key] = $html;
    }

    /**
     * Helper function that prints all the javascript templates
     *
     * @return void
     */
    static function print_templates() {
        foreach (self::$templates as $key => $template) {
            echo "\n<script type='text/html' id='tmpl-{$key}'>\n";
            echo $template;
            echo "\n</script>\n\n";
        }
        //reset the array
        self::$templates = array();
    }

    /**
     * 
     * The select method renders a single select element: it either lists custom values, all wordpress pages or all wordpress categories
     * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
     * @return string $output the string returned contains the html code generated within the method
     */
    function select($element) {
        $select = __('Select', 'redux-page-builder');
        //print_r($element);
        //echo "<br /><br />";
        if ($element['options'] == 'cat') {
            $add_taxonomy = "";

            if (!empty($element['taxonomy']))
                $add_taxonomy = "&taxonomy=" . $element['taxonomy'];

            $entries = get_categories('title_li=&orderby=name&hide_empty=0' . $add_taxonomy);
        }
        else if (!is_array($element['options'])) {
            global $wpdb;
            $table_name = $wpdb->prefix . "posts";
            $limit = 4000;
            $entries = $wpdb->get_results("SELECT ID, post_title FROM {$table_name} WHERE post_status = 'publish' AND post_type = '" . $element['options'] . "' ORDER BY post_title ASC LIMIT {$limit}");
        } else {
            $select = 'Select...';
            $entries = $element['options'];
        }

        $data_string = "";
        /*if (isset($element['data'])) {
            foreach ($element['data'] as $key => $data) {
                $data_string .= "data-" . $key . "='" . $data . "'";
            }
        }*/

        if (empty($entries))
            return;

       $multi = $multi_class = "";
       if (isset($element['multiple'])) {
           $multi_class = " multiple_select";
           $multi = 'multiple="multiple" size="' . $element['multiple'] . '"';
           $element['default'] = explode(',', $element['default']);
       }

        $id_string = empty($element['id']) ? "" : "id='" . $this->parent->widget->get_field_id($element['id'])  . "'";
        $name_string = empty($element['id']) ? "" : "name='" . $this->parent->widget->get_field_name($element['id']) . "[]'";

        $output = '<select '.$multi.' class="redux-select-item ' . $element['class'] . '" ' . $id_string . ' ' . $name_string . ' ' . $data_string . '> ';


        if (isset($element['with_first'])) {
            $output .= '<option value="">' . $select . '</option>  ';
            $fake_val = $select;
        }

        $real_entries = array();
        
        foreach ($entries as $key => $entry) {
            if (!is_array($entry)) {
                $real_entries[$key] = $entry;
            } else {
                $real_entries['option_group_' . $key] = $key;

                foreach ($entry as $subkey => $subentry) {
                    $real_entries[$subkey] = $subentry;
                }

                $real_entries['close_option_group_' . $key] = "close";
            }
        }

        $entries = $real_entries;
        //print_r($entries);die();
        foreach ($entries as $key => $entry) {
            if ($element['options'] == 'cat') {
                if (isset($entry->term_id)) {
                    $id = $entry->term_id;
                    $title = $entry->name;
                }
            } else if (!is_array($element['options'])) {
                $id = $entry->ID;
                $title = $entry->post_title;
            } else {
                $id = $key;
                $title = $entry;
            }

            if (!empty($title) || (isset($title) && $title === 0)) {
                if (!isset($fake_val))
                    $fake_val = $title;
                $selected = "";
                if ($element['default'] == $id || (is_array($element['default']) && in_array($id, $element['default']))) {
                    $selected = "selected='selected'";
                    $fake_val = $title;
                }

                if (strpos($id, 'option_group_') === 0) {
                    $output .= "<optgroup label='" . $title . "'>";
                } else if (strpos($id, 'close_option_group_') === 0) {
                    $output .= "</optgroup>";
                } else {
                    $output .= "<option $selected value='" . $id . "'>" . $title . "</option>";
                }
            }
        }
        $output .= '</select>';

        return $output;
    }

    /**
     * 
     * The input method renders one or more input type:text elements, based on the definition of the $elements array
     * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
     * @return string $output the string returned contains the html code generated within the method
     */
    function input($element) {
        $output = '<input type="text" class="' . $element['class'] . '" value="' . $element['default'] . '" id="' . $element['id'] . '" name="' . $this->parent->widget->get_field_name($element['id']) . '[]"/>';
        return $output;
    }

//function
}

//class