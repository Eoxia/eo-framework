<?php
/**
 * Helpers principales
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.0.0
 * @copyright 2015-2018
 * @package EO_Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'eoxia\fill_date' ) ) {
	/**
	 * Remplis les champs de type 'wpeo_date'.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  string $current_time Le date envoyé par l'objet.
	 * @return array {
	 *         Les propriétés
	 *
	 *         @type array data_input {
	 *               Les propriétés de date_input
	 *
	 *               @type string date La date au format MySQL
	 *               @type array  fr_FR {
	 *                     Les propriétés de fr_FR
	 *
	 *                     @type string date      La date au format d/m/Y
	 *                     @type string date_time La date au format d/m/Y H:i:s
	 *               }
	 *               @type array  en_US {
	 *                     Les propriétés de en_US
	 *
	 *                     @type string date      La date au format m-d-y
	 *                     @type string date_time La date au format m-d-y H:i:s
	 *               }
	 *               @type string date_human_readable La date au format lisible.
	 *         }
	 * }
	 */
	function fill_date( $current_time ) {
		$data = array();

		$locale = get_locale();
		$date   = new \DateTime( $current_time );

		$data['date_input']['date']    = $current_time;
		$data['date_input']['iso8601'] = mysql2date( 'Y-m-d\TH:i:s\Z', $current_time );

		$data['date_input']['fr_FR']['date']      = mysql2date( 'd/m/Y', $current_time );
		$data['date_input']['fr_FR']['date_time'] = mysql2date( 'd/m/Y H:i:s', $current_time );
		$data['date_input']['fr_FR']['time']      = mysql2date( 'H:i:s', $current_time );

		$data['date_input']['en_US']['date']      = mysql2date( 'm-d-y', $current_time );
		$data['date_input']['en_US']['date_time'] = mysql2date( 'm-d-y H:i:s', $current_time );
		$data['date_input']['en_US']['time']      = mysql2date( 'H:i:s', $current_time );

		$data['mysql']   = $current_time;
		$data['iso8601'] = mysql_to_rfc3339( $current_time );

		$formatter    = new \IntlDateFormatter( $locale, \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE );
		$data['date'] = $formatter->format( $date );

		$formatter         = new \IntlDateFormatter( $locale, \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT );
		$data['date_time'] = $formatter->format( $date );

		$formatter    = new \IntlDateFormatter( $locale, \IntlDateFormatter::NONE, \IntlDateFormatter::SHORT );
		$data['time'] = $formatter->format( $date );

		$formatter                   = new \IntlDateFormatter( $locale, \IntlDateFormatter::FULL, \IntlDateFormatter::SHORT );
		$data['date_human_readable'] = \ucwords( $formatter->format( $date ) );

		return apply_filters( 'eo_model_fill_date', $data );
	}
}
