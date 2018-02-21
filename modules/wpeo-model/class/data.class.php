<?php
/**
 * Gestion de la construction des données selon les modèles.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.0.0
 * @copyright 2015-2018
 * @package EO_Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\Data_Class' ) ) {
	/**
	 * Gestion de la construction des données selon les modèles.
	 */
	class Data_Class extends Helper_Class {

		/**
		 * Types accepted in schema.
		 *
		 * @var array
		 */
		public static $accepted_types = array( 'string', 'integer', 'float', 'boolean', 'array', 'wpeo_date' );

		/**
		 * [private description]
		 * @var [type]
		 */
		private $wp_errors;

		/**
		 * [private description]
		 * @var [type]
		 */
		private $req_method;

		/**
		 * Appelle la méthode pour dispatcher les données.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param Array $data Les données non traité. Peut être null, permet de récupérer le schéma.
		 */
		public function __construct( $data = null, $req_method = null ) {
			$this->wp_errors  = new \WP_Error();
			$this->req_method = ( null !== $req_method ) ? strtoupper( $req_method ) : null;

			if ( null !== $data ) {
				$this->data = $this->handle_data( $data );

				if ( ! empty( $this->wp_errors->errors ) ) {
					echo wp_json_encode( $this->wp_errors );
					exit;
				}
			}
		}

		/**
		 * Dispatches les données selon le modèle.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param array $data           Toutes les données non traitée.
		 * @param array $schema         La définition des données.
		 *
		 * @return object               Les données traitées, typées et convertie en l'objet demandé.
		 */
		private function handle_data( $data, $schema = null ) {
			$object = null;
			$schema = ( null === $schema ) ? $this->schema : $schema;

			foreach ( $schema as $field_name => $field_def ) {
				// Définie les données  par défaut pour l'élément courant par rapport à "bydefault".
				$value = $this->set_default_data( $field_name, $field_def );

				// Si on est au premier niveau de $current_object, sinon si on est plus haut que le premier niveau.
				if ( isset( $field_def['field'] ) && isset( $data[ $field_def['field'] ] ) ) {
					$value = $data[ $field_def['field'] ];
				} elseif ( isset( $data[ $field_name ] ) && isset( $field_def ) && ! isset( $field_def['child'] ) ) {
					$value = $data[ $field_name ];
				}

				if ( null !== $this->req_method ) {
					$value = apply_filters( 'eo_model_handle_value', $value, $this, $field_def, $this->req_method );
				}

				// Traitement de $value au niveau du champ "required".
				if ( 'GET' !== $this->req_method && isset( $field_def['required'] ) && $field_def['required'] && null === $value ) {
					$this->wp_errors->add( 'eo_model_is_required', get_class( $this ) . ' => ' . $field_name . ' is required' );
				}

				// Force le typage de $value en requête mode "GET".
				if ( 'GET' === $this->req_method ) {
					$value = $this->handle_value_type( $value, $field_def );
				}

				// Vérifie le typage $value.
				if ( null !== $this->req_method ) {
					$this->check_value_type( $value, $field_name, $field_def );
				}

				// Pour remettre à jour la valeur dans l'objet.
				if ( null !== $value ) {
					$object[ $field_name ] = $value;
				}

				if ( 'GET' !== $this->req_method ) {
					if ( isset( $object[ $field_name ] ) && null === $value && isset( $field_def['required'] ) && $field_def['required'] ) {
						unset( $object[ $field_name ] );
					}
				}

				// Si la définition de la donnée ne contient pas "child".
				if ( isset( $field_def['child'] ) ) {
					// Values car c'est un tableau, nous sommes dans "child". Nous avons donc un tableau dans $data[ $field_name ].
					$values = ! empty( $data[ $field_name ] ) ? $data[ $field_name ] : array();

					// Récursivité sur les enfants de la définition courante.
					$object[ $field_name ] = $this->handle_data( $values, $field_def['child'] );
				}
			}

			return $object;
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
				if ( isset( $field_def['default'] ) ) {
					return $field_def['default'];
				}
			}

			return null;
		}

		/**
		 * @todo: A commenter
		 * @param  [type] $value      [description]
		 * @param  [type] $field_name [description]
		 * @param  [type] $field_def  [description]
		 * @return [type]             [description]
		 */
		public function check_value_type( $value, $field_name, $field_def ) {
			// Vérifie le type de $value.
			if ( null !== $value ) {
				if ( empty( $field_def['type'] ) ) {
					$this->wp_errors->add( 'eo_model_invalid_type', get_class( $this ) . ' => ' . $field_name . ': ' . $value . '(' . gettype( $value ) . ') no setted in schema. Type accepted: ' . join( ',', self::$accepted_types ) );
				} else {
					switch ( $field_def['type'] ) {
						case 'string':
							if ( ! is_string( $value ) ) {
								$this->wp_errors->add( 'eo_model_invalid_type', get_class( $this ) . ' => ' . $field_name . ': ' . $value . '(' . gettype( $value ) . ') is not a ' . $field_def['type'] );
							}
							break;
						case 'integer':
							if ( ! is_int( $value ) ) {
								$this->wp_errors->add( 'eo_model_invalid_type', get_class( $this ) . ' => ' . $field_name . ': ' . $value . '(' . gettype( $value ) . ') is not a ' . $field_def['type'] );
							}
							break;
						case 'boolean':
							if ( ! is_bool( $value ) ) {
								$this->wp_errors->add( 'eo_model_invalid_type', get_class( $this ) . ' => ' . $field_name . ': ' . $value . '(' . gettype( $value ) . ') is not a ' . $field_def['type'] );
							}
							break;
						case 'array':
							if ( ! is_array( $value ) ) {
								$rendered_value = is_object( $value ) ? 'Object item' : $value;

								$this->wp_errors->add( 'eo_model_invalid_type', get_class( $this ) . ' => ' . $field_name . ': ' . $rendered_value . '(' . gettype( $value ) . ') is not a ' . $field_def['type'] );
							}
							break;
						default:
							if ( ! in_array( $field_def['type'], self::$accepted_types, true ) ) {
								$this->wp_errors->add( 'eo_model_invalid_type', get_class( $this ) . ' => ' . $field_name . ': ' . $value . '(' . gettype( $value ) . ') incorrect type: "' . $field_def['type'] . '". Type accepted: ' . join( ',', self::$accepted_types ) );
							}
							break;
					}
				}
			}
		}

		/**
		 * Forces le typage des données.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param mixed $value     La valeur courante.
		 * @param array $field_def La définition du champ.
		 *
		 * @return mixed           L'objet avec le typage forcé.
		 */
		public function handle_value_type( $value, $field_def ) {
			if ( null === $value ) {
				settype( $value, $field_def['type'] );
				return $value;
			}

			// On construit l'objet "wpeo_date" uniquement dans le GET.
			if ( 'wpeo_date' === $field_def['type'] ) {
				return $value;
			}

			if ( ! is_array( $value ) && ! is_object( $value ) && 'float' === $field_def['type'] ) {
				$value = str_replace( ',', '.', $value );
			}

			// @see Schema_Class::$accepted_types
			settype( $value, $field_def['type'] );

			if ( 'GET' === $this->req_method && 'string' === $field_def['type'] ) {
				$value = stripslashes( $value );
			}

			// On force le typage des enfants uniquement si array_type est définie.
			if ( ! empty( $field_def['array_type'] ) && is_array( $value ) && ! empty( $value ) ) {
				foreach ( $value as $key => $val ) {
					// @see Schema_Class::$accepted_types
					settype( $value[ $key ], $field_def['array_type'] );
				}
			}

			return $value;
		}

		/**
		 * Convertis le modèle en un tableau compatible WordPress.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param array $datas  Toutes les données à convertir au format WordPress selon $schema.
		 * @param array $schema Le schéma des données à convertir.
		 *
		 * @return array Tableau compatible avec les fonctions WordPress.
		 */
		public function convert_to_wordpress() {
			$data = array();

			foreach ( $this->schema as $field_name => $field_def ) {

				if ( ! empty( $field_def['field'] ) ) {
					if ( isset( $this->$field_name ) ) {
						$value = $this->$field_name;
						if ( 'wpeo_date' !== $field_def['type'] ) {
							$data[ $field_def['field'] ] = $value;
						} else {
							if ( isset( $value['raw'] ) ) {
								$data[ $field_def['field'] ] = $value['raw'];
							}
						}
					}
				}
			}

			return $data;
		}

	}

} // End if().
