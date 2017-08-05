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


	private $base_uri = 'https://api.untappd.com/v4/';

	/**
	 * App client ID.
	 *
	 * @var string
	 * @since 1.1.0
	 */
	private $client_id = '';

	/**
	 * App client secret ID.
	 *
	 * @var string
	 * @since 1.1.0
	 */
	private $client_secret = '';

	/**
	 * Formstack_API_V2 constructor.
	 *
	 * @param array $args Array of arguments for instance.
	 */
	public function __construct( $args = array() ) {
		$this->client_id     = isset( $args['client_id'] ) ? $args['client_id'] : '';
		$this->client_secret = isset( $args['client_secret'] ) ? $args['client_secret'] : '';
		$this->username      = isset( $args['username'] ) ? $args['username'] : '';
	}

	public function get_user_checkins( $args = array() ) {
		$defaults = array(
			'username' => '',
			'limit'    => 25,
		);
		$args = wp_parse_args( $args, $defaults );
		$url = $this->base_uri . '/user/checkins/' . $args['username'];

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

	public function get_brewery_checkins( $args = array() ) {
		$defaults = array(
			'brewery' => '',
			'limit'   => 25,
		);
		$args = wp_parse_args( $args, $defaults );
		$url = $this->base_uri . '/brewery/checkins/' . $args['brewery'];

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
