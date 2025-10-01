<?php

declare(strict_types=1);

namespace Lotus\CreateUser\Core;

/**
 * Class Activator
 * 
 * @since 1.0.0
 */
class Activator {

	/**
	 * Generates and saves the API token on activation.
	 */
	public static function activate(): void {
		if ( ! get_option( 'lotus_user_create_api_token' ) ) {
			$token = wp_generate_password( 64, false );
			update_option( 'lotus_user_create_api_token', $token, false );
		}
	}
}
