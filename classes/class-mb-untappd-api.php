<?php
/**
 * Untappd API class.
 *
 * @package Untappd
 * @since   1.3.0
 */

/**
 * Class MB_Untappd_API
 *
 * @since 1.3.0
 */
class MB_Untappd_API {

	/**
	 * Base Untappd API endpoint.
	 *
	 * @var string
	 * @since 1.3.0
	 */
	protected $base_uri = 'https://api.untappd.com/v4/';

	/**
	 * Untappd API Client ID.
	 *
	 * @var mixed|string
	 * @since 1.3.0
	 */
	protected $client_id = '';

	/**
	 * Untappd API Client Secret ID.
	 *
	 * @var mixed|string
	 * @since 1.3.0
	 */
	protected $client_secret = '';

	/**
	 * MB_Untappd_API constructor.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		$this->client_id     = $args['client_id'];
		$this->client_secret = $args['client_secret'];
		$this->username      = $args['username'];
	}

	/**
	 * Get our current rate limit from the Untappd API.
	 *
	 * @since 1.3.0
	 *
	 * @param $request WP HTTP API request.
	 * @return string
	 */
	public function get_current_limit( $request ) {
		return wp_remote_retrieve_header( $request, 'x-ratelimit-remaining' );
	}

	/**
	 * Return a blanket rate limit met message.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	public function get_rate_limit_met_message() {
		return array( 'error' => esc_html__( 'Reached rate limit for current hour.', 'mb_untappd' ) );
	}
}
