<?php namespace WpStarterPlugin;

use WpStarterPlugin\Base\Singleton;

/**
 * Manages plugin initialization.
 */
class WpStarterPlugin extends Singleton {

	/**
	 * Registers any needed WordPress hooks, actions, filters.
	 */
	public static function init() {
		$plugin = self::instance();

		// Register activation and deactivation hooks
		register_activation_hook( WP_STARTER_PLUGIN_PATH . 'wp-starter-plugin.php', array( __NAMESPACE__ . '\\WpStarterPlugin', 'activate_plugin' ) );
		register_deactivation_hook( WP_STARTER_PLUGIN_PATH . 'wp-starter-plugin.php', array( __NAMESPACE__ . '\\WpStarterPlugin', 'deactivate_plugin' ) );

		// Register custom post types.
		$plugin::register_cpts();

		// Register custom blocks.
		$plugin::register_blocks();

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', array( __NAMESPACE__ . '\\WpStarterPlugin', 'enqueue_frontend_styles' ) );
		add_action( 'wp_enqueue_scripts', array( __NAMESPACE__ . '\\WpStarterPlugin', 'enqueue_frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __NAMESPACE__ . '\\WpStarterPlugin', 'enqueue_backend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __NAMESPACE__ . '\\WpStarterPlugin', 'enqueue_backend_styles' ) );

		// Add settings pages.
		$plugin::register_settings_pages();

		return $plugin;
	}

	/**
	 *  Manages tasks done on plugin activation.
	 */
	public static function activate_plugin() {  }

	/**
	 * Manages tasks done on plugin deactivation.
	 */
	public static function deactivate_plugin() {}

	/**
	 * Registration for custom settings pages.
	 */
	public static function register_settings_pages() {
		\WpStarterPlugin\Settings\ExampleSettingsPage::init();
		\WpStarterPlugin\Settings\ExampleSettingsSubPage::init();
		// \WpStarterPlugin\Settings\ExampleACFSettingsPage::init();
	}

	/**
	 * Registration for custom post types.
	 */
	public static function register_cpts() {
		PostTypes\ExamplePostType::init();
	}

	/**
	 * Registration for block scripts and styles.
	 * Blocks themselves are registered in scripts.
	 */
	public static function register_blocks() {
		// Register blocks in the format $dir => $render_callback.
		$blocks = array(
			'hello-world' => '', 
			'single-post-query' => ''
		);

		foreach ( $blocks as $dir => $render_callback ) {
			$args = array();
			if ( ! empty( $render_callback ) ) {
				$args['render_callback'] = $render_callback;
			}
			register_block_type( WP_STARTER_PLUGIN_PATH . 'dist/blocks/' . $dir, $args );
		}
	}

	/**
	 * Registration for frontend plugin styles.
	 */
	public static function enqueue_frontend_styles() {
		wp_enqueue_style( 'wpstarterplugin-frontend-styles', WP_STARTER_PLUGIN_URL . 'dist/css/frontend.css', wp_rand(), false, 'all' );
	}

	/**
	 * Registration for backend styles.
	 */
	public static function enqueue_backend_styles() {
		wp_enqueue_style( 'wpstarterplugin-backend-styles', WP_STARTER_PLUGIN_URL . 'dist/css/backend.css', wp_rand(), false, 'all' );
	}

	/**
	 * Registration for frontend scripts and script localization.
	 */
	public static function enqueue_frontend_scripts() {
		wp_enqueue_script( 'wpstarterplugin-frontend-scripts', WP_STARTER_PLUGIN_URL . 'dist/js/frontend.js', 'jquery', wp_rand(), true );
		wp_localize_script(
			'wpstarterplugin-frontend-scripts',
			'wpstarterplugin',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ajax_nonce' ),
			)
		);
	}

	/**
	 * Registration for backend scripts.
	 */
	public static function enqueue_backend_scripts() {
		wp_enqueue_script( 'wpstarterplugin-backend-scripts', WP_STARTER_PLUGIN_URL . 'dist/js/backend.js', 'jquery', rand(), true );
		wp_localize_script(
			'wpstarterplugin-backend-scripts',
			'wpstarterplugin',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ajax_nonce' ),
			)
		);
	}
}
