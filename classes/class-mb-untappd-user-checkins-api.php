<?php
/**
 * Class for fetching user checkins.
 *
 * @package    Untappd WordPress Widget
 * @subpackage MB_Untappd_API
 * @since      1.3.0
 */

/**
 * Class MB_Untappd_User_Checkins_API
 *
 * @since 1.3.0
 */
class MB_Untappd_User_Checkins_API extends MB_Untappd_API {

	/**
	 * Untappd API endpoint to query.
	 *
	 * @var string
	 * @since 1.3.0
	 */
	protected $endpoint = 'user/checkins/';

	/**
	 * Retrieve user checkins.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args Array of arguments.
	 * @return array
	 */
	public function get_checkins( $args = array() ) {
		$defaults = array(
			'username' => '',
			'limit'    => 25,
		);
		$args     = wp_parse_args( $args, $defaults );
		$url      = $this->base_uri . $this->endpoint . $this->username;

		$results = wp_remote_get(
			add_query_arg(
				array(
					'client_id'     => $this->client_id,
					'client_secret' => $this->client_secret,
					'limit'         => $args['limit'],
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
