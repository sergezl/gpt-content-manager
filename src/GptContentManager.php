<?php
/**
 * Class GptContentManager
 *
 * Main class for the GPT Content Manager plugin, responsible for initializing the plugin,
 * defining admin and frontend hooks, and managing settings.
 *
 * @package GptContentManager
 */


namespace SZ;

use SZ\Settings;

if (!defined('WPINC') || !defined("ABSPATH")) {
    die();
}
class GptContentManager {

    /** @var self */
    private static ?self $instance = null;

    /**
     * @var bool
     */
    private bool $loaded = false;

    /** Forbidding creation via new */
    private function __construct() {}

    /** Forbidding cloning */
    private function __clone() {}

    /** Forbidding deserialization */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    /**
     * Get Singleton instance
     * @return self
     */
    public static function getInstance()
    {
        return self::$instance ??= new self(); // PHP 7.4+
    }

    /**
     * Load base plugin components
     */
    public function load() {
        if ($this->loaded) {
            return;
        }
        $this->loaded = true;

        $this->hook_init();
        $this->add_menu();

        Settings::init();
        MetaBoxes::init();
    }

    /**
     * Init hooks
     */
    public function hook_init() {
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue' ] , 20);
        add_action( 'rest_api_init', [RestController::class, 'register_routes'] );
    }

    /**
     * Add enqueue
     */
	public function enqueue( $hook ) {
        if ( in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
            wp_enqueue_style( 'gcm-styles', GCM_URL . 'assets/css/gpt-content-manager-admin.css' );
            wp_enqueue_script( 'gcm-scripts', GCM_URL . 'assets/js/gpt-content-manager-admin.js' );

            global $post;
            wp_localize_script(
                'gcm-scripts',
                'gcmData',
                [
                    'root'  => esc_url_raw( rest_url() ),
                    'nonce' => wp_create_nonce( 'wp_rest' ),
                    'post_id'  => $post->ID ?? 0,
                ]
            );
        }
	}

    /**
     * Add menus
     */
    public function add_menu() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Add admin menu
     */
    public function admin_menu() {
        if( current_user_can('manage_options' ) ) {
            add_options_page(
                'GPT Content Manager',
                'GPT Content Manager',
                'manage_options',
                'gpt_content_manager',
                [ $this, 'admin_options_page' ]
            );
        }

    }

    /**
     * Admin option menu callback
     */
    public function admin_options_page() {
        // Include settings view
        include_once( GCM_DIR . 'views/admin/gpt-content-manager-admin-display.php' );
    }
}
