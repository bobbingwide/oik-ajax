# oik-ajax 
![banner](https://raw.githubusercontent.com/bobbingwide/oik-ajax/master/assets/oik-ajax-banner-772x250.jpg)
* Contributors: bobbingwide
* Donate link: http://www.oik-plugins.com/oik/oik-donate/
* Tags: upload, plugin, theme
* Requires at least: 4.4
* Tested up to: 4.7.1
* Stable tag: 0.0.0
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description 
Ajaxifies the output of paged shortcodes so that more content can be loaded to replace the existing content.

## Installation 
1. Upload the contents of the oik-ajax plugin to the `/wp-content/plugins/oik-ajax` directory
1. Activate the oik-ajax plugin through the 'Plugins' menu in WordPress.

## Frequently Asked Questions 

# What is this plugin for? 

- To reduce server load
- To improve end user experience

# Why is this plugin dependent upon oik? 
It hooks into filters which are invoked by oik when a shortcode is invoked.


# How does it work? 
It wraps the output of a paginated shortcode with a div and some jQuery code
that hooks into each of the pagination links.

In order to achieve this it needs some additional information that is passed back to the server.

## Screenshots 
1. None yet
2.
3.

## Upgrade Notice 
# 0.0.1 
Formal release. Tested with WordPress 4.7.1

# 0.0.0-alpha.0407 
For testing on herbmiller.me and bobbingwide.org.uk

# 0.0.0-alpha.0328 
Supports ajaxified pagination of code using meta_query in the get posts query

# 0.0.0-alpha.0316 
Now fetches the required post for context

# 0.0.0-alpha.0314 
New plugin, also available from GitHub and oik-plugins.

## Changelog 
# 0.0.1 
* Changed: banner image
* Tested: With WordPress 4.7.1 and WordPress Multisite

# 0.0.0-alpha.0407 
* Tested: With WordPress 4.5-RC1

# 0.0.0-alpha.0328 
* Added: Support ajaxified pagination of shortcode / queries with meta_query in $atts https://github.com/bobbingwide/oik-ajax/issues/4
* Added: Scroll to start of a new page on update https://github.com/bobbingwide/oik-ajax/issues/3
* Fixed: Need to push and pop to only wrap the current section
* Added: Support pagination of shortcodes with nested content https://github.com/bobbingwide/oik-ajax/issues/1

# 0.0.0-alpha.0316
* Added: oika_get_post() fetches the selected post before running the shortcode

# 0.0.0-alpha.0314 
* Added: New plugin - to improve the end user experience of WP-a2z.com and oik-plugins.com


