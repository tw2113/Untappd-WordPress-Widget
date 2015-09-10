<?php
/*
Plugin Name: Untappd WordPress Widgets
Plugin URI: http://michaelbox.net/
Description: Displays recent Untappd checkins for a provided user.
Version: 1.2.0
Author: Michael Beckwith
Author URI: http://michaelbox.net
License: WTFPL
Text Domain: mb_untappd
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
 * register widgets
 */
function mb_untappd_register_widgets() {
	register_widget( 'mb_untappd_user_checkins' );
	register_widget( 'mb_untappd_brewery_checkins' );
}
add_action( 'widgets_init', 'mb_untappd_register_widgets' );

/**
 * Register and load our textdomain
 */
function mb_untappd_widget_init() {
	load_plugin_textdomain( 'mb_untappd', false, dirname( plugin_basename( __FILE__ ) . '/languages/' ) );

	require_once 'widgets/user_checkins.php';
	require_once 'widgets/brewery_checkins.php';
}
add_action( 'plugins_loaded', 'mb_untappd_widget_init' );
