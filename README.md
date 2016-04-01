## Module Control for Jetpack

[Jetpack](https://wordpress.org/plugins/jetpack/) adds powerful features... but sometimes we don't want them all. This plugin brings additional control over Jetpack modules. You can blacklist / remove individual modules, prevent auto-activation or allow activation without a WordPress.com account.

For more information, check out [Module Control for Jetpack on WordPress.org](https://wordpress.org/plugins/jetpack-module-control/).

## Contributors

Pull requests, bug reports or feature requests are always welcome!

## Wishlist

More options beside blacklist: select which to auto-activate, which to show in featured, jumpstart...

Consider if an option to "force_deactivate" as described on https://github.com/Automattic/jetpack/issues/1452 could be useful for multisite.

Option to completely disable JUMPSTART with `Jetpack_Options::update_option( 'jumpstart', 'jumpstart_dismissed' );` or does jumpstart disappear automagically if `apply_filters( 'jetpack_module_feature' ... ` returns empty?

Can we disable Debug link in the footer menu? Afraid not... Maybe contribute a filter to Jetpack?

Can we disable the "Connect to get started" nag?

## License

[GPLv3+](http://www.gnu.org/licenses/gpl-3.0.html)
