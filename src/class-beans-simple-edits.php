<?php

namespace LearningCurve\BeansSimpleEdits;


class Beans_Simple_Edits {

	/**
	 * Plugin version
	 */
	public $plugin_version = '0.1';

	/**
	 * The plugin textdomain, for translations.
	 */
	public $plugin_textdomain = 'beans-simple-edits';

	/**
	 * The url to the plugin directory.
	 */
	public $plugin_dir_url;

	/**
	 * The path to the plugin directory.
	 */
	public $plugin_dir_path;

	/**
	 * The array of simple edits.
	 */
	public $simple_edits;

	/**
	 * Core functionality.
	 */
	public $core;

	/**
	 * Admin menu and settings page.
	 */
	public $admin;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
	function __construct() {

		$this->plugin_dir_url  = plugin_dir_url( __FILE__ );
		$this->plugin_dir_path = plugin_dir_path( __FILE__ );
		$this->simple_edits    = array( 'post_meta', 'split_footer', 'center_footer' );

	}

	/**
	 * Initialize.
	 *
	 * @since 1.0
	 */
	public function init() {

		add_action( 'beans_after_init', array( $this, 'instantiate' ) );

	}


	/**
	 * Include the class file, instantiate the classes, create objects.
	 *
	 * @since 1.0
	 */
	public function instantiate() {

		require_once( $this->plugin_dir_path . 'class-beans-simple-edits-core.php' );
		$this->core = new Beans_Simple_Edits_Core;
		$this->core->init();

		if ( is_admin() ) {
			require_once( $this->plugin_dir_path . 'class-beans-simple-edits-admin.php' );
			$this->admin = new Beans_Simple_Edits_Admin;
			$this->admin->init();
		}

	}

}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @since 1.0
 */
function Beans_Simple_Edits() {

	static $object;

	if ( null == $object ) {
		$object = new Beans_Simple_Edits;
	}

	return $object;

}

/**
 * Initialize the object on `plugins_loaded`.
 */
add_action( 'plugins_loaded', array( Beans_Simple_Edits(), 'init' ) );
