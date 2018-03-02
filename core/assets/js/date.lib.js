/**
 * Handle date
 *
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! window.eoxiaJS.date ) {

	window.eoxiaJS.date = {};

	window.eoxiaJS.date.init = function() {
		jQuery( document ).on ('click', '.group-date .date', function( e ) {
			var defaultDate = jQuery( this ).closest( '.group-date' ).find( '.mysql-date' ).val() ?  jQuery( this ).closest( '.group-date' ).find( '.mysql-date' ).val() : new Date();
			jQuery( this ).datetimepicker( {
				lang: 'fr',
				format: 'd/m/Y',
				mask: true,
				timepicker: false,
				startDate: defaultDate,
				closeOnDateSelect: true,
				onChangeDateTime : function(ct, $i) {
					$i.closest( '.group-date' ).find( '.mysql-date' ).val( ct.dateFormat('Y-m-d') );
				}
			} ).datetimepicker( 'show' );
		});
	};
}
