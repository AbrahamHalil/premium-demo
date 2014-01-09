<?php
/**
 * The Redux Page Builder
 *
 * A simple, truly extensible and fully responsive options framework 
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:     Redux Page Builder
 * Plugin URI:      http://reduxframework.com/builder/
 * Description:     Drag and Drop Page Builder / Layout Builder / Content Builder for WordPress
 * Author:          Abdullah Almesbahi
 * Author URI:      http://www.cadr.sa
 * Version:         1.0.0
 * Text Domain:     redux-page-builder
 * License:         GPL3+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:     /ReduxPageBuilder/languages
 *
 * @package         ReduxPageBuilder
 * @author          Abdullah Almesbahi <abdullah@cadr.sa>
 * @author          Dovy Paukstys <info@simplerain.com>
 * @license         GNU General Public License, version 3
 * @copyright       2013-2014 Redux Framework
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}

// Require the main plugin class
require_once( plugin_dir_path( __FILE__ ) . 'class.redux-page-builder.php' );

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'ReduxFrameworkPlugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ReduxFrameworkPlugin', 'deactivate' ) );

// Get plugin instance
add_action( 'plugins_loaded', array( 'ReduxPageBuilder', 'instance' ) );


