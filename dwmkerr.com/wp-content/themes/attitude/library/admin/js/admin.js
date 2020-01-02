/**
 * Admin javascript.
 *
 * Javascript for the theme options some effects.
 */
jQuery( document ).ready( function() {
	var selected = 0;
	if( jQuery.cookie( 'attitude_tab' ) ) {
		selected = jQuery.cookie( 'attitude_tab' );
		jQuery.cookie( 'attitude_tab', null );
	}
	
	var tabs = jQuery( '#attitude_tabs' ).tabs( { selected: selected } );
	
	jQuery( '#wpbody-content form' ).submit( function() {
		var selected = tabs.tabs( 'option', 'selected' );
		jQuery.cookie( 'attitude_tab', selected );
	} );
	
	jQuery( '.sortable' ).sortable( {
		handle: 'label',
		update: function( event, ui ) {
			var index = 1;
			var attrname = jQuery( this ).find( 'input:first' ).attr( 'name' );
			var attrbase = attrname.substring( 0, attrname.indexOf( '][' ) + 1 );
			
			jQuery( this ).find( 'tr' ).each( function() {
				jQuery( this ).find( '.count' ).html( index );
				jQuery( this ).find( 'input' ).attr( 'name', attrbase + '[' + index + ']' );
				index++;
			} );
		}
	} );
} );