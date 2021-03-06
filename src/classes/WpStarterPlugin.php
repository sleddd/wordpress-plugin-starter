<?php namespace WpStarterPlugin;

use WpStarterPlugin\Base\Singleton;
use WpStarterPlugin\Base\API;

/**
 * Manages plugin initialization.
 */
class WpStarterPlugin extends Singleton {

	public static $TEST_API = array();

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
		add_action( 'init', array( __NAMESPACE__ . '\\WpStarterPlugin', 'register_blocks' ) );

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_styles', array( __NAMESPACE__ . '\\WpStarterPlugin', 'enqueue_frontend_styles' ) );
		add_action( 'wp_enqueue_scripts', array( __NAMESPACE__ . '\\WpStarterPlugin', 'enqueue_frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __NAMESPACE__ . '\\WpStarterPlugin', 'enqueue_backend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __NAMESPACE__ . '\\WpStarterPlugin', 'enqueue_backend_styles' ) );

		// Add browsersync.
		add_action( 'wp_footer', array( __NAMESPACE__ . '\\WpStarterPlugin', 'add_browser_sync' ) );

		// Add settings pages.
		$plugin::register_settings_pages();

		// Hide the ACF admin menu item.
		add_filter(
			'acf/settings/show_admin',
			function ( $show_admin ) {
				return false;
			}
		);

		// Set up apis for user in plugin or theme
		$plugin::init_api();

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

	/* Add a static api endpoint - only for API endpoints that do change */
	private static function init_api() {
		self::$TEST_API = new API(
			'placeholder_test',
			'https://jsonplaceholder.typicode.com/todos',
			false,
			false,
			true,
			0
		);
	}

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
		wp_enqueue_style(
			'wpstarterplugin-blockcss',
			WP_STARTER_PLUGIN_URL . 'dist/css/blocks.css'
		);
		wp_enqueue_script(
			'wpstarterplugin-blockjs',
			WP_STARTER_PLUGIN_URL . 'dist/js/blocks.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components' ),
			null,
			true
		);
		wp_localize_script(
			'wpstarterplugin-blockjs',
			'wpstarterplugin',
			array(
				'pluginDirPath' => WP_STARTER_PLUGIN_PATH,
				'pluginDirUrl'  => WP_STARTER_PLUGIN_URL,
			)
		);
	}

	/**
	 * Registration for frontend plugin styles.
	 */
	public static function enqueue_frontend_styles() {
		wp_enqueue_style( 'wpstarterplugin-styles', WP_STARTER_PLUGIN_URL . 'dist/css/frontend.css', rand(), false, 'all' );
	}

	/**
	 * Registration for backend styles.
	 */
	public static function enqueue_backend_styles() {
		wp_enqueue_style( 'wpstarterplugin-styles', WP_STARTER_PLUGIN_URL . 'dist/css/backend.css', rand(), false, 'all' );
	}

	/**
	 * Registration for frontend scripts and script localization.
	 */
	public static function enqueue_frontend_scripts() {
		 wp_enqueue_script( 'wpstarterplugin-scripts', WP_STARTER_PLUGIN_URL . 'dist/js/frontend.js', 'jquery', rand(), true );
		$nonce = wp_create_nonce( 'ajax_nonce' );
		wp_localize_script(
			'wpstarterplugin-frontend-scripts',
			'wpstarterplugin',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => $nonce,
			)
		);
	}

	/**
	 * Registration for backend scripts.
	 */
	public static function enqueue_backend_scripts() {
		wp_enqueue_script( 'wpstarterplugin-scripts', WP_STARTER_PLUGIN_URL . 'dist/js/backend.js', 'jquery', rand(), true );
		$nonce = wp_create_nonce( 'ajax_nonce' );
		wp_localize_script(
			'wpstarterplugin-backend-scripts',
			'wpstarterplugin',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => $nonce,
			)
		);
	}

	/**
	 * Echoes browsersync custom script tag.
	 */
	public static function add_browser_sync() {
		 // echo '<script id="__bs_script__">//<![CDATA[
		// document.write("<script async src=' . 'http://HOST:62584/browser-sync/browser-sync-client.js?v=2.27.7' . '><\/script>".replace("HOST", location.hostname));
		// ]]></script>';
	}
}
