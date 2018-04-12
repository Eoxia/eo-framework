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
			var format = 'd/m/Y';
			var timepicker = false;

			if ( jQuery( this ).closest( '.group-date' ).data( 'time' ) ) {
				format += ' H:i:s';
				timepicker = true;
			}

			jQuery( this ).datetimepicker( {
				lang: 'fr',
				format: format,
				mask: true,
				timepicker: timepicker,
				closeOnDateSelect: true,
				onChangeDateTime : function(ct, $i) {
					if ( $i.closest( '.group-date' ).data( 'time' ) ) {
						$i.closest( '.group-date' ).find( '.mysql-date' ).val( ct.dateFormat('Y-m-d H:i:s') );
					} else {
						$i.closest( '.group-date' ).find( '.mysql-date' ).val( ct.dateFormat('Y-m-d') );
					}

					if ( $i.closest( '.group-date' ).attr( 'data-namespace' ) && $i.closest( '.group-date' ).attr( 'data-module' ) && $i.closest( '.group-date' ).attr( 'data-after-method' ) ) {
						window.eoxiaJS[$i.closest( '.group-date' ).attr( 'data-namespace' )][$i.closest( '.group-date' ).attr( 'data-module' )][$i.closest( '.group-date' ).attr( 'data-after-method' )]( $i );
					}
				}
			} ).datetimepicker( 'show' );
		});
	};

	window.eoxiaJS.date.convertMySQLDate = function( date, time = true ) {
		if ( ! time ) {
			date += ' 00:00:00';
		}
		var timestamp = new Date(date.replace(' ', 'T')).getTime();
		var d = new Date( timestamp );

		var day = d.getDate();
		if ( 1 === day.toString().length ) {
			day = '0' + day.toString();
		}

		var month = d.getMonth() + 1;
		if ( 1 === month.toString().length ) {
			month = '0' + month.toString();
		}

		if ( time ) {
			var hours = d.getHours();
			if ( 1 === hours.toString().length ) {
				hours = '0' + hours.toString();
			}

			var minutes = d.getMinutes();
			if ( 1 === minutes.toString().length ) {
				minutes = '0' + minutes.toString();
			}

			var seconds = d.getSeconds();
			if ( 1 === seconds.toString().length ) {
				seconds = '0' + seconds.toString();
			}

			return day + '/' + month + '/' + d.getFullYear() + ' ' + hours + ':' + minutes + ':' + seconds;
		} else {
			return day + '/' + month + '/' + d.getFullYear();
		}
	};
}
