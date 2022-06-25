<?php

class MB_Untappd_Recent_Venue_Template implements MB_Untappd_Templates {

	/**
	 * Render our unordered list for our brews.
	 *
	 * @param array $brew_data Array of data for a specific checkin.
	 *
	 * @return string $value Rendered list of brews.
	 * @since 1.3.0
	 */
	public function brew_list( $brew_data = array() ) {
		$brew_list_start = sprintf(
			'<ul class="%s">',
			$brew_data['classes']
		);

		$brew_list_listing = '';
		foreach ( $brew_data['brew_list'] as $pint ) {
			$brew_list_listing .= '<li>';
			$venue_or_brewery  = $this->get_venue_or_brewery( $pint );
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
	 * @param string|object $pint Checkin object.
	 *
	 * @return string
	 * @since 1.3.0
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
	 * @param array $trans_args Array of transient name, username, Untappd API credentials, and listing limit.
	 *
	 * @return array                json-decoded data array from Untappd
	 * @since 1.3.0
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
					'limit' => $trans_args['untappd_limit'],
				)
			);

			/**
			 * Filters the duration to store our transients.
			 *
			 * @param int $value Time in seconds.
			 *
			 * @since 1.0.0
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
}
