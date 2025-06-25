<?php
/**
 * Module Control for Jetpack Admin
 *
 * @package Module Control for Jetpack
 */

namespace JMC;

use JMC\Settings;

/**
 * Module Control for Jetpack Admin Class
 *
 * Since 1.7.1
 */
class Network {

	/**
	 * Saves the network settings
	 *
	 * @since 0.2
	 */
	public static function save_network_settings() {
		// Nonce verification for security.
		if (
			! isset( $_POST['_jetpack_mc_nonce'] ) ||
			! \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['_jetpack_mc_nonce'] ) ), 'jetpack_mc_network_settings' )
		) {
			\add_settings_error(
				'jetpack_mc_network_settings',
				'jetpack_mc_nonce_fail',
				\esc_html__( 'Security check failed. Module Control for Jetpack settings were not updated.', 'jetpack-module-control' ),
				'error'
			);
			return;
		}

		// Get sanitized blacklist from POST data.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$blacklist = isset( $_POST['jetpack_mc_blacklist'] ) ? Settings::sanitize_blacklist( \wp_unslash( $_POST['jetpack_mc_blacklist'] ) ) : false;

		// Construct the settings array to save.
		$settings = array(
			'jetpack_mc_manual_control'   => isset( $_POST['jetpack_mc_manual_control'] ),
			'jetpack_mc_development_mode' => isset( $_POST['jetpack_mc_development_mode'] ),
			'jetpack_mc_blacklist'        => $blacklist,
			'jetpack_mc_subsite_override' => isset( $_POST['jetpack_mc_subsite_override'] ),
		);

		foreach ( $settings as $name => $value ) {
			\update_site_option( $name, $value );
		}
	}

	/**
	 * Prints the network settings
	 *
	 * @since 0.2
	 */
	public static function show_network_settings() {
		$subsite_override = \get_site_option( 'jetpack_mc_subsite_override' );
		?>
		<h3><a name="jetpack-mc"></a><?php \esc_html_e( 'Module Control for Jetpack', 'jetpack-module-control' ); ?></h3>
		<?php
			Settings::add_settings_section( '' );
			// Add nonce field for security.
			\wp_nonce_field( 'jetpack_mc_network_settings', '_jetpack_mc_nonce' );
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php \esc_html_e( 'Sub-site Override', 'jetpack-module-control' ); ?></th>
					<td>
						<label>
							<input type='checkbox' name='jetpack_mc_subsite_override' value='1' <?php \checked( $subsite_override, '1' ); ?>>
							<?php \esc_html_e( 'Allow individual site administrators to manage their own settings for Module Control for Jetpack', 'jetpack-module-control' ); ?>
						</label>
						<p class="description"><?php printf( /* translators: General Settings */ \esc_html__( 'This adds the below options to each sub-site %s. Their settings here will be treated as default settings.', 'jetpack-module-control' ), esc_html( translate( 'General Settings' ) ) ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php \esc_html_e( 'Manual Control', 'jetpack-module-control' ); ?></th>
					<td>
						<?php
						Settings::manual_control_settings();
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php \esc_html_e( 'Development Mode', 'jetpack-module-control' ); ?></th>
					<td>
						<?php
						Settings::development_mode_settings();
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php \esc_html_e( 'Blacklist Modules', 'jetpack-module-control' ); ?></th>
					<td>
						<?php
						Settings::blacklist_settings();
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
}
