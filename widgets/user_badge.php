<?php
/**
 * Untappd User Badges Widget.
 *
 * @package Untappd
 * @subpackage Widgets
 * @since   1.3.0
 */

/**
 * Extend our class and create our new widget.
 *
 * @since 1.3.0
 */
class mb_untappd_user_badges extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => '',
			'description' => esc_html__( 'Display latest user Untappd badge', 'mb_untappd' ),
		);
		parent::__construct( 'mb_untappd_user_badge', esc_html__( 'Untappd Latest User Badge', 'mb_untappd' ), $widget_ops );
	}

	/**
	 * Form method.
	 *
	 * @since 1.3.0
	 *
	 * @param array $instance Widget instance.
	 * @return void
	 */
	function form( $instance = array() ) {

		$untappd_api = get_option( 'mb_untappd_settings', array() );

		// Conditionally show our notification.
		if ( empty( $untappd_api['client_id'] ) || empty( $untappd_api['client_secret'] ) ) {
			mb_untappd_settings_page_notification();
		}

		$defaults = array(
			'title'        => esc_html__( 'My latest Untappd badge', 'mb_untappd' ),
			'username'     => '',
			'clientID'     => '',
			'clientSecret' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title        = trim( strip_tags( $instance['title'] ) );
		$username     = trim( strip_tags( $instance['username'] ) );
		$clientID     = trim( strip_tags( $instance['clientID'] ) );
		$clientSecret = trim( strip_tags( $instance['clientSecret'] ) );

		$this->form_input(
			array(
				'label' => esc_html__( 'Title:', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'title' ),
				'id'    => $this->get_field_id( 'title' ),
				'type'  => 'text',
				'value' => $title,
			)
		);

		$this->form_input(
			array(
				'label' => esc_html__( 'Username:', 'mb_untappd' ),
				'name'  => $this->get_field_name( 'username' ),
				'id'    => $this->get_field_id( 'username' ),
				'type'  => 'text',
				'value' => $username,
			)
		);

		if ( empty( $untappd_api['client_id'] ) || empty( $untappd_api['client_secret'] ) ) {
			$this->form_input(
				array(
					'label' => esc_html__( 'Client Key:', 'mb_untappd' ),
					'name'  => $this->get_field_name( 'clientID' ),
					'id'    => $this->get_field_id( 'clientID' ),
					'type'  => 'text',
					'value' => $clientID,
				)
			);

			$this->form_input(
				array(
					'label' => esc_html__( 'Client Secret:', 'mb_untappd' ),
					'name'  => $this->get_field_name( 'clientSecret' ),
					'id'    => $this->get_field_id( 'clientSecret' ),
					'type'  => 'text',
					'value' => $clientSecret,
				)
			);
		}
	}

	/**
	 * Update method.
	 *
	 * @since 1.3.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $old_instance Old widget instance.
	 * @return array
	 */
	function update( $new_instance = array(), $old_instance = array() ) {
		$instance                 = $old_instance;
		$instance['title']        = trim( strip_tags( $new_instance['title'] ) );
		$instance['username']     = trim( strip_tags( $new_instance['username'] ) );
		$instance['clientID']     = trim( strip_tags( $new_instance['clientID'] ) );
		$instance['clientSecret'] = trim( strip_tags( $new_instance['clientSecret'] ) );

		delete_transient( apply_filters( 'untappd_badge_filter', 'untappd_user_badge_' . $instance['username'] ) );

		return $instance;
	}

	/**
	 * Widget display method.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget instance.
	 */
	function widget( $args = array(), $instance = array() ) {

		$untappd_api = get_option( 'mb_untappd_settings', array() );

		$title        = trim( strip_tags( $instance['title'] ) );
		$username     = trim( strip_tags( $instance['username'] ) );
		$clientID = ( ! empty( $untappd_api['client_id'] ) ) ?
			trim( strip_tags( $untappd_api['client_id'] ) ) :
			trim( strip_tags( $instance['clientID'] ) );
		$clientSecret = ( ! empty( $untappd_api['client_secret'] ) ) ?
			trim( strip_tags( $untappd_api['client_secret'] ) ) :
			trim( strip_tags( $instance['clientSecret'] ) );
		$error        = false;

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

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
			$transient  = apply_filters( 'untappd_badge_filter', 'untappd_user_badge_' . $username );
			$trans_args = array(
				'transient_name'     => $transient,
				'untappd_user'       => $username,
				'untappd_api_ID'     => $clientID,
				'untappd_api_secret' => $clientSecret,
			);
			$badge      = $this->getTransient( $trans_args );

			if ( is_wp_error( $badge ) ) {
				echo $badge->get_error_message();
			} else {
				if ( $badge && ! in_array( $badge->meta->code, array( '500', '404' ) ) ) {
					/**
					 * Filters the list of classes to apply to our widget output.
					 * @since 1.1.0
					 *
					 * @param array  $value Array of classes to use.
					 * @param string $value Check-in type.
					 */
					$classes = implode( ', ', apply_filters( 'untappd_checkins_list_classes', array( 'untappd_badge' ), 'badge' ) );

					$badge_data   = array(
						'badge' => $badge->response->items,
						'classes'   => $classes,
					);
					$badge_markup = apply_filters( 'untappd_user_badge_markup', '', $badge_data );

					echo ( '' !== $badge_markup ) ? $badge_markup : $this->badge( $badge_data, $trans_args['untappd_user'] );

				} else {
					echo '<p>' . esc_html__( 'Nothing to display yet', 'mb_untappd' ) . '</p>';
				}
			}
		} // End if().
		echo $args['after_widget'];
	}

	/**
	 * Render our badge.
	 *
	 * @since 1.3.0
	 *
	 * @param array  $badge_data Array of data for a badge.
	 * @param string $username   Untappd username.
	 * @return string $value Rendered list of brews.
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
	 * @since 1.3.0
	 *
	 * @param object $badge_item
	 * @param string $username
	 * @return string
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
	 * @since 1.3.0
	 *
	 * @param object $badge_item
	 * @param string $size
	 * @return mixed
	 */
	public function get_user_badge_image_url( $badge_item, $size = 'lg' ) {
		return $badge_item->media->{"badge_image_$size"};
	}

	/**
	 * Retrieve our Untappd API data, from a transient first, if available.
	 *
	 * @since 1.3.0
	 *
	 * @param array $trans_args Array of transient name, username, Untappd API credentials, and listing limit.
	 * @return array JSON-decoded data array from Untappd
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
			 * @since 1.0.0
			 *
			 * @param int $value Time in seconds.
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

	/**
	 * Render a form input for use in our form input.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args Array of argus to use with the markup.
	 * @return void
	 */
	function form_input( $args = array() ) {
		printf(
			'<p><label for="%s">%s</label><input type="%s" class="widefat" name="%s" id="%s" value="%s" /></p>',
			esc_attr( $args['id'] ),
			esc_attr( $args['label'] ),
			esc_attr( $args['type'] ),
			esc_attr( $args['name'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['value'] )
		);
	}
}
