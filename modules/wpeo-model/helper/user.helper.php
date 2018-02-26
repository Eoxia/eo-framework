<?php
/**
 * Les fonctions helpers des modèles
 *
 * @package Task manager\Plugin
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'eoxia\build_user_initial' ) ) {
	/**
	 * Construit les initiales des utilisateurs
	 *
	 * @since 0.1.0
	 * @version 1.0.0
	 *
	 * @param  User_class $user Les données de l'utilisateur.
	 * @return User_class       Les données de l'utilisateur avec les intiales
	 */
	function build_user_initial( $user ) {
		$initial = '';

		if ( ! empty( $user['data']['firstname'] ) ) {
			$initial .= substr( $user['data']['firstname'], 0, 1 );
		}
		if ( ! empty( $user['data']['lastname'] ) ) {
			$initial .= substr( $user['data']['lastname'], 0, 1 );
		}
		if ( empty( $initial ) ) {
			if ( ! empty( $user['data']['login'] ) ) {
				$initial .= substr( $user['data']['login'], 0, 1 );
			}
		}

		$user->data['initial'] = $initial;

		return $user;
	}
}

if ( ! function_exists( 'eoxia\build_avatar_color' ) ) {
	/**
	 * Construit la couleur de fond de l'avatar.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  Array $user Les données de l'utilisateur.
	 *
	 * @return Array       Les données de l'utilisateur avec la couleur de fond de l'avatar.
	 */
	function build_avatar_color( $user ) {
		$avatar_color = array(
			'e9ad4f',
			'50a1ed',
			'e05353',
			'e454a2',
			'47e58e',
			'734fe9',
		);

		$user['data']['avatar_color'] = $avatar_color[ array_rand( $avatar_color, 1 ) ];

		return $user;
	}
}
