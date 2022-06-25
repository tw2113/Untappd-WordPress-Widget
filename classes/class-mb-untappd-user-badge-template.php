<?php

class MB_Untappd_User_Badge_Template implements MB_Untappd_Templates {

	/**
	 * Render our badge.
	 *
	 * @param array  $badge_data Array of data for a badge.
	 * @param string $username   Untappd username.
	 *
	 * @return string $value Rendered list of brews.
	 * @since 1.3.0
	 */
	public function badge( $badge_data = array(), $username ) {
		$badge_start = sprintf(
			'<div class="%s">',
			$badge_data['classes']
		);

		$badge = '';
		foreach ( $badge_data['badge'] as $badge_item ) {
			$badge = sprintf(
				'<p><span class="badge-name">%s</span><br/><a href="%s"><img src="%s" alt="%s" /></a><br/>%s <span><a href="%s">%s</a></span></p>',
				$badge_item->badge_name,
				$this->get_user_badge_url( $badge_item, $username ),
				$this->get_user_badge_image_url( $badge_item ),
				sprintf(
				// translators: placeholder will be Untappd badge name.
					esc_attr__( 'Badge image for %s', 'mb_untappd' ),
					$badge_item->badge_name
				),
				$badge_item->badge_description,
				$this->get_user_badge_url( $badge_item, $username ),
				esc_html__( 'View more', 'mb_untappd' )
			);
		}

		$badge_end = '</div>';

		return $badge_start . $badge . $badge_end;
	}

	/**
	 * Formats a user badge URL.
	 *
	 * @param object $badge_item
	 * @param string $username
	 *
	 * @return string
	 * @since 1.3.0
	 */
	public function get_user_badge_url( $badge_item, $username = '' ) {
		return sprintf(
			'https://untappd.com/user/%s/badges/%s',
			$username,
			$badge_item->user_badge_id
		);
	}

	/**
	 * Returns a given user badge image URL.
	 *
	 * @param object $badge_item
	 * @param string $size
	 *
	 * @return mixed
	 * @since 1.3.0
	 */
	public function get_user_badge_image_url( $badge_item, $size = 'lg' ) {
		return $badge_item->media->{"badge_image_$size"};
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
		$badge = get_transient( $trans_args['transient_name'] );
		if ( false === $badge ) {
			$user = new MB_Untappd_Badges_API(
				array(
					'client_id'     => $trans_args['untappd_api_ID'],
					'client_secret' => $trans_args['untappd_api_secret'],
					'username'      => $trans_args['untappd_user'],
				)
			);

			$new_badge = $user->get_user_badges();

			/**
			 * Filters the duration to store our transients.
			 *
			 * @param int $value Time in seconds.
			 *
			 * @since 1.0.0
			 */
			$duration = apply_filters( 'untappd_transient_duration', 60 * 10 );

			// Save only if we get a good response back.
			if ( 200 === wp_remote_retrieve_response_code( $new_badge ) ) {
				$badge = json_decode( wp_remote_retrieve_body( $new_badge ) );
				set_transient( $trans_args['transient_name'], $badge, $duration );
			} else {
				if ( current_user_can( 'manage_options' ) ) {
					if ( is_array( $new_badge ) && isset( $new_badge['error'] ) ) {
						$message = $new_badge['error'];
					} else {
						$message = $new_badge->get_error_message();
					}

					printf(
						esc_html__( 'Admin-only error: %s', 'mb_untappd' ),
						$message
					);
				}
			}
		}

		return $badge;
	}
}
