<?php

class Untappd_MB_Gutenberg {

	public function hooks() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'on_enqueue_block_editor_assets' ) );
	}

	public function on_enqueue_block_editor_assets() {

		wp_register_script(
			'untappd-mb-gutenberg',
			plugin_dir_url( __DIR__ ) . 'build/index.js',
			[ 'wp-blocks', 'wp-element', 'wp-i18n' ],
			'1.4.0',
			true
		);

		wp_register_style(
			'untappd-mb-gutenberg-editor',
			plugin_dir_url( __DIR__ ) . 'build/editor.css',
			[],
			'1.4.0',
			true
		);

		wp_register_style(
			'untappd-mb-gutenberg-frontend',
			plugin_dir_url( __DIR__ ) . 'build/style.css',
			[],
			'1.4.0',
			true
		);

		wp_enqueue_script( 'untappd-mb-gutenberg' );
		wp_enqueue_style( 'untappd-mb-gutenberg-editor' );
		wp_enqueue_style( 'untappd-mb-gutenberg-frontend' );
	}
}
