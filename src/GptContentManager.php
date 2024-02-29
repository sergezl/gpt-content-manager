<?php
/**
 * Class GptContentManager
 *
 * Main class for the GPT Content Manager plugin, responsible for initializing the plugin,
 * defining admin and frontend hooks, and managing settings.
 *
 * @package GptContentManager
 */

namespace GptContentManager;

use GptContentManager\Admin\AdminAssets;
use GptContentManager\Admin\MetaBoxes;
use GptContentManager\Admin\SettingsPage;
use GptContentManager\Frontend\FrontendAssets;
use GptContentManager\Settings;
use GptContentManager\WpIncludes\I18n;

class GptContentManager {

	/**
	 * @var Settings $settings The settings object for the plugin.
	 */
	protected Settings $settings;

	/**
	 * GptContentManager constructor.
	 *
	 * Initializes the plugin by setting up hooks and loading necessary assets.
	 *
	 * @param Settings $settings The settings object for the plugin.
	 */
	public function __construct( Settings $settings ) {
		$this->settings = $settings;

		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_frontend_hooks();
	}

	/**
	 * Set the plugin's locale.
	 *
	 * Loads the internationalization files for the plugin.
	 */
	protected function set_locale(): void {
		$pluginI18n = new I18n();

		add_action( 'init', [ $pluginI18n, 'load_plugin_gpt_content_manager' ] );
	}

	/**
	 * Define admin hooks.
	 *
	 * Registers and enqueues admin-specific assets and initializes settings page and meta boxes.
	 */
	protected function define_admin_hooks(): void {
		$admin_assets = new AdminAssets( $this->settings );
		add_action( 'admin_enqueue_scripts', [ $admin_assets, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $admin_assets, 'enqueue_scripts' ] );

		$settings_page = new SettingsPage( $this->settings );
		add_action( 'admin_menu', [ $settings_page, 'add_settings_page' ] );
		add_action( 'admin_init', [ $settings_page, 'setup_sections' ] );
		add_action( 'admin_init', [ $settings_page, 'setup_fields' ] );
		add_action( 'admin_init', [ $settings_page, 'register_settings' ] );

		$meta_boxes = new MetaBoxes( $this->settings );
	}

	/**
	 * Define frontend hooks.
	 *
	 * Registers and enqueues frontend-specific assets.
	 */
	protected function define_frontend_hooks(): void {
		$frontend_assets = new FrontendAssets( $this->settings );

		add_action( 'wp_enqueue_scripts', [ $frontend_assets, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $frontend_assets, 'enqueue_scripts' ] );
	}
}
