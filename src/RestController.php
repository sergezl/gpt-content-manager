<?php
namespace SZ;

use WP_REST_Request;
use WP_REST_Response;

class RestController {

    public static function register_routes(): void {
        register_rest_route('gcm/v1', '/generate/', [
            'methods'             => 'POST',
            'callback'            => [self::class, 'handle_generate'],
            'permission_callback' => fn() => current_user_can('manage_options'),
        ]);
    }

    public static function handle_generate(WP_REST_Request $request): WP_REST_Response {
        $post_id = (int) $request->get_param('post_id');
        $prompt  = sanitize_textarea_field($request->get_param('prompt'));

        if ( ! $post_id || ! $prompt ) {
            return new WP_REST_Response(['success' => false, 'message' => 'Missing data'], 400);
        }

        $globalPrompt = Settings::get('global_prompt', '');
        $fullPrompt   = $globalPrompt ? "{$globalPrompt}\n\n{$prompt}" : $prompt;

        try {
            $generator = GeneratorFactory::make();
            $content   = $generator->generate($fullPrompt);

            wp_update_post([
                'ID'           => $post_id,
                'post_content' => $content,
            ]);

            return new WP_REST_Response([
                'success' => true,
                'message' => 'Content generated and saved.',
                'content' => $content,
            ]);
        } catch ( \Exception $e ) {
            return new WP_REST_Response([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}