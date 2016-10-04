<?php

/**
 * Extend our class and create our new widget
 */
class mb_untappd_brewery_checkins extends WP_Widget {

	/**
	 * Constructor.
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => '',
			'description' => __( 'Display recent Brewery Untappd Checkins', 'mb_untappd' )
		);
		parent::__construct( 'mb_untappd_brewery', __( 'Untappd Recent Brewery Checkins', 'mb_untappd' ), $widget_ops );
	}

	/**
	 * Form method.
	 *
	 * @since 1.2.0
	 *
	 * @param array $instance Widget instance.
	 * @return void
	 */
	function form( $instance = array() ) {
		$defaults = array(
			'title'        => __( 'Recent Brewery Untappd Checkins', 'mb_untappd' ),
			'brewery'     => '',
			'clientID'     => '',
			'clientSecret' => '',
			'limit'        => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title        = trim( strip_tags( $instance['title'] ) );
		$brewery      = trim( strip_tags( $instance['brewery'] ) );
		$clientID     = trim( strip_tags( $instance['clientID'] ) );
		$clientSecret = trim( strip_tags( $instance['clientSecret'] ) );
		$limit        = trim( strip_tags( $instance['limit'] ) );

		$this->form_input(
			array(
				'label' => __( 'Title:', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'title' ),
				'id'    => $this->get_field_id( 'title' ),
				'type'  => 'text',
				'value' => $title
			)
		);

		$this->form_input(
			array(
				'label' => __( 'Brewery Numeral ID:', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'brewery' ),
				'id'    => $this->get_field_id( 'brewery' ),
				'type'  => 'text',
				'value' => $brewery
			)
		);
		echo '<a href="https://wordpress.org/plugins/untappd-checkins-widget/faq/">';
		_e( 'Help finding brewery ID.', 'mb_untappd' );
		echo '</a>';

		$this->form_input(
			array(
				'label' => __( 'Client Key:', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'clientID' ),
				'id'    => $this->get_field_id( 'clientID' ),
				'type'  => 'text',
				'value' => $clientID
			)
		);

		$this->form_input(
			array(
				'label' => __( 'Client Secret:', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'clientSecret' ),
				'id'    => $this->get_field_id( 'clientSecret' ),
				'type'  => 'text',
				'value' => $clientSecret
			)
		);

		$this->form_input(
			array(
				'label' => __( 'Listing limit (default: 25, max: 50):', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'limit' ),
				'id'    => $this->get_field_id( 'limit' ),
				'type'  => 'text',
				'value' => $limit
			)
		);

	}

	/**
	 * Update method.
	 *
	 * @since 1.2.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $old_instance Old widget instance.
	 * @return array
	 */
	function update( $new_instance = array(), $old_instance = array() ) {
		$instance                 = $old_instance;
		$instance['title']        = trim( strip_tags( $new_instance['title'] ) );
		$instance['brewery']      = trim( strip_tags( $new_instance['brewery'] ) );
		$instance['clientID']     = trim( strip_tags( $new_instance['clientID'] ) );
		$instance['clientSecret'] = trim( strip_tags( $new_instance['clientSecret'] ) );
		$instance['limit']        = trim( strip_tags( $new_instance['limit'] ) );

		return $instance;
	}

	/**
	 * Widget display method.
	 *
	 * @since 1.2.0
	 *
	 * @param array $args     Widget args.
	 * @param array $instance Widget instance.
	 */
	function widget( $args = array(), $instance = array() ) {

		$title        = trim( strip_tags( $instance['title'] ) );
		$brewery      = trim( strip_tags( $instance['brewery'] ) );
		$clientID     = trim( strip_tags( $instance['clientID'] ) );
		$clientSecret = trim( strip_tags( $instance['clientSecret'] ) );
		$limit        = trim( strip_tags( $instance['limit'] ) );
		$error        = false;

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$limit = ( ! empty( $limit ) && is_numeric( $limit ) ) ? absint( $limit ) : '25';

		// These three fields are required to get data out of Untappd.
		if ( empty( $brewery ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . __( 'Please provide a brewery ID', 'mb_untappd' ) . '</p>';
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

		// Lets grab and display some data!
		if ( false === $error ) {
			$transient  = apply_filters( 'untappd_checkins_brewery_filter', 'untappd_brewery_checkins' );
			$trans_args = array(
				'transient_name'     => $transient,
				'untappd_brewery'    => $brewery,
				'untappd_api_ID'     => $clientID,
				'untappd_api_secret' => $clientSecret,
				'untappd_limit'      => $limit
			);
			$brews      = $this->getTransient( $trans_args );

			if ( is_wp_error( $brews ) ) {
				echo $brews->get_error_message();
			} else {
				if ( ! in_array( $brews->meta->code, array( '500', '404' ) ) ) {
					$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array( 'untappd_checkins' ), 'brewery' ) );

					$brew_data   = array(
						'brew_list' => $brews->response->checkins->items,
						'classes'   => $classes
					);
					$brewery_markup = apply_filters( 'untappd_brewery_markup', '', $brew_data );

					echo ( '' !== $brewery_markup ) ? $brewery_markup : $this->brew_list( $brew_data );

				} else {
					echo '<p>' . __( 'Nothing to display yet', 'mb_untapped' ) . '</p>';
				}
			}
		}
		echo $args['after_widget'];
	}

	/**
	 * Render our unordered list for our brews.
	 *
	 * @since 1.2.0
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
	 * @since 1.2.0
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
	 * @since 1.2.0
	 *
	 * @param array $trans_args Array of transient name, username, Untappd API credentials, and listing limit.
	 * @return array                json-decoded data array from Untappd
	 */
	public function getTransient( $trans_args = array() ) {
		if ( false === ( $brew = get_transient( $trans_args['transient_name'] ) ) ) {
			$url      = 'https://api.untappd.com/v4/brewery/checkins/' . $trans_args['untappd_brewery'] . '?client_id=' . $trans_args['untappd_api_ID'] . '&client_secret=' . $trans_args['untappd_api_secret'] . '&limit=' . $trans_args['untappd_limit'];
			$brew     = json_decode( wp_remote_retrieve_body( wp_remote_get( $url ) ) );
			$duration = apply_filters( 'untappd_transient_duration', 60 * 10 );

			// Save only if we get a good response back.
			if ( '200' == $brew->meta->code ) {
				set_transient( $trans_args['transient_name'], $brew, $duration );
			}
		}

		return $brew;
	}

	/**
	 * Render a form input for use in our form input.
	 *
	 * @since 1.2.0
	 *
	 * @param array $args Array of argus to use with the markup.
	 * @return void
	 */
	function form_input( $args = array() ) {
		$label = esc_attr( $args['label'] );
		$name  = esc_attr( $args['name'] );
		$id    = esc_attr( $args['id'] );
		$type  = esc_attr( $args['type'] );
		$value = esc_attr( $args['value'] );

		printf(
			'<p><label for="%s">%s</label><input type="%s" class="widefat" name="%s" id="%s" value="%s" /></p>',
			$id,
			$label,
			$type,
			$name,
			$id,
			$value
		);
	}
}
