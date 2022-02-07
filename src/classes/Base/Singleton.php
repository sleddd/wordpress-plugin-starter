<?php namespace WpStarterPlugin\Base;

/**
 * Singleton
 */
abstract class Singleton {


	/**
	 * Instance
	 *
	 * @var Singleton
	 */
	public static $instance = null;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function __construct() {
	}

	/**
	 * Get instance
	 *
	 * @return Singleton
	 */
	public static function instance() {
		$class = get_called_class();
		if ( null === self::$instance ) {
			self::$instance = new $class();
		}
		return self::$instance;
	}
}
