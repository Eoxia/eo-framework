/**
 * @namespace EO_Framework_Actions
 *
 * @author Eoxia <dev@eoxia.com>
 * @copyright 2015-2018 Eoxia
 *
 * @since 0.1.0
 * @version 1.0.0
 * @license GPLv3
 *
 * @description Gestion des actions XHR principaux
 *
 * * action-input: Déclenches une requête XHR avec les balises inputs contenu dans le contenaire parent.
 * * action-attribute: Déclenches une requête XHR avec les attributs de l'élément déclencheur.
 * * action-delete: Déclenches une requête XHR avec les attributs de l'élément déclencheur si l'utilisateur confirme la popin "confirm" du navigateur.
 */

if ( ! window.eoxiaJS.action ) {
	/**
	 * @summary L'objet principal "Action" ajouté à l'objet eoxiaJS afin de permêttre au fichier init.js de booter la méthode "init" de cet objet.
	 *
	 * @memberof EO_Framework_Actions
	 *
	 * @since 0.1.0
	 * @version 1.0.0
	 *
	 * @type {Object}
	 */
	window.eoxiaJS.action = {};

	/**
	 * @summary Méthode obligatoire pour le boot de l'objet "Action". Cette méthode est appelée automatiquement par init.js.
	 *
	 * Cette méthode appelle la méthode "event" de l'objet "Action".
	 *
	 * @since 0.1.0
	 * @version 1.0.0
	 *
	 * @memberof EO_Framework_Actions
	 *
	 * @returns {void}
	 */
	window.eoxiaJS.action.init = function() {
		window.eoxiaJS.action.event();
	};

	/**
	 * @summary Méthode "event" définie les 3 events principaux des actions de EO Framework: "action-input", "action-attribute" et "action-delete".
	 *
	 * Ses trois évènements sont déclenchés au clic gauche de la souris.
	 *
	 * La classe "no-action" permet d'annuler l'évènement.
	 *
	 * @since 0.1.0
	 * @version 1.0.0
	 *
	 * @memberof EO_Framework_Actions
	 *
	 * @returns {void}
	 */
	window.eoxiaJS.action.event = function() {
		jQuery( document ).on( 'click', '.action-input:not(.no-action)', window.eoxiaJS.action.execInput );
		jQuery( document ).on( 'click', '.action-attribute:not(.no-action)', window.eoxiaJS.action.execAttribute );
		jQuery( document ).on( 'click', '.action-delete:not(.no-action)', window.eoxiaJS.action.execDelete );
	};

	/**
	 * @summary Permet de lancer une requête avec les valeurs des <inputs> trouvé dans le contenaire défini par l'attribut "data-parent".
	 *
	 * @since 0.1.0
	 * @version 1.0.0
	 *
	 * @example
	 *
	 * <div class="my-form">
	 * 	<input type="hidden" name="action" value="create_post" />
	 * 	<input type="text" name="title" />
	 * 	<button class="action-input" data-parent="my-form">Envoyé</button>
	 * </div>
	 *
	 * @param  {MouseEvent} event Properties of element triggered by the MouseEvent.
	 *
	 * @memberof EO_Framework_Actions
	 *
	 * @returns {void}
	 */
	window.eoxiaJS.action.execInput = function( event ) {
		var element = jQuery( this ), parentElement = element, listInput = undefined, data = {}, i = 0, doAction = true, key = undefined, inputAlreadyIn = [];
		event.preventDefault();

		if ( element.attr( 'data-parent' ) ) {
			parentElement = element.closest( '.' + element.attr( 'data-parent' ) );
		}

		/** Méthode appelée avant l'action */
		if ( element.attr( 'data-module' ) && element.attr( 'data-before-method' ) ) {
			doAction = false;
			doAction = window.eoxiaJS[element.attr( 'data-namespace' )][element.attr( 'data-module' )][element.attr( 'data-before-method' )]( element );
		} else {
			if ( ! doAction ) {
				doAction = window.eoxiaJS.action.checkBeforeCB(element);
			}
		}

		if ( doAction ) {
			window.eoxiaJS.loader.display( element );

			listInput = window.eoxiaJS.arrayForm.getInput( parentElement );
			for ( i = 0; i < listInput.length; i++ ) {
				if ( listInput[i].name && -1 === inputAlreadyIn.indexOf( listInput[i].name ) ) {
					inputAlreadyIn.push( listInput[i].name );
					data[listInput[i].name] = window.eoxiaJS.arrayForm.getInputValue( listInput[i] );
				}
			}

			element.get_data( function( attrData ) {
				for ( key in attrData ) {
					data[key] = attrData[key];
				}

				window.eoxiaJS.request.send( element, data );
			} );
		}
	};

	/**
	 * Make a request with data on HTML element clicked.
	 *
	 * @since 0.1.0
	 * @version 1.0.0
	 *
	 * @param  {MouseEvent} event Properties of element triggered by the MouseEvent.
	 *
	 * @memberof EO_Framework_Actions
	 *
	 * @returns {void}
	 */
	window.eoxiaJS.action.execAttribute = function( event ) {
	  var element = jQuery( this );
		var doAction = true;

		event.preventDefault();

		/** Méthode appelée avant l'action */
		if ( element.attr( 'data-module' ) && element.attr( 'data-before-method' ) ) {
			doAction = false;
			doAction = window.eoxiaJS[element.attr( 'data-namespace' )][element.attr( 'data-module' )][element.attr( 'data-before-method' )]( element );
		}

		if ( element.hasClass( '.grey' ) ) {
			doAction = false;
		}

		if ( doAction ) {
			if ( jQuery( this ).attr( 'data-confirm' ) ) {
				if ( window.confirm( jQuery( this ).attr( 'data-confirm' ) ) ) {
					element.get_data( function( data ) {
						window.eoxiaJS.loader.display( element );
						window.eoxiaJS.request.send( element, data );
					} );
				}
			} else {
				element.get_data( function( data ) {
					window.eoxiaJS.loader.display( element );
					window.eoxiaJS.request.send( element, data );
				} );
			}
		}

		event.stopPropagation();
	};

	/**
	 * Make a request with data on HTML element clicked with a custom delete message.
	 *
	 * @memberof EO_Framework_Actions
	 *
	 * @since 0.1.0
	 * @version 1.0.0
	 *
	 * @param  {MouseEvent} event Properties of element triggered by the MouseEvent.
	 *
	 * @returns {void}
	 */
	window.eoxiaJS.action.execDelete = function( event ) {
		var element = jQuery( this );
		var doAction = true;

		event.preventDefault();

		/** Méthode appelée avant l'action */
		if ( element.attr( 'data-namespace' ) && element.attr( 'data-module' ) && element.attr( 'data-before-method' ) ) {
			doAction = false;
			doAction = window.eoxiaJS[element.attr( 'data-namespace' )][element.attr( 'data-module' )][element.attr( 'data-before-method' )]( element );
		}

		if ( element.hasClass( '.grey' ) ) {
			doAction = false;
		}

		if ( doAction ) {
			if ( window.confirm( element.attr( 'data-message-delete' ) ) ) {
				element.get_data( function( data ) {
					window.eoxiaJS.loader.display( element );
					window.eoxiaJS.request.send( element, data );
				} );
			}
		}
	};

	/**
	 * Si une méthode de callback existe avant l'action, cette méthode l'appel.
	 *
	 * @memberof EO_Framework_Actions
	 *
	 * @since 0.1.0
	 * @version 1.0.0
	 *
	 * @param  {Object} element L'élément déclencheur.
	 *
	 * @returns {bool}           True si l'action peut être envoyé, sinon False.
	 */
	window.eoxiaJS.action.checkBeforeCB = function( element ) {
		var beforeMethod = element.attr( 'wpeo-before-cb' );

		if ( ! beforeMethod ) {
			return true;
		}

		beforeMethod = beforeMethod.split( '/' );

		if ( ! beforeMethod[0] || ! beforeMethod[1] || ! beforeMethod[2] ) {
			return true;
		}

		return window.eoxiaJS[beforeMethod[0]][beforeMethod[1]][beforeMethod[2]]( element );
	}
}
