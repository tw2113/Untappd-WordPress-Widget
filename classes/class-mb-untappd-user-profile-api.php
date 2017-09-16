<?php
/**
 * Class for fetching user profile information.
 *
 * @package    Untappd WordPress Widget
 * @subpackage MB_Untappd_API
 * @since      1.3.0
 */

/**
 * Class MB_Untappd_User_Profile_API
 *
 * @since 1.3.0
 */
class MB_Untappd_User_Profile_API extends MB_Untappd_API {

	/**
	 * Untappd API endpoint to query.
	 *
	 * @var string
	 * @since 1.3.0
	 */
	protected $endpoint = 'user/info/';

	/**
	 * Retrieve brewery checkins.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	public function get_info() {
		$url = $this->base_uri . $this->endpoint . $this->username;

		$results = wp_remote_get(
			add_query_arg(
				array(
					'client_id'     => $this->client_id,
					'client_secret' => $this->client_secret,
					'compact'       => 'true',
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
