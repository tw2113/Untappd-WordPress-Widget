<?php

$untappd_api = get_option( 'mb_untappd_settings', array() );

$title        = trim( strip_tags( $attributes['title'] ) );
$venue        = trim( strip_tags( $attributes['venue'] ) );
$clientID     = strip_tags( $untappd_api['client_id'] );
$clientSecret = strip_tags( $untappd_api['client_secret'] );
$limit        = trim( strip_tags( $attributes['limit'] ) );
$error        = false;

$template = new MB_Untappd_Recent_Venue_Template();

if ( ! empty( $title ) ) {
	echo '<h3>' . $title . '</h3>';
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
	 * @param string $value Transient name.
	 *
	 * @since 1.3.2 Moved filter to dynamic to allow multiple streams.
	 * @since 1.3.0
	 */
	$transient  = apply_filters( 'untappd_checkins_venue_filter', 'untappd_venue_checkins_' . $venue );
	$trans_args = array(
		'transient_name'     => $transient,
		'untappd_venue'      => $venue,
		'untappd_api_ID'     => $clientID,
		'untappd_api_secret' => $clientSecret,
		'untappd_limit'      => $limit,
	);
	$brews      = $template->getTransient( $trans_args );

	if ( is_wp_error( $brews ) ) {
		echo $brews->get_error_message();
	} else {
		if ( $brews && ! in_array( $brews->meta->code, array( '500', '404' ) ) ) {
			/**
			 * Filters the list of classes to apply to our widget output.
			 *
			 * @param array  $value Array of classes to use.
			 * @param string $value Check-in type.
			 *
			 * @since 1.1.0
			 */
			$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array( 'untappd_checkins' ), 'venue' ) );

			$brew_data = array(
				'brew_list' => $brews->response->checkins->items,
				'classes'   => $classes,
			);
			/**
			 * Filters the markup to use for the brewery widget.
			 *
			 * @param string $value     Markup to use. Default empty string.
			 * @param array  $brew_data Array of brewery checkin data.
			 *
			 * @since 1.3.0
			 */
			$brewery_markup = apply_filters( 'untappd_venue_markup', '', $brew_data );

			echo ( '' !== $brewery_markup ) ? $brewery_markup : $template->brew_list( $brew_data );

		} else {
			echo '<p>' . esc_html__( 'Nothing to display yet', 'mb_untappd' ) . '</p>';
		}
	}
}
