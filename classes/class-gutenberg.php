<?php

class Untappd_MB_Gutenberg {

	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'on_enqueue_block_editor_assets' ) );
	}

	public function on_enqueue_block_editor_assets() {

		wp_enqueue_style(
			'ctct-gutenberg-block', // Handle.
			$this->plugin->url . 'assets/css/editor.css',
			array(
				'wp-edit-blocks',
			)
		);

		$forms = array(
			0 => array(
				'value' => 0,
				'label' => esc_html__( 'Choose your form', 'constant-contact-forms' )
			)
		);

		$formmeta = array(
			0 => esc_html__( 'Choose your form', 'constant-contact-forms' )
		);

		$ctct_forms = $this->get_forms();
		foreach( $ctct_forms[0] as $id => $form_title ) {
			$forms[] = array(
				'value' => $id,
				'label' => $form_title
			);

			$formmeta[ $id ] = array( 'title' => $form_title );
		}

		wp_localize_script(
			'ctct_form',
			'ctct_gutenberg_block_params',
			array(
				'forms' => $forms,
				'formmeta' => $formmeta
			)
		);
	}
}
