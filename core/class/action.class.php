<?php
/**
 * Fonctions utilitaires pour les actions
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.0.0
 * @copyright 2015-2018 Eoxia
 * @package EO-Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( '\eoxia\the_before_callback' ) ) {
	/**
	 * Renvoies la sortie de l'attribut "wpeo-before-cb" avec les données reçu.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  string $namespace   Le namespace JS.
	 * @param  string $module      Le module JS.
	 * @param  string $method_name Le nom de la méthode.
	 *
	 * @return void
	 */
	function the_before_callback( $namespace, $module, $method_name ) {
		if ( empty( $namespace ) || empty( $module ) || empty( $method_name ) ) {
			return;
		}

		$namespace   = sanitize_text_field( $namespace );
		$module      = sanitize_text_field( $module );
		$method_name = sanitize_text_field( $method_name );

		echo 'wpeo-before-cb="' . esc_attr( $namespace ) . '/' . esc_attr( $module ) . '/' . esc_attr( $method_name ) . '"';
	}
}
