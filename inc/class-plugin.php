<?php
/**
 * Module Control for Jetpack Filters
 *
 * @package Module Control for Jetpack
 * @since 1.7
 */

namespace JMC;

/**
 * Module Control for Jetpack Filters Class
 *
 * Since 1.7
 */
class Plugin {
	/**
	 * Holds the blacklist of Jetpack modules.
	 *
	 * @since 1.7
	 * @var bool|null
	 */
	private static $manual_control;

	/**
	 * Holds the blacklist of Jetpack modules.
	 *
	 * @since 1.7
	 * @var bool|null
	 */
	private static $development_mode;

	/**
	 * Holds the blacklist of Jetpack modules.
	 *
	 * @since 1.7
	 * @var array|null
	 */
	private static $blacklist;

	/**
	 * Gets subsite or site option
	 *
	 * @since 1.7
	 * @see get_network_option(), wp_load_alloptions(), is_plugin_active_for_network()
	 * @param string $option_name The option name to retrieve.
	 * @return mixed
	 */
	public static function get_option( $option_name ) {
		$network = \is_multisite();

		// Network active and subsite override not allowed, return network option.
		if ( $network && ! \get_network_option( null, 'jetpack_mc_subsite_override' ) ) {
			return \get_network_option( null, $option_name );
		}

		// Get our autoload setting from wp_load_alloptions to avoid loading the option table when option is not set.
		$all   = \wp_load_alloptions();
		$value = isset( $all[ $option_name ] ) ? \maybe_unserialize( $all[ $option_name ] ) : false;

		// Fall back on network setting if necessary.
		if ( $network && false === $value ) {
			$value = \get_network_option( null, $option_name );
		}

		return $value;
	}

	/**
	 * Activates Manual Control by returning an empty array on module auto-activation.
	 * First modelled after Manual Control for Jetpack by Mark Jaquith http://coveredwebservices.com/
	 * To be converted to allow selected modules instead of all or none.
	 *
	 * Hooked to jetpack_get_default_modules filter.
	 *
	 * @since 0.1
	 * @param array $modules Modules array.
	 * @return array Empty array if Manual Control is enabled, otherwise the modules array.
	 */
	public static function manual_control( $modules ) {
		if ( null === self::$manual_control ) {
			self::$manual_control = self::get_option( 'jetpack_mc_manual_control' );
		}

		return ! empty( self::$manual_control ) ? array() : $modules;
	}

	/**
	 * Activates Development Mode by returning true on jetpack_development_mode filter.
	 * Based on http://jeremy.hu/customize-the-list-of-modules-available-in-jetpack/
	 *
	 * Hooked to jetpack_offline_mode filter.
	 *
	 * @since 1.0
	 * @return bool True if development mode is enabled, false otherwise.
	 */
	public static function development_mode() {
		if ( null === self::$development_mode ) {
			self::$development_mode = self::get_option( 'jetpack_mc_development_mode' );
		}

		return ! empty( self::$development_mode );
	}

	/**
	 * Blacklist Jetpack modules
	 * Modelled after ParhamG's blacklist_jetpack_modules.php https://gist.github.com/ParhamG/
	 *
	 * Hooked to jetpack_get_available_modules filter.
	 *
	 * @since 0.1
	 * @param array $modules Modules array.
	 * @return array Allowed modules after unsetting blacklisted modules from all modules array
	 */
	public static function blacklist( $modules ) {
		if ( null === self::$blacklist ) {
			$blacklist       = self::get_option( 'jetpack_mc_blacklist' );
			self::$blacklist = ! empty( $blacklist ) ? \array_flip( (array) $blacklist ) : array();
		}

		return ! empty( self::$blacklist ) ? \array_diff_key( $modules, self::$blacklist ) : $modules;
	}
}
