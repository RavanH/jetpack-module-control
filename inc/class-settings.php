<?php
/**
 * Module Control for Jetpack Settings
 *
 * @package Module Control for Jetpack
 */

namespace JMC;

use JMC\Plugin;

/**
 * Module Control for Jetpack Settings Class
 *
 * Since 1.7.1
 */
class Settings {
	/**
	 * Available modules array
	 *
	 * @since 0.1
	 * @access  private
	 * @var array
	 */
	private static $modules = null;

	/**
	 * Know modules array with names
	 *
	 * @since 0.2
	 * @access  private
	 * @var array
	 */
	private static $known_modules = array(
		'account-protection'    => array(
			'name'                => 'Account protection',
			'requires_connection' => true,
		),
		'wordads'               => array(
			'name'                => 'Ads',
			'requires_connection' => true,
		),
		'blaze'                 => array(
			'name'                => 'Blaze',
			'requires_connection' => true,
		),
		'blocks'                => array(
			'name'                => 'Blocks',
			'requires_connection' => false,
		),
		'carousel'              => array(
			'name'                => 'Carousel',
			'requires_connection' => false,
		),
		'comments'              => array(
			'name'                => 'Comments',
			'requires_connection' => true,
		),
		'comment-likes'         => array(
			'name'                => 'Comment Likes',
			'requires_connection' => true,
		),
		'contact-form'          => array(
			'name'                => 'Contact Form',
			'requires_connection' => false,
		),
		'copy-post'             => array(
			'name'                => 'Copy Post',
			'requires_connection' => false,
		),
		'custom-content-types'  => array(
			'name'                => 'Custom content types',
			'requires_connection' => false,
		),
		'google-fonts'          => array(
			'name'                => 'Google Fonts (Beta)',
			'requires_connection' => false,
		),
		'gravatar-hovercards'   => array(
			'name'                => 'Gravatar Hovercards',
			'requires_connection' => false,
		),
		'infinite-scroll'       => array(
			'name'                => 'Infinite Scroll',
			'requires_connection' => false,
		),
		'json-api'              => array(
			'name'                => 'JSON API',
			'requires_connection' => true,
		),
		'latex'                 => array(
			'name'                => 'Beautiful Math',
			'requires_connection' => false,
		),
		'waf'                   => array(
			'name'                => 'Firewall',
			'requires_connection' => true,
		),
		'likes'                 => array(
			'name'                => 'Likes',
			'requires_connection' => true,
		),
		'markdown'              => array(
			'name'                => 'Markdown',
			'requires_connection' => false,
		),
		'monitor'               => array(
			'name'                => 'Monitor',
			'requires_connection' => true,
		),
		'notes'                 => array(
			'name'                => 'Notifications',
			'requires_connection' => true,
		),
		'photon'                => array(
			'name'                => 'Image CDN',
			'requires_connection' => true,
		),
		'photon-cdn'            => array(
			'name'                => 'Asset CDN',
			'requires_connection' => false,
		),
		'post-by-email'         => array(
			'name'                => 'Post by email',
			'requires_connection' => true,
		),
		'post-list'             => array(
			'name'                => 'Post List',
			'requires_connection' => false,
		),
		'protect'               => array(
			'name'                => 'Brute force protection',
			'requires_connection' => true,
		),
		'publicize'             => array(
			'name'                => 'Publicize',
			'requires_connection' => true,
		),
		'related-posts'         => array(
			'name'                => 'Related posts',
			'requires_connection' => true,
		),
		'search'                => array(
			'name'                => 'Search',
			'requires_connection' => true,
		),
		'seo-tools'             => array(
			'name'                => 'SEO Tools',
			'requires_connection' => false,
		),
		'sharedaddy'            => array(
			'name'                => 'Sharing',
			'requires_connection' => false,
		),
		'shortcodes'            => array(
			'name'                => 'Shortcode Embeds',
			'requires_connection' => false,
		),
		'shortlinks'            => array(
			'name'                => 'WP.me Shortlinks',
			'requires_connection' => true,
		),
		'sitemaps'              => array(
			'name'                => 'Sitemaps',
			'requires_connection' => false,
		),
		'sso'                   => array(
			'name'                => 'Secure Sign On',
			'requires_connection' => true,
		),
		'stats'                 => array(
			'name'                => 'Jetpack Stats',
			'requires_connection' => true,
		),
		'subscriptions'         => array(
			'name'                => 'Newsletter',
			'requires_connection' => true,
		),
		'tiled-gallery'         => array(
			'name'                => 'Tiled Galleries',
			'requires_connection' => false,
		),
		'vaultpress'            => array(
			'name'                => 'Backups and Scanning',
			'requires_connection' => true,
		),
		'verification-tools'    => array(
			'name'                => 'Site verification',
			'requires_connection' => false,
		),
		'videopress'            => array(
			'name'                => 'VideoPress',
			'requires_connection' => true,
		),
		'widget-visibility'     => array(
			'name'                => 'Widget Visibility',
			'requires_connection' => false,
		),
		'widgets'               => array(
			'name'                => 'Extra Sidebar Widgets',
			'requires_connection' => false,
		),
		'woocommerce-analytics' => array(
			'name'                => 'WooCommerce Analytics',
			'requires_connection' => true,
		),
	);

	/**
	 * Know modules array with dashicons
	 * https://developer.wordpress.org/resource/dashicons/
	 *
	 * @since 0.3
	 * @access  private
	 * @var array
	 */
	private static $known_modules_icons = array(
		'account-protection'    => 'superhero',
		'wordads'               => 'money-alt',
		'blaze'                 => 'megaphone',
		'blocks'                => 'block-default',
		'carousel'              => 'camera',
		'comments'              => 'format-chat',
		'comment-likes'         => 'star-filled',
		'contact-form'          => 'feedback',
		'copy-post'             => 'admin-page',
		'custom-content-types'  => 'media-default',
		'google-fonts'          => 'editor-textcolor',
		'gravatar-hovercards'   => 'id', // not available.
		'infinite-scroll'       => 'arrow-down-alt',
		'json-api'              => 'rest-api',
		'latex'                 => 'editor-customchar',
		'likes'                 => 'star-filled',
		'markdown'              => 'editor-code',
		'monitor'               => 'flag',
		'notes'                 => 'admin-comments',
		'photon'                => 'performance',
		'photon-cdn'            => 'performance',
		'post-by-email'         => 'email',
		'post-list'             => 'list-view',
		'protect'               => 'lock',
		'publicize'             => 'share',
		'related-posts'         => 'update',
		'search'                => 'search',
		'seo-tools'             => 'chart-bar',
		'sharedaddy'            => 'share-alt',
		'shortcodes'            => 'shortcode',
		'shortlinks'            => 'admin-links',
		'sitemaps'              => 'networking',
		'sso'                   => 'wordpress-alt',
		'stats'                 => 'chart-area',
		'subscriptions'         => 'email',
		'tiled-gallery'         => 'layout',
		'vaultpress'            => 'vault',
		'verification-tools'    => 'yes-alt',
		'waf'                   => 'shield',
		'videopress'            => 'format-video',
		'widget-visibility'     => 'visibility',
		'widgets'               => 'welcome-widgets-menus',
		'woocommerce-analytics' => 'cart',
	);

	/**
	 * Return Jetpack available modules
	 *
	 * @since 0.1
	 * @return array
	 */
	private static function get_available_modules() {
		if ( null === self::$modules ) {
			if ( \class_exists( 'Jetpack' ) ) {
				\remove_filter( 'jetpack_get_available_modules', array( '\JMC\Plugin', 'blacklist' ) );
				$modules = array();
				foreach ( \Jetpack::get_available_modules() as $slug ) {
					$module = \Jetpack::get_module( $slug );
					if ( $module ) {
						$modules[ $slug ] = $module;
					}
				}
				self::$modules = $modules;
				\add_filter( 'jetpack_get_available_modules', array( '\JMC\Plugin', 'blacklist' ) );
			} else {
				self::$modules = self::$known_modules;
			}
		}

		return \apply_filters( 'jmc_get_available_modules', self::$modules );
	}

	/**
	 * Get the Jetpack Without WordPress.com option
	 *
	 * @since 1.6
	 *
	 * @return bool|string
	 */
	private static function get_development_mode() {
		if ( \is_network_admin() ) {
			// we're in network admin.
			if ( \is_plugin_active_for_network( 'slimjetpack/slimjetpack.php' ) || \is_plugin_active_for_network( 'unplug-jetpack/unplug-jetpack.php' ) ) {
				$option = '1';
			} else {
				// retrieve network settings.
				$option = \get_site_option( 'jetpack_mc_development_mode' );
			}
		} elseif ( \is_plugin_active( 'slimjetpack/slimjetpack.php' ) || \is_plugin_active( 'unplug-jetpack/unplug-jetpack.php' ) ) {
			$option = '1';
		} else {
			$option = Plugin::get_option( 'jetpack_mc_development_mode' );
		}

		return $option;
	}

	/**
	 * Adds the Manual Control option
	 *
	 * @since 0.1
	 * @see get_site_option(), checked(), disabled()
	 *
	 * @uses jetpack_mc_manual_control network option
	 * @echo Html Checkbox input field for jetpack_mc_manual_control option
	 * @return void
	 */
	public static function manual_control_settings() {
		if ( \is_network_admin() ) {
			// we're in network admin.
			if ( \is_plugin_active_for_network( 'manual-control/manual-control.php' ) ) {
				$disabled = true;
				$option   = '1';
			} else {
				// retrieve network settings.
				$option   = \get_site_option( 'jetpack_mc_manual_control' );
				$disabled = false;
			}
		} elseif ( \is_plugin_active( 'manual-control/manual-control.php' ) ) {
			$option   = '1';
			$disabled = true;
		} else {
			$option   = Plugin::get_option( 'jetpack_mc_manual_control' );
			$disabled = \defined( 'JETPACK_MC_LOCKDOWN' ) && JETPACK_MC_LOCKDOWN ? true : false;
		}

		?>
		<label>
			<input type='checkbox' name='jetpack_mc_manual_control' value='1'
			<?php \checked( $option, '1' ); ?>
			<?php \disabled( $disabled ); ?>>
			<?php \esc_html_e( 'Prevent Jetpack from auto-activating (new) modules', 'jetpack-module-control' ); ?>
		</label>
		<p class="description"><?php printf( /* translators: the Protect module name */ \esc_html__( 'Note: The module %s is excepted from this rule.', 'jetpack-module-control' ), esc_html_x( 'Protect', 'Module Name', 'jetpack' ) ); ?></p>
		<?php
	}

	/**
	 * Adds the Jetpack Without WordPress.com option
	 *
	 * @since 1.0
	 * @see get_site_option(), checked(), disabled()
	 *
	 * @echo Html Checkbox input field for jetpack_mc_development_mode option
	 * @return void
	 */
	public static function development_mode_settings() {
		$option   = self::get_development_mode();
		$disabled = ! \is_network_admin() && \defined( 'JETPACK_MC_LOCKDOWN' ) && JETPACK_MC_LOCKDOWN ? true : false;

		if ( ( \is_network_admin() && ( \is_plugin_active_for_network( 'slimjetpack/slimjetpack.php' ) || \is_plugin_active_for_network( 'unplug-jetpack/unplug-jetpack.php' ) ) ) || \is_plugin_active( 'slimjetpack/slimjetpack.php' ) || \is_plugin_active( 'unplug-jetpack/unplug-jetpack.php' ) ) {
			$disabled = true;
		}

		?>
		<label>
			<input type='checkbox' name='jetpack_mc_development_mode' value='1'
			<?php \checked( $option, '1' ); ?>
			<?php \disabled( $disabled ); ?>>
			<?php \esc_html_e( 'Use Jetpack without a WordPress.com connection', 'jetpack-module-control' ); ?>
		</label>
		<p class="description"><?php \esc_html_e( 'By forcing Jetpack into development mode, modules are used without a WordPress.com account. All modules that require a WordPress.com connection will be unavailable. These modules are marked with an asterisk (*) below. The admin message about Jetpack running in development mode will be hidden.', 'jetpack-module-control' ); ?></p>
		<?php
	}

	/**
	 * Adds a checkmark list of modules to blacklist
	 *
	 * @since 0.1
	 * @see get_site_option(), checked()
	 *
	 * @uses jetpack_mc_blacklist network option
	 * @echo Html Checkbox input field table for jetpack_mc_blacklist option
	 */
	public static function blacklist_settings() {
		if ( \is_network_admin() ) {
			// in network admin retrieve network settings.
			$blacklist = \get_site_option( 'jetpack_mc_blacklist', array() );
			$disabled  = false;
		} else {
			$blacklist = Plugin::get_option( 'jetpack_mc_blacklist' );
			$disabled  = \defined( 'JETPACK_MC_LOCKDOWN' ) && JETPACK_MC_LOCKDOWN ? true : false;
		}

		$devmode = self::get_development_mode();

		// blacklist must be an array, if anything else then just make it an empty array.
		if ( ! is_array( $blacklist ) ) {
			$blacklist = array();
		}

		$modules = self::get_available_modules();
		asort( $modules );

		?>
		<fieldset><legend class="screen-reader-text"><span><?php \esc_html_e( 'Blacklist Modules', 'jetpack-module-control' ); ?></span></legend>
		<?php
		foreach ( $modules as $slug => $module ) {
			$icon    = isset( self::$known_modules_icons[ $slug ] ) ? self::$known_modules_icons[ $slug ] : 'star-empty';
			$reqconn = ! empty( $module['requires_connection'] ) && true === $module['requires_connection'];
			$name    = ! empty( $module['name'] ) ? $module['name'] : __( 'Unknown', 'jetpack-module-control' );
			if ( $devmode && $reqconn ) {
				continue;
			}
			?>
			<label>
				<input type='checkbox' name='jetpack_mc_blacklist[]' value='<?php echo \esc_attr( $slug ); ?>'
				<?php \checked( in_array( $slug, $blacklist, true ) ); ?>
				<?php \disabled( $disabled ); ?>>
				<span class="dashicons dashicons-<?php echo \esc_attr( $icon ); ?>"></span> <?php echo \esc_html_x( $name, 'Module Name', 'jetpack' );  // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?>
			</label><?php echo $reqconn ? ' <a href="#jmc-note-1" style="text-decoration:none" title="' . \esc_html__( 'Requires a WordPress.com connection', 'jetpack-module-control' ) . '">*</a>' : ''; ?><br>
			<?php
		}
		if ( ! $devmode ) {
			echo '<aside role="note" id="jmc-note-1"><p class="description">' . \esc_html__( '*) Modules marked with an asterisk require a WordPress.com connection. They will be unavailable if Jetpack is forced into offline mode.', 'jetpack-module-control' ) . '</p></aside>';
		}
		?>
		</fieldset>
		<?php
	}

	/**
	 * Sanitizes blacklist array
	 *
	 * @since 1.6
	 * @param mixed $options Options array.
	 */
	public static function sanitize_blacklist( $options ) {
		// If not an array or empty, return false.
		if ( ! is_array( $options ) || empty( $options ) ) {
			return '';
		}
		// Get only array values.
		$options = array_values( $options );
		// Remove empty values.
		$options = array_filter( $options );
		// Remove duplicates.
		$options = array_unique( $options );
		// Sanitize each.
		$options = array_map( 'sanitize_text_field', $options );

		return $options;
	}

	/**
	 * Echos a settings section header
	 *
	 * @since 0.1
	 *
	 * @echo Html
	 */
	public static function add_settings_section() {
		echo '<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=Jetpack%20Module%20Control'
				. '&no_shipping=0&tax=0&charset=UTF%2d8&currency_code=EUR" title="'
				. \esc_html__( 'Donate to keep plugin development going!', 'jetpack-module-control' )
				. '" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" style="border:none;float:right;margin:5px 0 0 10px" alt="'
				. \esc_html__( 'Donate to keep plugin development going!', 'jetpack-module-control' ) . '" width="92" height="26" /></a>'
				. sprintf( /* translators: plugin name, linked to plugin home page */
					\esc_html__( 'The options in this section are provided by %s.', 'jetpack-module-control' ),
					'<strong><a href="http://status301.net/wordpress-plugins/jetpack-module-control/">'
					. \esc_html__( 'Module Control for Jetpack', 'jetpack-module-control' ) . '</a></strong>'
				) . ' '
				. \esc_html__( 'This plugin brings additional control over Jetpack modules. You can blacklist / remove individual modules, prevent auto-activation or allow activation without a WordPress.com account.', 'jetpack-module-control' ) . ' ';

		if ( ! \is_multisite() ) {
			printf( /* translators: code snippet */ \esc_html__( 'These settings can be locked down by adding %s to your wp-config.php file.', 'jetpack-module-control' ), '<code>define(\'JETPACK_MC_LOCKDOWN\', true);</code>' );
		} elseif ( \is_network_admin() ) {
				echo '<br><em>' . \esc_html__( 'These settings are only visible to you as Super Admin and these settings affect all sites on the network.', 'jetpack-module-control' ) . '</em>';
		}

		echo '</p>';
	}
}
