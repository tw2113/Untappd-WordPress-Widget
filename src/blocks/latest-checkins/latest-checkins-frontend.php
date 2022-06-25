<?php

$untappd_api = get_option( 'mb_untappd_settings', array() );

$title        = trim( strip_tags( $attributes['title'] ) );
$username     = trim( strip_tags( $attributes['username'] ) );
$clientID     = strip_tags( $untappd_api['client_id'] );
$clientSecret = strip_tags( $untappd_api['client_secret'] );
$limit        = trim( strip_tags( $attributes['limit'] ) );
$error        = false;

$template = new MB_Untappd_User_Checkins_Template();

if ( ! empty( $title ) ) {
	echo '<h3>' . $title . '</h3>';
}

$limit = ( ! empty( $limit ) && is_numeric( $limit ) ) ? absint( $limit ) : 25;

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
			$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array( 'untappd_checkins' ), 'user' ) );

			$brew_data   = array(
				'brew_list' => $brews->response->checkins->items,
				'classes'   => $classes,
			);
			$user_markup = apply_filters( 'untappd_user_markup', '', $brew_data );

			echo ( '' !== $user_markup ) ? $user_markup : $template->brew_list( $brew_data );

		} else {
			echo '<p>' . esc_html__( 'Nothing to display yet', 'mb_untappd' ) . '</p>';
		}
	}
} // End if().
