<?php
/**
 * Untappd User Checkins Widget.
 *
 * @package Untappd
 * @subpackage Widgets
 * @since 1.0.0
 */

/**
 * Extend our class and create our new widget.
 *
 * @since 1.0.0
 */
class mb_untappd_user_checkins extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => '',
			'description' => esc_html__( 'Display recent user Untappd checkins', 'mb_untappd' ),
		);
		parent::__construct( 'mb_untappd_user', esc_html__( 'Untappd Recent User Checkins', 'mb_untappd' ), $widget_ops );
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
			'title'        => esc_html__( 'My recent Untappd Checkins', 'mb_untappd' ),
			'username'     => '',
			'clientID'     => '',
			'clientSecret' => '',
			'limit'        => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title        = trim( strip_tags( $instance['title'] ) );
		$username     = trim( strip_tags( $instance['username'] ) );
		$clientID     = trim( strip_tags( $instance['clientID'] ) );
		$clientSecret = trim( strip_tags( $instance['clientSecret'] ) );
		$limit        = trim( strip_tags( $instance['limit'] ) );

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

		$this->form_input(
			array(
				'label' => esc_html__( 'Listing limit (default: 25, max: 50):', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'limit' ),
				'id'    => $this->get_field_id( 'limit' ),
				'type'  => 'text',
				'value' => $limit,
			)
		);

	}

	/**
	 * Update method.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $old_instance Old widget instance.
	 * @return array
	 */
	function update( $new_instance = array(), $old_instance = array() ) {
		$instance                 = $old_instance;
		$instance['title']        = trim( strip_tags( $new_instance['title'] ) );
		$instance['username']     = trim( strip_tags( $new_instance['username'] ) );
		$instance['clientID']     = trim( strip_tags( $new_instance['clientID'] ) );
		$instance['clientSecret'] = trim( strip_tags( $new_instance['clientSecret'] ) );
		$instance['limit']        = trim( strip_tags( $new_instance['limit'] ) );

		delete_transient( apply_filters( 'untappd_checkins_filter', 'untappd_checkins_' . $instance['username'] ) );

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

		$title        = trim( strip_tags( $instance['title'] ) );
		$username     = trim( strip_tags( $instance['username'] ) );
		$clientID = ( ! empty( $untappd_api['client_id'] ) ) ?
			trim( strip_tags( $untappd_api['client_id'] ) ) :
			trim( strip_tags( $instance['clientID'] ) );
		$clientSecret = ( ! empty( $untappd_api['client_secret'] ) ) ?
			trim( strip_tags( $untappd_api['client_secret'] ) ) :
			trim( strip_tags( $instance['clientSecret'] ) );
		$limit        = trim( strip_tags( $instance['limit'] ) );
		$error        = false;

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$limit = ( ! empty( $limit ) && is_numeric( $limit ) ) ? absint( $limit ) : '25';

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
			$transient  = apply_filters( 'untappd_checkins_filter', 'untappd_checkins_' . $username );
			$trans_args = array(
				'transient_name'     => $transient,
				'untappd_user'       => $username,
				'untappd_api_ID'     => $clientID,
				'untappd_api_secret' => $clientSecret,
				'untappd_limit'      => $limit,
			);
			$brews      = $this->getTransient( $trans_args );

			if ( is_wp_error( $brews ) ) {
				echo $brews->get_error_message();
			} else {
				if ( $brews && ! in_array( $brews->meta->code, array( '500', '404' ) ) ) {
					/**
					 * Filters the list of classes to apply to our widget output.
					 *
					 * @since 1.1.0
					 *
					 * @param array  $value Array of classes to use.
					 * @param string $value Check-in type.
					 */
					$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array( 'untappd_checkins' ), 'user' ) );

					$brew_data   = array(
						'brew_list' => $brews->response->checkins->items,
						'classes'   => $classes,
					);
					$user_markup = apply_filters( 'untappd_user_markup', '', $brew_data );

					echo ( '' !== $user_markup ) ? $user_markup : $this->brew_list( $brew_data );

				} else {
					echo '<p>' . esc_html__( 'Nothing to display yet', 'mb_untappd' ) . '</p>';
				}
			}
		} // End if().
		echo $args['after_widget'];
	}

	/**
	 * Render our unordered list for our brews.
	 *
	 * @since 1.0.0
	 *
	 * @param array $brew_data Array of data for a specific checkin.
	 * @return string $value Rendered list of brews.
	 */
	public function brew_list( $brew_data = array() ) {
		$brew_list_start = sprintf(
			'<ul class="%s">',
			$brew_data['classes']
		);

		$brew_list_listing = '';
		foreach ( $brew_data['brew_list'] as $pint ) {
			$brew_list_listing .= '<li>';
			$brew_list_listing .= sprintf(
				__( 'I had a %s by %s on %s %s', 'mb_untappd' ),
				'<a href="https://untappd.com/beer/' . $pint->beer->bid . '">' . $pint->beer->beer_name . '</a>',
				'<a href="https://untappd.com/brewery/' . $pint->brewery->brewery_id . '">' . $pint->brewery->brewery_name . '</a>',
				date( get_option( 'date_format' ), strtotime( $pint->created_at ) ),
				sprintf( __( '%sDetails%s', 'mb_untappd' ),
					'<a href="https://untappd.com/user/' . $pint->user->user_name . '/checkin/' . $pint->checkin_id . '" title="' . esc_attr__( "View checkin details on Untappd's website", 'mb_untappd' ) . '">',
					'</a>'
				)
			);
		}

		$brew_list_end = '</ul>';

		return $brew_list_start . $brew_list_listing . $brew_list_end;
	}

	/**
	 * Retrieve our Untappd API data, from a transient first, if available.
	 *
	 * @since 1.0.0
	 *
	 * @param array $trans_args Array of transient name, username, Untappd API credentials, and listing limit.
	 * @return array JSON-decoded data array from Untappd
	 */
	public function getTransient( $trans_args = array() ) {
		$brew = get_transient( $trans_args['transient_name'] );
		if ( false === $brew ) {
			$user = new MB_Untappd_User_Checkins_API(
				array(
					'client_id'     => $trans_args['untappd_api_ID'],
					'client_secret' => $trans_args['untappd_api_secret'],
					'username' => $trans_args['untappd_user'],
				)
			);

			$new_brew = $user->get_checkins(
				array(
					'limit'    => $trans_args['untappd_limit'],
				)
			);

			/**
			 * Filters the duration to store our transients.
			 *
			 * @since 1.0.0
			 *
			 * @param int $value Time in seconds.
			 */
			$duration = apply_filters( 'untappd_transient_duration', 60 * 10 );

			// Save only if we get a good response back.
			if ( 200 === wp_remote_retrieve_response_code( $new_brew ) ) {
				$brew = json_decode( wp_remote_retrieve_body( $new_brew ) );
				set_transient( $trans_args['transient_name'], $brew, $duration );
			} else {
				if ( current_user_can( 'manage_options' ) ) {
					if ( is_array( $new_brew ) && isset( $new_brew['error'] ) ) {
						$message = $new_brew['error'];
					} else {
						$message = $new_brew->get_error_message();
					}

					printf(
						esc_html__( 'Admin-only error: %s', 'mb_untappd' ),
						$message
					);
				}
			}
		}

		return $brew;
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
