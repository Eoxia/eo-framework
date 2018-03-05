/**
 * Gestion du dropdown.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! window.eoxiaJS.dropdown  ) {
	window.eoxiaJS.dropdown = {};

	window.eoxiaJS.dropdown.init = function() {
		window.eoxiaJS.dropdown.event();
	};

	window.eoxiaJS.dropdown.event = function() {
		jQuery( document ).on( 'keyup', window.eoxiaJS.dropdown.keyup );
		jQuery( document ).on( 'click', '.wpeo-dropdown .dropdown-toggle:not(.disabled)', window.eoxiaJS.dropdown.open );
		jQuery( document ).on( 'click', 'body', window.eoxiaJS.dropdown.close );
	};

	window.eoxiaJS.dropdown.keyup = function( event ) {
		if ( 27 === event.keyCode ) {
			window.eoxiaJS.dropdown.close();
		}
	};

	window.eoxiaJS.dropdown.open = function( event ) {
		window.eoxiaJS.dropdown.close();

		var triggeredElement = jQuery( this );
		triggeredElement.closest( '.wpeo-dropdown' ).toggleClass( 'dropdown-active' );

		/* Toggle Button Icon */
		var angleElement = triggeredElement.find('[data-fa-i2svg]');
		if ( angleElement ) {
			window.eoxiaJS.dropdown.toggleAngleClass( angleElement );
		}

		event.stopPropagation();
	};

	window.eoxiaJS.dropdown.close = function( event ) {
		jQuery( '.wpeo-dropdown.dropdown-active:not(.no-close)' ).each( function() {
			var toggle = jQuery( this );
			toggle.removeClass( 'dropdown-active' );

			/* Toggle Button Icon */
			var angleElement = jQuery( this ).find('.dropdown-toggle').find('[data-fa-i2svg]');
			if ( angleElement ) {
				window.eoxiaJS.dropdown.toggleAngleClass( angleElement );
			}
		});
	};

	window.eoxiaJS.dropdown.toggleAngleClass = function( button ) {
		if ( button.hasClass('fa-caret-down') || button.hasClass('fa-caret-up') ) {
			button.toggleClass('fa-caret-down').toggleClass('fa-caret-up');
		}
		else if ( button.hasClass('fa-caret-circle-down') || button.hasClass('fa-caret-circle-up') ) {
			button.toggleClass('fa-caret-circle-down').toggleClass('fa-caret-circle-up');
		}
		else if ( button.hasClass('fa-angle-down') || button.hasClass('fa-angle-up') ) {
			button.toggleClass('fa-angle-down').toggleClass('fa-angle-up');
		}
		else if ( button.hasClass('fa-chevron-circle-down') || button.hasClass('fa-chevron-circle-up') ) {
			button.toggleClass('fa-chevron-circle-down').toggleClass('fa-chevron-circle-up');
		}
	}
}
