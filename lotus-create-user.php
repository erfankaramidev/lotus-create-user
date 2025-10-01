<?php

/**
 * Lotus Create User
 * 
 * @author Erfan Karami
 * @copyright 2025 Erfan Karami - Lotusweb
 * @license GPL-2.0-or-later
 * 
 * @wordpress-plugin
 * Plugin Name:  Lotus Create User
 * Description:  A plugin to create a new user via a secure REST API endpoint.
 * Requires PHP: 7.4
 * Text Domain:  lotus-user-create
 * Domain Path:  /languages
 * Version:      1.0
 * Author: 		 Erfan Karami
 * Author URI:   https://erfankarami.dev
 * License:      GPL-2.0-or-later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure Composer autoloader is loaded.
if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	add_action(
		'admin_notices',
		function () { ?>
		<div class="notice notice-error is-dismissible">
			<p><?php esc_html_e( 'Lotus Create User requires Composer autoloader.', 'lotus-user-create' ) ?></p>
		</div>
		<?php
		}
	);
	return;
}

require_once __DIR__ . '/vendor/autoload.php';
