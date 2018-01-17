<?php
/**
 * Gestion de la construction des données selon les modèles.
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

if ( ! class_exists( '\eoxia\Constructor_Data_Class' ) ) {
	/**
	 * Gestion de la construction des données selon les modèles.
	 */
	class Constructor_Data_Class extends Helper_Class {

		/**
		 * Appelle la méthode pour dispatcher les données.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param Array $data Les données non traité.
		 */
		public function __construct( $data ) {
			$this->handle_data( $data );
		}

		/**
		 * Dispatches les données selon le modèle.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param array  $data           Toutes les données non traitée.
		 * @param array  $current_data   Les données actuelles.
		 * @param object $current_object L'objet en cours de construction.
		 * @param array  $model          La définition des données.
		 *
		 * @return object                Les données traitées, typées et convertie en l'objet demandé.
		 */
		private function handle_data( $data, $current_data = null, $current_object = null, $model = null ) {
			$current_data   = ( null === $current_data ) ? $data : $current_data;
			$current_object = ( null === $current_object ) ? $this : $current_object;
			$model          = ( null === $model ) ? $this->model : $model;

			foreach ( $model as $field_name => $field_def ) {
				// Définie les données  par défaut pour l'élément courant par rapport à "bydefault".
				$value = $this->set_default_data( $field_name, $field_def );

				// Si la définition de la donnée ne contient pas "child".
				if ( ! isset( $field_def['child'] ) ) {

					// Si on est au premier niveau de $current_object, sinon si on est plus haut que le premier niveau.
					if ( isset( $field_def['field'] ) && isset( $current_data[ $field_def['field'] ] ) ) {
						$value = $current_data[ $field_def['field'] ];
					} elseif ( isset( $current_data[ $field_name ] ) && isset( $field_def ) && ! isset( $field_def['child'] ) ) {
						$value = $current_data[ $field_name ];
					}

					// Enregistres la valeur soit dans un objet, ou alors dans un tableau.
					if ( is_object( $current_object ) ) {
						$current_object->$field_name = $value;
					} else {
						$current_object[ $field_name ] = $value;
					}
				} else {
					// Values car c'est un tableau, nous sommes dans "child". Nous avons donc un tableau dans $data[ $field_name ].
					$values = ! empty( $data[ $field_name ] ) ? $data[ $field_name ] : array();

					if ( empty( $current_object->$field_name ) ) {
						$current_object->$field_name = new \stdClass();
					}

					// Récursivité sur les enfants de la définition courante.
					$current_object->$field_name = $this->handle_data( $data, $values, $current_object->$field_name, $field_def['child'] );
				}

				// Force le typage.
				$current_object = $this->handle_type( $current_object, $field_def, $field_name );
			}

			return $current_object;
		}

		/**
		 * Si la définition bydefault existe, récupères la valeur.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param string $field_name Le nom du champ.
		 * @param array  $field_def  La définition du champ.
		 *
		 * @return mixed             La donnée par défaut.
		 */
		private function set_default_data( $field_name, $field_def ) {
			if ( 'wpeo_date' === $field_def['type'] ) {
				return current_time( 'mysql' );
			} else {
				if ( isset( $field_def['bydefault'] ) ) {
					return $field_def['bydefault'];
				}
			}
		}

		/**
		 * Convertis le modèle en un tableau compatible WordPress.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @return array Tableau compatible avec les fonctions WordPress.
		 */
		public function do_wp_object() {
			$data = array();

			foreach ( $this->model as $field_name => $field_def ) {

				// Le champ est de type "wpeo_date", on le repasse au format mysql pour l'enregistrement.
				if ( 'wpeo_date' === $field_def['type'] && ! empty( $field_def['field'] ) ) {
					$data[ $field_def['field'] ] = $this->{$field_name}['date_input']['date'];
					$this->{$field_name}         = $this->{$field_name}['date_input']['date'];
				}
			}

			foreach ( $this->model as $field_name => $field_def ) {

				if ( ! empty( $field_def['field'] ) ) {
					if ( 'wpeo_date' !== $field_def['type'] ) {
						if ( isset( $this->$field_name ) ) {
							$data[ $field_def['field'] ] = $this->$field_name;
						}
					}
				}
			}
			return $data;
		}

		/**
		 * Forces le typage des données.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param  Object $current_object L'objet courant.
		 * @param  array  $field_def      La définition du champ.
		 * @param  string $field_name     Le nom du champ.
		 * @return Object                 L'objet avec le typage forcé.
		 */
		public function handle_type( $current_object, $field_def, $field_name ) {
			$data = is_object( $current_object ) ? $current_object->$field_name : $current_object[ $field_name ];
			if ( ! empty( $field_def['type'] ) ) {
				if ( 'wpeo_date' !== $field_def['type'] ) {
					if ( ! is_array( $data ) && ! is_object( $data ) && 'float' === $field_def['type'] ) {
						$data = str_replace( ',', '.', $data );
					}
					settype( $data, $field_def['type'] );

					if ( ! empty( $field_def['array_type'] ) ) {
						if ( ! empty( $data ) ) {
							foreach ( $data as &$element ) {
								settype( $element, $field_def['array_type'] );
							}
						}
					}
				} else {

					// Si c'est un type "wpeo_date".
					$current_time = ! empty( $current_object->{$field_name}['date_input'] ) && ! empty( $current_object->{$field_name}['date_input']['date'] ) ? $current_object->{$field_name}['date_input']['date'] : $current_object->$field_name;
					$data         = $this->fill_date( $current_time );
				}
			}

			if ( is_object( $current_object ) ) {
				$current_object->$field_name = $data;
			} else {
				$current_object[ $field_name ] = $data;
			}

			return $current_object;
		}

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
		public function fill_date( $current_time ) {
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

			return apply_filters( 'eoframework_fill_date', $data );
		}

	}
} // End if().
