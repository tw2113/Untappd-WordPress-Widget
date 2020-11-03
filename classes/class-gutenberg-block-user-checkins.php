<?php

/**
 * Renders the output.
 */
class Untappd_MB_Gutenberg_User_Checkins {

	public function __construct() {
	}

	public function hooks() {
		add_action( 'init', array( $this, 'on_init' ) );
	}

	public function on_init() {
		register_block_type( 'untappd-mb-gutenberg/latest-checkins', [
			'render_callback' => [ $this, 'on_render_block' ]
		] );
	}

	public function on_render_block( $attributes ) {

		if ( is_admin() ) {
			return;
		}

		$untappd_api = get_option( 'mb_untappd_settings', [] );

		if ( empty( $untappd_api ) ) {
			echo 'Please set your API keys on the settings page';
			return;
		}

		$title        = trim( $attributes['title'] );
		$username     = trim( $attributes['userName'] );
		$clientID     = $untappd_api['client_id'];
		$clientSecret = $untappd_api['client_secret'];
		$limit        = trim( $attributes['limit'] );
		$error        = false;

		if ( ! empty( $title ) ) {
			echo $title;
		}

		$limit = ( ! empty( $limit ) && is_numeric( $limit ) ) ? absint( $limit ) : '25';

		// These three fields are required to get data out of Untappd.
		if ( empty( $username ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . esc_html__( 'Please provide a user ID', 'mb_untappd' ) . '</p>';
			}
		}
		if ( empty( $clientID ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . esc_html__( 'Please provide a client ID provided by Untappd', 'mb_untappd' ) . '</p>';
			}
		}
		if ( empty( $clientSecret ) ) {
			$error = true;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p>' . esc_html__( 'Please provide a client Secret provided by Untappd', 'mb_untappd' ) . '</p>';
			}
		}

		// Lets grab and display some data!
		if ( false === $error ) {
			$transient  = apply_filters( 'untappd_checkins_filter', 'untappd_checkins_' . $username );
			$trans_args = array(
				'transient_name'     => $transient,
				'untappd_user'       => $username,
				'untappd_api_ID'     => $clientID,
				'untappd_api_secret' => $clientSecret,
				'untappd_limit'      => $limit,
			);
			$brews      = $this->getTransient( $trans_args );

			if ( is_wp_error( $brews ) ) {
				echo $brews->get_error_message();
			} else {
				if ( $brews && ! in_array( $brews->meta->code, array( '500', '404' ) ) ) {
					/**
					 * Filters the list of classes to apply to our widget output.
					 *
					 * @param array  $value Array of classes to use.
					 * @param string $value Check-in type.
					 *
					 * @since 1.1.0
					 */
					$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array( 'untappd_checkins' ), 'user' ) );

					$brew_data   = array(
						'brew_list' => $brews->response->checkins->items,
						'classes'   => $classes,
					);
					$user_markup = apply_filters( 'untappd_user_markup', '', $brew_data );

					echo ( '' !== $user_markup ) ? $user_markup : $this->brew_list( $brew_data );

				} else {
					echo '<p>' . esc_html__( 'Nothing to display yet', 'mb_untappd' ) . '</p>';
				}
			}
		}
	}

	public function brew_list( $brew_data = [] ) {
		$brew_list_start = sprintf(
			'<ul class="%s">',
			$brew_data['classes']
		);

		$brew_list_listing = '';
		foreach ( $brew_data['brew_list'] as $pint ) {
			$brew_list_listing .= '<li>';
			$brew_list_listing .= sprintf(
				__( 'I had a %s by %s on %s %s', 'mb_untappd' ),
				'<a href="https://untappd.com/beer/' . $pint->beer->bid . '">' . $pint->beer->beer_name . '</a>',
				'<a href="https://untappd.com/brewery/' . $pint->brewery->brewery_id . '">' . $pint->brewery->brewery_name . '</a>',
				date( get_option( 'date_format' ), strtotime( $pint->created_at ) ),
				sprintf( __( '%sDetails%s', 'mb_untappd' ),
					'<a href="https://untappd.com/user/' . $pint->user->user_name . '/checkin/' . $pint->checkin_id . '" title="' . esc_attr__( "View checkin details on Untappd's website", 'mb_untappd' ) . '">',
					'</a>'
				)
			);
		}

		$brew_list_end = '</ul>';

		return $brew_list_start . $brew_list_listing . $brew_list_end;
	}

	public function getTransient( $trans_args = array() ) {
		$brew = get_transient( $trans_args['transient_name'] );
		if ( false === $brew ) {
			$user = new MB_Untappd_User_Checkins_API(
				array(
					'client_id'     => $trans_args['untappd_api_ID'],
					'client_secret' => $trans_args['untappd_api_secret'],
					'username'      => $trans_args['untappd_user'],
				)
			);

			$new_brew = $user->get_checkins(
				array(
					'limit' => $trans_args['untappd_limit'],
				)
			);

			/**
			 * Filters the duration to store our transients.
			 *
			 * @param int $value Time in seconds.
			 *
			 * @since 1.0.0
			 */
			$duration = apply_filters( 'untappd_transient_duration', 60 * 10 );

			// Save only if we get a good response back.
			if ( 200 === wp_remote_retrieve_response_code( $new_brew ) ) {
				$brew = json_decode( wp_remote_retrieve_body( $new_brew ) );
				set_transient( $trans_args['transient_name'], $brew, $duration );
			} else {
				if ( current_user_can( 'manage_options' ) ) {
					if ( is_array( $new_brew ) && isset( $new_brew['error'] ) ) {
						$message = $new_brew['error'];
					} else {
						$message = $new_brew->get_error_message();
					}

					printf(
						esc_html__( 'Admin-only error: %s', 'mb_untappd' ),
						$message
					);
				}
			}
		}

		return $brew;
	}
}
