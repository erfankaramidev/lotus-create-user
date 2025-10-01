<?php

declare(strict_types=1);

namespace Lotus\CreateUser\Api;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Manages the REST API endpoint for creating users.
 * 
 * @since 1.0.0
 */
final class RestController {

	private string $namespace = 'lotus/v1';

	/**
	 * Register the hooks related to the REST API.
	 */
	public function register_hooks(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register the REST API routes.
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace,
			'/create-user',
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'create_user_callback' ],
				'permission_callback' => [ $this, 'permission_check' ],
				'args'                => $this->get_endpoint_args()
			]
		);
	}

	/**
	 * Ensure the request has a valid Bearer token.
	 */
	public function permission_check(): bool|WP_Error {
		$header    = isset( $_SERVER['HTTP_AUTHORIZATION'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_AUTHORIZATION'] ) ) : '';
		$api_token = get_option( 'lotus_user_create_api_token' );

		if ( ! $api_token ) {
			return new WP_Error( 'api_token_not_found', __( 'The API token has not been generated.', 'lotus-create-user' ) );
		}

		if ( ! empty( $header ) && preg_match( '/^Bearer\s(\S+)/', $header, $matches ) ) {
			if ( hash_equals( $api_token, $matches[1] ) ) {
				return true;
			}
		}

		return new WP_Error( 'rest_forbidden', __( 'Missing or invalid authentication token.', 'lotus-create-user' ), [ 'status' => 401 ] );
	}

	/**
	 * Main callback to create user.
	 */
	public function create_user_callback( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$params = $request->get_params();

		$user_data = [
			'user_login'   => $params['username'],
			'user_pass'    => wp_generate_password(),
			'display_name' => $params['first_name'] . ' ' . $params['last_name'],
			'role'         => 'customer',
			'first_name'   => $params['first_name'],
			'last_name'    => $params['last_name'],
		];

		$user_id = wp_insert_user( $user_data );

		if ( is_wp_error( $user_id ) ) {
			return new WP_Error( 'user_creation_failed', $user_id->get_error_message(), [ 'status' => 400 ] );
		}

		update_user_meta( $user_id, 'billing_first_name', $params['first_name'] );
		update_user_meta( $user_id, 'billing_last_name', $params['last_name'] );
		update_user_meta( $user_id, 'billing_phone', $params['phone'] );

		$response_data = [
			'user_id' => $user_id,
			'message' => __( 'User created successfully.', 'lotus-create-user' ),
			'created' => true
		];

		return new WP_REST_Response( $response_data, 201 );
	}

	/**
	 * Get the arguments for the endpoint.
	 */
	private function get_endpoint_args(): array {
		return [
			'username'   => [
				'required'          => true,
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => [ $this, 'validate_username' ]
			],
			'phone'      => [
				'required'          => true,
				'sanitize_callback' => 'sanitize_text_field'
			],
			'first_name' => [
				'required'          => true,
				'sanitize_callback' => 'sanitize_text_field'
			],
			'last_name'  => [
				'required'          => true,
				'sanitize_callback' => 'sanitize_text_field'
			]
		];
	}

	public function validate_username( $username ): bool|WP_Error {
		if ( username_exists( $username ) ) {
			return new WP_Error( 'username_exists', __( 'Username already exists', 'lotus-create-user' ) );
		}

		return true;
	}
}
