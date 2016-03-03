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
	add_action( "wp_ajax_oik-ajax-do-shortcode", "oika_oik_ajax_do_shortcode" );
	add_action( "wp_ajax_nopriv_oik-ajax-do-shortcode", "oika_oik_ajax_do_shortcode" );
}

/**
 * Implement "oik_shortcode_result" for oik-ajax
 *
 * @TODO This function only intercepts the results of shortcodes which produce paginated output
 * We need something else to handle other ways that paginated output is produced.
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
	$bwscid = bw_array_get( $atts, 'bwscid', null );    
	$paged = bw_array_get( $atts, "paged", null );
	if ( $bwscid ) {
		oika_enqueue_jquery();
		$ajaxurl = admin_url( "admin-ajax.php" );
		unset( $atts['paged'] );
		$flat_atts = oika_flatten_atts( $atts );
		$kvs = kv( "data-url", "$ajaxurl" );
		$kvs .= kv( "data-shortcode", "$tag$flat_atts" ); 
		$kvs .= kv( "data-action", "oik-ajax-do-shortcode" );
		$kvs .= kv( "data-post", oika_current_post() );
		$kvs .= kv( "data-bwscid", $bwscid );
		$kvs .= kv( "data-paged", $paged );
		sdiv( "ajax-shortcode", null, $kvs);
		//e( "[$tag$flat_atts]" );
		//e( $current_post ); 
		e( $result );
	
		ediv();
	
		$ajax_shortcode = bw_ret() ;
	} else {	
		$ajax_shortcode = $result;
	} 
	
	return( $ajax_shortcode );
}


/**
 * Flatten the atts parameter back into shortcode parameters
 *
 * We can't use json_encode since it baulks on objects in the array
 * so let's just do outr own thing
 */
function oika_flatten_atts( $atts ) {
	bw_trace2();
	$flat_atts = "";
	if ( is_array( $atts) && count( $atts ) ) {
		foreach ( $atts as $key => $value ) {
			if ( is_object( $value ) ) {
				// WP_Query contains some interesting stuff - do we need it?
			} else {
				$flat_atts .= kv( $key, $value );
			}
		}
	}	
  // $flat_atts .= "atts_count=" .count( $atts );
	
	// Do we need to urlencode this, or convert " to ' or something?
	
	$flat_atts = urlencode( $flat_atts );
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
 * Should we also call wp_localize_script to set the admin URL only once?
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


function oika_oik_ajax_do_shortcode() {
	bw_trace2();
	do_action( "oik_add_shortcodes" );
	//bw_trace2( $_REQUEST, "_REQUEST" );
	$shortcode = urldecode( bw_array_get( $_REQUEST, "shortcode", null ) );
	$link = bw_array_get( $_REQUEST, "link", null );
	$bwscid = bw_array_get( $_REQUEST, "bwscid", null );
	bw_trace2( $shortcode, "shortcode", false );
	//$shortcode = oika_alter_shortcode( $shortcode, $link, $bwscid );
	
  $_SERVER['REQUEST_URI'] = $link;
	$page = oika_get_page_from_link( $link, $bwscid );
	
	$_REQUEST["bwscid$bwscid"] = $page;
	
	$result = bw_do_shortcode( "[$shortcode]" );
	
	//$content = bw_array_get( $_REQUEST
	//echo $result;
	$json_response = array( "result" => $result
												, "page" => $page
												, "link" => $link
												);
					
	$response = json_encode( $json_response ); 
	echo $response;
	bw_trace2( $response, "response", false );
	die();

}

/**
 * Updated the shortcode to reflect the requested page
 *
 * Do we need to update the shortcode or can we fiddle it some other way? All we need is the page number of the link
 * 
 * `
C:\apache\htdocs\wordpress\wp-content\plugins\oik-ajax\oik-ajax.php(199:0) oika_oik_ajax_do_shortcode(3) 207 2016-03-02T10:11:30+00:00 0.408477 0.000688 cf=wp_ajax_oik-ajax-do-shortcode 8 0 6486384/6574264 F=331 _REQUEST Array
(
    [action] => oik-ajax-do-shortcode
    [shortcode] => [bw_list+post_type%3D%22oik_pluginversion%2Coik_premiumversion%22+orderby%3D%22date%22+order%3D%22DESC%22+posts_per_page%3D%2210%22+format%3D%22L+de%22+numberposts%3D%2223%22+offset%3D%221%22+class%3D%22w50p2%22+bwscid%3D%221%22+paged%3D%221%22]
    [post] => 31772
    [link] => /wordpress/?bwscid1=2
)`
 `
 [bw_list post_type="oik_pluginversion,oik_premiumversion" orderby="date" order="DESC" posts_per_page="10" format="L de" numberposts="23" offset="1" class="w50p2" bwscid="1" paged="1"]
 `
 * 
 * @param string $shortcode shortcode which should contain bwscid=n where n is the shortcode instance starting from 1
 * @param string $link which should contain bwscidn=p where p is the requested page for this shortcode instance
 * 
 */ 
function oika_alter_shortcode( $shortcode, $link, $bwscid ) {
	$page = oika_get_page_from_link( $link, $bwscid );
	bw_trace2( $page, "page" );
	$shortcode = "[$shortcode paged=$page]";
	return( $shortcode );
}

/**
 * 
 */
function oika_get_page_from_link( $link, $bwscid ) {
	$parts = wp_parse_url( $link );
  parse_str( $parts['query'], $query );
	$page = bw_array_get( $query, "bwscid$bwscid", null );
	return( $page );
	
}


