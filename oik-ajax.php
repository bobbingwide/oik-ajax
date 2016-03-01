<?php 
/**
Plugin Name: oik-ajax
Plugin URI: http://www.oik-plugins.com/oik-plugins/oik-ajax
Description: Ajaxify paged shortcodes
Version: 0.0.0
Author: bobbingwide
Author URI: http://www.oik-plugins.com/author/bobbingwide
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2016 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

oik_ajax_loaded();


/**
 * Functions to invoke when oik-ajax is loaded
 * 
 * "oik_shortcode_result" is automatically invoked for each shortcode
 * but not necessarily invoked for each paginatable output.
 
 * If that's required you can invoke the filter yourself.
 * BUT we have to be able to convert whatever's been called into a shortcode
 * OR at least an AJAX/JSON request.
 * 
 */
function oik_ajax_loaded() {
	add_filter( "oik_shortcode_result", "oika_oik_shortcode_result", 11, 4 );
}

/**
 * Implement "oik_shortcode_result" for oik-ajax
 * 
 * @param string $result - the output of the shortcode
 * @param array $atts - the shortcode parameters
 * @param string $content - the text content passed to the shortcode
 * @param string $tag - the shortcode tag
 * @return string $result 
 */
function oika_oik_shortcode_result( $result, $atts, $content, $tag ) {
	$result = oika_build_ajax_shortcode( $result, $atts, $content, $tag );					 
	//$result .= $ajax_shortcode;
	
	return( $result );
}

/**
 * Build an "ajax" shortcode
 *
 * I'm just playing at the moment
 * Finding out what's necessary
 * 
 * The request should be an AJAX request similar to shortcake
 * passing the shortcode and the context under which the shortcode was invoked
 * 
 *
 */
function oika_build_ajax_shortcode( $result, $atts, $content, $tag ) {
	oika_enqueue_jquery();
	sdiv( "ajax-shortcode" );
	$flat_atts = oika_flatten_atts( $atts );
	e( "[$tag$flat_atts]" );
	$current_post = oika_current_post();
	e( $current_post ); 
	e( $result );
	
	ediv();
	
	$ajax_shortcode = bw_ret();
	return( $ajax_shortcode );
}


/**
 * Flatten the atts parameter back into shortcode parameters
 *
 */
function oika_flatten_atts( $atts ) {
	bw_trace2();
	$flat_atts = null;
	if ( is_array( $atts) && count( $atts ) ) {
		foreach ( $atts as $key => $value ) {
			if ( is_object( $value ) ) {
				// WP_Query contains some interesting stuff - do we need it?
			} else {
				$flat_atts .= " $key=$value";
			}
		}
	}	
	return( $flat_atts );
}

/**
 * Return the current post ID
 *
 * @TODO Do we need multiple post IDs if we're working with nested shortcodes
 * OR can we extract something from WP_Query? 
 * OR is that how it works anyway.
 * 
 */
function oika_current_post() {
	$post = bw_global_post();
	if ( $post ) {
		$post_id = $post->ID;
	} else {
		$post_id = 0;
	}
	return( $post_id );
}


/**
 * Enqueue the jQuery to hook into the pagination links
 *
 */
function oika_enqueue_jquery() {
	//bw_jquery_enqueue_script(
	$script = "oik-ajax";
	$script_url = oik_url( "js/oik-ajax.js", "oik-ajax" );
	$dependence = array( "jquery" );
	$enqueued = wp_enqueue_script( $script, $script_url, $dependence ); 
}


/*
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

*/
