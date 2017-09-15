<?php

/**
 * Extend our class and create our new widget.
 */
class mb_untappd_user_profile extends WP_Widget {

	/**
	 * Constructor.
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => '',
			'description' => esc_html__( 'Display Untappd user profile', 'mb_untappd' ),
		);
		parent::__construct( 'mb_untappd_user_profile', esc_html__( 'Untappd User Profile', 'mb_untappd' ), $widget_ops );
	}

	/**
	 * Form method.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Widget instance.
	 * @return void
	 */
	function form( $instance = array() ) {

		$untappd_api = get_option( 'mb_untappd_settings', array() );

		// Conditionally show our notification.
		if ( empty( $untappd_api['client_id'] ) || empty( $untappd_api['client_secret'] ) ) {
			mb_untappd_settings_page_notification();
		}

		$defaults = array(
			'title'              => esc_html__( 'My Untappd profile', 'mb_untappd' ),
			'username'           => '',
			'showavatar'         => '',
			'showlocation'       => '',
			'showtotal_badges'   => '',
			'showtotal_checkins' => '',
			'showtotal_beers'    => '',
			'showtotal_friends'  => '',
			'clientID'           => '',
			'clientSecret'       => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title              = trim( strip_tags( $instance['title'] ) );
		$username           = trim( strip_tags( $instance['username'] ) );
		$showavatar         = trim( strip_tags( $instance['showavatar'] ) );
		$showlocation       = trim( strip_tags( $instance['showlocation'] ) );
		$showtotal_badges   = trim( strip_tags( $instance['showtotal_badges'] ) );
		$showtotal_checkins = trim( strip_tags( $instance['showtotal_checkins'] ) );
		$showtotal_beers    = trim( strip_tags( $instance['showtotal_beers'] ) );
		$showtotal_friends  = trim( strip_tags( $instance['showtotal_friends'] ) );
		$clientID           = trim( strip_tags( $instance['clientID'] ) );
		$clientSecret       = trim( strip_tags( $instance['clientSecret'] ) );

		$this->form_input(
			array(
				'label' => esc_html__( 'Title:', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'title' ),
				'id'    => $this->get_field_id( 'title' ),
				'type'  => 'text',
				'value' => $title,
			)
		);

		$this->form_input(
			array(
				'label' => esc_html__( 'Username:', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'username' ),
				'id'    => $this->get_field_id( 'username' ),
				'type'  => 'text',
				'value' => $username,
			)
		);
		?>
		<h3>Options</h3>
		<ul>
		<li><?php
		$this->form_input_check(
			array(
				'label'   => esc_html__( 'Show User Avatar', 'mb_untappd' ),
				'name'    => $this->get_field_name( 'showavatar' ),
				'id'      => $this->get_field_id( 'showavatar' ),
				'value'   => 'on',
				'checked' => $showavatar,
				'default' => 'on',
			)
		);
		?>
		</li>
		<li><?php
			$this->form_input_check(
				array(
					'label'   => esc_html__( 'Show User Location', 'mb_untappd' ),
					'name'    => $this->get_field_name( 'showlocation' ),
					'id'      => $this->get_field_id( 'showlocation' ),
					'value'   => 'on',
					'checked' => $showlocation,
					'default' => 'on',
				)
			);
			?>
		</li>
		<li><?php
			$this->form_input_check(
				array(
					'label'   => esc_html__( 'Show Total Checkins', 'mb_untappd' ),
					'name'    => $this->get_field_name( 'showtotal_checkins' ),
					'id'      => $this->get_field_id( 'showtotal_checkins' ),
					'value'   => 'on',
					'checked' => $showtotal_checkins,
					'default' => 'on',
				)
			);
			?>
		</li>
		<li><?php
			$this->form_input_check(
				array(
					'label'   => esc_html__( 'Show Total Beers', 'mb_untappd' ),
					'name'    => $this->get_field_name( 'showtotal_beers' ),
					'id'      => $this->get_field_id( 'showtotal_beers' ),
					'value'   => 'on',
					'checked' => $showtotal_beers,
					'default' => 'on',
				)
			);
			?>
		</li>
		<li><?php
			$this->form_input_check(
				array(
					'label'   => esc_html__( 'Show Total Badges', 'mb_untappd' ),
					'name'    => $this->get_field_name( 'showtotal_badges' ),
					'id'      => $this->get_field_id( 'showtotal_badges' ),
					'value'   => 'on',
					'checked' => $showtotal_badges,
					'default' => 'on',
				)
			);
			?>
		</li>
		<li><?php
			$this->form_input_check(
				array(
					'label'   => esc_html__( 'Show Total Friends', 'mb_untappd' ),
					'name'    => $this->get_field_name( 'showtotal_friends' ),
					'id'      => $this->get_field_id( 'showtotal_friends' ),
					'value'   => 'on',
					'checked' => $showtotal_friends,
					'default' => 'on',
				)
			);
			?>
		</li>
		</ul>

		<?php
		if ( empty( $untappd_api['client_id'] ) || empty( $untappd_api['client_secret'] ) ) {
			$this->form_input(
				array(
					'label' => esc_html__( 'Client Key:', 'mb_untappd' ),
					'name'  => $this->get_field_name( 'clientID' ),
					'id'    => $this->get_field_id( 'clientID' ),
					'type'  => 'text',
					'value' => $clientID,
				)
			);

			$this->form_input(
				array(
					'label' => esc_html__( 'Client Secret:', 'mb_untappd' ),
					'name'  => $this->get_field_name( 'clientSecret' ),
					'id'    => $this->get_field_id( 'clientSecret' ),
					'type'  => 'text',
					'value' => $clientSecret,
				)
			);
		}
	}

	/**
	 * Update method.
	 * @since 1.0.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $old_instance Old widget instance.
	 * @return array
	 */
	function update( $new_instance = array(), $old_instance = array() ) {
		$instance                       = $old_instance;
		$instance['title']              = trim( strip_tags( $new_instance['title'] ) );
		$instance['username']           = trim( strip_tags( $new_instance['username'] ) );
		$instance['showavatar']         = trim( strip_tags( $new_instance['showavatar'] ) );
		$instance['showlocation']       = trim( strip_tags( $new_instance['showlocation'] ) );
		$instance['showtotal_badges']   = trim( strip_tags( $new_instance['showtotal_badges'] ) );
		$instance['showtotal_checkins'] = trim( strip_tags( $new_instance['showtotal_checkins'] ) );
		$instance['showtotal_beers']    = trim( strip_tags( $new_instance['showtotal_beers'] ) );
		$instance['showtotal_friends']  = trim( strip_tags( $new_instance['showtotal_friends'] ) );
		$instance['clientID']           = trim( strip_tags( $new_instance['clientID'] ) );
		$instance['clientSecret']       = trim( strip_tags( $new_instance['clientSecret'] ) );

		return $instance;
	}

	/**
	 * Widget display method.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget instance.
	 */
	function widget( $args = array(), $instance = array() ) {

		$untappd_api = get_option( 'mb_untappd_settings', array() );

		$title              = trim( strip_tags( $instance['title'] ) );
		$username           = trim( strip_tags( $instance['username'] ) );
		$showavatar         = trim( strip_tags( $instance['showavatar'] ) );
		$showlocation       = trim( strip_tags( $instance['showlocation'] ) );
		$showtotal_badges   = trim( strip_tags( $instance['showtotal_badges'] ) );
		$showtotal_checkins = trim( strip_tags( $instance['showtotal_checkins'] ) );
		$showtotal_beers    = trim( strip_tags( $instance['showtotal_beers'] ) );
		$showtotal_friends  = trim( strip_tags( $instance['showtotal_friends'] ) );

		$conditional_data = array_filter(
			array(
				'avatar'         => $showavatar,
				'location'       => $showlocation,
				'total_badges'   => $showtotal_badges,
				'total_checkins' => $showtotal_checkins,
				'total_beers'    => $showtotal_beers,
				'total_friends'  => $showtotal_friends,
			)
		);

		$clientID = ( ! empty( $untappd_api['client_id'] ) ) ?
			trim( strip_tags( $untappd_api['client_id'] ) ) :
			trim( strip_tags( $instance['clientID'] ) );
		$clientSecret = ( ! empty( $untappd_api['client_secret'] ) ) ?
			trim( strip_tags( $untappd_api['client_secret'] ) ) :
			trim( strip_tags( $instance['clientSecret'] ) );
		$error        = false;

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// These three fields are required to get data out of Untappd.
		if ( empty( $username ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . esc_html__( 'Please provide a user ID', 'mb_untappd' ) . '</p>';
			}
		}
		if ( empty( $clientID ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . esc_html__( 'Please provide a client ID provided by Untappd', 'mb_untappd' ) . '</p>';
			}
		}
		if ( empty( $clientSecret ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . esc_html__( 'Please provide a client Secret provided by Untappd', 'mb_untappd' ) . '</p>';
			}
		}

		// Lets grab and display some data!
		if ( false === $error ) {
			$transient  = apply_filters( 'untappd_profile_filter', 'untappd_user_profile' );
			$trans_args = array(
				'transient_name'     => $transient,
				'untappd_user'       => $username,
				'untappd_api_ID'     => $clientID,
				'untappd_api_secret' => $clientSecret,
			);
			$profile    = $this->getTransient( $trans_args );

			if ( is_wp_error( $profile ) ) {
				echo $profile->get_error_message();
			} else {
				if ( $profile && ! in_array( $profile->meta->code, array( '500', '404' ) ) ) {
					/**
					 * Filters the list of classes to apply to our widget output.
					 * @since 1.1.0
					 *
					 * @param array  $value Array of classes to use.
					 * @param string $value Check-in type.
					 */
					$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array(
						'untappd-profile',
						'untappd-profile-' . $profile->response->user->user_name,
					), 'profile' ) );

					$profile_data   = array(
						'badge' => $profile->response->user,
						'classes'   => $classes,
					);
					$profile_markup = apply_filters( 'untappd_user_markup', '', $profile_data );

					echo ( '' !== $profile_markup ) ? $profile_markup : $this->profile( $profile_data, $trans_args['untappd_user'] );

				} else {
					echo '<p>' . esc_html__( 'Nothing to display yet', 'mb_untappd' ) . '</p>';
				}
			}
		} // End if().
		echo $args['after_widget'];
	}

	/**
	 * Render our profile.
	 *
	 * @param array  $profile_data Array of data for a badge.
	 * @param string $username   Untappd username.
	 * @return string $value Rendered profile.
	 */
	public function profile( $profile_data = array(), $username ) {
		$profile_start = sprintf(
			'<div class="%s">',
			$profile_data['classes']
		);

		$profile = '';
		$user = $profile_data['badge']->user_name;
		$firstname = $profile_data['badge']->first_name;
		$pic = $profile_data['badge']->user_avatar_hd;
		$location = $profile_data['badge']->location;
		$permalink = $profile_data['badge']->untappd_url;
		$member_since = $profile_data['badge']->date_joined;

		$profile = sprintf(
			'<img class="untappd-user-pic" src="%s" alt="%s" /><p><a href="%s">%s - %s</a><br/>%s</p><ul>%s</ul>',
			esc_attr( $pic ),
			sprintf(
				esc_attr__( 'User profile photo for %s', 'mb_untappd' ),
				$firstname
			),
			esc_attr( $permalink ),
			$user,
			$location,
			sprintf(
				esc_html__( 'Member since: %s', 'mb_untappd' ),
				date( get_option( 'date_format' ), strtotime( $member_since ) )
			),
			$this->get_stats_list( $profile_data['badge']->stats )
		);

		$profile_end = '</div>';

		return $profile_start . $profile . $profile_end;
	}

	public function get_stats_list( $stats ) {
		$stats_list = '';
		foreach ( $stats as $stat => $value ) {
			$stat_type = explode( 'total_', $stat );
			$stats_list .= '<li>' . ucfirst( $stat_type[1] ) . ': ' . $value . '</li>';
		}
		return $stats_list;
	}

	/**
	 * Retrieve our Untappd API data, from a transient first, if available.
	 *
	 * @param array $trans_args Array of transient name, username, Untappd API credentials, and listing limit.
	 * @return array JSON-decoded data array from Untappd
	 */
	public function getTransient( $trans_args = array() ) {
		$profile = get_transient( $trans_args['transient_name'] );
		if ( false === $profile ) {
			$user = new MB_Untappd_User_Profile_API(
				array(
					'client_id'     => $trans_args['untappd_api_ID'],
					'client_secret' => $trans_args['untappd_api_secret'],
					'username'      => $trans_args['untappd_user'],
				)
			);

			$new_profile = $user->get_info();

			/**
			 * Filters the duration to store our transients.
			 * @since 1.0.0
			 *
			 * @param int $value Time in seconds.
			 */
			$duration = apply_filters( 'untappd_transient_duration', 60 * 10 );

			// Save only if we get a good response back.
			if ( 200 === wp_remote_retrieve_response_code( $new_profile ) ) {
				$profile = json_decode( wp_remote_retrieve_body( $new_profile ) );
				set_transient( $trans_args['transient_name'], $profile, $duration );
			} else {
				if ( current_user_can( 'manage_options' ) ) {
					printf(
						esc_html__( 'Admin-only error: %s', 'mb_untappd' ),
						$new_profile->get_error_message()
					);
				}
			}
		}

		return $profile;
	}

	/**
	 * Render a form input for use in our form input.
	 *
	 * @since 1.1.3
	 *
	 * @param array $args Array of argus to use with the markup.
	 * @return void
	 */
	function form_input( $args = array() ) {
		printf(
			'<p><label for="%s">%s</label><input type="%s" class="widefat" name="%s" id="%s" value="%s" /></p>',
			esc_attr( $args['id'] ),
			esc_attr( $args['label'] ),
			esc_attr( $args['type'] ),
			esc_attr( $args['name'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['value'] )
		);
	}
}
