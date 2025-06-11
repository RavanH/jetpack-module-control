<?php
/**
 * Jetpack Module Control Admin
 *
 * @package Module Control for Jetpack
 * @since 1.7
 */

namespace JMC;

/**
 * Jetpack Module Control Admin Class
 *
 * Since 1.7
 */
class Admin {
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
		'wordads'               => array(
			'name'                => 'Ads',
			'requires_connection' => true,
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
		'custom-css'            => array(
			'name'                => 'Custom CSS',
			'requires_connection' => false,
		),
		'enhanced-distribution' => array(
			'name'                => 'Enhanced Distribution',
			'requires_connection' => true,
		),
		'google-analytics'      => array(
			'name'                => 'Google Analytics',
			'requires_connection' => true,
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
		'lazy-images'           => array(
			'name'                => 'Lazy Images',
			'requires_connection' => false,
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
			'requires_connection' => true,
		),
		'post-by-email'         => array(
			'name'                => 'Post by Email',
			'requires_connection' => true,
		),
		'protect'               => array(
			'name'                => 'Protect',
			'requires_connection' => true,
		),
		'publicize'             => array(
			'name'                => 'Publicize',
			'requires_connection' => true,
		),
		'related-posts'         => array(
			'name'                => 'Related Posts',
			'requires_connection' => true,
		),
		'search'                => array(
			'name'                => 'Search',
			'requires_connection' => true,
		),
		'seo-tools'             => array(
			'name'                => 'SEO Tools',
			'requires_connection' => true,
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
			'name'                => 'Site Stats',
			'requires_connection' => true,
		),
		'subscriptions'         => array(
			'name'                => 'Subscriptions',
			'requires_connection' => true,
		),
		'tiled-gallery'         => array(
			'name'                => 'Tiled Galleries',
			'requires_connection' => false,
		),
		'vaultpress'            => array(
			'name'                => 'Backups and Scanning',
			'requires_connection' => false,
		),
		'verification-tools'    => array(
			'name'                => 'Site Verification',
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
		'masterbar'             => array(
			'name'                => 'WordPress.com Toolbar',
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
		'wordads'               => 'megaphone',
		'after-the-deadline'    => 'edit',
		'carousel'              => 'camera',
		'comments'              => 'format-chat',
		'comment-likes'         => 'star-filled',
		'contact-form'          => 'feedback',
		'copy-post'             => 'admin-page',
		'custom-content-types'  => 'media-default',
		'custom-css'            => 'admin-appearance',
		'enhanced-distribution' => 'share',
		'google-analytics'      => 'chart-line',
		'gravatar-hovercards'   => 'id', // not available.
		'infinite-scroll'       => 'star-filled',
		'json-api'              => 'share-alt',
		'latex'                 => 'star-filled',
		'likes'                 => 'star-filled',
		'lazy-images'           => 'images-alt',
		'manage'                => 'wordpress-alt',
		'markdown'              => 'editor-code',
		'minileven'             => 'smartphone',
		'monitor'               => 'flag',
		'notes'                 => 'admin-comments',
		'omnisearch'            => 'search',
		'photon'                => 'visibility',
		'photon-cdn'            => 'visibility',
		'post-by-email'         => 'email',
		'protect'               => 'lock',
		'publicize'             => 'share',
		'related-posts'         => 'update',
		'search'                => 'search',
		'seo-tools'             => 'chart-bar',
		'sharedaddy'            => 'share-alt',
		'shortcodes'            => 'text',
		'shortlinks'            => 'admin-links',
		'site-icon'             => 'admin-site',
		'sitemaps'              => 'networking',
		'sso'                   => 'wordpress-alt',
		'stats'                 => 'chart-area',
		'subscriptions'         => 'email',
		'tiled-gallery'         => 'layout',
		'vaultpress'            => 'shield-alt', // not availabe.
		'verification-tools'    => 'clipboard', // maybe yes.
		'videopress'            => 'embed-video',
		'widget-visibility'     => 'welcome-widgets-menus',
		'widgets'               => 'welcome-widgets-menus',
		'woocommerce-analytics' => 'cart',
		'masterbar'             => 'wordpress',
	);

	/**
	 * Return Jetpack available modules
	 *
	 * @since 0.1
	 * @return array
	 */
	private function get_available_modules() {
		if ( null === self::$modules ) {
			if ( class_exists( 'Jetpack' ) ) {
				remove_filter( 'jetpack_get_available_modules', array( __CLASS__, 'blacklist' ) );
				$modules = array();
				foreach ( Jetpack::get_available_modules() as $slug ) {
					$module = Jetpack::get_module( $slug );
					if ( $module ) {
						$modules[ $slug ] = $module;
					}
				}
				self::$modules = $modules;
				add_filter( 'jetpack_get_available_modules', array( __CLASS__, 'blacklist' ) );
			} else {
				self::$modules = self::$known_modules;
			}
		}

		return apply_filters( 'jmc_get_available_modules', self::$modules );
	}

	/**
	 * Sub-site override
	 */

	/**
	 * Adds the sub-site override option
	 *
	 * @see get_site_option(), checked(), disabled()
	 * @uses jetpack_mc_subsite_override network option
	 * @echo Html Checkbox input field for jetpack_mc_subsite_override option
	 * @return void
	 */
	public static function subsite_override_settings() {

		if ( is_network_admin() ) {
			$option = get_site_option( 'jetpack_mc_subsite_override' );
		}
		$disabled = false;
		?>
		<label>
			<input type='checkbox' name='jetpack_mc_subsite_override' value='1'
			<?php checked( $option, '1' ); ?>
			<?php disabled( $disabled ); ?>>
			<?php esc_html_e( 'Allow individual site administrators to manage their own settings for Jetpack Module Control', 'jetpack-module-control' ); ?>
		</label>

		<?php
	}

	/**
	 * MANUAL CONTROL
	 */

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

		if ( is_network_admin() ) {
			// we're in network admin: retrieve network settings.
			$disabled = is_plugin_active_for_network( 'manual-control/manual-control.php' );
			$option   = $disabled ? '1' : get_site_option( 'jetpack_mc_manual_control' );
		} elseif ( is_plugin_active( 'manual-control/manual-control.php' ) ) {
			$option   = '1';
			$disabled = true;
		} else {
			// check if subsite override allowed.
			if ( self::subsite_override() ) {
				// retrieve site setting.
				$option = get_option( 'jetpack_mc_manual_control' );
			} else {
				$option = false;
			}
			// fall back on network settings.
			if ( false === $option && is_multisite() ) {
				$option = get_site_option( 'jetpack_mc_manual_control' );
			}
			$disabled = defined( 'JETPACK_MC_LOCKDOWN' ) && JETPACK_MC_LOCKDOWN ? true : false;
		}

		?>
		<label>
			<input type='checkbox' name='jetpack_mc_manual_control' value='1'
			<?php checked( $option, '1' ); ?>
			<?php disabled( $disabled ); ?>>
			<?php ec_html_e( 'Prevent Jetpack from auto-activating (new) modules', 'jetpack-module-control' ); ?>
		</label>
		<p class="description"><?php printf( /* translators: the Protect module name */ esc_html__( 'Note: The module %s is excepted from this rule.', 'jetpack-module-control' ), esc_html_x( 'Protect', 'Module Name', 'jetpack' ) ); ?></p>
		<?php
	}

	/**
	 * DEVELOPMENT MODE
	 */

	/**
	 * Get the Jetpack Without WordPress.com option
	 *
	 * @since 1.6
	 *
	 * @return bool|string
	 */
	private function get_development_mode() {

		if ( is_network_admin() ) {
			// we're in network admin.
			if ( is_plugin_active_for_network( 'slimjetpack/slimjetpack.php' ) || is_plugin_active_for_network( 'unplug-jetpack/unplug-jetpack.php' ) ) {
				$option = '1';
			} else {
				// retrieve network settings.
				$option = get_site_option( 'jetpack_mc_development_mode' );
			}
		} elseif ( is_plugin_active( 'slimjetpack/slimjetpack.php' ) || is_plugin_active( 'unplug-jetpack/unplug-jetpack.php' ) ) {
			$option = '1';
		} else {
			// check if subsite override allowed.
			if ( self::subsite_override() ) {
				// retrieve site setting.
				$option = get_option( 'jetpack_mc_development_mode' );
			} else {
				$option = false;
			}
			// fall back on network settings.
			if ( false === $option && is_multisite() ) {
				$option = get_site_option( 'jetpack_mc_development_mode' );
			}
		}

		return $option;
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
		$disabled = ! is_network_admin() && defined( 'JETPACK_MC_LOCKDOWN' ) && JETPACK_MC_LOCKDOWN ? true : false;

		if ( ( is_network_admin() && ( is_plugin_active_for_network( 'slimjetpack/slimjetpack.php' ) || is_plugin_active_for_network( 'unplug-jetpack/unplug-jetpack.php' ) ) ) || is_plugin_active( 'slimjetpack/slimjetpack.php' ) || is_plugin_active( 'unplug-jetpack/unplug-jetpack.php' ) ) {
			$disabled = true;
		}

		?>
		<label>
			<input type='checkbox' name='jetpack_mc_development_mode' value='1'
			<?php checked( $option, '1' ); ?>
			<?php disabled( $disabled ); ?>>
			<?php esc_html_e( 'Use Jetpack without a WordPress.com connection', 'jetpack-module-control' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'By forcing Jetpack into development mode, modules are used without a WordPress.com account. All modules that require a WordPress.com connection will be unavailable. These modules are marked with an asterisk (*) below. The admin message about Jetpack running in development mode will be hidden.', 'jetpack-module-control' ); ?></p>
		<?php
	}

	/**
	 * ADMIN NOTICES
	 */

	/**
	 * Disables Centralized Site Management banner by returning false on can_display_jetpack_manage_notice filter.
	 *
	 * @since 0.1
	 * @see add_filter()
	 */
	private function no_manage_notice() {
		// check if subsite override allowed.
		if ( self::subsite_override() ) {
			$blacklist = get_option( 'jetpack_mc_blacklist' );
		} else {
			$blacklist = false;
		}

		// fall back on network setting.
		if ( false === $blacklist && is_multisite() ) {
			$blacklist = get_site_option( 'jetpack_mc_blacklist' );
		}

		if ( is_array( $blacklist ) && in_array( 'manage', $blacklist, true ) ) {
			add_filter( 'can_display_jetpack_manage_notice', '__return_false' );
		}
	}

	/**
	 * Disables Centralized Site Management banner by removing show_development_mode_notice from jetpack_notices actions.
	 *
	 * @since 0.1
	 * @see add_filter()
	 */
	private function no_dev_notice() {
		if ( class_exists( 'Jetpack' ) && ( get_option( 'jetpack_mc_development_mode' ) || get_site_option( 'jetpack_mc_development_mode' ) ) ) {
			remove_action( 'jetpack_notices', array( Jetpack::init(), 'show_development_mode_notice' ) );
		}
	}

	/**
	 * BLACKLIST
	 */

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

		if ( is_network_admin() ) {
			// in network admin retrieve network settings.
			$blacklist = get_site_option( 'jetpack_mc_blacklist', array() );
			$disabled  = false;
		} else {
			// check if subsite override allowed.
			if ( self::subsite_override() ) {
				// in site admin retrieve site settings.
				$blacklist = get_option( 'jetpack_mc_blacklist' );
			} else {
				$blacklist = false;
			}

			// fall back on network setting.
			if ( false === $blacklist && is_multisite() ) {
				$blacklist = get_site_option( 'jetpack_mc_blacklist' );
			}
			$disabled = defined( 'JETPACK_MC_LOCKDOWN' ) && JETPACK_MC_LOCKDOWN ? true : false;
		}

		$devmode = self::get_development_mode();

		// blacklist must be an array, if anything else then just make it an empty array.
		if ( ! is_array( $blacklist ) ) {
			$blacklist = array();
		}

		$modules = self::get_available_modules();
		asort( $modules );

		?>
		<fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Blacklist Modules', 'jetpack-module-control' ); ?></span></legend>
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
				<input type='checkbox' name='jetpack_mc_blacklist[]' value='<?php echo esc_attr( $slug ); ?>'
				<?php checked( in_array( $slug, $blacklist, true ) ); ?>
				<?php disabled( $disabled ); ?>>
				<span class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></span> <?php echo esc_html_x( $name, 'Module Name', 'jetpack' );  // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?>
			</label><?php echo $reqconn ? ' <a href="#jmc-note-1" style="text-decoration:none" title="' . esc_html__( 'Requires a WordPress.com connection', 'jetpack-module-control' ) . '">*</a>' : ''; ?><br>
			<?php
		}
		if ( ! $devmode ) {
			echo '<aside role="note" id="jmc-note-1"><p class="description">' . esc_html__( '*) Modules marked with an asterisk require a WordPress.com connection. They will be unavailable if Jetpack is forced into offline mode.', 'jetpack-module-control' ) . '</p></aside>';
		}
		?>
		</fieldset>
		<?php
	}

	/**
	 * ADMIN
	 */

	/**
	 * Initiate plugins admin stuff
	 *
	 * @since 0.1
	 */
	public static function init() {

		// Admin translations.
		load_plugin_textdomain( 'jetpack-module-control' );

		self::no_manage_notice();

		self::no_dev_notice();

		if ( is_plugin_active_for_network( JMC_BASENAME ) ) {
			// Check for network activation, else these will also take effect when
			// plugin is activated on the primary site alone.
			// TODO : see if you can actually use this scenario where plugin is activatied on site 1 and
			// network options can be set to serve as default settings for other site activations !

			// Add settings to Network Settings
			// thanks to http://zao.is/2013/07/adding-settings-to-network-settings-for-wordpress-multisite/.
			add_filter( 'wpmu_options', array( __CLASS__, 'show_network_settings' ) );
			add_action( 'update_wpmu_options', array( __CLASS__, 'save_network_settings' ) );

			// Plugin action links.
			add_filter( 'network_admin_plugin_action_links_' . JMC_BASENAME, array( __CLASS__, 'add_action_link' ) );
		}

		// check if subsite override allowed.
		if ( self::subsite_override() ) {
			// Plugin action links.
			add_filter( 'plugin_action_links_' . JMC_BASENAME, array( __CLASS__, 'add_action_link' ) );

			// Do regular register/add_settings stuff in 'general' settings on options-general.php.
			$settings = 'general';

			add_settings_section( 'jetpack-module-control', '<a name="jetpack-mc"></a>' . __( 'Jetpack Module Control', 'jetpack-module-control' ), array( __CLASS__, 'add_settings_section' ), $settings );

			// register settings.
			if ( ! defined( 'JETPACK_MC_LOCKDOWN' ) || ! JETPACK_MC_LOCKDOWN ) {
				register_setting( $settings, 'jetpack_mc_manual_control' ); // sanitize_callback 'boolval' ?
				register_setting( $settings, 'jetpack_mc_development_mode' ); // sanitize_callback 'boolval' ?
				register_setting( $settings, 'jetpack_mc_blacklist', array( __CLASS__, 'sanitize_blacklist' ) );
			}

			// add settings fields.
			add_settings_field( 'jetpack_mc_manual_control', __( 'Manual Control', 'jetpack-module-control' ), array( __CLASS__, 'manual_control_settings' ), $settings, 'jetpack-module-control' );
			add_settings_field( 'jetpack_mc_development_mode', __( 'Offline Mode', 'jetpack-module-control' ), array( __CLASS__, 'development_mode_settings' ), $settings, 'jetpack-module-control' );
			add_settings_field( 'jetpack_mc_blacklist', __( 'Blacklist Modules', 'jetpack-module-control' ), array( __CLASS__, 'blacklist_settings' ), $settings, 'jetpack-module-control' );
		}
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
			return false;
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
	 * Saves the network settings
	 *
	 * @since 0.2
	 */
	public static function save_network_settings() {
		// Nonce verification for security.
		if (
			! isset( $_POST['_jetpack_mc_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_jetpack_mc_nonce'] ) ), 'jetpack_mc_network_settings' )
		) {
			add_settings_error(
				'jetpack_mc_network_settings',
				'jetpack_mc_nonce_fail',
				esc_html__( 'Security check failed. Jetpack Module Control settings were not updated.', 'jetpack-module-control' ),
				'error'
			);
			return;
		}

		// Get sanitized blacklist from POST data.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$blacklist = isset( $_POST['jetpack_mc_blacklist'] ) ? self::sanitize_blacklist( wp_unslash( $_POST['jetpack_mc_blacklist'] ) ) : false;

		// Construct the settings array to save.
		$settings = array(
			'jetpack_mc_manual_control'   => isset( $_POST['jetpack_mc_manual_control'] ),
			'jetpack_mc_development_mode' => isset( $_POST['jetpack_mc_development_mode'] ),
			'jetpack_mc_blacklist'        => $blacklist,
			'jetpack_mc_subsite_override' => isset( $_POST['jetpack_mc_subsite_override'] ),
		);

		foreach ( $settings as $name => $value ) {
			update_site_option( $name, $value );
		}
	}

	/**
	 * Prints the network settings
	 *
	 * @since 0.2
	 */
	public static function show_network_settings() {
		?>
		<h3><a name="jetpack-mc"></a><?php esc_html_e( 'Jetpack Module Control', 'jetpack-module-control' ); ?></h3>
		<?php
			self::add_settings_section( '' );
			// Add nonce field for security.
			wp_nonce_field( 'jetpack_mc_network_settings', '_jetpack_mc_nonce' );
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Sub-site Override', 'jetpack-module-control' ); ?></th>
					<td>
						<?php
						self::subsite_override_settings();
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Manual Control', 'jetpack-module-control' ); ?></th>
					<td>
						<?php
						self::manual_control_settings();
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Development Mode', 'jetpack-module-control' ); ?></th>
					<td>
						<?php
						self::development_mode_settings();
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Blacklist Modules', 'jetpack-module-control' ); ?></th>
					<td>
						<?php
						self::blacklist_settings();
						?>
					</td>
				</tr>
			</tbody>
			</table>
			<?php
	}

	/**
	 * Echos a settings section header
	 *
	 * @since 0.1
	 *
	 * @echo Html
	 */
	public static function add_settings_section() {
		echo '<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=Jetpack%20Module%20Control&item_number='
				. esc_url( self::version )
				. '&no_shipping=0&tax=0&charset=UTF%2d8&currency_code=EUR" title="'
				. esc_html__( 'Donate to keep plugin development going!', 'jetpack-module-control' )
				. '" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" style="border:none;float:right;margin:5px 0 0 10px" alt="'
				. esc_html__( 'Donate to keep plugin development going!', 'jetpack-module-control' ) . '" width="92" height="26" /></a>'
				. sprintf( /* translators: plugin name, linked to plugin home page */
					esc_html__( 'The options in this section are provided by %s.', 'jetpack-module-control' ),
					'<strong><a href="http://status301.net/wordpress-plugins/jetpack-module-control/">'
					. esc_html__( 'Jetpack Module Control', 'jetpack-module-control' ) . ' ' . esc_html( self::version ) . '</a></strong>'
				) . ' '
				. esc_html__( 'This plugin brings additional control over Jetpack modules. You can blacklist / remove individual modules, prevent auto-activation or allow activation without a WordPress.com account.', 'jetpack-module-control' ) . ' ';

		if ( ! is_multisite() ) {
			printf( /* translators: code snippet */ esc_html__( 'These settings can be locked down by adding %s to your wp-config.php file.', 'jetpack-module-control' ), '<code>define(\'JETPACK_MC_LOCKDOWN\', true);</code>' );
		} elseif ( is_network_admin() ) {
				echo '<br><em>' . esc_html__( 'These settings are only visible to you as Super Admin and these settings affect all sites on the network.', 'jetpack-module-control' ) . '</em>';
		}

		echo '</p>';
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
		$settings_link = is_plugin_active_for_network( JMC_BASENAME ) ?
			'<a href="' . network_admin_url( 'settings.php#jetpack-mc' ) . '">' . esc_html__( 'Network Settings' ) . '</a>' :
			'<a href="' . admin_url( 'options-general.php#jetpack-mc' ) . '">' . esc_html__( 'Settings' ) . '</a>';

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
			'jetpack_mc_subsite_override' => '',
			'jetpack_mc_blacklist'        => '',
		);

		// Prepare default options on network activation.
		foreach ( $default_options as $option => $value ) {
			if ( $network_wide ) {
				add_site_option( $option, $value );
			} else {
				add_option( $option, $value, '', true ) || wp_set_options_autoload( array( $option ), true );
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
		$default_options = array(
			'jetpack_mc_manual_control',
			'jetpack_mc_development_mode',
			'jetpack_mc_subsite_override',
			'jetpack_mc_blacklist',
		);

		if ( $network_wide ) {
			// Get sites in the network.
			$args  = array(
				'fields'                 => 'ids',
				'number'                 => 1000, // Limit to 1000 sites.
				'update_site_meta_cache' => false,
			);
			$blogs = get_sites( $args );

			foreach ( $blogs as $_id ) {
				switch_to_blog( $_id );

				// Disable autoload.
				wp_set_options_autoload( $default_options, false );

				restore_current_blog();
			}
		} else {
			wp_set_options_autoload( $default_options, false );
		}
	}
}
