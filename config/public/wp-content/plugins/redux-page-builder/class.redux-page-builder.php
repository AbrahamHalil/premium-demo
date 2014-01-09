<?php
/**
 * ReduxPageBuilder main class
 *
 * @package     ReduxFramework\ReduxPageBuilder
 * @since       3.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    exit;
}


if( !class_exists( 'ReduxPageBuilder' ) ) {

    /**
     * Main ReduxPageBuilder class
     *
     * @since       3.0.0
     */
    class ReduxPageBuilder {

        public static $_version = '1.0.0';
        public static $_dir;
        public static $_url;
        public static $_slug    = "redux-page-builder";
        public static $_domain  = "redux-page-builder";

        /**
         * @const       string VERSION The plugin version, used for cache-busting and script file references
         * @since       1.0.0
         */
        const VERSION = '1.0.0';

        /**
         * @access      protected
         * @var         string $plugin_screen_hook_suffix The slug of the plugin screen
         * @since       3.0.0
         */
        protected $plugin_screen_hook_suffix = null;

        /**
         * @access      protected
         * @var         string $plugin_network_activated Check for plugin network activation
         * @since       3.0.0
         */
        protected $plugin_network_activated = null;

        /**
         * @access      private
         * @var         \ReduxPageBuilder $instance The one true ReduxPageBuilder
         * @since       3.0.0
         */
        private static $instance;



        public function __construct( ) {
            
            // Construct hook
            do_action( 'redux/page-builder/contruct', $this );
            
            $this->_internationalization();

            //add_theme_support( 'post-formats', array('link','video','image' ) ); // add post format options
            add_theme_support( 'post-formats', array('image' ) ); // add post format options


        }

        /**
         * Load the plugin text domain for translation.
         * @param string $opt_name
         * @since    3.0.5
         */
        public function _internationalization() {
            $locale = apply_filters( 'redux/page-builder/textdomain/', get_locale(), self::$_domain );
            load_textdomain( self::$_domain, trailingslashit( WP_LANG_DIR ) . self::$_domain . '/' . self::$_domain . '-' . $locale . '.mo' );
            load_textdomain( self::$_domain, dirname( __FILE__ ) . '/languages/' . self::$_domain . '-' . $locale . '.mo' );
        }

        /**
         * Get active instance
         *
         * @access      public
         * @since       3.1.3
         * @return      self::$instance The one true ReduxPageBuilder
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new self;
                self::$instance->get_options();
                self::$instance->includes();
                self::$instance->hooks();
            }

            return self::$instance;
        }

        static function init() {

            // Windows-proof constants: replace backward by forward slashes. Thanks to: @peterbouwmeester
            self::$_dir     = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
            $wp_content_dir = trailingslashit( str_replace( '\\', '/', WP_CONTENT_DIR ) );
            $relative_url   = str_replace( $wp_content_dir, '', self::$_dir );
            $wp_content_url = ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL );
            self::$_url     = trailingslashit( $wp_content_url ) . $relative_url;

        }

        /**
         * Get Redux options
         *
         * @access      public
         * @since       3.1.3
         * @return      void
         */
        public function get_options() {
            return;
            // Setup defaults
            $defaults = array(
                'demo'      => false,
            );

            // If multisite is enabled
            if( is_multisite() ) {

                // Get network activated plugins
                $plugins = get_site_option( 'active_sitewide_plugins' );

                foreach( $plugins as $file => $plugin ) {
                    if( strpos( $file, 'redux-framework.php' ) !== false ) {
                        $this->plugin_network_activated = true;
                        $this->options = get_site_option( 'ReduxPageBuilder', $defaults );
                    }
                }
            }

            // If options aren't set, grab them now!
            if( empty( $this->options ) ) {
                $this->options = get_option( 'ReduxPageBuilder', $defaults );
            }
        }


        /**
         * Include necessary files
         *
         * @access      public
         * @since       3.1.3
         * @return      void
         */
        public function includes() {
            
            require_once ( dirname( __FILE__ ) . '/inc/utils.php' );
            require_once ( dirname( __FILE__ ) . '/inc/post-formats.php' );
            require_once ( dirname( __FILE__ ) . '/inc/awesome-icons.php' );
            require_once ( dirname( __FILE__ ) . '/inc/widgets.php' );
            require_once ( dirname( __FILE__ ) . '/widgets-config.php' );
            require_once ( dirname( __FILE__ ) . '/inc/core.php' );

            if( class_exists('ReduxBuilder') ) {
                new ReduxBuilder();
            }

        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       3.1.3
         * @return      void
         */
        private function hooks() {
            add_action( 'wp_loaded', array( $this, 'options_toggle_check' ) );

            // Activate plugin when new blog is added
            add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

            // Display admin notices
            add_action( 'admin_notices', array( $this, 'admin_notices' ) );

            // Edit plugin metalinks
            add_filter( 'plugin_row_meta', array( $this, 'plugin_metalinks' ), null, 2 );

            do_action( 'redux/plugin/hooks', $this );

        }


        /**
         * Fired on plugin activation
         *
         * @access      public
         * @since       3.0.0
         * @param       boolean $network_wide True if plugin is network activated, false otherwise
         * @return      void
         */
        public static function activate( $network_wide ) {
            return;
            if( function_exists( 'is_multisite' ) && is_multisite() ) {
                if( $network_wide ) {
                    // Get all blog IDs
                    $blog_ids = self::get_blog_ids();

                    foreach( $blog_ids as $blog_id ) {
                        switch_to_blog( $blog_id );
                        self::single_activate();
                    }
                    restore_current_blog();
                } else {
                    self::single_activate();
                }
            } else {
                self::single_activate();
            }

            delete_site_transient( 'update_plugins' );
        }


        /**
         * Fired when plugin is deactivated
         *
         * @access      public
         * @since       3.0.0
         * @param       boolean $network_wide True if plugin is network activated, false otherwise
         * @return      void
         */
        public static function deactivate( $network_wide ) {
            return;
            if( function_exists( 'is_multisite' ) && is_multisite() ) {
                if( $network_wide ) {
                    // Get all blog IDs
                    $blog_ids = self::get_blog_ids();

                    foreach( $blog_ids as $blog_id ) {
                        switch_to_blog( $blog_id );
                        self::single_deactivate();
                    }
                    restore_current_blog();
                } else {
                    self::single_deactivate();
                }
            } else {
                self::single_deactivate();
            }

            delete_option( 'ReduxPageBuilder' );
        }


        /**
         * Fired when a new WPMU site is activated
         *
         * @access      public
         * @since       3.0.0
         * @param       int $blog_id The ID of the new blog
         * @return      void
         */
        public function activate_new_site( $blog_id ) {
            return;
            if( 1 !== did_action( 'wpmu_new_blog' ) ) {
                return;
            }

            switch_to_blog( $blog_id );
            self::single_activate();
            restore_current_blog();
        }


        /**
         * Get all IDs of blogs that are not activated, not spam, and not deleted
         *
         * @access      private
         * @since       3.0.0
         * @global      object $wpdb
         * @return      array|false Array of IDs or false if none are found
         */
        private static function get_blog_ids() {
            return;
            global $wpdb;

            // Get an array of IDs
            $sql = "SELECT blog_id FROM $wpdb->blogs
                    WHERE archived = '0' AND spam = '0'
                    AND deleted = '0'";

            return $wpdb->get_col( $sql );
        }


        /**
         * Fired for each WPMS blog on plugin activation
         *
         * @access      private
         * @since       3.0.0
         * @return      void
         */
        private static function single_activate() {
            return;
            $notices = get_option( 'ReduxPageBuilder_ACTIVATED_NOTICES', array() );
            $notices[] = __( 'Redux Framework has an embedded demo.', 'redux-framework' ) . ' <a href="./plugins.php?ReduxPageBuilder=demo">' . __( 'Click here to activate the sample config file.', 'redux-framework' ) . '</a>';

            update_option( 'ReduxPageBuilder_ACTIVATED_NOTICES', $notices );
        }


        /**
         * Display admin notices
         *
         * @access      public
         * @since       3.0.0
         * @return      void
         */
        public function admin_notices() {
            return;
            do_action( 'ReduxPageBuilder_admin_notice' );

            if( $notices = get_option( 'ReduxPageBuilder_ACTIVATED_NOTICES' ) ) {
                foreach( $notices as $notice ) {
                    echo '<div class="updated"><p>' . $notice . '</p></div>';
                }

                delete_option( 'ReduxPageBuilder_ACTIVATED_NOTICES' );
            }
        }


        /**
         * Fired for each blog when the plugin is deactivated
         *
         * @access      private
         * @since       3.0.0
         * @return      void
         */
        private static function single_deactivate() {
            return;
            delete_option( 'ReduxPageBuilder_ACTIVATED_NOTICES' );
        }


        /**
         * Turn on or off
         *
         * @access      public
         * @since       3.0.0
         * @global      string $pagenow The current page being displayed
         * @return      void
         */
        public function options_toggle_check() {
            return;
            global $pagenow;

            if( $pagenow == 'plugins.php' && is_admin() && !empty( $_GET['ReduxPageBuilder'] ) ) {
                $url = './plugins.php';

                if( $_GET['ReduxPageBuilder'] == 'demo' ) {
                    if( $this->options['demo'] == false ) {
                        $this->options['demo'] = true;
                    } else {
                        $this->options['demo'] = false;
                    }
                }

                if( is_multisite() && is_network_admin() && $this->plugin_network_activated ) {
                    update_site_option( 'ReduxPageBuilder', $this->options );
                } else {
                    update_option( 'ReduxPageBuilder', $this->options );
                }

                wp_redirect( $url );
            }
        }

        /**
         * Edit plugin metalinks
         *
         * @access      public
         * @since       3.0.0
         * @param       array $links The current array of links
         * @param       string $file A specific plugin row
         * @return      array The modified array of links
         */
        public function plugin_metalinks( $links, $file ) {
            if( strpos($file,'redux-page-builder.php') !== false ) {

                $new_links = array(
                    '<a href="https://github.com/ReduxFramework/ReduxFramework" target="_blank">' . __( 'Github Repo', 'redux-framework' ) . '</a>',
                    '<a href="https://github.com/ReduxFramework/ReduxFramework/issues/" target="_blank">' . __( 'Issue Tracker', 'redux-framework' ) . '</a>'
                );

                if( ( is_multisite() && $this->plugin_network_activated ) || !is_network_admin() || !is_multisite() ) {
                    if( $this->options['demo'] ) {
                        $new_links[1] .= '<br /><span style="display: block; padding-top: 6px;"><a href="./plugins.php?ReduxPageBuilder=demo" style="color: #bc0b0b;">' . __( 'Deactivate Demo Mode', 'redux-framework' ) . '</a></span>';
                    } else {
                        $new_links[1] .= '<br /><span style="display: block; padding-top: 6px;"><a href="./plugins.php?ReduxPageBuilder=demo" style="color: #bc0b0b;">' . __( 'Activate Demo Mode', 'redux-framework' ) . '</a></span>';
                    }
                }

                $links = array_merge( $links, $new_links );
            }

            return $links;
        }
    }

    do_action( 'redux/builder/init', ReduxPageBuilder::init() );

}




