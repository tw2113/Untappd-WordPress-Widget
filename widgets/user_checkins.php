<?php

/**
 * Extend our class and create our new widget
 */
class mb_untappd_user_checkins extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname'   => '',
		                     'description' => __( 'Display recent user Untappd checkins', 'mb_untappd' )
		);
		parent::__construct( 'mb_untappd_user', __( 'Untappd Recent User Checkins', 'mb_untappd' ), $widget_ops );
	}

	function form( $instance = array() ) {
		$defaults = array(
			'title'        => __( 'My recent Untappd Checkins', 'mb_untappd' ),
			'username'     => '',
			'clientID'     => '',
			'clientSecret' => '',
			'limit'        => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title        = trim( strip_tags( $instance['title'] ) );
		$username     = trim( strip_tags( $instance['username'] ) );
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
				'label' => __( 'Username:', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'username' ),
				'id'    => $this->get_field_id( 'username' ),
				'type'  => 'text',
				'value' => $username
			)
		);

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

	function update( $new_instance = array(), $old_instance = array() ) {
		$instance                 = $old_instance;
		$instance['title']        = trim( strip_tags( $new_instance['title'] ) );
		$instance['username']     = trim( strip_tags( $new_instance['username'] ) );
		$instance['clientID']     = trim( strip_tags( $new_instance['clientID'] ) );
		$instance['clientSecret'] = trim( strip_tags( $new_instance['clientSecret'] ) );
		$instance['limit']        = trim( strip_tags( $new_instance['limit'] ) );

		return $instance;
	}

	function widget( $args = array(), $instance = array() ) {

		$title        = trim( strip_tags( $instance['title'] ) );
		$username     = trim( strip_tags( $instance['username'] ) );
		$clientID     = trim( strip_tags( $instance['clientID'] ) );
		$clientSecret = trim( strip_tags( $instance['clientSecret'] ) );
		$limit        = trim( strip_tags( $instance['limit'] ) );
		$error        = false;

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$limit = ( ! empty( $limit ) && is_numeric( $limit ) ) ? absint( $limit ) : '25';

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
			$transient  = apply_filters( 'untappd_checkins_filter', 'untappd_checkins' );
			$trans_args = array(
				'transient_name'     => $transient,
				'untappd_user'       => $username,
				'untappd_api_ID'     => $clientID,
				'untappd_api_secret' => $clientSecret,
				'untappd_limit'      => $limit
			);
			$brews      = $this->getTransient( $trans_args );

			if ( is_wp_error( $brews ) ) {
				echo $brews->get_error_message();
			} else {
				if ( ! in_array( $brews->meta->code, array( '500', '404' ) ) ) {
					$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array( 'untappd_checkins' ), 'user' ) );

					$brew_data   = array(
						'brew_list' => $brews->response->checkins->items,
						'classes'   => $classes
					);
					$user_markup = apply_filters( 'untappd_user_markup', '', $brew_data );

					echo ( '' !== $user_markup ) ? $user_markup : $this->brew_list( $brew_data );

				} else {
					echo '<p>' . __( 'Nothing to display yet', 'mb_untapped' ) . '</p>';
				}
			}
		}
		//
		echo $args['after_widget'];
	}

	/**
	 * Render our unordered list for our brews.
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
					'<a href="https://untappd.com/user/' . $pint->user->user_name . '/checkin/' . $pint->checkin_id . '" title="' . esc_attr__( 'View checkin details on Untappd\'s website', 'mb_untappd' ) . '">',
					'</a>'
				)
			);
		}

		$brew_list_end = '</ul>';

		return $brew_list_start . $brew_list_listing . $brew_list_end;
	}

	/**
	 * Retrieve our Untappd API data, from a transient first, if available
	 *
	 * @param  array $trans_args Array of transient name, username, Untappd API credentials, and listing limit
	 *
	 * @return array                json-decoded data array from Untappd
	 */
	public function getTransient( $trans_args = array() ) {
		if ( false === ( $brew = get_transient( $trans_args['transient_name'] ) ) ) {
			$url      = 'https://api.untappd.com/v4/user/checkins/' . $trans_args['untappd_user'] . '?client_id=' . $trans_args['untappd_api_ID'] . '&client_secret=' . $trans_args['untappd_api_secret'] . '&limit=' . $trans_args['untappd_limit'];
			$brew     = json_decode( wp_remote_retrieve_body( wp_remote_get( $url ) ) );
			$duration = apply_filters( 'untappd_transient_duration', 60 * 10 );

			//Save only if we get a good response back.
			if ( '200' == $brew->meta->code ) {
				set_transient( $trans_args['transient_name'], $brew, $duration );
			}
		}

		return $brew;
	}

	/**
	 * Render a form input for use in our form input.
	 * @since 1.1.3
	 *
	 * @param array $args Array of argus to use with the markup.
	 *
	 * @return string $value Rendered html input.
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
