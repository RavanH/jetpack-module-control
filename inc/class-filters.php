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
class Filters {
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
		// Check if multisite or subsite override allowed.
		if ( ! is_multisite() || get_site_option( 'jetpack_mc_subsite_override' ) ) {
			$all    = wp_load_alloptions();
			$option = isset( $all['jetpack_mc_manual_control'] ) ? $all['jetpack_mc_manual_control'] : '';
		} else {
			$option = get_site_option( 'jetpack_mc_manual_control' );
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
		// Check if multisite or subsite override allowed.
		if ( ! is_multisite() || get_site_option( 'jetpack_mc_subsite_override' ) ) {
			$all    = wp_load_alloptions();
			$option = isset( $all['jetpack_mc_development_mode'] ) ? $all['jetpack_mc_development_mode'] : '';
		} else {
			$option = get_site_option( 'jetpack_mc_development_mode' );
		}

		return ! empty( $option );
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
		// Check if multisite or subsite override allowed.
		if ( ! is_multisite() || get_site_option( 'jetpack_mc_subsite_override' ) ) {
			$all       = wp_load_alloptions();
			$blacklist = isset( $all['jetpack_mc_blacklist'] ) ? $all['jetpack_mc_blacklist'] : '';
		} else {
			$blacklist = get_site_option( 'jetpack_mc_blacklist' );
		}

		foreach ( (array) $blacklist as $mod ) {
			if ( isset( $modules[ $mod ] ) ) {
				unset( $modules[ $mod ] );
			}
		}

		return $modules;
	}
}
