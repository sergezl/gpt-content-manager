<?php
/**
 * Class Settings
 *
 * Typed settings class responsible for retrieving plugin-specific configuration parameters.
 *
 * @package GptContentManager
 */

namespace GptContentManager;
class Settings {

	/**
	 * Get the plugin name.
	 *
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name(): string {
		return defined( 'GPT_CONTENT_MANAGER_NAME' )
			? GPT_CONTENT_MANAGER_NAME
			: 'GPT Content Manager';
	}

	/**
	 * Get the plugin version.
	 *
	 * @return string The version of the plugin.
	 */
	public function get_plugin_version(): string {
		return defined( 'GPT_CONTENT_MANAGER_VERSION' )
			? GPT_CONTENT_MANAGER_VERSION
			: '1.0.0';
	}

	/**
	 * Get the plugin basename.
	 *
	 * @return string The basename of the plugin.
	 */
	public function get_plugin_basename(): string {
		return defined( 'GPT_CONTENT_MANAGER_BASENAME' )
			? GPT_CONTENT_MANAGER_BASENAME
			: 'gpt-content-manager/gpt-content-manager.php';
	}

	/**
	 * Get the plugin slug.
	 *
	 * @return string The slug of the plugin.
	 */
	public function get_plugin_slug(): string {
		return 'gpt_content_manager';
	}

	/**
	 * Get the OpenAI API key.
	 *
	 * @return string The OpenAI API key.
	 */
	public function get_openai_api_key(): string {
		return get_option( $this->get_plugin_slug() . '_openai_api_key', '' );
	}
}
