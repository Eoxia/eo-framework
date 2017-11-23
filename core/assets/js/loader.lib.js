if ( ! window.eoxiaJS.loader ) {
	window.eoxiaJS.loader = {};

	window.eoxiaJS.loader.init = function() {
		window.eoxiaJS.loader.event();
	};

	window.eoxiaJS.loader.event = function() {
	};

	window.eoxiaJS.loader.display = function( element ) {
		element.addClass( 'wpeo-loader' );
	};

	window.eoxiaJS.loader.remove = function( element ) {
		if ( 0 < element.length ) {
			element.removeClass( 'wpeo-loader' );
		}
	};
}
