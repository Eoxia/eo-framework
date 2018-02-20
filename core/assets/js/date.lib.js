/**
 * Handle date
 *
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! window.eoxiaJS.date ) {

	window.eoxiaJS.date = {};

	window.eoxiaJS.date.init = function() {
		jQuery( '.group-date .date' ).datetimepicker( {
			lang: 'fr',
			format: 'd/m/Y',
			mask: true,
			timepicker: false,
			startDate: new Date(),
			onChangeDateTime : function(ct, $i) {
				$i.closest( '.group-date' ).find( '.mysql-date' ).val( ct.dateFormat('Y-m-d') );
			}
		} );
	};
}
