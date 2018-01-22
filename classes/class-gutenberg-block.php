<?php

/**
 * Renders the output.
 */
class Untappd_MB_Gutenberg_Block {

	private $plugin;

	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		$this->hooks();

	}

	public function hooks() {
		add_action( 'init', array( $this, 'on_init' ) );
	}


	public function on_init() {
		register_block_type( 'constantcontact-gutenberg/single-form', array(
			'render_callback' => array( $this, 'on_render_block' )
		) );
	}


	public function on_render_block( $attributes ) {
		$attributes = $attributes;

		return '';
	}


}
