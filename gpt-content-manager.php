<?php
/**
 * @since             1.0.0
 * @package           GptContentManager
 *
 * @wordpress-plugin
 * Plugin Name:       GPT Content Manager
 * Plugin URI:        https://github.com/sergezlobovski/gpt
 * Description:       WordPress Plugin for Managing Website Content with ChatGPT
 * Version:           1.0.0
 * Requires PHP:      8.0
 * Author:            Serge Zlobovski
 * Author URI:        https://github.com/sergezlobovski
 * License:           MIT
 * Text Domain:       gpt-content-manager
 * Domain Path:       /languages
 *
 * GitHub Plugin URI: https://github.com/sergezlobovski/gpt-content-manager
 * Release Asset:     true
 */

namespace GptContentManager;

use GptContentManager\WpIncludes\Activator;
use GptContentManager\WpIncludes\Deactivator;
use GptContentManager\Settings;


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

define( 'GPT_CONTENT_MANAGER_VERSION', '1.0.0' );
define( 'GPT_CONTENT_MANAGER_BASENAME', plugin_basename( __FILE__ ) );
define( 'GPT_CONTENT_MANAGER_PATH', plugin_dir_path( __FILE__ ) );
define( 'GPT_CONTENT_MANAGER_URL', trailingslashit( plugins_url( plugin_basename( __DIR__ ) ) ) );

register_activation_hook( __FILE__, [ Activator::class, 'activate' ] );
register_deactivation_hook( __FILE__, [ Deactivator::class, 'deactivate' ] );

function instantiate_gpt_content_manager(): void {
	$settings = new Settings();
	new GptContentManager( $settings );
}
instantiate_gpt_content_manager();