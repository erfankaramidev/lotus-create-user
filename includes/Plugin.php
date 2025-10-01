<?php

declare(strict_types=1);

namespace Lotus\CreateUser;

use Lotus\UserCreate\Admin\Settings;
use Lotus\UserCreate\Api\RestController;
use Lotus\UserCreate\Core\I18n;

/**
 * The core plugin class
 * 
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Initialize the plugin by loading the dependencies and setting up hooks.
	 */
	public function run(): void {
		$this->load_dependencies();
	}

	/**
	 * Load the plugin's dependencies.
	 */
	private function load_dependencies(): void {
		( new I18n() )->register_hooks();
	}
}
