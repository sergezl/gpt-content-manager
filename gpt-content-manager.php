<?php
/**
 * @since             1.1.0
 *
 * @wordpress-plugin
 * Plugin Name:       GPT Content Manager
 * Plugin URI:        https://github.com/sergezlobovski/gpt
 * Description:       WordPress Plugin for Managing Website Content with ChatGPT
 * Version:           1.1.0
 * Requires PHP:      8.2
 * Author:            Serge Zlobovski
 * Author URI:        https://github.com/sergezlobovski
 * License:           MIT
 * Text Domain:       gpt-content-manager
 * Domain Path:       /languages
 *
 * GitHub Plugin URI: https://github.com/sergezlobovski/gpt-content-manager
 * Release Asset:     true
 */


use \SZ\GptContentManager;

// If this file is called directly, abort.
if (!defined('WPINC') || !defined("ABSPATH")) {
    die();
}

define( 'GCM_DIR', plugin_dir_path( __FILE__ ) );
// Define plugin base url.
define( 'GCM_URL', plugin_dir_url( __FILE__ ) );

// Load custom autoloader
$autoload = GCM_DIR . 'autoload.php';

if (is_readable($autoload)) {
    require_once $autoload;
}

add_action('plugins_loaded', static function () {
    // Initialize the main plugin instance
    $plugin = SZ\GptContentManager::getInstance();
    // Load the plugin
    $plugin->load(); 
});
