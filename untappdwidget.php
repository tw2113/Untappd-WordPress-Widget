<?php
/*
Plugin Name: Untappd User Stream widget
Plugin URI: http://michaelbox.net/
Description: Displays recent Untappd checkins for a provided user.
Version: 1.0
Author: Michael Beckwith
Author URI: http://michaelbox.net
License: WTFPL
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

add_action( 'widgets_init', 'rbc_register_widgets' );
/**
 * register widgets
 */
function rbc_register_widgets() {
	register_widget( 'mb_untappd' );
}

class mb_untappd extends WP_Widget {

	//process the new widget
	function mb_untappd() {
		$widget_ops = array(
			'classname' => '',
			'description' => 'Display recent Untappd Checkins'
			);
		$this->WP_Widget( 'mb_untappd', 'Untappd Recent Checkins', $widget_ops );
	}

	 //build the widget settings form
	function form($instance) {
		$defaults = array(
			'title' => 'Countdown to Camp',
			'apikey' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = $instance['title'];
		$apikey = $instance['apikey'];
		?>
			<p><label>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>"  type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
			<p><label>API Key: <input class="widefat" name="<?php echo $this->get_field_name( 'apikey' ); ?>"  type="text" value="<?php echo esc_attr( $apikey ); ?>" /></label></p>
		<?php
	}

	//save the widget settings
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['apikey'] = $new_instance['apikey'];

		return $instance;
	}

	//display the widget
	function widget($args, $instance) {
		extract($args);

		$key = $instance['apikey'];

		echo $before_widget;
		if (!empty($instance['title'])) { echo $before_title . $instance['title'] . $after_title; }

		$beers = $this->getTransient('untappd_checkins');

		echo $after_widget;
	}

	public function getTransient( $transient ) {
		if ( false === ( $brew = get_transient( $transient ) ) ) {
			$brew = json_decode( wp_remote_retrieve_body( wp_remote_get( 'FILL ME IN' ) ) );
			set_transient( $transient, $brew, 60*60 );
		}
		return $brew;
	}
}
// Have a nice day!
$BeerMe = new mb_untappd;