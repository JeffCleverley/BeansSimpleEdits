<?php

namespace LearningCurve\BeansSimpleEdits;

class Beans_Simple_Edits_Core {

	/**
	 * The plugin textdomain, for translations.
	 */
	public $plugin_textdomain;

	/**
	 * Content from the Beans Simple Edits Entry Meta (Above Content) textarea.
	 */
	private $post_meta_above_content;

	/**
	 * Checkbox state to remove the Entry Meta (Above Content) span completely. - If enabled then remove.
	 */
	private $remove_post_meta_above_content;

	/**
	 * Content from the Beans Simple Edits Entry Meta (Below Content) textarea.
	 */
	private $post_meta_below_content;

	/**
	 * Checkbox state to remove the Entry Meta (Above Content) span completely - if enabled then remove.
	 */
	private $remove_post_meta_below_content;

	/**
	 * Content from the Beans Simple Edits Split Footer (Left) textarea.
	 */
	private $split_footer_left;

	/**
	 * Checkbox state to remove the Split Footer (Left) span completely - if enabled then remove.
	 */
	private $remove_split_footer_left;

	/**
	 * Content from the Beans Simple Edits Split Footer (Right) textarea.
	 */
	private $split_footer_right;

	/**
	 * Checkbox state to remove the Split Footer (Right) span completely - if enabled then remove.
	 */
	private $remove_split_footer_right;

	/**
	 * Content from the Beans Simple Edits Center Footer textarea.
	 */
	private $center_footer;

	/**
	 * Checkbox state to display the Center Footer and disable any Split Footer - if enabled then display.
	 */
	private $show_center_footer;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->plugin_textdomain              = Beans_Simple_Edits()->plugin_textdomain;
		$this->post_meta_above_content        = get_option( 'beans_post_meta_above_content' );
		$this->remove_post_meta_above_content = get_option( 'beans_remove_post_meta_above_content_checkbox' );
		$this->post_meta_below_content        = get_option( 'beans_post_meta_below_content' );
		$this->remove_post_meta_below_content = get_option( 'beans_remove_post_meta_below_content_checkbox' );
		$this->split_footer_left              = get_option( 'beans_split_footer_left' );
		$this->remove_split_footer_left       = get_option( 'beans_remove_split_footer_left_checkbox' );
		$this->split_footer_right             = get_option( 'beans_split_footer_right' );
		$this->remove_split_footer_right      = get_option( 'beans_remove_split_footer_right_checkbox' );
		$this->center_footer                  = get_option( 'beans_center_footer' );
		$this->show_center_footer             = get_option( 'beans_show_center_footer_checkbox' );

	}

	/**
	 * Initialize
	 *
	 * Hook in callbacks to remove and replace the post meta and footer areas if the Beans Simple Edits areas are in operation.
	 *
	 * @since 1.0
	 */
	public function init() {

		if ( $this->post_meta_above_content || $this->remove_post_meta_above_content ) {

			beans_remove_action( 'beans_post_meta' );

			if ( ! $this->remove_post_meta_above_content ) {

				add_action( 'beans_post_header', array( $this, 'beans_simple_edits_post_meta' ), 15 );
			}
		}

		if ( $this->post_meta_below_content || $this->remove_post_meta_below_content ) {

			beans_remove_action( 'beans_post_meta_categories' );

			beans_remove_action( 'beans_post_meta_tags' );

			if ( ! $this->remove_post_meta_below_content ) {

				add_action( 'beans_post_body', array( $this, 'beans_simple_edits_post_meta_categories' ), 25 );
			}
		}


		if ( $this->show_center_footer ) {

			beans_add_smart_action( 'after_setup_theme', array( $this, 'beans_simple_edits_replace_central_footer') );

		} elseif ( $this->split_footer_left || $this->split_footer_right ) {

			beans_add_smart_action( 'after_setup_theme', array( $this, 'beans_simple_edits_replace_split_footers') );

		}

	}

	/**
	 * Replace the standard above content post meta with the Beans Simple Edits above content post meta.
	 *
	 * @since 1.0
	 */
	function beans_simple_edits_post_meta() {

		beans_open_markup_e( 'beans_post_meta', 'ul', array( 'class' => 'uk-article-meta uk-subnav uk-subnav-line' ) );

		beans_open_markup_e( "beans_post_meta_items", 'li' );

		beans_output_e(
			'beans_post_meta',
			do_shortcode( __( $this->post_meta_above_content, $this->plugin_textdomain ) )
		);

		beans_close_markup_e( "beans_post_meta_items", 'li' );

		beans_close_markup_e( 'beans_post_meta', 'ul' );

	}

	/**
	 * Replace the standard below content post meta with the Beans Simple Edits below content post meta.
	 *
	 * @since 1.0
	 */
	function beans_simple_edits_post_meta_categories() {

		beans_open_markup_e( 'beans_post_meta_categories', 'span', array( 'class' => 'uk-text-small uk-text-muted uk-clearfix' ) );

		beans_output_e(
			'beans_post_meta_categories',
			do_shortcode( __( $this->post_meta_below_content, $this->plugin_textdomain ) )
		);

		beans_close_markup_e( 'beans_post_meta_categories', 'span' );

	}

	/**
	 * Replace the footer content with the simple edits split footer if it is active.
	 * Hooked to 'after_theme_setup' to ensure third party child theme footer customisations are overwritten.
	 *
	 * @since 1.0
	 */
	function beans_simple_edits_replace_split_footers() {

		beans_remove_action( 'beans_footer_content' );

		beans_add_smart_action( 'beans_footer', array( $this, 'beans_simple_edits_split_footer' ) );

	}

	/**
	 * Replace the footer content with the simple edits central footer if it is active.
	 * Hooked to 'after_theme_setup' to ensure third party child theme footer customisations are overwritten.
	 *
	 * @since 1.0
	 */
	function beans_simple_edits_replace_central_footer() {

		beans_remove_action( 'beans_footer_content' );

		beans_add_smart_action( 'beans_footer', array( $this, 'beans_simple_edits_center_footer' ) );
	}

	/**
	 * Output the Beans Simple Edits Center Footer
	 *
	 * @since 1.0
	 */
	function beans_simple_edits_center_footer() {

		beans_open_markup_e( 'beans_footer_credit', 'div', array(
			'class' => 'uk-clearfix uk-text-small uk-text-muted uk-align-center'
		) );

		beans_open_markup_e( 'beans_footer_credit_span', 'span', array(
				'class' => 'uk-align-center uk-margin-small-bottom',
				'style' => 'text-align:center'
		) );

		if ( ! $this->center_footer ) {

			$framework_link = beans_open_markup( 'beans_footer_credit_framework_link', 'a', array(
					'href' => 'http://www.getbeans.io', // Automatically escaped.
					'rel'  => 'designer', ) );
			$framework_link .= beans_output( 'beans_footer_credit_framework_link_text', 'Beans' );
			$framework_link .= beans_close_markup( 'beans_footer_credit_framework_link', 'a' );

			beans_output_e(
				'beans_center_footer_credit_text',
				sprintf(
					__( '&#x000A9; %1$s - %2$s. All rights reserved. %3$s theme for WordPress.', $this->plugin_textdomain ),
					date( 'Y' ),
					get_bloginfo( 'name' ),
					$framework_link
				)
			);

		} else {

			beans_output_e(
				'beans_footer_center_credit_text',
				do_shortcode( __( $this->center_footer, $this->plugin_textdomain ) )
			);
		}

		beans_close_markup_e( 'beans_footer_credit_span', 'span' );

		beans_close_markup_e( 'beans_footer_credit_hit', 'div' );

	}

	/**
	 * Output the Beans Simple Edits Split Footer
	 *
	 * @since 1.0
	 */
	function beans_simple_edits_split_footer() {

		if ( $this->split_footer_left ) {

			ob_start();
			beans_output_e( 'beans_footer_credit_text', sprintf(
				__( '&#x000A9; %1$s - %2$s. All rights reserved.', 'tm-beans' ),
				date( 'Y' ),
				get_bloginfo( 'name' )
			) );
			$beans_left_credit = ob_get_clean();

		} else {

			$beans_left_credit =  do_shortcode( __( $this->split_footer_left, $this->plugin_textdomain ) );
		}


		if ( $this->split_footer_right ) {

			$beans_right_credit = beans_open_markup( 'beans_footer_credit_framework_link', 'a', array(
				'href' => 'http://www.getbeans.io', // Automatically escaped.
				'rel'  => 'designer',
			) );

			$beans_right_credit .= beans_output( 'beans_footer_credit_framework_link_text', 'Beans' );

			$beans_right_credit .= beans_close_markup( 'beans_footer_credit_framework_link', 'a' );

			$beans_right_credit .= beans_output( 'beans_footer_credit_framework_after_link_text', ' theme for WordPress' );

		} else {

			$beans_right_credit =  do_shortcode( __( $this->split_footer_right, $this->plugin_textdomain ) );
		}


		beans_open_markup_e( 'beans_footer_credit', 'div', array( 'class' => 'uk-clearfix uk-text-small uk-text-muted' ) );

		beans_open_markup_e( 'beans_footer_credit_left', 'span', array(
			'class' => 'uk-align-medium-left uk-margin-small-bottom',
		) );

		beans_output_e(
			'beans_footer_credit_text',
			$beans_left_credit
		);

		beans_close_markup_e( 'beans_footer_credit_left', 'span' );

		beans_open_markup_e( 'beans_footer_credit_right', 'span', array(
			'class' => 'uk-align-medium-right uk-margin-bottom-remove',
		) );

		beans_output_e(
			'beans_footer_credit_text',
			$beans_right_credit
		);

		beans_close_markup_e( 'beans_footer_credit_right', 'span' );

		beans_close_markup_e( 'beans_footer_credit', 'div' );
	}

}


