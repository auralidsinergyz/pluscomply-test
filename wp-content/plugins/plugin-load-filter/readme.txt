=== Plugin Load Filter ===
Contributors: enomoto celtislab
Tags: plugin, dynamic deactivate, disable plugins, filter, performance, language, locale
Requires at least: 5.3
Tested up to: 6.1
Requires PHP: 7.2
Stable tag: 4.0.13
Donate link: https://celtislab.net/en/wp-plugin-load-filter-addon/
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Dynamically activate the selected plugins for each page. Response will be faster by filtering plugins.

== Description ==

Although you may have installed a lot of plugins, you may not want (or need) them activated for all of your posts and pages. With this plugin, you will be able to deactivate unnecessary plugins for each individual post and page.

By filtering the activation of plugins, you can significantly speed up your website.

Features

 * Support Post Format type
 * Support Custom Post type
 * Support Jetpack Modules filtering
 * Support WP Embed Content card (is_embed template)
 * Support Simple Post Language Locale switcher

In addition to blog posts and pages, for example providing services as a Web application, you can also distinguish the plugins for blog and Web applications.


= To further performance up plugin =

[YASAKANI Cache](https://wordpress.org/plugins/yasakani-cache/) is a simple and easy to use super high speed page cache.


For more detailed information, there is an introduction page.

[Documentation](http://celtislab.net/en/wp-plugin-load-filter/ )


== Installation ==

1. Upload the `plugin-load-filter` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress
3. Set up from `Plugin Load Filter` to be added to the Plugins menu of Admin mode.

Note

 * This plugin to automatically activated as must-use plugin installed plf-filter.php file to MU-plugins folder. Depending on the permissions of the folders and files there is a possibility that it is not possible to install the plf-filter.php file.
 * There is also plugins that can not be filtering, such as cache plugins or must-use plugins.

Usage

 * In the Page Type Filter, you can choose from two types of filters as needed (Filter Registration)
 
  * Admin Type : Register the plugins to be used only in admin page.
  * Page Type : Register the plugins for selecting whether to activate each Page type or Post. Page Type registration plugins are once blocked, but is activated by `Page Type filter Activation` setting.

 * Select the plugins from `Page Type Filter` registration to activate (Page Type filter Activation)

  * Desktop/Mobile Filter : plugins to be used only in desktop/moble device. (wp_is_mobile function use)
  * Select the plugins that you want to activate for each Page type or Post Format type or Custom Post type.
  * Can be selected plugins to activate from Post content editing screen

 * Check

  * Please perform sufficient test whether the setting is working as expected.
  * Please also check the operation if you add or remove a plugin.
  * Filter priority :  Each Single Post Filter > Admin Type > Page Type Filter

== Upgrade Notice ==

= 4.0.0 =
URL filter is changed incompatible with the old version, so if you used it you need to reset it. 

== Screenshots ==

1. Filter Registration setting.
2. Page Filter Activation setting.
3. Setting of each post

== Changelog ==

= 4.0.13 =
* 2023-1-23
* Fixed Warning that occurred in PHP8 or higher.
* Added filter hook 'plf_custom_changes_to_active_plugins'
* Added filter hook 'plf_experimental_custom_parse_request'


= 4.0.12 =
* 2022-12-12
* Measures to prevent unintended disabling of plugins due to updates of DB data active_plugins by other plugins.


= 4.0.11 =
* 2022-11-9
* Since unnecessary data was saved in the plf_queryvars option data, it was removed and reduced in size. 
* Fixed a case where custom status post information could not be obtained.


= 4.0.10 =
* 2022-10-19 
* Fixed unnecessary queries issued in locale processing
* Fixed a case where private post judgment processing interfered with some plugins and caused a PHP error


= 4.0.9 =
* 2022-5-30 
* Fixed a bug that the use filter is incorrect and Not Use in the PLF display of admin bar.


= 4.0.8 =
* 2022-2-7 
* Fixed some cases where the used filter name was not displayed correctly in the PLF status of admin bar. 
* PHP8.1 tested and Fixed PHP Notice


= 4.0.6 =
* 2021-4-8 Fixed a filtering bug in the multi-site siteeide plugi.
Fixed a bug where url filter Addon would match only home (/) settings to all URL trail slashe.

= 4.0.5 =
* 2021-3-23 Fixed a bug that caused conflicts for some custom post types.

= 4.0.4 =
* 2021-2-12 Fixed a bug that language locale switching process was not working for private posts. 
Fixed a bug that the portfolio custom post type page in WP Jetpack plugin can't be displayed.


= 4.0.2 =
* 2020-8-31 Fixed a bug that the display of Woocommerce order management page shop_order list was blocked.
* CSS adjustment of display position shift.
* "A variable mismatch has been detected" error countermeasure 


= 4.0.1 =
* 2020-8-28 Fix bug: Fatal error: Uncaught Error: Using $this when not in object context

= 4.0.0 =
* 2020-8-26 Separate URL filtering feature into Addon.
* Added a link in Admin-bar to show the filtering status of the plugins.
* Fixed some bugs and refactored the processing code.

= 3.3.0 =
* 2020-6-5  Added simple language locale switching for per page.
* Changed conditions to PHP7.2 and WordPress5.3 or more.

= 3.1.1 =
* 2019-12-2 Fixed bug where filtering did not work when the permalink structure was set to "Plain".

= 3.1.0 =
* 2019-2-25 change. URL Filter specification (available character types and maximum number of registrations) 　　

= 3.0.5 =
* 2019-2-18 Fixed. plf-filter PHP Warning (Illegal offset type). 　　

= 3.0.4 =
* 2018-8-15 Meta Boxes CSS adjustment when using gutenberg editor. 　　

= 3.0.3 =
* 2018-6-6  Fixed. Exclude plugin_load_filter action from Ajax URL Filter.　　　　　　　

= 3.0.2 =
* 2018-5-23  Fixed bug that the filter did not work on bbPress private page, and URL filter priority modification.　　　　　　　

= 3.0.0 =
* 2018-5-11  Add REST API and Ajax request judgment function to URL filter (incompatible with old version).

= 2.5.1 =
* 2017-5-11   Add confirmation dialog to clear setting button. And Fix regular expression for AMP / URL page judgment.

= 2.5.0 =
* 2017-1-20   AMP/URL page filter support. And addition of monitoring process of "rewrite_rule" data for custom post type.

= 2.4.1 =
* 2016-10-21  fix. Archive of judgment miss (category, tag), and corresponding at the time of custom post type used to "rewrite_rules", "wp_post_statuses". 

= 2.4.0 =
* 2016-08-31  Multisite support.

= 2.3.1 =
* 2016-06-20  When the plugin update, has been fixed because there was a case of plf-filter file of MU-plugins folder is not updated

= 2.3.0 =
* 2016-06-17  Change user interface option settings. And is_embed template support. (Filter for WP Embed content card API)

= 2.2.1 =
* 2016-04-18  WP4.5 support. (get_currentuserinfo is deprecated since version 4.5! change wp_get_current_user)

= 2.2.0 =
* 2015-07-23  Code cleanups (Stop the use of transient API cache of intermediate processing data)

= 2.1.0 =
* 2015-04-30  Change user interface option settings screen.

= 2.0.1 =
* 2015-04-22  Exclude GET request(with? Parameters) to the home page from the filter. For example, Link to download the Download Manager plugins.

= 2.0.0 =
* 2015-04-16  Release
 
