<?php
/**
 * The MetaBoxes class for creating meta boxes in WordPress admin.
 *
 * @since      1.0.0
 * @package    GptContentManager
 */

namespace GptContentManager\Admin;

use GptContentManager\Settings;
use GptContentManager\OpenAiConnector;
use WP_Post;

/**
 * The MetaBoxes class.
 */
class MetaBoxes {

	protected Settings $settings;

	/**
	 * The constructor.
	 */
	public function __construct( Settings $settings ) {

		$this->settings = $settings;

		// Add meta box(es) to the post editor screen.
		add_action( 'add_meta_boxes', array( $this, 'add_custom_meta_box' ) );

		// Save meta box data when the post is saved.
		add_action( 'save_post', array( $this, 'save_custom_meta_box_data' ) );
	}

	/**
	 * Add custom meta box to the post editor screen.
	 */
	public function add_custom_meta_box(): void {
		add_meta_box(
			'gpt_content_manager_meta_box',
			'GPT Content Manager',
			[ $this, 'render_custom_meta_box' ],
			'post',
			'normal',
		);
	}

	/**
	 * Render custom meta box content.
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function render_custom_meta_box( $post ): void {
		// Add nonce for security and authentication.
		wp_nonce_field( 'gpt_content_manager_meta_box', 'gpt_content_manager_meta_box_nonce' );

		// Retrieve existing prompt value for this post (if any).
		$prompt = get_post_meta( $post->ID, '_gpt_content_manager_prompt', true );
		$template = 'admin/gpt-content-manager-meta-boxes.php';

		$template_admin_meta_boxes = WP_PLUGIN_DIR . '/' . plugin_dir_path( $this->settings->get_plugin_basename() ) . 'templates/' . $template;
		include $template_admin_meta_boxes;
	}

	/**
	 * Save custom meta box data when the post is saved.
	 *
	 * @param int $post_id The current post ID.
	 */
	public function save_custom_meta_box_data( $post_id ): void {
		// Check if our nonce is set.
		if ( ! isset( $_POST['gpt_content_manager_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['gpt_content_manager_meta_box_nonce'], 'gpt_content_manager_meta_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'post' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$gpt_content_manager_prompt = sanitize_text_field( $_POST['gpt_content_manager_prompt'] );

		// Update or add the prompt value for this post.
		if ( isset( $_POST['gpt_content_manager_prompt'] ) ) {
			update_post_meta( $post_id, '_gpt_content_manager_prompt', $gpt_content_manager_prompt );
		}

		// Create an instance of OpenAiConnector
		$openAiConnector = new OpenAiConnector($this->settings->get_openai_api_key());

		// Generate response from OpenAI
		$response = $openAiConnector->generateResponse($gpt_content_manager_prompt);

		// Update post content with the generated response
		wp_update_post([
			'ID'           => get_the_ID(),
			'post_content' => $response,
		]);
	}
}
