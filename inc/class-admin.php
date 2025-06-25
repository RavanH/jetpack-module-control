<?php
/**
 * Module Control for Jetpack Admin
 *
 * @package Module Control for Jetpack
 */

namespace JMC;

use JMC\Plugin;

/**
 * Module Control for Jetpack Admin Class
 *
 * Since 1.7
 */
class Admin {
	/**
	 * Initiate plugins admin stuff
	 *
	 * @since 0.1
	 */
	public static function init() {
		// Admin translations.
		\load_plugin_textdomain( 'jetpack-module-control' );

		if ( \is_plugin_active_for_network( JMC_BASENAME ) ) {
			// Check for network activation, else these will also take effect when
			// plugin is activated on the primary site alone.
			// TODO : see if you can actually use this scenario where plugin is activatied on site 1 and
			// network options can be set to serve as default settings for other site activations !

			// Add settings to Network Settings
			// thanks to http://zao.is/2013/07/adding-settings-to-network-settings-for-wordpress-multisite/.
			\add_filter( 'wpmu_options', array( '\JMC\Network', 'show_network_settings' ) );
			\add_action( 'update_wpmu_options', array( '\JMC\Network', 'save_network_settings' ) );

			// Plugin action links.
			\add_filter( 'network_admin_plugin_action_links_' . JMC_BASENAME, array( __CLASS__, 'add_action_link' ) );
		}

		// check if subsite override allowed.
		if ( ! \is_multisite() || \get_site_option( 'jetpack_mc_subsite_override' ) ) {
			// Plugin action links.
			\add_filter( 'plugin_action_links_' . JMC_BASENAME, array( __CLASS__, 'add_action_link' ) );

			// Do regular register/add_settings stuff in 'general' settings on options-general.php.
			$settings = 'general';

			\add_settings_section( 'jetpack-module-control', '<a name="jetpack-mc"></a>' . __( 'Module Control for Jetpack', 'jetpack-module-control' ), array( '\JMC\Settings', 'add_settings_section' ), $settings );

			// register settings.
			if ( ! \defined( 'JETPACK_MC_LOCKDOWN' ) || ! JETPACK_MC_LOCKDOWN ) {
				\register_setting( $settings, 'jetpack_mc_manual_control' );
				\register_setting( $settings, 'jetpack_mc_development_mode' );
				\register_setting( $settings, 'jetpack_mc_blacklist', array( 'sanitize_callback' => array( '\JMC\Settings', 'sanitize_blacklist' ) ) );
			}

			// add settings fields.
			\add_settings_field( 'jetpack_mc_manual_control', __( 'Manual Control', 'jetpack-module-control' ), array( '\JMC\Settings', 'manual_control_settings' ), $settings, 'jetpack-module-control' );
			\add_settings_field( 'jetpack_mc_development_mode', __( 'Offline Mode', 'jetpack-module-control' ), array( '\JMC\Settings', 'development_mode_settings' ), $settings, 'jetpack-module-control' );
			\add_settings_field( 'jetpack_mc_blacklist', __( 'Blacklist Modules', 'jetpack-module-control' ), array( '\JMC\Settings', 'blacklist_settings' ), $settings, 'jetpack-module-control' );
		}
	}

	/**
	 * Returns the autoload value for the option.
	 *
	 * @since 1.7
	 * @see add_filter()
	 *
	 * @param string $autoload The autoload value.
	 * @param string $option The option name.
	 *
	 * @return string The autoload value, 'no' if manual control is enabled.
	 */
	public static function autoload_value( $autoload, $option ) {
		$options_autoload = array(
			'jetpack_mc_manual_control',
			'jetpack_mc_development_mode',
			'jetpack_mc_blacklist',
		);

		return in_array( $option, $options_autoload, true ) ? true : $autoload;
	}

	/**
	 * Adds an action link on the Plugins page
	 *
	 * @since 0.1
	 * @see is_plugin_active_for_network(), admin_url(), network_admin_url()
	 *
	 * @param array $links Plugin de/activation and deletion links.
	 * @return array Plugin links plus Settings link.
	 */
	public static function add_action_link( $links ) {
		$settings_link = \is_plugin_active_for_network( JMC_BASENAME ) ?
			'<a href="' . \network_admin_url( 'settings.php#jetpack-mc' ) . '">' . \esc_html__( 'Network Settings' ) . '</a>' :
			'<a href="' . \admin_url( 'options-general.php#jetpack-mc' ) . '">' . \esc_html__( 'Settings' ) . '</a>';

		return array_merge(
			array( 'settings' => $settings_link ),
			$links
		);
	}

	/**
	 * Activate the plugin
	 *
	 * @since 1.7
	 * @param bool $network_wide Network activation.
	 */
	public static function activate( $network_wide ) {
		$default_options = array(
			'jetpack_mc_manual_control'   => '',
			'jetpack_mc_development_mode' => '',
			'jetpack_mc_blacklist'        => '',
		);

		if ( $network_wide ) {
			foreach ( $default_options as $option => $value ) {
				\add_site_option( $option, $value );
			}
			\add_site_option( 'jetpack_mc_subsite_override', '' );

			// Get sites in the network.
			$args  = array(
				'fields'                 => 'ids',
				'number'                 => 1000, // Limit to 1000 sites.
				'update_site_meta_cache' => false,
			);
			$blogs = \get_sites( $args );

			foreach ( $blogs as $_id ) {
				\switch_to_blog( $_id );

				// Re-enable autoload.
				\wp_set_options_autoload( array_keys( $default_options ), true );

				\restore_current_blog();
			}
		} else {
			foreach ( $default_options as $option => $value ) {
				// Re-enable autoload.
				\wp_set_options_autoload( array_keys( $default_options ), true );
			}
		}
	}

	/**
	 * Deactivate the plugin
	 *
	 * @since 1.7
	 * @param bool $network_wide Network deactivation.
	 */
	public static function deactivate( $network_wide ) {
		$options_autoload = array(
			'jetpack_mc_manual_control',
			'jetpack_mc_development_mode',
			'jetpack_mc_blacklist',
		);

		if ( $network_wide ) {
			// Get sites in the network.
			$args  = array(
				'fields'                 => 'ids',
				'number'                 => 1000, // Limit to 1000 sites.
				'update_site_meta_cache' => false,
			);
			$blogs = \get_sites( $args );

			foreach ( $blogs as $_id ) {
				\switch_to_blog( $_id );

				// Disable autoload.
				\wp_set_options_autoload( $options_autoload, false );

				\restore_current_blog();
			}
		} else {
			\wp_set_options_autoload( $options_autoload, false );
		}
	}

	/**
	 * Control admin submenus.
	 * 
	 * @since 1.7.2
	 */
	public static function control_submenus() {
		$blacklist = Plugin::get_option( 'jetpack_mc_blacklist' );

		if ( empty( $blacklist ) || Plugin::development_mode() ) {
			return;
		}

		if ( \in_array( 'search', $blacklist ) ) {
			// Remove Jetpack Search submenu.
			\remove_submenu_page( 'jetpack', 'jetpack-search' );
		}

		if ( \in_array( 'publicize', $blacklist ) ) {
			// Remove Jetpack Social submenu.
			\remove_submenu_page( 'jetpack', 'jetpack-social' );
		}
	}
}
