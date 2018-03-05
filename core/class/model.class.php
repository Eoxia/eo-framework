<?php
/**
 * Méthodes utiles pour WPEO_Model.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.0.0
 * @copyright 2015-2017 Eoxia
 * @package WPEO_Util
 * @todo: Est-ce utilisé ?
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\Model_Util' ) ) {
	/**
	 * Méthodes utiles pour WPEO_Model.
	 */
	class Model_Util extends \eoxia\Singleton_Util {
		public static $namespace = '';
		/**
		 * Le constructeur obligatoirement pour utiliser la classe \eoxia\Singleton_Util
		 *
		 * @return void nothing
		 */
		protected function construct() {}

		public function set_namespace( $namespace ) {
			self::$namespace = $namespace;
		}

		public function get_namespace() {
			return self::$namespace . '\\';
		}

		/**
		 * [exec_callback description]
		 * @param  [type] $data      [description]
		 * @param  [type] $functions [description]
		 * @return [type]            [description]
		 */
		public static function exec_callback( $functions, $data, $args = array() ) {
			if ( ! empty( $functions ) ) {
				foreach ( $functions as $function ) {
					$data = call_user_func( $function, $data, $args );
				}
			}

			return $data;
		}
	}
}
