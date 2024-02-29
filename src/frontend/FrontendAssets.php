<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package GptContentManager\Frontend
 */

namespace GptContentManager\Frontend;

use GptContentManager\Settings;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the frontend-facing stylesheet and JavaScript.
 */
class FrontendAssets {

	/**
	 * The plugin settings.
	 *
	 * @uses Settings::get_plugin_version() for caching.
	 * @uses Settings::get_plugin_basename() for determining the plugin URL.
	 *
	 * @var Settings
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
	 * Register the stylesheets for the frontend-facing side of the site.
	 *
	 * @hooked wp_enqueue_scripts
	 *
	 */
	public function enqueue_styles(): void {
		$version = $this->settings->get_plugin_version();

		$plugin_url = plugin_dir_url( $this->settings->get_plugin_basename() );

		wp_enqueue_style( 'gpt-content-manager', $plugin_url . 'assets/gpt-content-manager-frontend.css', array(), $version, 'all' );

	}

	/**
	 * Register the JavaScript for the frontend-facing side of the site.
	 *
	 * @hooked wp_enqueue_scripts
	 *
	 */
	public function enqueue_scripts(): void {
		$version = $this->settings->get_plugin_version();

		$plugin_url = plugin_dir_url( $this->settings->get_plugin_basename() );

		wp_enqueue_script( 'gpt-content-manager', $plugin_url . 'assets/gpt-content-manager-frontend.js', array( 'jquery' ), $version, false );

	}

}
