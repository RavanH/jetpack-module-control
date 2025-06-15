<?php
/**
 * Plugin Name: Module Control for Jetpack
 * Plugin URI: https://status301.net/wordpress-plugins/jetpack-module-control/
 * Description: This plugin brings additional control over Jetpack modules. You can blacklist / remove individual modules, prevent auto-activation or allow activation without a WordPress.com account.
 * Author: RavanH
 * Author URI: https://status301.net/
 * Requires Plugins: jetpack
 * Network: true
 * Text Domain: jetpack-module-control
 * License: GPL2+
 * Version: 1.7-alpha3
 *
 * @package Module Control for Jetpack
 */

/*
 * ROADMAP
 *
 * version 2.0
 * Replace "Prevent the Jetpack plugin from auto-activating (new) modules" with
 * finer grained "Select which modules to auto-activate"
 * see http://jeremy.hu/customize-the-list-of-modules-available-in-jetpack/
 *  function jeherve_auto_activate_stats() {
		return array( 'stats' );
	}
	add_filter( 'jetpack_get_default_modules', 'jeherve_auto_activate_stats' );
 *
 * TO CONSIDER
 *
 * Make blacklist or whitelist optional
 *
 * Option to disable JUMPSTART with "Jetpack_Options::update_option( 'jumpstart', 'jumpstart_dismissed' );" ??
 * or do we need to go through apply_filters( 'jetpack_module_feature' ...
 * If we want to be able to select which modules should appear in Jumpstart later!
 *
 * Can we disable Debug link in the footer menu?
 *
 * Option to "force_deactivate" (same as blacklist?) as described on https://github.com/Automattic/jetpack/issues/1452
 *
 */

defined( 'WPINC' ) || die( 'No direct access allowed.' );

define( 'JMC_BASENAME', plugin_basename( __FILE__ ) );
define( 'JMC_DIR', __DIR__ );

add_filter( 'jetpack_get_default_modules', array( '\JMC\Filters', 'manual_control' ), 99 );
add_filter( 'jetpack_offline_mode', array( '\JMC\Filters', 'development_mode' ) );
add_filter( 'jetpack_get_available_modules', array( '\JMC\Filters', 'blacklist' ) );

add_action( 'admin_init', array( '\JMC\Admin', 'init' ), 11 );

register_activation_hook( __FILE__, array( '\JMC\Admin', 'activate' ) );
register_deactivation_hook( __FILE__, array( '\JMC\Admin', 'deactivate' ) );

/**
 * Register JMC autoloader
 * http://justintadlock.com/archives/2018/12/14/php-namespaces-for-wordpress-developers
 *
 * @since 1.7
 *
 * @param string $class_name Namespaced class name.
 */
spl_autoload_register(
	function ( $class_name ) {
		// Bail if the class is not in our namespace.
		if ( 0 !== strpos( $class_name, 'JMC\\' ) ) {
			return;
		}

		// Build the filename and path.
		$class_name = str_replace( 'JMC', 'inc', $class_name );
		$class_name = strtolower( $class_name );
		$path_array = explode( '\\', $class_name );
		$class_name = array_pop( $path_array );
		$class_name = str_replace( '_', '-', $class_name );
		$file       = realpath( JMC_DIR ) . DIRECTORY_SEPARATOR . \implode( DIRECTORY_SEPARATOR, $path_array ) . DIRECTORY_SEPARATOR . 'class-' . $class_name . '.php';

		// If the file exists for the class name, load it.
		if ( file_exists( $file ) ) {
			include_once $file;
		}
	}
);
