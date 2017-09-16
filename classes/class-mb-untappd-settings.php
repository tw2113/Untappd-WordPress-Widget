<?php
/**
 * Class for setting up options page.
 * @package    Untappd WordPress Widget
 * @subpackage MB_Untappd_Settings
 * @since      1.3.0
 */

/**
 * Class MB_Untappd_Settings
 *
 * @since 1.3.0
 */
class MB_Untappd_Settings {

	/**
	 * Run our hooks.
	 *
	 * @since 1.3.0
	 */
	public function do_hooks() {
		add_action( 'admin_init', array( $this, 'settings_registration' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Register our settings.
	 *
	 * @since 1.3.0
	 */
	public function settings_registration() {
		register_setting( 'mb_untappd', 'mb_untappd_settings', array( $this, 'settings_validate' ) );
		$settings      = get_option( 'mb_untappd_settings', '' );
		$client_id     = ( isset( $settings['client_id'] ) ) ? $settings['client_id'] : '';
		$client_secret = ( isset( $settings['client_secret'] ) ) ? $settings['client_secret'] : '';
		add_settings_section(
			'mb_untappd_settings',
			esc_html__( 'Untappd WordPress Widgets API settings', 'mb_untappd' ),
			array( $this, 'mb_untappd_do_section' ),
			'mb_untappd_do_options'
		);
		add_settings_field(
			'untappd_client_id',
			'<label for="untappd_client_id">' . esc_html__( 'Client Key', 'mb_untappd' ) . '</label>',
			array( $this, 'input_fields' ),
			'mb_untappd_do_options',
			'mb_untappd_settings',
			array(
				'class' => 'regular-text',
				'id'    => 'untappd_client_id',
				'type'  => 'text',
				'name'  => 'mb_untappd_settings[client_id]',
				'value' => $client_id,
			)
		);
		add_settings_field(
			'untappd_client_secret',
			'<label for="untappd_client_secret">' . esc_html__( 'Client Secret', 'mb_untappd' ) . '</label>',
			array( $this, 'input_fields' ),
			'mb_untappd_do_options',
			'mb_untappd_settings',
			array(
				'class' => 'regular-text',
				'id'    => 'untappd_client_secret',
				'type'  => 'text',
				'name'  => 'mb_untappd_settings[client_secret]',
				'value' => $client_secret,
			)
		);
	}

	/**
	 * Helper method for displaying our options page.
	 *
	 * @since 1.3.0
	 */
	public function mb_untappd_do_section() {
		?>
		<p>
		<?php
			printf(
				// translators: placeholder will have link to Untappd API docs.
				esc_html__( 'Information and API access application can be found at %s.', 'mb_untappd' ),
				'<a href="https://untappd.com/api/dashboard">Untappd API Central</a>'
			);
			?>
		</p>
		<?php
	}

	/**
	 * Helper method to display inputs for settings page.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args Array of arguments for method.
	 */
	public function input_fields( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'class'       => null,
			'id'          => null,
			'type'        => null,
			'name'        => null,
			'value'       => '',
			'description' => null,
		) );
		switch ( $args['type'] ) {
			case 'text':
				echo '<input type="' . esc_attr( $args['type'] ) . '" class="' . esc_attr( $args['class'] ) . '" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '" placeholder="' . esc_attr( $args['description'] ) . '" value="' . esc_attr( $args['value'] ) . '" />';
				break;
			default:
				echo '<input type="text" class="' . esc_attr( $args['class'] ) . '" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '" placeholder="' . esc_attr( $args['description'] ) . '" value="' . esc_attr( $args['value'] ) . '" />';
		}
	}

	/**
	 * Helper method for sanitization of our options.
	 *
	 * @since 1.3.0
	 *
	 * @param string $input API key.
	 * @return string
	 */
	public function settings_validate( $input = '' ) {
		foreach ( $input as $name => $value ) {
			$new_input[ $name ] = sanitize_text_field( $value );
		}

		return $new_input;
	}

	/**
	 * Set up our admin menu.
	 *
	 * @since 1.3.0
	 */
	public function admin_menu() {
		add_options_page(
			esc_html__( 'Untappd Widgets API Settings', 'mb_untappd' ),
			esc_html__( 'Untappd Widgets API', 'mb_untappd' ),
			'manage_options',
			'mb_untappd_settings',
			array( $this, 'plugin_options' )
		);
	}

	/**
	 * Render the Untappd Widgets settings page via template file.
	 *
	 * @since 1.3.0
	 */
	public function plugin_options() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'tmpl/options.php';
	}
}
$untappd_settings = new MB_Untappd_Settings();
$untappd_settings->do_hooks();
