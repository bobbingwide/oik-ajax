/** (C) Copyright Bobbing Wide 2016
 *
 * jQuery to initiate AJAX requests to perform pagination
 * 
 * 
 */
// closure to avoid namespace collision
(function($) { 

  var pageloaded = function( json_result ) {
    page = json_result.page;
    link = json_result.link;
    result = json_result.result
    //alert( result.page + result.link );
    // We need to find the div.ajax-shortcode that this data is to replace
    // a class='page-numbers' href='/wordpress/2016/03/02/page-2/?bwscid1=3'>
    // we need to find the link that has href=link
    $parent = $( "a[href='" + link + "']" ).parents( "div.ajax-shortcode" );
    //$parent.html( result );
    $('html, body').animate( { scrollTop: $parent.offset().top }, 500 );
    $parent.replaceWith( result );
    $('div.ajax-shortcode a.page-numbers').click( loadpage ); 
  }

  var loadpage = function() {
    //alert( $this );
    // We need to find the div.ajax-shortcode that this link is part of
    // meanwhile find the href
    $link = $(this).attr( "href" );

    $parent= $(this).parents( "div.ajax-shortcode" );
    url = $parent.data( "url" );
    shortcode = $parent.data( "shortcode" );
    post = $parent.data( "post" );
    bwscid = $parent.data( "bwscid" );
    content0 = $parent.data( "content0" );
    data = { 'action': 'oik-ajax-do-shortcode',
          'shortcode': shortcode,
          'post': post,
          'link': $link,
          'bwscid': bwscid, 
          'content0': content0 };
    //alert( url  ) ;
    $.post( url, data, pageloaded, 'json');
    
    return( false ); 
  }

  $(document).ready( function($) {
    $('div.ajax-shortcode a.page-numbers').click( loadpage ); 
  })
}) (jQuery);
        

