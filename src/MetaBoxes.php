<?php
/**
 * The MetaBoxes class for creating meta boxes in WordPress admin.
 *
 * @since      1.0.0
 * @package    GptContentManager
 */

namespace SZ;

use WP_Post;
use SZ\RestController;

/**
 * The MetaBoxes class.
 */
class MetaBoxes {

	protected Settings $settings;

	/**
	 * The constructor.
	 */
    public static function init() {
        add_action( 'add_meta_boxes', [ __CLASS__, 'add_meta_box' ] );
    }

	/**
	 * Add custom meta box to the post editor screen.
	 */
    public static function add_meta_box() {
        add_meta_box(
            'prompt_metabox',
            'Prompt Generator',
            [ __CLASS__, 'render_meta_box' ],
            ['post', 'page'],
            'normal',
            'default'
        );
    }

	/**
	 * Render custom meta box content.
	 *
	 * @param WP_Post $post The current post object.
	 */
    public static function render_meta_box( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'gpt_content_manager_meta_box', 'gpt_content_manager_meta_box_nonce' );

		// Retrieve existing prompt value for this post (if any).
		$prompt = get_post_meta( $post->ID, '_gpt_content_manager_prompt', true );

        include_once( GCM_DIR . 'views/admin/gpt-content-manager-meta-boxes.php' );
	}

    public static function check_admin_permission() {
        return current_user_can( 'manage_options' );
    }

    public static function handle_generate( WP_REST_Request $request ) {
        $prompt = sanitize_textarea_field( $request->get_param( 'prompt' ) );



        return rest_ensure_response( [
            'success' => true,
            'message' => 'Prompt received: ' . $prompt,
        ] );
    }
}
