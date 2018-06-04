<?php

namespace LearningCurve\BeansSimpleEdits;

class Beans_Simple_Edits_Core {

	/**
	 * The plugin textdomain, for translations.
	 */
	public $plugin_textdomain;

	/**
	 * Post meta above content option.
	 */
	private $post_meta_above_content;

	/**
	 * Remove post meta above content option.
	 */
	private $remove_post_meta_above_content;

	/**
	 * Post meta below content option.
	 */
	private $post_meta_below_content;

	/**
	 * Remove post meta below content option.
	 */
	private $remove_post_meta_below_content;

	/**
	 * Split footer left content option.
	 */
	private $split_footer_left;

	/**
	 * Remove split footer left content option.
	 */
	private $remove_split_footer_left;

	/**
	 * Split footer right content option.
	 */
	private $split_footer_right;

	/**
	 * Remove split footer right content option.
	 */
	private $remove_split_footer_right;

	/**
	 * Display center footer option.
	 */
	private $center_footer;

	/**
	 * Display center footer show option.
	 */
	private $show_center_footer;

	/**
	 * Constructor.
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
	 *
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

			beans_remove_action( 'beans_footer_content' );

			beans_add_smart_action( 'beans_footer', array( $this, 'beans_simple_edits_footer_content' ) );

		} else {

			beans_add_filter( 'beans_footer_credit_text_output', array( $this, 'modify_footer_credit_text_left' ), 9999 );

			beans_add_filter( 'beans_footer_credit_right_text_output', array( $this, 'modify_footer_credit_text_right' ), 9999 );

		}

	}

	/**
	 *
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
	 *
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
	 * @param $output
	 *
	 * @return string
	 */
	function modify_footer_credit_text_left( $output ) {

		if ( $this->remove_split_footer_left ) {

			return '';

		}

		if ( ! $this->split_footer_left ) {

			return $output;

		}

		return do_shortcode( __( $this->split_footer_left, $this->plugin_textdomain ) );

	}

	/**
	 * @param $output
	 *
	 * @return string
	 */
	function modify_footer_credit_text_right( $output ) {

		if ( $this->remove_split_footer_right ) {

			return '';

		}

		if ( ! $this->split_footer_right ) {

			return $output;

		}

		return do_shortcode( __( $this->split_footer_right, $this->plugin_textdomain ) );

//		return beans_output_e(
//			'beans_footer_center_credit_text',
//			do_shortcode( __( $this->split_footer_right, $this->plugin_textdomain ) )
//		);

	}

	/**
	 *
	 */
	function beans_simple_edits_footer_content() {

		beans_open_markup_e( 'beans_footer_credit_hit', 'div', array(
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

}
