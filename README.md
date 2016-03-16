# oik-ajax 
* Contributors: bobbingwide
* Donate link: http://www.oik-plugins.com/oik/oik-donate/
* Tags: upload, plugin, theme
* Requires at least: 4.4
* Tested up to: 4.5-beta3
* Stable tag: 0.0.0
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description 
Ajaxifies the output of paged shortcodes so that more content can be loaded to replace the existing content.

![banner](https://raw.githubusercontent.com/bobbingwide/oik-ajax/master/assets/oik-ajax-banner-772x250.png)

![icon](https://raw.githubusercontent.com/bobbingwide/oik-ajax/master/assets/oik-ajax-icon-772x250.png)



## Installation 
1. Upload the contents of the oik-ajax plugin to the `/wp-content/plugins/oik-ajax` directory
1. Activate the oik-ajax plugin through the 'Plugins' menu in WordPress.

## Frequently Asked Questions 

# What is this plugin for? 

- To reduce server load
- To improve end user experience

# Why is this plugin dependent upon oik? 
It hooks into filters which are invoked by oik when a shortcode is invoked.

# Does it use Angular.js? 
No. But it might in the future.


# How does it work? 


 *
 * Here is some sample output from the [bw_navi] shortcode
 * we don't know the parameters that were used in the shortcode
 * so can't reproduce the results at will
 * We can tell from the output that we're viewing page 1
 * and we also know the URL where the shortcode was executed
 * What we need in the output first of all, is the context of the shortcode.
 *

`
<p><span class="bw_s2eofn">1 to 15 of 207</span>
<ol class="bw_list">
<li><a href="http://qw/wporg/oik_shortcodes/ad/" title="ad &#8211; theme_advertisement">ad &#8211; theme_advertisement</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/api/" title="api &#8211; Link to API definitions">api &#8211; Link to API definitions</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/apiref-display-the-api-reference/" title="apiref &#8211; Display the API Reference">apiref &#8211; Display the API Reference</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/apis/" title="apis &#8211; Link to API definitions">apis &#8211; Link to API definitions</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/archives/" title="archives &#8211; Archive index">archives &#8211; Archive index</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/artisteer/" title="artisteer &#8211; Styled form of Artisteer">artisteer &#8211; Styled form of Artisteer</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/audio-2/" title="audio &#8211; Displays uploaded audio file as an audio player">audio &#8211; Displays uploaded audio file as an audio player</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/audio/" title="audio &#8211; Embed audio files">audio &#8211; Embed audio files</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/bbboing-obfuscate-text-but-leave-it-readable/" title="bbboing &#8211; obfuscate text but leave it readable">bbboing &#8211; obfuscate text but leave it readable</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/bing-2/" title="bing &#8211; Styled form of bing">bing &#8211; Styled form of bing</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/blog_title/" title="blog_title &#8211; blog title (Artisteer theme)">blog_title &#8211; blog title (Artisteer theme)</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/bob-3/" title="bob &#8211; Styled form of bob">bob &#8211; Styled form of bob</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/bong/" title="bong &#8211; Styled form of bong">bong &#8211; Styled form of bong</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/bp/" title="bp &#8211; Styled form of BuddyPress">bp &#8211; Styled form of BuddyPress</a></li>
<li><a href="http://qw/wporg/oik_shortcodes/bw/" title="bw &#8211; Expand to the logo for Bobbing Wide">bw &#8211; Expand to the logo for Bobbing Wide</a></li>
</ol>
<p><span class='page-numbers current'>[1]</span>
<a class='page-numbers' href='/wporg/oik_shortcodes/bw_navi/?bwscid1=2'>[2]</a>
<a class='page-numbers' href='/wporg/oik_shortcodes/bw_navi/?bwscid1=3'>[3]</a>
<span class="page-numbers dots">&hellip;</span>
<a class='page-numbers' href='/wporg/oik_shortcodes/bw_navi/?bwscid1=14'>[14]</a>
<a class="next page-numbers" href="/wporg/oik_shortcodes/bw_navi/?bwscid1=2">Next &raquo;</a></p>
`

## Screenshots 
1. None yet
2.
3.

## Upgrade Notice 
# 0.0.0-alpha.0316 
Now fetches the required post for context

# 0.0.0-alpha.0314 
New plugin, also available from GitHub and oik-plugins.

## Changelog 
# 0.0.0-alpha.0316
* Added: oika_get_post() fetches the selected post before running the shortcode

# 0.0.0-alpha.0314 
* Added: New plugin - to improve the end user experience of WP-a2z.com and oik-plugins.com


