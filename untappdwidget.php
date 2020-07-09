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
 * Version: 1.3.3
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
