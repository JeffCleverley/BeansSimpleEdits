<?php

namespace LearningCurve\BeansSimpleEdits;

class Beans_Simple_Edits_Admin {

	/**
	 * Plugin version
	 */
	public $plugin_version;

	/**
	 * The plugin textdomain, for translations.
	 */
	public $plugin_textdomain;

	/**
	 * The array of simple edits.
	 */
	public $simple_edits;

	/**
	 * The array of Beans Simple Shortcodes that can be used.
	 */
	public $simple_shortcodes;


	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->plugin_version    = Beans_Simple_Edits()->plugin_version;
		$this->plugin_textdomain = Beans_Simple_Edits()->plugin_textdomain;
		$this->simple_edits      = Beans_Simple_Edits()->simple_edits;

		if ( class_exists( 'LearningCurve\BeansSimpleShortcodes\Beans_Simple_Shortcodes' ) ) {
			$this->simple_shortcodes = \LearningCurve\BeansSimpleShortcodes\Beans_Simple_Shortcodes()->enabled_shortcodes;
			array_unshift( $this->simple_edits, 'simple_shortcodes' );
		}

	}

	/**
	 * Initialize.
	 *
	 * @since 1.0
	 */
	public function init() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 150 );
		array_map( array( $this, 'add_simple_edits' ), $this->simple_edits );

	}

	/**
	 * Add beans simple edits menu.
	 *
	 * @since 1.0
	 */
	public function admin_menu() {

		add_theme_page(
			__( 'Simple Edits', $this->plugin_textdomain ),
			__( 'Simple Edits', $this->plugin_textdomain ),
			'manage_options',
			'beans_simple_edits_settings',
			array(
				$this,
				'display_simple_edits_settings_screen'
			)
		);

	}

	/**
	 * Hook methods to register each of the simple edits metaboxes.
	 *
	 * @since 1.0
	 *
	 * @param $simple_edit string single item from the $simple_edits array.
	 */
	public function add_simple_edits( $simple_edit ) {

		add_action( 'admin_init', array( $this, 'register_' . $simple_edit . '_edits' ) );

	}

	/**
	 * Beans Simple Edits options page view.
	 *
	 * @since 1.0
	 */
	public function display_simple_edits_settings_screen() {

		require __DIR__ . "/views/admin.php";

	}


	/**
	 * Register the available Beans Simple Shortcodes metabox.
	 *
	 * @since 1.0
	 */
	public function register_simple_shortcodes_edits() {

		$shortcodes = $this->simple_shortcodes;

		$available_shortcodes = [];

		foreach ( $shortcodes as $shortcode ) {
			$available_shortcodes[] = '<span style="display: inline-block; margin: 10px 15px;"> [beans_' . $shortcode . ']</span>';
		}

		$available_shortcodes = implode( " ", $available_shortcodes );

		$shortcodes_settings = esc_url( get_site_url() . '/wp-admin/themes.php?page=beans_simple_shortcodes_settings' );

		$fields = array(
			array(
				'id'          => 'beans_available_simple_shortcodes',
				'label'       => __( 'For a full description of each shortcodes functionality and attributes, and to enable or disable different shortcodes, please refer to the <a href="' . $shortcodes_settings . '">Beans Simple Shortcodes settings page</a>', $this->plugin_textdomain ),
				'description' => __( "<p style='text-align: left;'>{$available_shortcodes}</p>", $this->plugin_textdomain ),
				'type'        => '',
				'default'     => ''
			),
		);

		beans_register_options( $fields, 'beans_simple_edits_settings', 'available_beans_simple_shortcodes', array(
			'title'   => __( 'Available Beans Simple Shortcodes', $this->plugin_textdomain ),
			'context' => 'normal',
		) );
	}

	/**
	 * Register the Beans Simple Edits Entry Meta metabox.
	 *
	 * @since 1.0
	 */
	public function register_post_meta_edits() {

		$fields = array(
			array(
				'id'          => 'beans_post_meta_above_content',
				'label'       => __( 'Entry Meta (Above Content)', $this->plugin_textdomain ),
				'description' => __( 'This entry meta will appear above the content - If this field is empty the default content will show', $this->plugin_textdomain ),
				'type'        => 'textarea',
				'default'     => ''
			),
			array(
				'id'             => 'beans_remove_post_meta_above_content_checkbox',
				'checkbox_label' => __( 'Check this box to <strong>Remove</strong> the <strong>Entry Meta</strong> above the content', 'tm-beans' ),
				'type'           => 'checkbox',
				'default'        => 0,
				'description'    => __(
					'Warning: Checking this option will result in the entry meta not being displayed above the content',
					$this->plugin_textdomain
				)
			),
			array(
				'id'          => 'beans_post_meta_below_content',
				'label'       => __( 'Entry Meta (Below Content)', $this->plugin_textdomain ),
				'description' => __( 'This entry meta will appear below the content - If this field is empty the default content will show', $this->plugin_textdomain ),
				'type'        => 'textarea',
				'default'     => ''
			),
			array(
				'id'             => 'beans_remove_post_meta_below_content_checkbox',
				'checkbox_label' => __( 'Check this box to <strong>Remove</strong> the <strong>Entry Meta</strong> below the content', $this->plugin_textdomain ),
				'type'           => 'checkbox',
				'default'        => 0,
				'description'    => __(
					'Warning:  Checking this option will result in the entry meta not being displayed below the content',
					$this->plugin_textdomain
				),
			)
		);

		beans_register_options( $fields, 'beans_simple_edits_settings', 'beans_post_meta_edits', array(
			'title'   => __( 'Beans Simple Edits Entry Meta', $this->plugin_textdomain ),
			'context' => 'normal',
		) );

	}

	/**
	 * Register the Beans Simple Edits Split Footer metabox
	 *
	 * @since 1.0
	 */
	public function register_split_footer_edits() {

		$fields = array(
			array(
				'id'          => 'beans_split_footer_left',
				'label'       => __( '<strong>Left Footer Credit Text</strong>', $this->plugin_textdomain ),
				'description' => __( 'Edit the Credits on the Left Side of the Footer - If you leave this box empty, the default text will display.', $this->plugin_textdomain ),
				'type'        => 'textarea',
				'default'     => ''
			),
			array(
				'id'             => 'beans_remove_split_footer_left_checkbox',
				'checkbox_label' => __( 'Check this box to <strong>not show</strong> the <strong>Left Side Footer</strong>', $this->plugin_textdomain ),
				'type'           => 'checkbox',
				'default'        => 0,
				'description'    => __(
					'Warning: Checking this option will result in no text being displayed in the left footer area no matter what text you enter below',
					$this->plugin_textdomain
				),
			),
			array(
				'id'          => 'beans_split_footer_right',
				'label'       => __( '<strong>Right Footer Credit Text</strong>', $this->plugin_textdomain ),
				'description' => __( 'Edit the Credits on the Right Side of the Footer - If you leave this box empty, the default text will display.', $this->plugin_textdomain ),
				'type'        => 'textarea',
				'default'     => ''
			),
			array(
				'id'             => 'beans_remove_split_footer_right_checkbox',
				'checkbox_label' => __( 'Check this box to <strong>not show</strong> the <strong>Right Side Footer</strong>', $this->plugin_textdomain ),
				'type'           => 'checkbox',
				'default'        => 0,
				'description'    => __(
					'Warning: Checking this option will result in no text being displayed in the right footer area no matter what text you enter below',
					$this->plugin_textdomain
				),
			),
		);

		beans_register_options( $fields, 'beans_simple_edits_settings', 'beans_split_footer_text_credits_edits', array(
			'title'   => __( 'Beans Simple Edits Split Footer', $this->plugin_textdomain ),
			'context' => 'normal',
		) );

	}

	/**
	 * Register the Beans Simple Edits Center Footer metabox.
	 *
	 * @since 1.0
	 */
	public function register_center_footer_edits() {

		$fields = array(
			array(
				'id'             => 'beans_show_center_footer_checkbox',
				'checkbox_label' => __( 'Check this box to <strong>disable the Left and Right footer areas</strong> and <strong>replace</strong> them with a <strong>Central Footer area</strong>', $this->plugin_textdomain ),
				'type'           => 'checkbox',
				'default'        => 0,
				'description'    => __(
					'Note: Checking this option will result in a complete override of the standard split left and right footer, including the content from contained in the metabox above.',
					$this->plugin_textdomain
				),
			),
			array(
				'id'          => 'beans_center_footer',
				'label'       => __( '<strong>Centered Footer Credit Text</strong>', $this->plugin_textdomain ),
				'description' => __( 'Edit the credit text in the Center of the Footer - If you leave this field empty, the default content from the standard left and right split footer will be displayed.', $this->plugin_textdomain ),
				'type'        => 'textarea',
				'default'     => ''
			),
		);

		beans_register_options( $fields, 'beans_simple_edits_settings', 'beans_center_footer_edits', array(
			'title'   => __( 'Beans Simple Edits Center Footer', $this->plugin_textdomain ),
			'context' => 'normal',
		) );

	}

}

