<?php

declare(strict_types=1);

namespace Lotus\CreateUser\Core;

/**
 * Class Uninstaller
 * 
 * @since 1.1.0
 */
final class Uninstaller {

	/**
	 * Remove the plugin options on uninstallation.
	 */
	public static function uninstall(): void {
		delete_option( 'lotus_user_create_api_token' );
	}
}
