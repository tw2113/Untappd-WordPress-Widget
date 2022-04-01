<?php
/**
 * Plugin loader.
 *
 * @package Untappd WordPress Widget
 * @since 1.0.0
 */

/*
 * Plugin Name: Untappd WordPress Widgets
 * Plugin URI: http://michaelbox.net/
 * Description: Displays recent Untappd checkins for a provided user.
 * Version: 1.4.0
 * Author: Michael Beckwith
 * Author URI: http://michaelbox.net
 * License: WTFPL
 * Text Domain: mb_untappd
 */

/*
		DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
			Version 2, December 2004

 Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>

 Everyone is permitted to copy and distribute verbatim or modified
 copies of this license document, and changing it is allowed as long
 as the name is changed.

			DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

  0. You just DO WHAT THE FUCK YOU WANT TO.

*/


/**
 * Register widgets.
 *
 * @since 1.0.0
 */
function mb_untappd_register_widgets() {
	register_widget( 'mb_untappd_user_checkins' );
	register_widget( 'mb_untappd_brewery_checkins' );
	register_widget( 'mb_untappd_venue_checkins' );
	register_widget( 'mb_untappd_user_badges' );
	register_widget( 'mb_untappd_user_profile' );
}
add_action( 'widgets_init', 'mb_untappd_register_widgets' );

/**
 * Register and load our textdomain.
 */
function mb_untappd_widget_init() {
	load_plugin_textdomain( 'mb_untappd', false, dirname( plugin_basename( __FILE__ ) . '/languages/' ) );

	require_once 'classes/class-mb-untappd-settings.php';
	require_once 'classes/class-mb-untappd-api.php';
	require_once 'classes/class-mb-untappd-badges-api.php';
	require_once 'classes/class-mb-untappd-user-checkins-api.php';
	require_once 'classes/class-mb-untappd-user-profile-api.php';
	require_once 'classes/class-mb-untappd-brewery-checkins-api.php';
	require_once 'classes/class-mb-untappd-venue-checkins-api.php';

	require_once 'widgets/user_checkins.php';
	require_once 'widgets/brewery_checkins.php';
	require_once 'widgets/venue_checkins.php';
	require_once 'widgets/user_badge.php';
	require_once 'widgets/user_profile.php';
}
add_action( 'plugins_loaded', 'mb_untappd_widget_init' );

/**
 * Display a quick message about new settings page.
 *
 * @since 1.3.0
 */
function mb_untappd_settings_page_notification() {
	printf(
		'<p>%s</p>',
		esc_html__( 'Client API keys can now be set on our settings page.', 'mb_untappd' )
	);
}

function mb_untappd_register_blocks() {

	// Define our assets.
	$editor_script   = './build/index.js';
	$editor_style    = './build/index.css';
	$frontend_style  = './build/style-index.css';
	$frontend_script = './build/frontend.js';

	// Verify we have an editor script.
	if ( ! file_exists( plugin_dir_path( __FILE__ ) . $editor_script ) ) {
		wp_die( esc_html__( 'Whoops! You need to run `npm run build` first.', 'mb_untappd' ) );
	}

	// Autoload dependencies and version.
	$asset_file = require plugin_dir_path( __FILE__ ) . './build/index.asset.php';

	// Register editor script.
	wp_register_script(
		'untappd-mb-editor-script',
		plugins_url( $editor_script, __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	// Register editor style.
	if ( file_exists( plugin_dir_path( __FILE__ ) . $editor_style ) ) {
		wp_register_style(
			'untappd-mb-editor-style',
			plugins_url( $editor_style, __FILE__ ),
			[ 'wp-edit-blocks' ],
			filemtime( plugin_dir_path( __FILE__ ) . $editor_style )
		);
	}

	// Register frontend style.
	if ( file_exists( plugin_dir_path( __FILE__ ) . $frontend_style ) ) {
		wp_register_style(
			'untappd-mb-style',
			plugins_url( $frontend_style, __FILE__ ),
			[],
			filemtime( plugin_dir_path( __FILE__ ) . $frontend_style )
		);
	}

	// Register block with WordPress.
	register_block_type( 'untappd-mb-gutenberg/latest-checkins', [
		'editor_script'   => 'untappd-mb-editor-script',
		'editor_style'    => 'untappd-mb-editor-style',
		'style'           => 'untappd-mb-style',
		'render_callback' => 'mb_untappd_latest_checkins_cb',
	] );

	// Register frontend script.
	if ( file_exists( plugin_dir_path( __FILE__ ) . $frontend_script ) ) {
		wp_enqueue_script(
			'untappd-mb-frontend-script',
			plugins_url( $frontend_script, __FILE__ ),
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);
	}
}
add_action( 'init', 'mb_untappd_register_blocks' );

function mb_untappd_latest_checkins_cb( $attributes ) {
	ob_start();
	echo $attributes['title']  . ': ' . $attributes['userName'];
	return ob_get_clean();
}
