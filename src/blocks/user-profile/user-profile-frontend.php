<?php

$untappd_api = get_option( 'mb_untappd_settings', array() );


$title              = trim( strip_tags( $attributes['title'] ) );
$username           = trim( strip_tags( $attributes['username'] ) );
$showavatar         = trim( strip_tags( $attributes['showavatar'] ) );
$showlocation       = trim( strip_tags( $attributes['showlocation'] ) );
$showtotal_badges   = trim( strip_tags( $attributes['showtotal_badges'] ) );
$showtotal_checkins = trim( strip_tags( $attributes['showtotal_checkins'] ) );
$showtotal_beers    = trim( strip_tags( $attributes['showtotal_beers'] ) );
$showtotal_friends  = trim( strip_tags( $attributes['showtotal_friends'] ) );
$clientID           = strip_tags( $untappd_api['client_id'] );
$clientSecret       = strip_tags( $untappd_api['client_secret'] );
$error              = false;

$template = new MB_Untappd_User_Profile_Template();

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

if ( ! empty( $title ) ) {
	echo '<h3>' . $title . '</h3>';
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
	$transient  = apply_filters( 'untappd_profile_filter', 'untappd_user_profile_' . $username );
	$trans_args = array(
		'transient_name'     => $transient,
		'untappd_user'       => $username,
		'untappd_api_ID'     => $clientID,
		'untappd_api_secret' => $clientSecret,
	);
	$profile    = $template->getTransient( $trans_args );

	if ( is_wp_error( $profile ) ) {
		echo $profile->get_error_message();
	} else {
		if ( $profile && ! in_array( $profile->meta->code, array( '500', '404' ) ) ) {
			/**
			 * Filters the list of classes to apply to our widget output.
			 *
			 * @param array  $value Array of classes to use.
			 * @param string $value Check-in type.
			 *
			 * @since 1.1.0
			 */
			$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array(
				'untappd-profile',
				'untappd-profile-' . $profile->response->user->user_name,
			), 'profile' ) );

			$profile_data   = array(
				'badge'            => $profile->response->user,
				'classes'          => $classes,
				'conditional_data' => $conditional_data,
			);
			$profile_markup = apply_filters( 'untappd_user_profile_markup', '', $profile_data );

			echo ( '' !== $profile_markup ) ? $profile_markup : $template->profile( $profile_data, $trans_args['untappd_user'] );

		} else {
			echo '<p>' . esc_html__( 'Nothing to display yet', 'mb_untappd' ) . '</p>';
		}
	}
} // End if().
