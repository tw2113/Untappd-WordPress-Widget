<?php
/**
 * Untappd Venue Checkins Widget.
 * @package Untappd
 * @subpackage Widgets
 * @since 1.3.0
 */

/**
 * Extend our class and create our new widget.
 *
 * @since 1.3.0
 */
class mb_untappd_venue_checkins extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => '',
			'description' => esc_html__( 'Display recent venue Untappd Checkins', 'mb_untappd' ),
		);
		parent::__construct( 'mb_untappd_venue', esc_html__( 'Untappd Recent Venue Checkins', 'mb_untappd' ), $widget_ops );
	}

	/**
	 * Form method.
	 *
	 * @since 1.3.0
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
			'title'        => esc_html__( 'Recent Venue Untappd Checkins', 'mb_untappd' ),
			'venue'        => '',
			'clientID'     => '',
			'clientSecret' => '',
			'limit'        => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title        = trim( strip_tags( $instance['title'] ) );
		$venue        = trim( strip_tags( $instance['venue'] ) );
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
				'label' => esc_html__( 'Venue Numeral ID:', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'venue' ),
				'id'    => $this->get_field_id( 'venue' ),
				'type'  => 'text',
				'value' => $venue,
			)
		);
		echo '<a href="https://wordpress.org/plugins/untappd-checkins-widget/faq/">';
		esc_html_e( 'Help finding venue ID', 'mb_untappd' );
		echo '</a>';

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
				'label' => esc_html__( 'Listing limit (default: 25, max: 25):', 'mb_untappd' ),
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
	 * @since 1.3.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $old_instance Old widget instance.
	 * @return array
	 */
	function update( $new_instance = array(), $old_instance = array() ) {
		$instance                 = $old_instance;
		$instance['title']        = trim( strip_tags( $new_instance['title'] ) );
		$instance['venue']        = trim( strip_tags( $new_instance['venue'] ) );
		$instance['clientID']     = trim( strip_tags( $new_instance['clientID'] ) );
		$instance['clientSecret'] = trim( strip_tags( $new_instance['clientSecret'] ) );
		$instance['limit']        = trim( strip_tags( $new_instance['limit'] ) );

		delete_transient( apply_filters( 'untappd_checkins_venue_filter', 'untappd_venue_checkins_' . $instance['venue'] ) );

		return $instance;
	}

	/**
	 * Widget display method.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args     Widget args.
	 * @param array $instance Widget instance.
	 */
	function widget( $args = array(), $instance = array() ) {

		$untappd_api = get_option( 'mb_untappd_settings', array() );

		$title        = trim( strip_tags( $instance['title'] ) );
		$venue        = trim( strip_tags( $instance['venue'] ) );
		$clientID     = ( ! empty( $untappd_api['client_id'] ) ) ?
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
		if ( empty( $venue ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . esc_html__( 'Please provide a venue ID', 'mb_untappd' ) . '</p>';
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
			/**
			 * Filters the transient name to use.
			 *
			 * @since 1.3.0
			 * @since 1.3.2 Moved filter to dynamic to allow multiple streams.
			 *
			 * @param string $value Transient name.
			 */
			$transient  = apply_filters( 'untappd_checkins_venue_filter', 'untappd_venue_checkins_' . $venue );
			$trans_args = array(
				'transient_name'     => $transient,
				'untappd_venue'      => $venue,
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
					$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array( 'untappd_checkins' ), 'venue' ) );

					$brew_data   = array(
						'brew_list' => $brews->response->checkins->items,
						'classes'   => $classes,
					);
					/**
					 * Filters the markup to use for the brewery widget.
					 *
					 * @since 1.3.0
					 *
					 * @param string $value     Markup to use. Default empty string.
					 * @param array  $brew_data Array of brewery checkin data.
					 */
					$brewery_markup = apply_filters( 'untappd_venue_markup', '', $brew_data );

					echo ( '' !== $brewery_markup ) ? $brewery_markup : $this->brew_list( $brew_data );

				} else {
					echo '<p>' . esc_html__( 'Nothing to display yet', 'mb_untappd' ) . '</p>';
				}
			}
		}
		echo $args['after_widget'];
	}

	/**
	 * Render our unordered list for our brews.
	 *
	 * @since 1.3.0
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
			$venue_or_brewery = $this->get_venue_or_brewery( $pint );
			$brew_list_listing .= sprintf(
				__( '%s had a %s %s on %s %s', 'mb_untappd' ),
				$pint->user->first_name . ' ' . $pint->user->last_name,
				'<a href="https://untappd.com/beer/' . $pint->beer->bid . '">' . $pint->beer->beer_name . '</a>',
				$venue_or_brewery,
				date( get_option( 'date_format' ), strtotime( $pint->created_at ) ),
				sprintf( __( '%sDetails%s', 'mb_untappd' ),
					'<a href="https://untappd.com/user/' . $pint->user->user_name . '/checkin/' . $pint->checkin_id . '" title="' . esc_attr__( 'View checkin details on Untappd\'s website', 'mb_untappd' ) . '">',
					'</a>'
				)
			);
		}

		$brew_list_end = '</ul>';

		return $brew_list_start . $brew_list_listing . $brew_list_end;
	}

	/**
	 * Determine if a brewery or venue.
	 *
	 * @since 1.3.0
	 *
	 * @param string|object $pint Checkin object.
	 * @return string
	 */
	public function get_venue_or_brewery( $pint = '' ) {
		if ( empty( $pint->venue ) ) {
			return sprintf( __( 'by %s', 'mb_untappd' ),
				'<a href="https://untappd.com/brewery/' . $pint->brewery->brewery_id . '">' . $pint->brewery->brewery_name . '</a>'
			);
		}
		return sprintf( __( 'at %s', 'mb_untappd' ),
			'<a href="https://untappd.com/venue/' . $pint->venue->venue_id . '">' . $pint->venue->venue_name . '</a>'
		);
	}

	/**
	 * Retrieve our Untappd API data, from a transient first, if available
	 *
	 * @since 1.3.0
	 *
	 * @param array $trans_args Array of transient name, username, Untappd API credentials, and listing limit.
	 * @return array                json-decoded data array from Untappd
	 */
	public function getTransient( $trans_args = array() ) {
		$brew = get_transient( $trans_args['transient_name'] );
		if ( false === $brew ) {
			$venue = new MB_Untappd_Venue_Checkins_API(
				array(
					'client_id'     => $trans_args['untappd_api_ID'],
					'client_secret' => $trans_args['untappd_api_secret'],
					'username'      => $trans_args['untappd_venue'],
				)
			);

			$new_venue = $venue->get_checkins(
				array(
					'limit'   => $trans_args['untappd_limit'],
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
			if ( 200 === wp_remote_retrieve_response_code( $new_venue ) ) {
				$brew = json_decode( wp_remote_retrieve_body( $new_venue ) );
				set_transient( $trans_args['transient_name'], $brew, $duration );
			} else {
				if ( current_user_can( 'manage_options' ) ) {
					if ( is_array( $new_venue ) && isset( $new_venue['error'] ) ) {
						$message = $new_venue['error'];
					} else {
						$message = $new_venue->get_error_message();
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
	 * @since 1.3.0
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
