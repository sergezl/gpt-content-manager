<?php
/**
 * Class AdminAssets
 *
 * The admin-specific JS and CSS of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package GptContentManager\Admin
 */

namespace GptContentManager\Admin;

use GptContentManager\Settings;

class AdminAssets {

	/**
	 * The plugin settings.
	 *
	 * @var Settings $settings The plugin settings.
	 */
	protected Settings $settings;

	/**
	 * Constructor
	 *
	 * @param Settings $settings The plugin settings.
	 */
	public function __construct( Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @hooked admin_enqueue_scripts
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(): void {

		$version = $this->settings->get_plugin_version();
		$plugin_dir = plugin_dir_url( $this->settings->get_plugin_basename() );
		wp_enqueue_style( 'gpt-content-manager', $plugin_dir . 'assets/gpt-content-manager-admin.css', [], $version );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @hooked admin_enqueue_scripts
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts(): void {

		$version = $this->settings->get_plugin_version();
		$plugin_url = plugin_dir_url( $this->settings->get_plugin_basename() );
		wp_enqueue_script( 'gpt-content-manager', $plugin_url . 'assets/gpt-content-manager-admin.js', array( 'jquery' ), $version, false );

	}
}
