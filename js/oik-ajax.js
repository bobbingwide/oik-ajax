/** (C) Copyright Bobbing Wide 2016
 *
 * jQuery to initiate AJAX requests to perform pagination
 * 
 * 
 */
// closure to avoid namespace collision
(function($) { 

  var wahay = function() {
    //alert( $this );
    // We need to find the div.ajax-shortcode that this link is part of
    // meanwhile find the href
    $link = $(this).attr( "href" );

    $parent= $(this).parents( "div.ajax-shortcode" );
    url = $parent.data( "url" );
    shortcode = $parent.data( "shortcode" );
    post = $parent.data( "post" );
    bwscid = $parent.data( "bwscid" );
    data = { 'action': 'oik-ajax-do-shortcode',
          'shortcode': shortcode,
          'post': post,
          'link': $link,
          'bwscid': bwscid };
    //alert( url  ) ;
    $.post( url, data );
    
    return( false ); 
  }

  $(document).ready( function($) {
    $('div.ajax-shortcode a.page-numbers').click( wahay ); 
  })
}) (jQuery);
        

