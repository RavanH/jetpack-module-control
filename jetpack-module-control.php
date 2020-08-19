<?php
/*
 * Plugin Name: Module Control for Jetpack
 * Plugin URI: https://status301.net/wordpress-plugins/jetpack-module-control/
 * Description: This plugin brings additional control over Jetpack modules. You can blacklist / remove individual modules, prevent auto-activation or allow activation without a WordPress.com account.
 * Author: RavanH
 * Author URI: https://status301.net/
 * Network: true
 * Text Domain: jetpack-module-control
 * License: GPL2+
 * Version: 1.6
 */

/*
 * ROADMAP
 *
 * version 2.0
 * Replace "Prevent the Jetpack plugin from auto-activating (new) modules" with
 * finer grained "Select which modules to auto-activate"
 * see http://jeremy.hu/customize-the-list-of-modules-available-in-jetpack/
 * 	function jeherve_auto_activate_stats() {
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
 * Can we disable Debug link in the footer menu? No...
 *
 * Option to "force_deactivate" (same as blacklist?) as described on https://github.com/Automattic/jetpack/issues/1452
 *
 */


/**
 * Jetpack Module Control Class
 *
 * since 0.1
 */
class Jetpack_Module_Control {

	/**
	 * The single instance.
	 * @var 	object
	 * @access  private
	 * @since 	0.1
	 */
	private static $instance = null;

	/**
	 * The plugins basename.
	 * @var 	string
	 * @access  private
	 * @since 	0.1
	 */
	private static $plugin_basename = null;

	/**
	 * Current plugin version.
	 * @since 0.1
	 * @var string
	 */
	public $version = '1.6';

	/**
	 * Available modules array
	 * @since 0.1
	 * @access  private
	 * @var array
	 */
	private static $modules = null;

	/**
	 * Know modules array with names
	 * @since 0.2
	 * @access  private
	 * @var array
	 */
	private static $known_modules = array(
					'wordads' 		=> array( 'name' 	=> 'Ads',
								'requires_connection' 	=> true ),
//					'after-the-deadline' 	=> array( 'name' 	=> 'Spelling and Grammar',
//								'requires_connection' 	=> true ),
					'carousel' 		=> array( 'name' 	=> 'Carousel',
								'requires_connection' 	=> false ),
					'comments' 		=> array( 'name' 	=> 'Comments',
								'requires_connection' 	=> true ),
					'comment-likes' 		=> array( 'name' 	=> 'Comment Likes',
								'requires_connection' 	=> true ),
					'contact-form' 		=> array( 'name' 	=> 'Contact Form',
								'requires_connection' 	=> false ),
					'copy-post' 		=> array( 'name' 	=> 'Copy Post',
								'requires_connection' 	=> false ),
					'custom-content-types' 	=> array( 'name' 	=> 'Custom content types',
								'requires_connection' 	=> false ),
					'custom-css' 		=> array( 'name' 	=> 'Custom CSS',
								'requires_connection' 	=> false ),
					'enhanced-distribution'	=> array( 'name' 	=> 'Enhanced Distribution',
								'requires_connection'	 => true ),
					'google-analytics' 	=> array( 'name' 	=> 'Google Analytics',
								'requires_connection' 	=> true ),
					'gravatar-hovercards' 	=> array( 'name' 	=> 'Gravatar Hovercards',
								'requires_connection' 	=> false ),
					'infinite-scroll' 	=> array( 'name' 	=> 'Infinite Scroll',
								'requires_connection' 	=> false ),
					'json-api' 		=> array( 'name' 	=> 'JSON API',
								'requires_connection' 	=> true ),
					'latex' 		=> array( 'name' 	=> 'Beautiful Math',
								'requires_connection' 	=> false ),
					'lazy-images' 		=> array( 'name' 	=> 'Lazy Images',
								'requires_connection' 	=> false ),
					'likes' 		=> array( 'name' 	=> 'Likes',
								'requires_connection' 	=> true ),
//					'manage' 		=> array( 'name' 	=> 'Manage',
//								'requires_connection' 	=> true ),
					'markdown' 		=> array( 'name' 	=> 'Markdown',
								'requires_connection' 	=> false ),
//					'minileven' 		=> array( 'name' 	=> 'Mobile Theme',
//								'requires_connection' 	=> false ),
					'monitor' 		=> array( 'name' 	=> 'Monitor',
								'requires_connection' 	=> true ),
					'notes' 		=> array( 'name' 	=> 'Notifications',
								'requires_connection' 	=> true ),
//					'omnisearch' 		=> array( 'name' 	=> 'Omnisearch',
//								'requires_connection' 	=> false ),
					'photon' 		=> array( 'name' 	=> 'Image CDN',
								'requires_connection' 	=> true ),
					'photon-cdn' 		=> array( 'name' 	=> 'Asset CDN',
								'requires_connection' 	=> true ),
					'post-by-email' 	=> array( 'name' 	=> 'Post by Email',
								'requires_connection' 	=> true ),
					'protect' 		=> array( 'name' 	=> 'Protect',
								'requires_connection' 	=> true ),
					'publicize' 		=> array( 'name' 	=> 'Publicize',
								'requires_connection' 	=> true ),
					'related-posts' 	=> array( 'name' 	=> 'Related Posts',
								'requires_connection' 	=> true ),
					'search' 		=> array( 'name' 	=> 'Search',
								'requires_connection' 	=> true ),
					'seo-tools' 		=> array( 'name' 	=> 'SEO Tools',
								'requires_connection' 	=> true ),
					'sharedaddy' 		=> array( 'name' 	=> 'Sharing',
								'requires_connection' 	=> false ),
					'shortcodes' 		=> array( 'name' 	=> 'Shortcode Embeds',
								'requires_connection' 	=> false ),
					'shortlinks' 		=> array( 'name' 	=> 'WP.me Shortlinks',
								'requires_connection' 	=> true ),
//					'site-icon' 		=> array( 'name' 	=> 'Site Icon',
//								'requires_connection' 	=> false ),
					'sitemaps' 		=> array( 'name' 	=> 'Sitemaps',
								'requires_connection' 	=> false ),
					'sso' 			=> array( 'name' 	=> 'Secure Sign On',
								'requires_connection' 	=> true ),
					'stats' 		=> array( 'name' 	=> 'Site Stats',
								'requires_connection' 	=> true ),
					'subscriptions' 	=> array( 'name' 	=> 'Subscriptions',
								'requires_connection' 	=> true ),
					'tiled-gallery' 	=> array( 'name' 	=> 'Tiled Galleries',
								'requires_connection' 	=> false ),
					'vaultpress' 		=> array( 'name' 	=> 'Backups and Scanning',
								'requires_connection' 	=> false ),
					'verification-tools' 	=> array( 'name' 	=> 'Site Verification',
								'requires_connection' 	=> false ),
					'videopress' 		=> array( 'name' 	=> 'VideoPress',
								'requires_connection' 	=> true ),
					'widget-visibility' 	=> array( 'name' 	=> 'Widget Visibility',
								'requires_connection' 	=> false ),
					'widgets' 		=> array( 'name' 	=> 'Extra Sidebar Widgets',
								'requires_connection' 	=> false ),
					'woocommerce-analytics' 		=> array( 'name' 	=> 'WooCommerce Analytics',
								'requires_connection' 	=> true ),
					'masterbar' 		=> array( 'name' 	=> 'WordPress.com Toolbar',
								'requires_connection' 	=> true )
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
						'wordads'		=> 'megaphone',
						'after-the-deadline'	=> 'edit',
						'carousel'		=> 'camera',
						'comments'		=> 'format-chat',
						'comment-likes' 		=> 'star-filled',
						'contact-form'		=> 'feedback',
						'copy-post'		=> 'admin-page',
						'custom-content-types' 	=> 'media-default',
						'custom-css' 		=> 'admin-appearance',
						'enhanced-distribution'	=> 'share',
						'google-analytics'	=> 'chart-line',
						'gravatar-hovercards'	=> 'id', // not available
						'infinite-scroll'		=> 'star-filled',
						'json-api'		=> 'share-alt',
						'latex' 		=> 'star-filled',
						'likes' 		=> 'star-filled',
						'lazy-images'	=> 'images-alt',
						'manage' 		=> 'wordpress-alt',
						'markdown' 		=> 'editor-code',
						'minileven' 		=> 'smartphone',
						'monitor' 		=> 'flag',
						'notes' 		=> 'admin-comments',
						'omnisearch' 		=> 'search',
						'photon' 		=> 'visibility',
						'photon-cdn' 		=> 'visibility',
						'post-by-email' 	=> 'email',
						'protect' 		=> 'lock',
						'publicize' 		=> 'share',
						'related-posts' 	=> 'update',
						'search'	=> 'search',
						'seo-tools' 		=> 'chart-bar',
						'sharedaddy' 		=> 'share-alt',
						'shortcodes' 		=> 'text',
						'shortlinks' 		=> 'admin-links',
						'site-icon' 		=> 'admin-site',
						'sitemaps' 		=> 'networking',
						'sso' 			=> 'wordpress-alt',
						'stats' 		=> 'chart-area',
						'subscriptions' 	=> 'email',
						'tiled-gallery' 	=> 'layout',
						'vaultpress' 		=> 'shield-alt', // not availabe
						'verification-tools' 	=> 'clipboard', // maybe yes
						'videopress' 		=> 'embed-video',
						'widget-visibility' 	=> 'welcome-widgets-menus',
						'widgets' 		=> 'welcome-widgets-menus',
						'woocommerce-analytics'	=> 'cart',
						'masterbar'	=> 'wordpress',
					);

	/**
	 * Default dashicon
	 * @since 1.0
	 * @access  private
	 * @var string
	 */
	private static $default_icon = 'star-empty';

	/**
	 * Return Jetpack available modules
	 * @since 0.1
	 * @return array
	 */
	private function get_available_modules() {
		if ( null === self::$modules ) {
			if ( class_exists('Jetpack') ) {
				remove_filter( 'jetpack_get_available_modules', array($this, 'blacklist') );
				$modules = array();
				foreach ( Jetpack::get_available_modules() as $slug ) {
					$module = Jetpack::get_module( $slug );
					if ( $module )
						$modules[$slug] = $module;
				}
				self::$modules = $modules;
				add_filter( 'jetpack_get_available_modules', array($this, 'blacklist') );
			} else {
				self::$modules = self::$known_modules;
			}
		}
		return self::$modules;
	}

	/**
	 * Return plugin basename
	 *
	 * @since 0.1
	 *
	 * @return string Plugin basename
	 */
	private function plugin_basename() {
		if ( null === self::$plugin_basename ) {
			self::$plugin_basename = plugin_basename( __FILE__ );
		}
		return self::$plugin_basename;
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
	public function subsite_override_settings() {

		if( is_network_admin() ) {
			$option = get_site_option('jetpack_mc_subsite_override');
		}
		$disabled = false;
		?>
		<label>
			<input type='checkbox' name='jetpack_mc_subsite_override' value='1'
			<?php checked( $option, '1' ); ?>
			<?php disabled( $disabled ); ?>>
			<?php _e('Allow individual site administrators to manage their own settings for Jetpack Module Control','jetpack-module-control'); ?>
		</label>

		<?php
	} // END subsite_override_settings()

	/**
	 * Checks if subsite override is allowed on multisite.
	 *
	 * @uses  get_site_option()
	 * @return  bool jetpack_mc_subsite_override network option. Always true if single site installation
	 */
	public function subsite_override() {

		if ( is_multisite() ) {
			$option = get_site_option('jetpack_mc_subsite_override');
		} else {
			//Always return true if not multisite
			$option = true;
		}

		return $option;

	} // END subsite_override()


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
	public function manual_control_settings() {

		if ( is_network_admin() ) {
			// we're in network admin: retrieve network settings
			$disabled = is_plugin_active_for_network('manual-control/manual-control.php');
			$option = $disabled ? '1' : get_site_option('jetpack_mc_manual_control');
		} else {
			// we're in site admin
			if ( is_plugin_active('manual-control/manual-control.php') ) {
				$option = '1';
				$disabled = true;
			} else {
				// check if subsite override allowed
				if( $this->subsite_override() ) {
					// retrieve site setting
					$option = get_option('jetpack_mc_manual_control');
				} else {
					$option = false;
				}
				// fall back on network settings
				if ( $option === false && is_multisite() ) $option = get_site_option('jetpack_mc_manual_control');
				$disabled = defined('JETPACK_MC_LOCKDOWN') && JETPACK_MC_LOCKDOWN ? true : false;
			}
		}

		?>
		<label>
			<input type='checkbox' name='jetpack_mc_manual_control' value='1'
			<?php checked( $option, '1' ); ?>
			<?php disabled( $disabled ); ?>>
			<?php _e('Prevent Jetpack from auto-activating (new) modules','jetpack-module-control'); ?>
		</label>
		<p class="description"><?php echo sprintf( __('Note: The module %s is excepted from this rule.','jetpack-module-control'), translate_with_gettext_context('Protect','Module Name','jetpack') ); ?></p>
		<?php

	} // END manual_control_settings()

	/**
	 * Activates Manual Control by returning an empty array on module auto-activation.
	 * First modelled after Manual Control for Jetpack by Mark Jaquith http://coveredwebservices.com/
	 * To be converted to allow selected modules instead of all or none.
	 *
	 * @since 0.1
	 * @see add_filter()
	 */
	public function manual_control( $modules ) {

		// check if subsite override allowed
		if( $this->subsite_override() ) {
			$option = get_option('jetpack_mc_manual_control');
		} else {
			$option = false;
		}
		// if false, fall back on network settings
		if ( $option === false && is_multisite() ) $option = get_site_option('jetpack_mc_manual_control');

		return !empty($option) ? array() : $modules;

	} // END manual_control()

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
 			// we're in network admin
 			if ( is_plugin_active_for_network('slimjetpack/slimjetpack.php') || is_plugin_active_for_network('unplug-jetpack/unplug-jetpack.php') ) {
 				$option = '1';
 			} else {
 				// retrieve network settings
 				$option = get_site_option('jetpack_mc_development_mode');
 			}
 		} else {
 			// we're in site admin
 			if ( is_plugin_active('slimjetpack/slimjetpack.php') || is_plugin_active('unplug-jetpack/unplug-jetpack.php') ) {
 				$option = '1';
 			} else {
 				// check if subsite override allowed
 				if( $this->subsite_override() ) {
 					//retrieve site setting
 					$option = get_option('jetpack_mc_development_mode');
 				} else {
 					$option = false;
 				}
 				// fall back on network settings
 				if ( $option === false && is_multisite() ) $option = get_site_option('jetpack_mc_development_mode');
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
	public function development_mode_settings() {

		$option = $this->get_development_mode();
		$disabled = ! is_network_admin() && defined('JETPACK_MC_LOCKDOWN') && JETPACK_MC_LOCKDOWN ? true : false;

		if ( is_network_admin() && ( is_plugin_active_for_network('slimjetpack/slimjetpack.php') || is_plugin_active_for_network('unplug-jetpack/unplug-jetpack.php') ) || is_plugin_active('slimjetpack/slimjetpack.php') || is_plugin_active('unplug-jetpack/unplug-jetpack.php') ) {
			$disabled = true;
		}

		?>
		<label>
			<input type='checkbox' name='jetpack_mc_development_mode' value='1'
			<?php checked( $option, '1' ); ?>
			<?php disabled( $disabled ); ?>>
			<?php _e('Use Jetpack modules without a WordPress.com connection','jetpack-module-control'); ?>
		</label>
		<p class="description"><?php _e('By forcing Jetpack into development mode, modules are used without a WordPress.com account. All modules that require a WordPress.com connection will be unavailable. These modules are marked with an asterisk (*) below. The admin message about Jetpack running in development mode will be hidden.','jetpack-module-control'); ?></p>
		<?php

	} // END development_mode_settings()

	/**
	 * Activates Development Mode by returning true on jetpack_development_mode filter.
	 * Based on http://jeremy.hu/customize-the-list-of-modules-available-in-jetpack/
	 *
	 * @since 1.0
	 * @see add_filter()
	 */
	public function development_mode() {
		// check if subsite override allowed
		if( $this->subsite_override() ) {
			$option = get_option('jetpack_mc_development_mode');
		} else {
			$option = false;
		}

		// if false, fall back on network settings
		if ( $option === false && is_multisite() ) {
			$option = get_site_option('jetpack_mc_development_mode');
		}

		return !empty($option) ? true : false;
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
		// check if subsite override allowed
		if( $this->subsite_override() ) {
			$blacklist = get_option('jetpack_mc_blacklist');
		} else {
			$blacklist = false;
		}

		// fall back on network setting
		if ( $blacklist === false && is_multisite() ) $blacklist = get_site_option('jetpack_mc_blacklist');

		if ( is_array( $blacklist ) && in_array( 'manage', $blacklist ) ) {
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
		if ( class_exists('Jetpack') && ( get_option('jetpack_mc_development_mode') || get_site_option('jetpack_mc_development_mode') ) ) {
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
	public function blacklist_settings() {

		if ( is_network_admin() ) {
			// in network admin retrieve network settings
			$blacklist = get_site_option( 'jetpack_mc_blacklist', array() );
			$disabled = false;
		} else {
			// check if subsite override allowed
			if( $this->subsite_override() ) {
				// in site admin retrieve site settings
				$blacklist = get_option( 'jetpack_mc_blacklist' );
			} else {
				$blacklist = false;
			}

			// fall back on network setting
			if ( $blacklist === false && is_multisite() ) $blacklist = get_site_option('jetpack_mc_blacklist');
			$disabled = defined('JETPACK_MC_LOCKDOWN') && JETPACK_MC_LOCKDOWN ? true : false;
		}

		$devmode = $this->get_development_mode();

		// blacklist must be an array, if anything else then just make it an empty array
		if ( !is_array($blacklist) ) $blacklist = array();

		$modules = $this->get_available_modules();
		asort($modules);

		?>
		<fieldset><legend class="screen-reader-text"><span><?php _e('Blacklist Modules','jetpack-module-control'); ?></span></legend>
		<?php
		foreach ( $modules as $slug => $module ) {
			$icon = isset(self::$known_modules_icons[$slug]) ? self::$known_modules_icons[$slug] : self::$default_icon;
			$reqconn = !empty($module['requires_connection']) && true === $module['requires_connection'];
			if ( $devmode && $reqconn ) continue;
			?>
			<label>
				<input type='checkbox' name='jetpack_mc_blacklist[]' value='<?php echo $slug; ?>'
				<?php checked( in_array( $slug, $blacklist ) ); ?>
				<?php disabled( $disabled ); ?>>
				<span class="dashicons dashicons-<?php echo $icon; ?>"></span> <?php echo translate_with_gettext_context( $module['name'], 'Module Name', 'jetpack' ) ?>
			</label><?php echo $reqconn ? ' <a href="#jmc-note-1" style="text-decoration:none" title="' . __('Requires a WordPress.com connection','jetpack-module-control') . '">*</a>' : ''; ?><br>
			<?php
		}
		if ( ! $devmode ) echo '<aside role="note" id="jmc-note-1"><p class="description">' . __('*) Modules marked with an asterisk require a WordPress.com connection. They will be unavailable if Jetpack is forced into offline mode.','jetpack-module-control') . '</p></aside>';
		?>
		</fieldset>
		<?php

	} // END blacklist_settings()

	/**
	 * Blacklist Jetpack modules
	 * Modelled after ParhamG's blacklist_jetpack_modules.php https://gist.github.com/ParhamG/6494979
	 *
	 * @since 0.1
	 * @param $modules
	 *
	 * @return Array Allowed modules after unsetting blacklisted modules from all modules array
	 */
	public function blacklist ( $modules ) {

		// check if subsite override allowed
		if( $this->subsite_override() ) {
			$blacklist = get_option('jetpack_mc_blacklist');
		} else {
			$blacklist = false;
		}

		// fall back on network setting
		if ( $blacklist === false && is_multisite() ) $blacklist = get_site_option('jetpack_mc_blacklist');

		foreach ( (array)$blacklist as $mod )
			if ( isset( $modules[$mod] ) )
				unset( $modules[$mod] );

		return $modules;

	}

	/**
	 * ADMIN
	 */

	/**
	 * Initiate plugins admin stuff
	 *
	 * @since 0.1
	 */
	public function admin_init(){

		$this->no_manage_notice();

		$this->no_dev_notice();

		if ( is_plugin_active_for_network( $this->plugin_basename() ) ) {
			// Check for network activation, else these will also take effect when
			// plugin is activated on the primary site alone.
			// TODO : see if you can actually use this scenario where plugin is activatied on site 1 and
			// network options can be set to serve as default settings for other site activations !

			// Add settings to Network Settings
			// thanks to http://zao.is/2013/07/adding-settings-to-network-settings-for-wordpress-multisite/
			add_filter( 'wpmu_options', array( $this, 'show_network_settings' ) );
			add_action( 'update_wpmu_options', array( $this, 'save_network_settings' ) );

			// Plugin action links
			add_filter( 'network_admin_plugin_action_links_' . $this->plugin_basename(), array($this, 'add_action_link') );
		}

		// check if subsite override allowed
		if($this->subsite_override()) {
			// Plugin action links
			add_filter( 'plugin_action_links_' . $this->plugin_basename(), array($this, 'add_action_link') );

			// Do regular register/add_settings stuff in 'general' settings on options-general.php
			$settings = 'general';

			add_settings_section('jetpack-module-control', '<a name="jetpack-mc"></a>' . __('Jetpack Module Control','jetpack-module-control'), array($this, 'add_settings_section'), $settings);

			// register settings
			if ( defined('JETPACK_MC_LOCKDOWN') && JETPACK_MC_LOCKDOWN ) {
				// do not register site settings to prevent them being updated
			} else {
				register_setting( $settings, 'jetpack_mc_manual_control' ); // sanitize_callback 'boolval' ?
				register_setting( $settings, 'jetpack_mc_development_mode' ); // sanitize_callback 'boolval' ?
				register_setting( $settings, 'jetpack_mc_blacklist', array($this, 'sanitize_blacklist') );
			}

			// add settings fields
			add_settings_field( 'jetpack_mc_manual_control', __('Manual Control','jetpack-module-control'), array($this, 'manual_control_settings'), $settings, 'jetpack-module-control' ); // array('label_for' => 'elementid')
			add_settings_field( 'jetpack_mc_development_mode', __('Offline Mode','jetpack-module-control'), array($this, 'development_mode_settings'), $settings, 'jetpack-module-control' ); // array('label_for' => 'elementid')
			add_settings_field( 'jetpack_mc_blacklist', __('Blacklist Modules','jetpack-module-control'), array($this, 'blacklist_settings'), $settings, 'jetpack-module-control' );
		}

	}

	/**
	 * Sanitizes blacklist array
	 *
	 * @since 1.6
	 */
	public function sanitize_blacklist( $options ) {
		return is_array($options) ? array_values($options) : $options;
	}

	/**
	 * Saves the network settings
	 *
	 * @since 0.2
	 */
	public function save_network_settings() {

		$posted_settings = array(
					'jetpack_mc_manual_control' => '',
					'jetpack_mc_development_mode' => '',
					'jetpack_mc_blacklist' => '',
					'jetpack_mc_subsite_override' => ''
					);

		isset( $_POST['jetpack_mc_subsite_override'] ) && $posted_settings['jetpack_mc_subsite_override'] = '1';

		isset( $_POST['jetpack_mc_manual_control'] ) && $posted_settings['jetpack_mc_manual_control'] = '1';

		isset( $_POST['jetpack_mc_development_mode'] ) && $posted_settings['jetpack_mc_development_mode'] = '1';

		isset( $_POST['jetpack_mc_blacklist'] ) && $posted_settings['jetpack_mc_blacklist'] = $this->sanitize_blacklist( $_POST['jetpack_mc_blacklist'] );

		foreach( $posted_settings as $name => $value ) {
			update_site_option( $name, $value );
		}

	}

	/**
	 * Prints the network settings
	 *
	 * @since 0.2
	 */
	public function show_network_settings() {
		?>
		<h3><a name="jetpack-mc"></a><?php _e('Jetpack Module Control','jetpack-module-control'); ?></h3>
		<?php
        	$this->add_settings_section('');
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php _e('Sub-site Override','jetpack-module-control'); ?></th>
					<td>
						<?php
						$this->subsite_override_settings();
						?>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Manual Control','jetpack-module-control'); ?></th>
					<td>
						<?php
						$this->manual_control_settings();
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Development Mode','jetpack-module-control'); ?></th>
					<td>
						<?php
						$this->development_mode_settings();
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Blacklist Modules','jetpack-module-control'); ?></th>
					<td>
						<?php
						$this->blacklist_settings();
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
	 * @param $option (unused)
	 * @echo Html
	 */
	public function add_settings_section( $option ) {
		echo '<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=Jetpack%20Module%20Control&item_number='
				. $this->version
				. '&no_shipping=0&tax=0&charset=UTF%2d8&currency_code=EUR" title="'
				. __('Donate to keep plugin development going!','jetpack-module-control')
				. '" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" style="border:none;float:right;margin:5px 0 0 10px" alt="'
				. __('Donate to keep plugin development going!','jetpack-module-control') . '" width="92" height="26" /></a>'
				. sprintf(__('The options in this section are provided by %s.','jetpack-module-control'),'<strong><a href="http://status301.net/wordpress-plugins/jetpack-module-control/">'
				. __('Jetpack Module Control','jetpack-module-control') . ' ' . $this->version . '</a></strong>') . ' '
				. __('This plugin brings additional control over Jetpack modules. You can blacklist / remove individual modules, prevent auto-activation or allow activation without a WordPress.com account.','jetpack-module-control') . ' ';

		if ( !is_multisite() )
			echo sprintf(__('These settings can be locked down by adding %s to your wp-config.php file.','jetpack-module-control'),'<code>define(\'JETPACK_MC_LOCKDOWN\', true);</code>');
		else if ( is_network_admin() )
				echo '<br><em>' . __('These settings are only visible to you as Super Admin and these settings affect all sites on the network.','jetpack-module-control') . '</em>';

		echo '</p>';
	}

	/**
	 * Adds an action link on the Plugins page
	 *
	 * @since 0.1
	 * @see is_plugin_active_for_network(), admin_url(), network_admin_url()
	 *
	 * @param array $links Plugin de/activation and deletion links
	 * @return array Plugin links plus Settings link
	 */
	public function add_action_link( $links ) {
		$settings_link = is_plugin_active_for_network( $this->plugin_basename() ) ?
			'<a href="' . network_admin_url('settings.php#jetpack-mc') . '">' . translate('Network Settings') . '</a>' :
			'<a href="' . admin_url('options-general.php#jetpack-mc') . '">' . translate('Settings') . '</a>';
		return array_merge(
			array( 'settings' => $settings_link ),
			$links
		);
		return $links;
	}

	/**
	 * Routines to execute on plugins_loaded
	 *
	 * https://github.com/Automattic/jetpack/pull/2027 >> request accepted and fixed
	 *
	 * @since 0.1
	 */
	public function plugins_loaded() {
		global $pagenow;

		// only need translations on admin page
		if ( is_admin() ) {
			load_plugin_textdomain( 'jetpack-module-control' );
		}

		add_filter( 'jetpack_get_default_modules', array( $this, 'manual_control' ), 99 );
		add_filter( 'jetpack_offline_mode', array( $this, 'development_mode' ) );
		add_filter( 'jetpack_get_available_modules', array( $this, 'blacklist' ) );
	}

	/**
	 * Getter method for retrieving single object instance.
	 *
	 * @since 0.1
	 *
	 * @return Jetpack_Module_Control|null instance object
	 */
	public static function instance() {
		if(is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since  0.1
	 */
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 0 );
		add_action( 'admin_init', array($this,'admin_init'), 11 );
	}

	/**
	 * Cloning
	 *
	 * @since  0.1
	 */
	private function __clone() { }

}

Jetpack_Module_Control::instance();
