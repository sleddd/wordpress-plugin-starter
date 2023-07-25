<?php
/**
 * Plugin Name:       WordPress Plugin Starter Code
 * Description:       Base code for beginning a WordPress Plugin
 * Requires at least: 5.0
 * Requires PHP:      7.0 or greater
 * Version:           1.0
 * Author:            Claudette Raynor
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wpstarterplugin
 */

/* Constants */
define( 'WP_STARTER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_STARTER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/* Autoloader */
require WP_STARTER_PLUGIN_PATH . 'vendor/autoload.php';

/* Initializing plugin */
$wp_starter_plugin = \WpStarterPlugin\WpStarterPlugin::init();
