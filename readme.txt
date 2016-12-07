=== Module Control for Jetpack ===
Contributors: RavanH
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=Module%20Control%20for%20Jetpack
Tags: Jetpack, jet pack, jetpack light, manual control, blacklist, blacklist jetpack modules, slim jetpack
Stable tag: 1.4.2
Requires at least: 4.0
Tested up to: 4.7
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Your Jetpack, Controlled.

== Description ==

[Jetpack](https://wordpress.org/plugins/jetpack/) adds powerful features... but sometimes we don't want them all. This plugin brings additional control over Jetpack modules.

= Features =

1. Blacklist / remove individual Jetpack modules.
1. Prevent module auto-activation on connection or after an upgrade.
1. Allow activation of Jetpack modules without a WordPress.com connection.
1. Single site and Multisite compatible.

= Examples =

In most use cases, a carefully considered combination of Jetpack modules can be a really good thing. But not always is much consideration being done beforehand. Or site admins just don't know all the implications...

- Maybe your Nginx web server uses FastCGI caching, making it incompatible with the Mobile Theme module? Blacklist that module to prevent any admin from activating it.
- Do you already a light box provided by your theme or another plugin? Then blacklist the Carousel module to prevent accidental activation.
- Or you're running a Multisite and do not want any admins monitoring uptime with the Monitor module just to call you every time their site is briefly inaccessible?
- Or you offer your own backup service and do not care much for VaultPress competition?
- You're running a school network and (some) sites are managed by minors who are not allowed to sign up for an account at WordPress.com? Then use the Jetpack Development Mode option to allow usage of modules that do not require a connection.

Any one of Jetpack's modules can bring overlapping or even conflicting functionality. In such cases, being able to prevent (accidental) activation is a good thing.

= Single site and Multisite =

Although the original idea arose on a multisite installation, Jetpack Module Control is developed for both single and multisite installations.

On **multisite**, it can only be network activated and allows global rules for Jetpack on all sites. At this point it also allows per-site changes by Super Admin only. Jetpack itself can, but does not need to be, network activated.

For **single site** installations, plugin settings can be locked down by adding `define('JETPACK_MC_LOCKDOWN', true)` to wp-config.php for complete security. This can be useful to prevent other admins being able to reactivate blacklisted modules.

= Development =

The project can be forked and or contributed to on [Github](https://github.com/RavanH/jetpack-module-control).

All contributions -- be it in the form of feature requests, bug reports, translations or code -- are welcome!

= Translations =

The package contains a sample jetpack-mc-xx_XX.po file ready for translators.

- **Dutch** * R.A. van Hagen http://status301.net/ (version 1.4)
- **French** * R.A. van Hagen http://status301.net/ (version 1.2, incomplete)

Please [contact me](http://status301.net/contact-en/) to submit your translation and get mentioned here :)

== Installation ==

1. Install Jetpack Module Control either via the WordPress.org plugin directory, or by uploading the files to your server.
2. After activating the plugin, go to either Settings > General (on single site) or Network Admin > Settings (on multisite) you can find the new Jetpack Module Control section.
3. Select any module you wish to remove and save settings.
4. If you are on a single site installation and you wish to prevent other admins from reactivating any blacklisted modules, add `define('JETPACK_MC_LOCKDOWN', true);` to your wp-config.php to lock down settings.
5. That's it.

== Screenshots ==

1. Options section with Blacklist.

== Upgrade Notice ==

= 1.5 =
Allow site admins override, contributed by @harshit_ps

== Changelog ==

= 1.5 =
Date: 2016/12/07

* NEW: option to allow site admins override contributed by @harshit_ps https://github.com/RavanH/jetpack-module-control/pull/8

= 1.4.2 =
Date: 2016/04/14

* Text domain name change to correspond with plugin slug

= 1.4.1 =
Date: 2016/04/01

* Added FR/NL translation files

= 1.4 =
Date: 2016/03/29

* Allow per site settings by Super Admin
* Added Sitemaps to known modules

= 1.3 =
Date: 2015/09/02

* Module names now translated using jetpack.mo
* BUGFIX options not saving deactivation
* BUGFIX translation files not found

= 1.2 =
Date: 2015/08/01

* Fix "Missing argument 1 for Jetpack_Module_Control::add_settings_section()" https://github.com/RavanH/jetpack-module-control/issues/2

= 1.1 =
Date: 2015/05/11

* Detect the Unplug Jetpack plugin

= 1.0 =
Date: 2015/05/05

* Jetpack Debug Mode to allow modules without connection
* Show which modules require a connection

= 0.4 =
Date: 2015/05/01

* Settings action link on Network plugins page

= 0.3 =
Date: 2015/04/27

* Added dashicons

= 0.2 =
Date: 2015/04/26

* Added network options

= 0.1 =
Date: 2015/04/25

* Initial release
