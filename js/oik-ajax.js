
/** (C) Copyright Bobbing Wide 2016
 *
 * jQuery to initiate AJAX requests to perform pagination
 */
// closure to avoid namespace collision
(function($) {       

  $(document).ready( function($) {
    $('div.ajax-shortcode a.page-numbers').click( function() { alert( "wahay" ) ; return( false ); } ); 
  })
}) (jQuery);
        

