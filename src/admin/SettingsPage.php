<?php
/**
 * Class SettingsPage
 *
 * The setting page of the plugin.
 *
 * @package GptContentManager\Admin
 */

namespace GptContentManager\Admin;
use GptContentManager\Settings;


class SettingsPage {

	/**
	 * The settings object.
	 *
	 * @var Settings $settings The settings object.
	 */
	protected Settings $settings;

	/**
	 * SettingsPage constructor.
	 *
	 * @param Settings $settings The settings object.
	 */
	public function __construct( Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Add settings page to the admin menu.
	 */
	public function add_settings_page(): void {
		// Add options page to the admin menu.
		add_options_page(
			$this->settings->get_plugin_name(),
			$this->settings->get_plugin_name(),
			'manage_options',
			$this->settings->get_plugin_slug(),
			[ $this, 'print_plugin_admin_page' ]
		);
	}

	/**
	 * Print the plugin's admin page.
	 */
	public function print_plugin_admin_page(): void {
		// Retrieve the template file path.
		$template = 'admin/gpt-content-manager-admin-display.php';
		$template_admin_settings_page = WP_PLUGIN_DIR . '/' . plugin_dir_path( $this->settings->get_plugin_basename() ) . 'templates/' . $template;

		// Check for template overrides.
		if ( file_exists( get_stylesheet_directory() . $template ) ) {
			$template_admin_settings_page = get_stylesheet_directory() . $template;
		} elseif ( file_exists( get_stylesheet_directory() . 'templates/' . $template ) ) {
			$template_admin_settings_page = get_stylesheet_directory() . 'templates/' . $template;
		}

		// Retrieve plugin slug and name.
		$plugin_slug = $this->settings->get_plugin_slug();
		$plugin_name = $this->settings->get_plugin_name();

		// Apply filters to the template.
		$filtered_template_admin_settings_page = apply_filters( "{$plugin_slug}_admin_settings_page_template", $template_admin_settings_page, func_get_args() );

		// Include the template.
		if ( ! file_exists( $filtered_template_admin_settings_page ) ) {
			include $template_admin_settings_page;
		} else {
			include $filtered_template_admin_settings_page;
		}
	}

	/**
	 * Setup sections for the settings page.
	 */
	public function setup_sections(): void {
		// Add a settings section.
		add_settings_section(
			$this->settings->get_plugin_slug() . '_general_section',
			'General settings',
			[$this, 'general_section_callback'],
			$this->settings->get_plugin_slug()
		);
	}

	/**
	 * Callback function for the general settings section.
	 */
	public function general_section_callback() {
		// General settings section callback.
	}

	/**
	 * Setup fields for the settings page.
	 */
	public function setup_fields(): void {
		// Add settings field.
		add_settings_field(
			$this->settings->get_plugin_slug() . '_openai_api_key',
			'OpenAI API Key',
			[$this, 'openai_api_key_callback'],
			$this->settings->get_plugin_slug(),
			$this->settings->get_plugin_slug() . '_general_section'
		);
	}

	/**
	 * Callback function for the OpenAI API Key field.
	 */
	public function openai_api_key_callback(): void {
		// Retrieve the OpenAI API key.
		$api_key = $this->settings->get_openai_api_key();
		$openai_field_name = $this->settings->get_plugin_slug() . '_openai_api_key';

		// Output HTML for the field.
		printf(
			'<input type="text" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />',
			esc_attr( $openai_field_name ),
			esc_attr( $api_key )
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings(): void {
		// Define setting ID.
		$setting_id = $this->settings->get_plugin_slug() . '_openai_api_key';

		// Register setting.
		register_setting(
			$this->settings->get_plugin_slug(),
			$setting_id,
			[
				'type'              => 'string',
				'description'       => 'OpenAI API Key for the GPT Content Manager plugin.',
				'sanitize_callback' => [ $this, 'sanitize_openai_api_key' ],
				'show_in_rest'      => false,
				'default'           => '',
			]
		);
	}

	/**
	 * Sanitize the OpenAI API key entered by the user.
	 *
	 * @param string $openai_api_key The API key entered by the user.
	 * @return string Sanitized API key.
	 */
	public function sanitize_openai_api_key( string $openai_api_key ): string {
		// Remove special characters from the API key.
		return preg_replace( '/[^a-zA-Z0-9\-]/', '', $openai_api_key );
	}
}
