=== JPEG Quality Settings ===
Contributors: invisibledragonltd
Tags: jpeg thumbnail
Requires at least: 5
Tested up to: 5.2.3
Stable tag: 4.3
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

JPEG Quality Settings allwows you to fine tune the quality settings Wordpress uses for making JPEG thumnails.

== Description ==

By default Wordpress forces all thumbnails be set to a quality level of 82. But what if you want to change that? Sure the
jpeg qualtiy filters exist if you want to code in a value, but they affect all JPEG thumbnails which may not be desirable.

For example you might want a blog background to be of a lower quality, hence lowering the filesize and increasing the speed
of which the page loads.

By using the JPEG Quality Settings plugin you can now customize the quality based on whatever values you want.

== Installation ==

Install in the usual Wordpress way. Reconmended to use the plugin directory from inside your Wordpress install.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->JPEG Quality screen to configure the plugin
1. (Make your instructions match the desired user flow for activating and installing your plugin. Include any steps that might be needed for explanatory purposes)

== Frequently Asked Questions ==

= Does this affect any existing thumbnails? =

No, you will need to install a plugin to regenrate thumbnails. Many good free options exist.

= Can my theme provide default options? =

Yes! By taking advantage of the filters installed. For example:

```
add_filter('jpegquality_my_blog_size', 'mytheme_myblogsize_quality');

function mytheme_myblogsize_quality(){
	return 10;
}
```

== Screenshots ==

1. This screenshot shows the admin panel for changing quality settings

== Changelog ==

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.0 =
Initial Release. How are you upgrading?
