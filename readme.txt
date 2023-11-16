=== oik-ajax ===
Contributors: bobbingwide
Donate link: http://www.oik-plugins.com/oik/oik-donate/
Tags: upload, plugin, theme
Requires at least: 4.4
Tested up to: 6.4.1
Stable tag: 0.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Ajaxifies the output of paged shortcodes so that more content can be loaded to replace the existing content.

== Installation ==
1. Upload the contents of the oik-ajax plugin to the `/wp-content/plugins/oik-ajax` directory
1. Activate the oik-ajax plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= What is this plugin for? =

- To reduce server load 
- To improve end user experience

= Why is this plugin dependent upon oik? =
It hooks into filters which are invoked by oik when a shortcode is invoked.


= How does it work? = 
It wraps the output of a paginated shortcode with a div and some jQuery code
that hooks into each of the pagination links.

In order to achieve this it needs some additional information that is passed back to the server.

== Screenshots ==
1. None yet
2.
3. 

== Upgrade Notice ==
= 0.2.1 =
Update for support for PHP 8.1 and PHP 8.2

= 0.2.0 =
Update for a fix for WordPress Multi Site

= 0.1.0 = 
Update for compatibility with jQuery 3.5.1 - planned for WordPress 5.6.0 

= 0.0.1 =
Formal release. Tested with WordPress 4.7.1 

= 0.0.0-alpha.0407 =
For testing on herbmiller.me and bobbingwide.org.uk 

= 0.0.0-alpha.0328 =
Supports ajaxified pagination of code using meta_query in the get posts query

= 0.0.0-alpha.0316 =
Now fetches the required post for context

= 0.0.0-alpha.0314 =
New plugin, also available from GitHub and oik-plugins.

== Changelog ==
= 0.2.1 =
* Changed: PHPUnit test with PHP 8.1 and PHP 8.2
* Tested: With WordPress 6.4.1 and WordPress Multisite
* Tested: With PHP 8.1 and PHP 8.2
* Tested: With PHPUnit 9.6

= 0.2.0 = 
* Fixed: Add oikai_get_ajaxurl() for use when blog switched,[github bobbingwide oik-ajax issues 5]
* Tested: With WordPress 6.0.1 and WordPress Multi Site
* Tested: With PHP 8.0

= 0.1.0 = 
* Fixed: Avoid deprecated messages with jQuery 3.5.1,[github bobbingwide oik-ajax issues 7]
* Tested: With WordPress 5.5.1
* Tested: With PHP 7.4

= 0.0.1 = 
* Changed: banner image
* Tested: With WordPress 4.7.1 and WordPress Multisite

= 0.0.0-alpha.0407 =
Tested: With WordPress 4.5-RC1

= 0.0.0-alpha.0328 =
Added: Support ajaxified pagination of shortcode / queries with meta_query in $atts [github bobbingwide oik-ajax issue 4]
Added: Scroll to start of a new page on update [github bobbingwide oik-ajax issue 3]
Fixed: Need to push and pop to only wrap the current section
Added: Support pagination of shortcodes with nested content [github bobbingwide oik-ajax issue 1]

= 0.0.0-alpha.0316
* Added: oika_get_post() fetches the selected post before running the shortcode

= 0.0.0-alpha.0314 =
* Added: New plugin - to improve the end user experience of WP-a2z.com and oik-plugins.com