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


	protected $base_uri = 'https://api.untappd.com/v4/';

	protected $client_id = '';

	protected $client_secret = '';

	public function __construct( $args = array() ) {
		$this->client_id     = $args['client_id'];
		$this->client_secret = $args['client_secret'];
		$this->username      = $args['username'];
	}
}
