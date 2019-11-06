<?php
/**
 * Filters for Custom Menu.
 *
 * @author Eoxia <dev@eoxia>
 * @since 1.0.0
 * @version 1.0.0
 * @copyright 2016-2019 Eoxia
 * @package EO_Framework\WPEO_Custom_Menu\Action
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\Custom_Menu_Filter' ) ) {
	/**
	 * Filters for wpeo_custom_menu.
	 */
	class Custom_Menu_Filter {

		/**
		 * Declare Filters.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_filter( 'admin_body_class', array( $this, 'custom_body_class' ) );
		}

		public function custom_body_class( $classes ) {
			$page = ( ! empty( $_REQUEST['page'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : ''; // WPCS: input var ok, CSRF ok.
			if ( in_array( $page, \eoxia\Config_Util::$init['eo-framework']->wpeo_custom_menu->inserts_page, true ) ) {
				$classes .= " eo-custom-page ";
			}

			return $classes;
		}
	}

	new Custom_Menu_Filter();
}
