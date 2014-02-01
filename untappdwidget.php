<?php
/*
Plugin Name: Untappd User Stream widget
Plugin URI: http://michaelbox.net/
Description: Displays recent Untappd checkins for a provided user.
Version: 1.0.1
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

add_action( 'widgets_init', 'mb_register_widgets' );
/**
 * register widgets
 */
function mb_register_widgets() {
	register_widget( 'mb_untappd' );
}
add_action('plugins_loaded', 'mb_untappd_init');
/**
 * Register and load our textdomain
 */
function mb_untappd_init() {
  load_plugin_textdomain( 'mb_untappd', false, dirname( plugin_basename( __FILE__ ) ) );
}

/**
 * Extend our class and create our new widget
 */
class mb_untappd extends WP_Widget {

	//process the new widget
	function __construct() {
		$widget_ops = array( 'classname' => '', 'description' => __( 'Display recent Untappd Checkins', 'mb_untappd' ) );
		parent::__construct( 'mb_untappd', __( 'Untappd Recent Checkins', 'mb_untappd' ), $widget_ops );
	}

	//build the widget settings form
	function form( $instance ) {
		$defaults = array(
            'title'             => __( 'My recent Untappd Checkins', 'mb_untappd' ),
            'username'          => '',
            'clientID'          => '',
            'clientSecret'      => '',
            'limit'             => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = trim( strip_tags( $instance['title'] ) );
		$username = trim( strip_tags( $instance['username'] ) );
		$clientID = trim( strip_tags( $instance['clientID'] ) );
		$clientSecret = trim( strip_tags( $instance['clientSecret'] ) );
		$limit = trim( strip_tags( $instance['limit'] ) );
		?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e('Title:', 'mb_untappd' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php _e('Username:', 'mb_untappd' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>"  type="text" value="<?php echo esc_attr( $username ); ?>" />
			</p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'clientID' ) ); ?>"><?php _e('Client Key:', 'mb_untappd' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'clientID' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'clientID' ) ); ?>"  type="text" value="<?php echo esc_attr( $clientID ); ?>" />
			</p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'clientSecret' ) ); ?>"><?php _e('Client Secret:', 'mb_untappd' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'clientSecret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'clientSecret' ) ); ?>"  type="text" value="<?php echo esc_attr( $clientSecret ); ?>" />
			</p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e('Limit (default: 25, max: 50):', 'mb_untappd' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>"  type="text" value="<?php echo esc_attr( $limit ); ?>" />
			</p>
		<?php
	}

	//save the widget settings
	function update( $new_instance, $old_instance ) {
        $instance                   = $old_instance;
        $instance['title']          = trim( strip_tags( $new_instance['title'] ) );
        $instance['username']       = trim( strip_tags( $new_instance['username'] ) );
        $instance['clientID']       = trim( strip_tags( $new_instance['clientID'] ) );
        $instance['clientSecret']   = trim( strip_tags( $new_instance['clientSecret'] ) );
        $instance['limit']          = trim( strip_tags( $new_instance['limit'] ) );

		return $instance;
	}

	//display the widget
	function widget( $args, $instance ) {
		extract( $args );

        $title          = trim( strip_tags( $instance['title'] ) );
        $username       = trim( strip_tags( $instance['username'] ) );
        $clientID       = trim( strip_tags( $instance['clientID'] ) );
        $clientSecret   = trim( strip_tags( $instance['clientSecret'] ) );
        $limit          = trim( strip_tags( $instance['limit'] ) );
        $error          = false;

		echo $before_widget;

		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		$limit = ( !empty( $limit ) && is_numeric( $limit ) ) ? absint( $limit ) : '25';

		/*
		These three fields are required to get data out of Untappd.
		 */
		if ( empty( $username ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . __( 'Please provide a user ID', 'mb_untappd' ) . '</p>';
			}
		}
		if ( empty( $clientID ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . __( 'Please provide a client ID provided by Untappd', 'mb_untappd' ) . '</p>';
			}
		}
		if ( empty( $clientSecret ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . __( 'Please provide a client Secret provided by Untappd', 'mb_untappd' ) . '</p>';
			}
		}

		/*
		Lets grab and display some data!
		 */
		if ( false === $error ) {
			$transient = apply_filters( 'untappd_checkins_filter', 'untappd_checkins' );
			$brews = $this->getTransient( $transient, $username, $clientID, $clientSecret, $limit );

			if ( is_wp_error( $brews ) ) {
				echo $brews->get_error_message();
			} else {
				$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array( 'untappd_checkins' ) ) );
				echo '<ul class="' . $classes . '">';
				foreach ( $brews->response->checkins->items as $pint ) {
					echo '<li>I had a <a href="https://untappd.com/beer/' . $pint->beer->bid . '">' . $pint->beer->beer_name . '</a> by <a href="https://untappd.com/brewery/' . $pint->brewery->brewery_id . '">' . $pint->brewery->brewery_name . '</a> at ' . date( get_option('date_format'), strtotime( $pint->created_at ) ) . ' <a href="https://untappd.com/user/' . $pint->user->user_name . '/checkin' . $pint->checkin_id . '" title="' . esc_attr__( 'View checkin details on Untappd\'s website', 'mb_untappd' ) . '">Details</a>';
				}
				echo '</ul>';
			}
		}
		//
		echo $after_widget;
	}

	/**
	 * Retrieve our Untappd API data, from a transient first, if available
	 * @param  string $transient    the transient key to use
	 * @param  string $username     the Untappd username to retrieve
	 * @param  string $clientID     Untappd API Client ID key
	 * @param  string $clientSecret Untappd API Client Secret key
	 * @param  string $limit        How many recent checkins to retrieve
	 * @return array               	json-decoded data array from Untappd
	 */
	public function getTransient( $transient, $username, $clientID, $clientSecret, $limit ) {
		if ( false === ( $brew = get_transient( $transient ) ) ) {
			$url = 'http://api.untappd.com/v4/user/checkins/' . $username . '?client_id=' . $clientID . '&client_secret=' . $clientSecret . '&limit=' . $limit;
			$brew = json_decode( wp_remote_retrieve_body( wp_remote_get( $url ) ) );
			$duration = apply_filters( 'untappd_transient_duration', 60*10 );
			set_transient( $transient, $brew, $duration );
		}
		return $brew;
	}
}
// Have a brewtastic day!
$BeerMe = new mb_untappd;
