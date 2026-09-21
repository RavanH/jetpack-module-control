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

		if ( \is_plugin_active_for_network( \JMC_BASENAME ) ) {
			// Check for network activation, else these will also take effect when
			// plugin is activated on the primary site alone.
			// TODO : see if you can actually use this scenario where plugin is activatied on site 1 and
			// network options can be set to serve as default settings for other site activations !

			// Add settings to Network Settings
			// thanks to http://zao.is/2013/07/adding-settings-to-network-settings-for-wordpress-multisite/.
			\add_filter( 'wpmu_options', array( '\JMC\Network', 'show_network_settings' ) );
			\add_action( 'update_wpmu_options', array( '\JMC\Network', 'save_network_settings' ) );
		}

		// Single site active or subsite override allowed.
		if ( ! \is_multisite() || ! \is_plugin_active_for_network( \JMC_BASENAME ) || \get_site_option( 'jetpack_mc_subsite_override' ) ) {
			// Do regular register/add_settings stuff in 'general' settings on options-general.php.
			$settings_page = 'jetpack-module-control';

			\add_settings_section( 'jetpack-module-control', null, array( __NAMESPACE__ . '\Settings', 'add_settings_section' ), $settings_page );

			// register settings.
			if ( ! \defined( 'JETPACK_MC_LOCKDOWN' ) || ! \JETPACK_MC_LOCKDOWN ) {
				\register_setting( $settings_page, 'jetpack_mc_manual_control', array( 'sanitize_callback' => 'absint' ) );
				\register_setting( $settings_page, 'jetpack_mc_development_mode', array( 'sanitize_callback' => 'absint' ) );
				\register_setting( $settings_page, 'jetpack_mc_blacklist', array( 'sanitize_callback' => array( __NAMESPACE__ . '\Settings', 'sanitize_blacklist' ) ) );
			}

			// add settings fields.
			\add_settings_field( 'jetpack_mc_manual_control', __( 'Manual Control', 'jetpack-module-control' ), array( __NAMESPACE__ . '\Settings', 'manual_control_settings' ), $settings_page, 'jetpack-module-control' );
			\add_settings_field( 'jetpack_mc_development_mode', __( 'Offline Mode', 'jetpack-module-control' ), array( __NAMESPACE__ . '\Settings', 'development_mode_settings' ), $settings_page, 'jetpack-module-control' );
			\add_settings_field( 'jetpack_mc_blacklist', __( 'Blacklist Modules', 'jetpack-module-control' ), array( __NAMESPACE__ . '\Settings', 'blacklist_settings' ), $settings_page, 'jetpack-module-control' );

			// Prepare for settings reset.
			\add_filter( 'pre_update_option_jetpack_mc_manual_control', array( __NAMESPACE__ . '\Settings', 'maybe_reset_option' ), 10, 3 );
			\add_filter( 'pre_update_option_jetpack_mc_development_mode', array( __NAMESPACE__ . '\Settings', 'maybe_reset_option' ), 10, 3 );
			\add_filter( 'pre_update_option_jetpack_mc_blacklist', array( __NAMESPACE__ . '\Settings', 'maybe_reset_option' ), 10, 3 );
		}

		// Plugin action links.
		\add_filter( 'plugin_action_links_' . \JMC_BASENAME, array( __CLASS__, 'action_links' ) );
		\add_filter( 'network_admin_plugin_action_links_' . \JMC_BASENAME, array( __CLASS__, 'action_links' ) );
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
	 * @return bool
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
	public static function action_links( $links ) {
		$settings_links = array();

		if ( ! \is_multisite() || ! \is_plugin_active_for_network( \JMC_BASENAME ) || \get_site_option( 'jetpack_mc_subsite_override' ) ) {
			$settings_links['settings'] = '<a href="' . \admin_url( 'admin.php?page=jetpack-module-control' ) . '">' . \esc_html( translate( 'Settings' ) ) . '</a>'; // phpcs:ignore WordPress.WP.I18n.LowLevelTranslationFunction
		}

		if ( \is_plugin_active_for_network( \JMC_BASENAME ) && current_user_can( 'manage_network_options' ) ) {
			$settings_links['network-settings'] = '<a href="' . \network_admin_url( 'settings.php#jetpack-mc' ) . '">' . \esc_html( translate( 'Network Settings' ) ) . '</a>'; // phpcs:ignore WordPress.WP.I18n.LowLevelTranslationFunction
		}

		return array_merge(
			$settings_links,
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
		// Make sure all known connection dependant submenus are removed.
		$devmode   = Plugin::development_mode();
		$blacklist = (array) Plugin::get_option( 'jetpack_mc_blacklist' );

		if ( $devmode || \in_array( 'ai', $blacklist ) ) {
			// Remove AI submenu.
			\remove_submenu_page( 'jetpack', 'jetpack-ai' );
		}

		if ( $devmode || \in_array( 'search', $blacklist ) ) {
			// Remove Jetpack Search submenu.
			\remove_submenu_page( 'jetpack', 'jetpack-search' );
		}

		if ( $devmode || \in_array( 'publicize', $blacklist ) ) {
			// Remove Jetpack Social submenu.
			\remove_submenu_page( 'jetpack', 'jetpack-social' );
		}

		if ( $devmode || \in_array( 'publicize', $blacklist ) ) {
			// Remove Jetpack Social submenu.
			\remove_submenu_page( 'jetpack', 'jetpack-newsletter' );
		}

		if ( \in_array( 'stats', $blacklist ) ) {
			\remove_menu_page( 'stats' );
		}

		if ( ! \is_multisite() || ! \is_plugin_active_for_network( \JMC_BASENAME ) || \get_site_option( 'jetpack_mc_subsite_override' ) ) {
			\add_submenu_page(
				'jetpack',
				__( 'Module Control', 'jetpack-module-control' ),
				__( 'Module Control', 'jetpack-module-control' ),
				'manage_options',
				'jetpack-module-control',
				array( __NAMESPACE__ . '\Settings', 'render_settings_page' ),
				999
			);
		}
	}

	/**
	 * Add admin style to hide offline notice.
	 *
	 * @since 1.7.5
	 */
	public static function hide_offline_notice() {
			if ( 'jetpack_page_jetpack_modules' === get_current_screen()->id && Plugin::development_mode() ) {
				echo '<style>.jetpack-admin-page .jetpack-offline-notice { display: none; }</style>';
			}
		}
}
