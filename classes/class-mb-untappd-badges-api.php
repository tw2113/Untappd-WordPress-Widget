<?php
/**
 * Class for fetching user badges.
 *
 * @package Untappd WordPress Widget
 * @subpackage MB_Untappd_API
 * @since 1.3.0
 */

/**
 * Class MB_Untappd_Badges_API
 *
 * @since 1.3.0
 */
class MB_Untappd_Badges_API extends MB_Untappd_API {

	/**
	 * Untappd API endpoint to query.
	 *
	 * @var string
	 * @since 1.3.0
	 */
	protected $endpoint = '/user/badges/';

	/**
	 * Retrieve latest user badge.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	public function get_user_badges() {
		$url = $this->base_uri . $this->endpoint . $this->username;

		$results = wp_remote_get(
			add_query_arg(
				array(
					'client_id'     => $this->client_id,
					'client_secret' => $this->client_secret,
					'limit'         => 1,
				),
				$url
			)
		);

		$limit = $this->get_current_limit( $results );
		if ( $limit === '2' ) {
			return $this->get_rate_limit_met_message();
		}

		return $results;
	}
}
