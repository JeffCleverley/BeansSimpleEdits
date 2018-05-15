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
	 * Constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->plugin_version    = Beans_Simple_Edits()->plugin_version;
		$this->plugin_textdomain = Beans_Simple_Edits()->plugin_textdomain;
		$this->simple_edits      = Beans_Simple_Edits()->simple_edits;
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
	 * Add each of the simple edits.
	 *
	 * @param $simple_edit string single item from the $simple_edits array.
	 */
	public function add_simple_edits( $simple_edit ) {

		add_action( 'admin_init', array( $this, 'register_' . $simple_edit . '_edits' ) );

	}

	/**
	 * Beans options page content.
	 */
	public function display_simple_edits_settings_screen() {

		require __DIR__ . "/views/admin.php";

	}

	/**
	 * Register options.
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
			'title'   => __( 'Beans Entry Meta Edits', $this->plugin_textdomain ),
			'context' => 'normal',
		) );

	}

	/**
	 * Register options.
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
			'title'   => __( 'Beans Standard (Split) Footer Credit Text Edits', $this->plugin_textdomain ),
			'context' => 'normal',
		) );

	}

	/**
	 * Register options.
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
			'title'   => __( 'Create a Centered Beans Footer', $this->plugin_textdomain ),
			'context' => 'normal',
		) );

	}

}

