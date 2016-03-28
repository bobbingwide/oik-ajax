<?php 
/**
Plugin Name: oik-ajax
Plugin URI: http://www.oik-plugins.com/oik-plugins/oik-ajax
Description: Ajaxify paged shortcodes
Version: 0.0.0-alpha.0316
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
 * 
 * Use "oik_navi_result" to enable ajaxified pagination when 
 * invoking the shortcode function directly. {@see bw_navi}
 * 
 * If that's required you can invoke the filter yourself.
 * BUT we have to be able to convert whatever's been called into a shortcode
 * OR at least an AJAX/JSON request.
 * 
 */
function oik_ajax_loaded() {
	add_filter( "oik_shortcode_result", "oika_oik_shortcode_result", 11, 4 );
	add_filter( "oik_navi_result", "oika_oik_shortcode_result", 11, 4 );
	add_action( "wp_ajax_oik-ajax-do-shortcode", "oika_oik_ajax_do_shortcode" );
	add_action( "wp_ajax_nopriv_oik-ajax-do-shortcode", "oika_oik_ajax_do_shortcode" );
	add_filter( "oik_shortcode_atts", "oika_oik_shortcode_atts", 11, 3 );
	add_filter( "oik_shortcode_content", "oika_oik_shortcode_content", 10, 3 );
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
 * We have to remove the following atts:
 * key   | reason
 * ------ | ------
 * paged | otherwise paging won't work
 * meta_query | oika_flatten_atts() can't handle complex arrays and do_shortcode is not good with serialized parameters
 * 
 * 
 * @param string $result the original shortcode result
 * @param array $atts the shortcode parameters
 * @param string $content shortcode content
 * @param string $tag the shortcode name
 * @return shortcode result wrapped in oik-ajax pagination
 */
function oika_build_ajax_shortcode( $result, $atts, $content, $tag ) {
  // @TODO Remove temporary code that prevents ajaxification for shortcodes with content
	if ( $content ) {
		//return( $result );
	}
	$bwscid = bw_array_get( $atts, 'bwscid', null );    
	$paged = bw_array_get( $atts, "paged", null );
	if ( $bwscid ) {
		bw_push();
		oika_enqueue_jquery();
		$ajaxurl = admin_url( "admin-ajax.php" );
		unset( $atts['paged'] );
		$content0 = bw_array_get( $atts, "content0", null );
		unset( $atts['content0'] );
		$meta_query = bw_array_get( $atts, "meta_query", null );
		if ( $meta_query ) {
			$meta_query = serialize( $meta_query );
			$meta_query = urlencode( $meta_query );
		}
		unset( $atts['meta_query'] );
		$flat_atts = oika_flatten_atts( $atts );
		$kvs = kv( "data-url", "$ajaxurl" );
		$kvs .= kv( "data-shortcode", "$tag$flat_atts" ); 
		$kvs .= kv( "data-action", "oik-ajax-do-shortcode" );
		$kvs .= kv( "data-post", oika_current_post() );
		$kvs .= kv( "data-bwscid", $bwscid );
		$kvs .= kv( "data-paged", $paged );
		$kvs .= kv( "data-content0", $content0 );
		$kvs .= kv( "data-meta_query", $meta_query );
		sdiv( "ajax-shortcode", null, $kvs);
		//e( "[$tag$flat_atts]" );
		//e( $current_post ); 
		e( $result );
	
		ediv();
	
		$ajax_shortcode = bw_ret();
		bw_pop();
	} else {	
		$ajax_shortcode = $result;
	} 
	
	return( $ajax_shortcode );
}


/**
 * Flatten the atts parameter back into shortcode parameters
 *
 * We can't use json_encode since it baulks on objects in the array
 * so let's just do our own thing
 */
function oika_flatten_atts( $atts ) {
	bw_trace2();
	$flat_atts = "";
	if ( is_array( $atts) && count( $atts ) ) {
		foreach ( $atts as $key => $value ) {
			if ( is_object( $value ) ) {
				// WP_Query contains some interesting stuff - do we need it?
			} else {
				if ( is_array( $value ) ) {
					$value = implode( ",",  $value );
				}
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


/**
 * Expand the chosen shortcode in the required context
 *
 * Paginated content can be created by a number of different shortcodes.
 * Smart shortcodes are able to determine what they need to do from the current context.
 * Normally this involves obtaining post information from the global post object.
 * We need to load the selected post in order to be able to do this.
 *
 * Security checking - can the user view the post? - should continue to be performed
 * 
 */
function oika_oik_ajax_do_shortcode() {
	bw_trace2();
	do_action( "oik_add_shortcodes" );
	//bw_trace2( $_REQUEST, "_REQUEST" );
	$shortcode = urldecode( bw_array_get( $_REQUEST, "shortcode", null ) );
	
	$post_id = bw_array_get( $_REQUEST, "post", null ); 
	
	$post = oika_get_post( $post_id );
	
	$link = bw_array_get( $_REQUEST, "link", null );
	$bwscid = bw_array_get( $_REQUEST, "bwscid", null );
	
	bw_trace2( $shortcode, "shortcode", false );
	//$shortcode = oika_alter_shortcode( $shortcode, $link, $bwscid );
	
  $_SERVER['REQUEST_URI'] = $link;
	$page = oika_get_page_from_link( $link, $bwscid );
	
	// Pretend that the shortcode being paged is the first one in the 'content'
	// Does this work for shortcodes in widgets?
	//
	//$_REQUEST["bwscid$bwscid"] = $page;
	$_REQUEST["bwscid1"] = $page;
	
	$content0 = bw_array_get( $_REQUEST, "content0", null );
	if ( $content0 ) {
		$shortcode_content = oika_fetch_shortcode_content( $post, $shortcode, $content0 );
		
		$result = bw_do_shortcode( $shortcode_content );	 // bw_do_shortcode( "[$shortcode]$content[/$shortcode]" );   
	
	} else { 
		$result = bw_do_shortcode( "[$shortcode]" );
	}
	
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
 * Return the requested page from the link
 * 
 * @param string $link The requested link
 * @param integer $bwscid 
 * @return integer the page number required
 */
function oika_get_page_from_link( $link, $bwscid ) {
	$parts = wp_parse_url( $link );
  parse_str( $parts['query'], $query );
	$page = bw_array_get( $query, "bwscid$bwscid", null );
	bw_trace2( $page, "page", true );
	return( $page );
}

/**
 * Fetch the selected post 
 * 
 * and make it the global post object
 *
 * @param ID $post_id - the post to be fetched
 */
function oika_get_post( $post_id ) {
	$post = get_post( $post_id );
	bw_global_post( $post );
	return( $post );
}
 
/**
 * Implement "oik_shortcode_content" to pre-paginate the shortcode content
 *
 * We should have already worked out the pagination with the following values set.
 * [posts_per_page] => 3
 * [bwscid] => 1  
 * [paged] => 1
 *
 * To make the pagination logic work we need to fiddle bw_query to look like we've actually done some SQL.
 * We also need to indicate to the front-end that the pagination is content based.
 * @TODO find a way to cater for start and end tags. e.g. for lists or tables
 * 
 * @param string $content content to be paginated
 * @param array $atts shortcode attributes 
 * @param string $tag the shortcode
 * @return string that part of the content to be processed
 */										 
function oika_oik_shortcode_content( $content, $atts, $tag ) {
	if ( $content ) {	
		$content = trim( $content );
		$content_array = explode( "\n", $content );
		$start = 0;
		$posts_per_page = bw_array_get( $atts, "posts_per_page", null );
		if ( $posts_per_page ) {
			$page = bw_array_get( $atts, "paged", 1 );
			if ( $page > 1 ) {
				$start = ( $page-1 ) * $posts_per_page;
			}
			//$end = $start + $posts_per_page;
			$count = count( $content_array );
			bw_trace2( $content_array, "content_array" );
      //$end = min( $start + $posts_per_page, $count ) -1 ;
			$content_array = array_slice( $content_array, $start, $posts_per_page );
			$content = implode( "\n", $content_array );
		}
	}
	return( $content );
}

/**
 * Implement "oik_shortcode_atts" for oik-ajax
 *
 * If the shortcode has nested content we need to choose the right page to be processed
 * If the pseudo-shortcode used meta_query we need to re-apply this; it's far too fiddly to attempt to pass a meta_query shortcode parameter
 *
 * @param array $atts shortcode attributes
 * @param string $content 
 * @param string $tag
 * @return array updated shortcode atts
 */ 
function oika_oik_shortcode_atts( $atts, $content, $tag ) {
	if ( $content ) {	
		$content = trim( $content );
		$content_array = explode( "\n", $content );
		$start = 0;
		$posts_per_page = bw_array_get( $atts, "posts_per_page", null );
		if ( $posts_per_page ) {
			$page = bw_array_get( $atts, "paged", 1 );
			if ( $page > 1 ) {
				$start = ( $page-1 ) * $posts_per_page;
			}
			//$end = $start + $posts_per_page;
			$count = count( $content_array );
			$atts['bw_query']->found_posts = $count;
			$atts['bw_query']->max_num_pages = ceil( $count / $posts_per_page );
			$atts['content0'] = $content_array[0];
		}
	}
	$meta_query = bw_array_get( $_REQUEST, "meta_query", null );
	if ( $meta_query ) {
		$meta_query = urldecode( $meta_query );
		$meta_query = unserialize( $meta_query );
		bw_trace2( $meta_query, "meta_query", false, BW_TRACE_VERBOSE );
		$atts['meta_query'] = $meta_query;
	}
	return( $atts );
}

/**
 * Locate the shortcode content from the post
 *
 * bw_do_shortcode( "[$shortcode]$content[/$shortcode]" );
 * 
 * We attempt to use the value of content0 to locate the shortcode in the post. 
 * We believe that the end of the shortcode cannot be part of the content, 
 * as this would confuse WordPress.
 *
 * @TODO Caveat: Things can go awry if there are two identical shortcodes with the same first line
 * Perhaps we need some sort of CRC check to confirm what we have found is what we were looking for.
 * 
 *
 * @param object $post
 * @param string $shortcode
 * @param string $content0
 * @return string the shortcode we want to expand
 */
function oika_fetch_shortcode_content( $post, $shortcode, $content0 ) {
	bw_trace2();
	//$result = $post->post_content;
	//$result .= $shortcode;
	$pos = strpos( $post->post_content, $shortcode );
	//$result .= $pos;
	$content0 = trim( $content0 );
	$content0 = stripslashes( $content0 );
	
	$pos = strpos( $post->post_content, $content0 );
	//$result .= $pos;
	
	$code_words = explode(" ", $shortcode );
	$code = $code_words[0];
	$len = strpos( $post->post_content, "[/$code]" );
	$len -= $pos;
	if ( $pos && $len ) {
		$result = "[$shortcode]";
		$result .= substr( $post->post_content, $pos, $len );
		$result .= "[/$code]";
	} else {
		$result = "Logic error in oika_fetch_shortcode_content" ;
	}
	 
	bw_trace2( $result, "RESULT!", false);
	 
	return( $result );


}


