<?php
/**
 * Plugin Name: Module Control for Jetpack
 * Plugin URI: https://status301.net/wordpress-plugins/jetpack-module-control/
 * Description: This plugin brings additional control over Jetpack modules. You can blacklist / remove individual modules, prevent auto-activation or allow activation without a WordPress.com account.
 * Author: RavanH
 * Author URI: https://status301.net/
 * Text Domain: jetpack-module-control
 * License: GPL2+
 * Version: 1.7.5
 *
 * @package Module Control for Jetpack
 */

defined( 'WPINC' ) || die( 'No direct access allowed.' );

define( 'JMC_BASENAME', plugin_basename( __FILE__ ) );

add_filter( 'jetpack_get_default_modules', array( '\JMC\Plugin', 'manual_control' ), 99 );
add_filter( 'jetpack_offline_mode', array( '\JMC\Plugin', 'development_mode' ) );
add_filter( 'jetpack_get_available_modules', array( '\JMC\Plugin', 'blacklist' ) );

add_action( 'admin_init', array( '\JMC\Admin', 'init' ), 11 );
add_action( 'admin_menu', array( '\JMC\Admin', 'control_submenus' ), 1001 );
add_filter( 'wp_default_autoload_value', array( '\JMC\Admin', 'autoload_value' ), 10, 2 );
add_action( 'admin_head', array( '\JMC\Admin', 'hide_offline_notice' ) );

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
		$file       = realpath( __DIR__ ) . DIRECTORY_SEPARATOR . \implode( DIRECTORY_SEPARATOR, $path_array ) . DIRECTORY_SEPARATOR . 'class-' . $class_name . '.php';

		// If the file exists for the class name, load it.
		if ( file_exists( $file ) ) {
			include_once $file;
		}
	}
);
