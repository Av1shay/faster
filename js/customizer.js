/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

    const api = wp.customize, primary = $('#primary'),
        secondary = $('#secondary'), siteInfo = $('#footer-site-info');

	// Site title and description.
    api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
    api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
    api( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	// Theme options
    api('faster_show_sidebar', function (val) {
        val.bind(function (checked) {
            if ( ! checked ) {
                secondary.addClass('d-none');
                primary.css({'flex': '0 0 100%', 'max-width': '100%'});
            } else {
                if ( secondary.hasClass('d-none') ) {
                    secondary.removeClass('d-none');
                    primary.css({'flex': '0 0 66.66667%', 'max-width': '66.66667%'});
                }
            }
        });
    });

    api('faster_show_nav_search', function (val) {
        val.bind(function (checked) {
            $('#primary-menu-search').toggleClass('d-none', ! checked);
        });
    });

    api('faster_footer_content', function (val) {
        val.bind(function (content) {
            siteInfo.html(content);
        });
    });

    api('faster_footer_bg_color', function (val) {
        val.bind(function (color) {
            siteInfo.css('background-color', color);
        });
    });

} )(jQuery);