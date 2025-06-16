<?php
/**
 * Jetpack Module Control Filters
 *
 * @package Module Control for Jetpack
 * @since 1.7
 */

namespace JMC;

/**
 * Jetpack Module Control Filters Class
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
	 * Initializes the plugin by setting up filters and static properties.
	 *
	 * @since 1.7
	 */
	public static function init() {
		// Initialize the static properties.
		self::$manual_control   = null;
		self::$development_mode = null;
		self::$blacklist        = null;

		// Check if Jetpack is active before adding filters.
		if ( \class_exists( 'Jetpack' ) ) {
			// Add filters for Jetpack module control.
			\add_filter( 'jetpack_get_default_modules', array( __CLASS__, 'manual_control' ), 99 );
			\add_filter( 'jetpack_offline_mode', array( __CLASS__, 'development_mode' ) );
			\add_filter( 'jetpack_get_available_modules', array( __CLASS__, 'blacklist' ) );
		}
	}

	/**
	 * Gets subsite or site option
	 *
	 * @since 1.7
	 * @see get_site_option(), wp_load_alloptions(), is_multisite()
	 * @uses jetpack_mc_manual_control network option
	 *
	 * @param string $option_name The option name to retrieve.
	 *
	 * @return mixed
	 */
	public static function get_option( $option_name ) {
		$value = false;

		// check if subsite override allowed.
		if ( ! \is_multisite() || \get_site_option( 'jetpack_mc_subsite_override' ) ) {
			// Get our autoload setting from wp_load_alloptions to avoid loading the option table.
			// $all   = \wp_load_alloptions();
			// $value = isset( $all[ $option_name ] ) ? \maybe_unserialize( $all[ $option_name ] ) : false;.

			// Get the option value.
			$value = \get_option( $option_name );
		}

		// fall back on network setting.
		if ( false === $value && \is_multisite() ) {
			$value = \get_site_option( $option_name );
		}

		return $value;
	}

	/**
	 * Activates Manual Control by returning an empty array on module auto-activation.
	 * First modelled after Manual Control for Jetpack by Mark Jaquith http://coveredwebservices.com/
	 * To be converted to allow selected modules instead of all or none.
	 *
	 * @since 0.1
	 * @see add_filter()
	 * @param array $modules Modules array.
	 */
	public static function manual_control( $modules ) {
		if ( null === self::$manual_control ) {
			self::$manual_control = self::get_option( 'jetpack_mc_manual_control' );
		}

		return ! empty( $option ) ? array() : $modules;
	}

	/**
	 * Activates Development Mode by returning true on jetpack_development_mode filter.
	 * Based on http://jeremy.hu/customize-the-list-of-modules-available-in-jetpack/
	 *
	 * @since 1.0
	 * @see add_filter()
	 */
	public static function development_mode() {
		if ( null === self::$development_mode ) {
			self::$development_mode = self::get_option( 'jetpack_mc_development_mode' );
		}

		return ! empty( self::$development_mode );
	}

	/**
	 * Blacklist Jetpack modules
	 * Modelled after ParhamG's blacklist_jetpack_modules.php https://gist.github.com/ParhamG/6494979
	 *
	 * @since 0.1
	 * @param array $modules Modules array.
	 *
	 * @return Array Allowed modules after unsetting blacklisted modules from all modules array
	 */
	public static function blacklist( $modules ) {
		if ( null === self::$blacklist ) {
			$blacklist       = self::get_option( 'jetpack_mc_blacklist' );
			self::$blacklist = ! empty( $blacklist ) ? \array_flip( (array) $blacklist ) : array();
		}

		return ! empty( self::$blacklist ) ? \array_diff_key( $modules, self::$blacklist ) : $modules;
	}
}
