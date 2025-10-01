<?php

declare(strict_types=1);

namespace Lotus\CreateUser\Admin;

/**
 * Admin Settings Class
 * 
 * @since 1.0.0
 */
final class Settings {

	/**
	 * Register the hooks related to admin settings.
	 */
	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'handle_reset_token' ] );
	}

	/**
	 * Handle the reset token action.
	 */
	public function handle_reset_token(): void {
		if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'lotus_reset_token' ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		check_admin_referer( 'lotus_reset_token_action', 'lotus_reset_token_nonce' );

		$new_token = wp_generate_password( 64, false );
		update_option( 'lotus_user_create_api_token', $new_token, false );

		add_settings_error(
			'lotus-create-user-notices',
			'lotus-reset-success',
			esc_html__( 'API token has been reset.', 'lotus-create-user' ),
			'success'
		);

		$redirect_url = admin_url( 'options-general.php' );
		$redirect_url = add_query_arg( [
			'page'   => 'lotus-create-user',
			'status' => 'lotus_token_reset_success'
		],
			$redirect_url
		);

		wp_safe_redirect( $redirect_url );
		exit;
	}

	/**
	 * Add the settings page to the admin menu.
	 */
	public function add_settings_page(): void {
		add_options_page(
			esc_html__( 'Lotus User Create API', 'lotus-create-user' ),
			esc_html__( 'Lotus User Create API', 'lotus-create-user' ),
			'manage_options',
			'lotus-create-user',
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Render the settings page content.
	 */
	public function render_settings_page(): void {
		$messages = [
			'lotus_token_reset_success' => esc_html__( 'API token has been reset.', 'lotus-create-user' )
		]
			?>
		<div class="wrap">
			<h1><?php esc_html_e( get_admin_page_title() ) ?></h1>

			<?php
			if ( isset( $_GET['status'] ) && isset( $messages[ $_GET['status'] ] ) ) {
				echo '<div class="notice notice-success is-dismissible"><p>' . $messages[ $_GET['status'] ] . '</p></div>';
			}
			?>

			<p>
				<?php esc_html_e( 'This plugin provides a secure REST API endpoint to create new users.', 'lotus-create-user' ); ?>
			</p>

			<h2 style="margin-top: 1rem;"><?php esc_html_e( 'Your API Token', 'lotus-create-user' ); ?></h2>
			<p>
				<?php esc_html_e( "Use this unique token in the 'Authorization' header of your API requests, prefixed with 'Bearer'.", 'lotus-create-user' ); ?>
			</p>

			<input type="text" value="<?php esc_attr_e( get_option( 'lotus_user_create_api_token' ) ); ?>" readonly
				style="width: 100%; max-width: 500px; padding: 8px;" onclick="this.select();">
			<p class="description"><?php esc_html_e( 'Click the field above to select the token.', 'lotus-create-user' ); ?></p>

			<hr style="margin-top: 1rem" />

			<h2><?php esc_html_e( 'Reset Token', 'lotus-create-user' ); ?></h2>
			<p class="description" style="color: #d50000ff;">
				<strong><?php esc_html_e( 'Warning: ', 'lotus-create-user' ); ?></strong>
				<?php esc_html_e( 'Resetting the token will invalidate the existing API token.', 'lotus-create-user' ); ?>
			</p>

			<form method="post">
				<input type="hidden" name="action" value="lotus_reset_token">
				<?php
				wp_nonce_field( 'lotus_reset_token_action', 'lotus_reset_token_nonce' );
				submit_button( esc_html__( 'Reset Token', 'lotus-create-user' ) );
				?>
			</form>
		</div>
		<?php
	}
}
