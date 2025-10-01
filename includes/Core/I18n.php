<?php

declare(strict_types=1);

namespace Lotus\CreateUser\Core;

/**
 * Internalization class
 * 
 * @since 1.0.0
 */
final class I18n {

	/**
	 * Register the hooks related to internationalization.
	 */
	public function register_hooks(): void {
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ] );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain(): void {
		load_plugin_textdomain(
			'lotus-create-user',
			false,
			dirname( plugin_basename( __DIR__ ), 2 ) . '/languages/'
		);
	}
}
