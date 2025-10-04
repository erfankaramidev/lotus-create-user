<?php

declare(strict_types=1);

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( class_exists( 'Lotus\CreateUser\Core\Uninstaller' ) ) {
	\Lotus\CreateUser\Core\Uninstaller::uninstall();
}
