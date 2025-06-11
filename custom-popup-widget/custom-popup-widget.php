<?php
/**
 * Plugin Name: Custom Popup Widget
 * Plugin URI: https://example.com/custom-popup-widget
 * Description: A customizable popup widget that can display any Gutenberg block with control over size, position, and timing.
 * Version: 1.0.0
 * Author: John Simmons
 * Author URI: https://johnsimmonshypertext.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-popup-widget
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CPW_VERSION', '1.0.0');
define('CPW_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CPW_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once CPW_PLUGIN_DIR . 'includes/class-popup-widget.php';
require_once CPW_PLUGIN_DIR . 'includes/class-popup-settings.php';

// Initialize the plugin
function cpw_init() {
    new Custom_Popup_Widget();
    new Custom_Popup_Settings();
}
add_action('plugins_loaded', 'cpw_init');

// Register activation hook
register_activation_hook(__FILE__, 'cpw_activate');
function cpw_activate() {
    // Add default settings
    $default_settings = array(
        'popup_width' => '500',
        'popup_height' => '400',
        'popup_position' => 'center',
        'popup_delay' => '5',
        'popup_frequency' => 'once_per_session'
    );
    add_option('cpw_settings', $default_settings);
}

// Register deactivation hook
register_deactivation_hook(__FILE__, 'cpw_deactivate');
function cpw_deactivate() {
    // Cleanup if needed
} 
