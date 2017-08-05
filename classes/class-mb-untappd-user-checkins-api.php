<?php
/**
 * Class for fetching user checkins.
 * @package    Untappd WordPress Widget
 * @subpackage MB_Untappd_API
 * @since      1.3.0
 */

class MB_Untappd_User_Checkins_API extends MB_Untappd_API {

	protected $endpoint = 'user/checkins/';

	public function __construct( array $args = array() ) {
		parent::__construct( $args );
	}

	/**
	 * Retrieve user checkins.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args Array of arguments.
	 * @return array|WP_Error
	 */
	public function get_user_checkins( $args = array() ) {
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

		return $results;
	}
}
