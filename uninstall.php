<?php
/**
 * Uninstall Jetpack Module Control.
 *
 * @package JMC
 */

// Exit if uninstall not called from WordPress.
defined( 'WP_UNINSTALL_PLUGIN' ) || exit();

$default_options = array(
	'jetpack_mc_manual_control',
	'jetpack_mc_development_mode',
	'jetpack_mc_subsite_override',
	'jetpack_mc_blacklist',
);

if ( is_multisite() ) {
	// Get sites in the network.
	$args  = array(
		'fields'                 => 'ids',
		'number'                 => 1000, // Limit to 1000 sites.
		'update_site_meta_cache' => false,
	);
	$blogs = get_sites( $args );

	foreach ( $blogs as $_id ) {
		switch_to_blog( $_id );
		// Remove site options.
		foreach ( $default_options as $option ) {
			delete_option( $option );
		}
		restore_current_blog();
	}
	// Remove network options.
	foreach ( $default_options as $option ) {
		delete_site_option( $option );
	}
} else {
	// Remove site options.
	foreach ( $default_options as $option ) {
		delete_option( $option );
	}
}
