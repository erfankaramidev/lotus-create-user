<?php

declare(strict_types=1);

namespace Lotus\UserCreate\Core;

class I18n {

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
			'lotus-user-create',
			false,
			dirname( plugin_basename( __FILE__ ), 2 ) . '/languages/'
		);
	}
}
