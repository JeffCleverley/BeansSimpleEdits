<?php
/**
 * Loads the Beans Simple Edits Plugin
 *
 * @package    LearningCurve\BeansSimpleEdits
 * @since      1.0
 * @author     Jeff Cleverley
 * @link       https://learningcurve.xyz
 * @license    GNU-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:     Beans Simple Edits
 * Plugin URI:      http://github.com/JeffCleverley/BeansSimpleEdits
 * Description:     Beans Simple Edits lets you edit three commonly modified areas in any Beans Child theme: the post-info, the post-meta, and the footer area.
 * Version:         1.0
 * Author:          Jeff Cleverley
 * Author URI:      https://learningcurve.xyz
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     beans-simple-edits
 * Requires WP:     4.6
 * Requires PHP:    5.6
 */

/**
 * Special thanks to:
 *
 * This plugin was inspired by StudioPress's Genesis Simple Edits Plugin, one of the most popular plugins for Genesis Developers and Implementors.
 * Standing on the shoulders of these Giants of Development:
 * StudioPress, Nathan Rice, and Ron Rennick - Thank you so much!
 *
 * Genesis Simple Edit WordPress Org Links:
 * - https://wordpress.org/plugins/genesis-simple-edits/
 * - https://profiles.wordpress.org/studiopress
 * - https://profiles.wordpress.org/nathanrice
 * - https://profiles.wordpress.org/wpmuguru
 */

namespace LearningCurve\BeansSimpleEdits;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Hello, Hello, Hello, what\'s going on here then?' );
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\deactivate_when_beans_not_activated_theme' );
add_action( 'switch_theme', __NAMESPACE__ . '\deactivate_when_beans_not_activated_theme' );
/**
 * If Beans is not the activated theme, deactivate this plugin and pop a die message when not switching themes.
 *
 * @since 1.0
 *
 * @return void
 */
function deactivate_when_beans_not_activated_theme() {
	// If Beans is the active theme, bail out.
	$theme = wp_get_theme();
	if ( in_array( $theme->template, array( 'beans', 'tm-beans' ), true ) ) {
		return;
	}

	deactivate_plugins( plugin_basename( __FILE__ ) );

	if ( current_filter() !== 'switch_theme' ) {
		$message = __( 'Sorry, you can\'t activate this plugin unless the <a href="https://www.getbeans.io" target="_blank">Beans</a> framework is installed and a child theme is activated.', 'beans-visual-hook-guide' );
		wp_die( wp_kses_post( $message ) );
	}
}

/**
 * Autoload the plugin's files.
 *
 * @since 1.0
 *
 * @return void
 */
function autoload_files() {
	$files = array(
		'class-beans-simple-edits.php',
	);

	foreach ( $files as $file ) {
		require __DIR__ . '/src/' . $file;
	}
}

/**
 * Launch the plugin.
 *
 * @since 1.0
 *
 * @return void
 */
function launch() {
	autoload_files();
}

launch();