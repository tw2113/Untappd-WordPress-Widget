<?php

class MB_Untappd_User_Profile_Template implements MB_Untappd_Templates {

	/**
	 * Render our profile.
	 *
	 * @param array  $profile_data Array of data for a badge.
	 * @param string $username     Untappd username.
	 *
	 * @return string $value Rendered profile.
	 * @since 1.3.0
	 */
	public function profile( $profile_data = array(), $username ) {
		$profile_start = sprintf(
			'<div class="%s">',
			$profile_data['classes']
		);

		$profile      = '';
		$location     = '';
		$user         = $profile_data['badge']->user_name;
		$firstname    = $profile_data['badge']->first_name;
		$pic          = $profile_data['badge']->user_avatar_hd;
		$permalink    = $profile_data['badge']->untappd_url;
		$member_since = $profile_data['badge']->date_joined;

		if ( ! empty( $profile_data['conditional_data']['avatar'] ) ) {
			$profile .= sprintf(
				'<img class="untappd-user-pic" src="%s" alt="%s" />',
				esc_attr( $pic ),
				sprintf(
				// Translators: placeholder will hold user name value from Untappd profile.
					esc_attr__( 'User profile photo for %s', 'mb_untappd' ),
					$firstname
				)
			);
		}
		if ( ! empty( $profile_data['conditional_data']['location'] ) ) {
			$location = '- ' . $profile_data['badge']->location;
		}
		$profile .= sprintf(
			'<p><a href="%s">%s %s</a><br/>%s</p><ul>%s</ul>',
			esc_attr( $permalink ),
			$user,
			$location,
			sprintf(
			// translators: placeholder will hold WP setting-formatted date representing their Untappd membership start.
				esc_html__( 'Member since: %s', 'mb_untappd' ),
				date( get_option( 'date_format' ), strtotime( $member_since ) )
			),
			$this->get_stats_list( $profile_data['badge']->stats, $profile_data['conditional_data'] )
		);

		$profile_end = '</div>';

		return $profile_start . $profile . $profile_end;
	}

	/**
	 * Render our list of user stats.
	 *
	 * @param array $stats
	 * @param array $data_to_keep
	 *
	 * @return string
	 * @since 1.3.0
	 */
	public function get_stats_list( $stats, $data_to_keep = array() ) {
		$stats_list = '';
		foreach ( $stats as $stat => $value ) {
			if ( ! in_array( $stat, array_keys( $data_to_keep ) ) ) {
				continue;
			}
			$stat_type  = explode( 'total_', $stat );
			$stats_list .= '<li>' . ucfirst( $stat_type[1] ) . ': ' . $value . '</li>';
		}

		return $stats_list;
	}

	/**
	 * Retrieve our Untappd API data, from a transient first, if available.
	 *
	 * @param array $trans_args Array of transient name, username, Untappd API credentials, and listing limit.
	 *
	 * @return array JSON-decoded data array from Untappd
	 * @since 1.3.0
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
			 *
			 * @param int $value Time in seconds.
			 *
			 * @since 1.0.0
			 */
			$duration = apply_filters( 'untappd_transient_duration', 60 * 10 );

			// Save only if we get a good response back.
			if ( 200 === wp_remote_retrieve_response_code( $new_profile ) ) {
				$profile = json_decode( wp_remote_retrieve_body( $new_profile ) );
				set_transient( $trans_args['transient_name'], $profile, $duration );
			} else {
				if ( current_user_can( 'manage_options' ) ) {
					if ( is_array( $new_profile ) && isset( $new_profile['error'] ) ) {
						$message = $new_profile['error'];
					} else {
						$message = $new_profile->get_error_message();
					}

					printf(
						esc_html__( 'Admin-only error: %s', 'mb_untappd' ),
						$message
					);
				}
			}
		}

		return $profile;
	}
}
