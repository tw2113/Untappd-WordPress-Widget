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
			'description' => __( 'Display recent Untappd Checkins', 'mb_untappd' )
			);
		$this->WP_Widget( 'mb_untappd', __( 'Untappd Recent Checkins', 'mb_untappd' ), $widget_ops );
	}

	 //build the widget settings form
	function form($instance) {
		$defaults = array(
			'title' => __( 'My recent Untappd Checkins', 'mb_untappd' ),
			'username' => '',
			'clientID' => '',
			'clientSecret' => '',
			'limit'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = trim( $instance['title'] );
		$username = trim( $instance['username'] );
		$clientID = trim( $instance['clientID'] );
		$clientSecret = trim( $instance['clientSecret'] );
		$limit = trim( $instance['limit'] );
		?>
			<p><label><?php __('Title:', 'mb_untappd' ); ?><input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>"  type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
			<p><label><?php __('Username:', 'mb_untappd' ); ?><input class="widefat" name="<?php echo $this->get_field_name( 'username' ); ?>"  type="text" value="<?php echo esc_attr( $username ); ?>" /></label></p>
			<p><label><?php __('Client Key:', 'mb_untappd' ); ?><input class="widefat" name="<?php echo $this->get_field_name( 'clientID' ); ?>"  type="text" value="<?php echo esc_attr( $clientID ); ?>" /></label></p>
			<p><label><?php __('Client Secret:', 'mb_untappd' ); ?><input class="widefat" name="<?php echo $this->get_field_name( 'clientSecret' ); ?>"  type="text" value="<?php echo esc_attr( $clientSecret ); ?>" /></label></p>
			<p><label><?php __('Limit (default: 25, max: 50):', 'mb_untappd' ); ?> <input class="widefat" name="<?php echo $this->get_field_name( 'limit' ); ?>"  type="text" value="<?php echo esc_attr( $limit ); ?>" /></label></p>
		<?php
	}

	//save the widget settings
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['username'] = $new_instance['username'];
		$instance['clientID'] = $new_instance['clientID'];
		$instance['clientSecret'] = $new_instance['clientSecret'];
		$instance['limit'] = $new_instance['limit'];

		return $instance;
	}

	//display the widget
	function widget($args, $instance) {
		extract($args);

		$username = $instance['username'];
		$clientID = $instance['clientID'];
		$clientSecret = $instance['clientSecret'];
		$limit = $instance['limit'];
		$error = false;

		echo $before_widget;

		if ( !empty( $instance['title'] ) ) { echo $before_title . $instance['title'] . $after_title; }
		$limit = ( empty( $instance['limit'] ) ) ? 25 : $instance['limit'];

		if ( empty( $username ) ) {
			$error = true;
			echo '<p>Please provide a user ID</p>';
		}
		if ( empty( $clientID ) ) {
			$error = true;
			echo '<p>Please provide a client ID provided by Untappd</p>';
		}
		if ( empty( $clientSecret ) ) {
			$error = true;
			echo '<p>Please provide a client Secret provided by Untappd</p>';
		}

		if ( false === $error ) {
			$transient = apply_filters( 'untapped_checkins_filter', 'untapped_checkins' );
			$brews = $this->getTransient($transient, $username, $clientID, $clientSecret, $limit );

			if ( is_wp_error( $brews ) ) {
				echo $brews->get_error_message();
			} else {
				echo '<ul class="untappd_checkins">';
				foreach ( $brews->response->checkins->items as $pint ) {
					echo '<li>I had a <a href="https://untappd.com/beer/' . $pint->beer->bid . '">' . $pint->beer->beer_name . '</a> by <a href="https://untappd.com/brewery/' . $pint->brewery->brewery_id . '">' . $pint->brewery->brewery_name . '</a> at ' . date( get_option('date_format'), strtotime( $pint->created_at ) ) . ' <a href="https://untappd.com/user/' . $pint->user->user_name . '/checkin' . $pint->checkin_id . '" title="View checkin details on Untappd\'s website">Details</a>';
				}
				echo '</ul>';
			}
		}
		//
		echo $after_widget;
	}

	public function getTransient( $transient, $username, $clientID, $clientSecret, $limit ) {
		if ( false === ( $brew = get_transient( $transient ) ) ) {
			$url = 'http://api.untappd.com/v4/user/checkins/' . $username . '?client_id=' . $clientID . '&client_secret=' . $clientSecret . '&limit=' . $limit;
			$brew = json_decode( wp_remote_retrieve_body( wp_remote_get( $url ) ) );
			$duration = apply_filters( 'untapped_transient_duration', 60*10 );
			set_transient( $transient, $brew, $duration );
		}
		return $brew;
	}
}
// Have a brewtastic day!
$BeerMe = new mb_untappd;