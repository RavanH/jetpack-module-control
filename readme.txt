=== Module Control for Jetpack ===
Contributors: RavanH
Tags: Jetpack, jet pack, jetpack light, manual control, blacklist, blacklist jepack modules, slim jetpack
Stable tag: 1.3
Requires at least: 4.0
Tested up to: 4.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Your Jetpack, Controlled.

== Description ==

[Jetpack](https://wordpress.org/plugins/jetpack/) adds powerful features... but sometimes we don't want them all. This plugin brings additional control over Jetpack modules. You can blacklist / remove individual modules, prevent auto-activation or allow activation without a WordPress.com account.

In most use cases, a carefully considered combination of Jetpack modules can be a really good thing. But not always is much consideration being done beforehand. Or site admins just don't know all the implications... 

For examples, your Nginx web server uses FastCGI caching, making it incompatible with the Mobile Theme module. Or you already have a lightbox plugin running, incompatible with Carousel. Or you do not want any admins monitoring uptime with the Monitor module just to call you every time their site is briefly inaccessible. Or you offer your own backup service and do not care much for VaultPress competition.

Any one of Jetpack's modules can bring overlapping or even conflicting functionality. In such cases, being able to prevent (accidental) activation is a good thing.
 
= Single and Multisite compatible =

Athough the original idea arose on a multisite installation, Jetpack Module Control is developed for both single and multisite installations. 

On **multisite**, it can only be network activated and controls Jetpack on all sites. Jetpack itself can, but does not need to be, network activated.

For **single site** installations, plugin settings can be locked down by adding `define('JETPACK_MC_LOCKDOWN', true)` to wp-config.php for complete security. This can be useful to prevent other admins being able to reactivate blacklisted modules.

= Development =

The project can be forked and or contributed to on [Github](https://github.com/RavanH/jetpack-module-control). All contributions -- be it in the form of feature requests, bugreports or code -- are welcome!

= Translations =

The package contains a sample jetpack-mc-xx_XX.po file ready for translators.

- **Dutch** * R.A. van Hagen http://status301.net/ (version 1.2)
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

= 1.3 =
Module names translated. Fix opions saving bug and translation path.

== Changelog ==

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
