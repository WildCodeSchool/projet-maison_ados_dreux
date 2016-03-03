=== Plugin Name ===
Contributors: mightydigital, farinspace
Tags: press, news, media, press release, press coverage, news coverage, media coverage, corporate, business
Requires at least: 3.9
Tested up to: 4.4.2
Stable tag: 0.12.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simplified press release and media coverage management for business websites.

== Description ==

The Nooz WordPress plugin simplifies management of your press releases and press coverage content. It adds custom post
types along with carefully crafted settings, giving you the flexibility needed to manage your corporate news section.

After installing the plugin, you will be prompted to create an initial set of pages needed to manage your news section:

* **/news/**
The news page shows a list of the latest 5 (configurable) press releases and press coverage items.

* **/news/press-releases/**
The press releases page shows a list of all available press releases.

* **/news/press-coverage/**
The press coverage page shows a list of all available press coverage.

The plugin exposes a `[nooz]` shortcode allowing you to insert press release and press coverage lists on any of your pages.

== Installation ==

The easiest way to install the plugin is to:

1. Login to your WordPress installation
2. Go to the Plugins page and click "Add New"
3. Perform a search for "Nooz"
4. Locate the Nooz plugin by Mighty Digital
5. Click the "Install Now" button and click "Ok" to confirm
6. Click "Activate Plugin" or activate the plugin from the Plugins page

If you've downloaded the latest plugin files:

1. Upload the Nooz plugin folder to the /wp-content/plugins/ directory
2. Activate the plugin from the Plugins page

== Screenshots ==

1. Adds support for press release and press coverage sections
2. Custom section for press releases
3. Custom section for external press coverage
4. General list page settings
5. Press release page settings
6. Press coverage settings

== Frequently Asked Questions ==

= Is WordPress Multisite Supported? =

We have not done much testing with the plugin on multisite, however, this is planned and on the roadmap.

= Is the plugin available in my language? =

If you would like to create a translation for your region, please contact us.

== Changelog ==

= 0.12.1 =
* fixed issue with default shortcode type
* cleanup, better tests

= 0.12.0 =
* added release output filter: add_filter( 'nooz_release', function ( $output, $data ) { return $output; }, 10, 2 );
* code adjustments, better tests

= 0.11.0 =
* added shortcode output filter: add_filter( 'nooz_shortcode', function ( $output, $data ) { return $output; }, 10, 2 );
* adjusted shortcode output data vars

= 0.10.0 =
* added "featured_image" shortcode attribute
* nooz shorcode "type" attribute works with other post types
* fixed issue when sorting by year and using priority/order

= 0.9.0 =
* prompts identify itself as coming from the Nooz plugin
* improved functionality (uth)

= 0.8.3 =
* conditionally enabling "post-thumbnails" theme support for plugin managed post types

= 0.8.2 =
* fixed warnings being displayed to subscriber role

= 0.8.1 =
* updated contextual help

= 0.8.0 =
* added featured image support for press releases and press coverage
* added option to set a default featured image
* added pagination support for press releases and press coverage list shortcode
* added "read more" links support for press releases and press coverage list shortcode
* added priority sorting option, allowing press release and press coverage items to be pinned
* better feed support for both press releases and press coverage
* improved functionality (uth)
* security additions

= 0.7.0 =
* added contextual help
* style adjustments to press release subtitle meta box

= 0.6.1 =
* adjustment to press-release datetime markup structure

= 0.6.0 =
* added support for release excerpts
* added support for coverage excerpts
* fixed support for coverage source
* added "nooz_init" action
* uninstall.php sends 404 on error
* updated lib files
* better autoloading
* code cleanup

= 0.5.5 =
* improved functionality (uth)
* code cleanup

= 0.5.4 =
* fixed issue with uninstall include file

= 0.5.3 =
* removed unused files in the core version which may have caused errors

= 0.5.2 =
* fixed issue with supporting files (proper version numbering)

= 0.5.1 =
* fixed issue with uninstall

= 0.5.0 =
* improved ui
* added date format field for press release and press coverage list
* press release ending field is now an open text field
* improved functionality (uth)

= 0.4.2 =
* fixed broken link to press coverage page

= 0.4.1 =
* fixed issue with press coverage display

= 0.4.0 =
* better plugin internals
* revised autoloader prevents interference with our other plugins
* added uninstall

= 0.3.0 =
* prompt editor role (and up) to create default press pages
* if not set, save "release_slug" option

= 0.2.0 =
* added user configurable settings
* better functionality (uth)

= 0.1.0 =
* initial release
