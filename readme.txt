=== Jetpack Module Control ===
Contributors: RavanH
Tags: Jetpack, jet pack, module, modules, manual control, blacklist, blacklist modules, slim jetpack, jetpack light
Stable tag: 1.0
Requires at least: 4.1
Tested up to: 4.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Your Jetpack, Restricted.

== Description ==

[Jetpack](https://wordpress.org/plugins/jetpack/) adds powerful features... but sometimes we don't want them all. This plugin brings additional control over Jetpack modules. You can blacklist / remove individual modules, prevent auto-activation or allow activation without a WordPress.com account.

This plugin can run on single and multisite installations. 

In most use cases, a carefully considered combination of Jetpack modules can be a really good thing. But not always is much consideration being done beforehand. Or site admins just don't know all the implications... 

Some examples: Does your web server use server side caching, making it incompatible with the Mobile Theme module? Do you already have a lightbox plugin running, making the Carousel module redundant? Do you not want any admins monitoring uptime with the Monitor module just to call you the second the site is not accessible? Do you offer your own backup service and do not care much for VaultPress competition? 

Any one of Jetpack's modules can bring overlapping or even conflicting functionality. In such cases, being able to remove them is a good thing.
 
On **multisite**, it can only be network activated and controls Jetpack on all sites. Jetpack itself can, but does not need to be, network activated.

For **single site** installations, plugin settings can be locked down by adding `define('JETPACK_MC_LOCKDOWN', true)` to wp-config.php for complete security. This can be useful to prevent other admins being able to reactivate any blacklisted modules.

**Development**

The project can be forked and or contributed to on [Github](https://github.com/RavanH/jetpack-module-control)

All contribution, be it in the form offeature requests, bugreports or code, is welcome!

**Translations**

The package contains a sample jetpack-mc-xx_XX.po file ready for translators.

- **Dutch** * R.A. van Hagen http://status301.net/ (version 1.0)
- **French** * R.A. van Hagen http://status301.net/ (version 0.3, incomplete)

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

= 1.0 =
New: Jetpack Debug Mode to allow modules without connection.

== Changelog ==

= 1.0 =
Date: Mai 5nd, 2015

* Jetpack Debug Mode to allow modules without connection
* Show which modules require a connection

= 0.4 =
Date: Mai 1st, 2015 

* Settings action link on Network plugins page

= 0.3 =
Date: April 27rd, 2015

* Added dashicons

= 0.2 =
Date: April 26rd, 2015

* Added network options

= 0.1 =
Date: April 25rd, 2015

* Initial release
