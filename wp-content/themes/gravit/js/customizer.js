/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'color': to,
					'position': 'relative'
				} );
			}
		} );
	} );

	wp.customize( 'remove_space', function( value ) {
		value.bind( function( to ) {
			if ( true === to) {		
				$( 'article:first-child' ).css( {					
					'margin-top': '20px'
				} );
			} else {
				$( 'article:first-child' ).css( {					
					'margin-top': '100px'
				} );
			}
		} );
	} );

	wp.customize( 'show_icons', function( value ) {
		value.bind( function( to ) {
			if ( false === to) {		
				$( '.post-symbol' ).css( {					
					'display': 'none'
				} );
			} else {
				$( '.post-symbol' ).css( {					
					'display': 'block'
				} );
			}
		} );
	} );

} )( jQuery );
