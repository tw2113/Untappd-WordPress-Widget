<?php
/**
 * Untappd API class.
 *
 * @package Untappd
 * @since   1.3.0
 */

/**
 * Class Formstack_API_V2
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
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		$this->client_id     = $args['client_id'];
		$this->client_secret = $args['client_secret'];
		$this->username      = $args['username'];
	}
}
