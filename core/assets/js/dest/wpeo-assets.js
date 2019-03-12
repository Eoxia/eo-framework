/**
 * Initialise l'objet principale de WPshop.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop = {};
window.eoxiaJS.wpshopFrontend = {};

window.eoxiaJS.wpshop.core = {}; // Déclaration de mon objet JS qui vas contenir toutes les functions


window.eoxiaJS.wpshop.core.init = function (){
};

/* ---------------- Affichage produit ciblé  --------------- */
/**
 * [Affichage produit ciblé ]
 * @param  {[type]} element  [description]
 * @param  {[type]} response [description]
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.core.product_focus = function( element, response ) {
    jQuery( '#product_focus' ).html( response.data.view );
};


/**
 * [Modifie l'affichage -> Ajoute un nouveau produit]
 * @param  {[type]} element  [description]
 * @param  {[type]} response [description]
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.core.show_popup = function( element, response ) {
    jQuery( '#success_add_product' ).css( 'display', 'block' );
	jQuery( '#table_listproduct' ).html( response.data.view );
};

/**
 * [Modifie l'affichage -> Création client]
 * @param  {[type]} element  [description]
 * @param  {[type]} response [description]
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.core.add_customer = function( element, response ){
	jQuery( '#text_information' ).html ( 'Etape 2 - Ajouter des produits ');
	jQuery( '.div_add_customer' ).css ( 'display', 'none' );
	jQuery( '.div_add_product' ).css ( 'display', 'block' );
	jQuery( '.div_add_product' ).html( response.data.view );
}

window.eoxiaJS.wpshop.core.update_panier = function( element, response ){
	jQuery( '#panier' ).html( response.data.view );
}


window.eoxiaJS.wpshop.core.modify_quantity = function( element, response ){
	jQuery( '#panier' ).html( response.data.view );
}

/**
 * [Modifie l'affichage -> Création de l'invoice]
 * @param  {[type]} element  [description]
 * @param  {[type]} response [description]
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.core.choose_product = function( element, response ){
	jQuery( '#text_information' ).html ( 'Etape 3 - Liste des produits !');
	jQuery( '.div_add_product' ).css ( 'display', 'none' );
	jQuery( '#div_finish' ).css ( 'display', 'block' );
	jQuery( '#div_finish' ).html ( response.data.view );
}

window.eoxiaJS.wpshop.core.passer_a_l_achat = function( element, response ){
	jQuery( '#achat_panier' ).html( response.data.view );
	jQuery( '#div_finish' ).css ( 'display', 'none' );

}

/**
 * [Modifie l'affiche -> Création du pdf]
 * @param  {[type]} element  [description]
 * @param  {[type]} response [description]
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.core.create_pdf = function( element, response ){
	jQuery( '#text_information' ).html ( 'Etape 4 - PDF généré !');
	jQuery( '#div_finish' ).css ( 'display', 'none' );
	jQuery( '#div_downloadpdf' ).css ( 'display', 'block' );
	jQuery( '#div_downloadpdf' ).html ( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties = {}; // Déclaration de mon objet JS qui vas contenir toutes les functions


window.eoxiaJS.wpshop.thirdParties.init = function () {
	window.eoxiaJS.wpshop.thirdParties.event();
};

window.eoxiaJS.wpshop.thirdParties.event = function () {
	jQuery( document ).on( 'click', '#wps-third-party-contacts .add-contact', window.eoxiaJS.wpshop.thirdParties.toggleContactFormNew );
	jQuery( document ).on( 'click', '.wpeo-autocomplete.search-contact .autocomplete-search-list .autocomplete-result', window.eoxiaJS.wpshop.thirdParties.putContactID );
};

window.eoxiaJS.wpshop.thirdParties.toggleContactFormNew = function() {
	jQuery( '#wps-third-party-contacts .row.new' ).toggle();
}


window.eoxiaJS.wpshop.thirdParties.putContactID = function() {
	jQuery( this ).closest( '.wpeo-autocomplete' ).find( '.button-associate-contact' ).attr( 'data-contact-id', jQuery( this ).data( 'id' ) );
	jQuery( this ).closest( '.wpeo-autocomplete' ).find( 'input#search-contact' ).val( jQuery( this ).data( 'result' ) );
};

window.eoxiaJS.wpshop.thirdParties.loaddedTitleEdit = function ( triggeredElement, response ) {
	triggeredElement.closest( 'h2' ).html( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties.savedThird = function ( triggeredElement, response ) {
	triggeredElement.closest( 'h2' ).html( response.data.view );
}


window.eoxiaJS.wpshop.thirdParties.loaddedBillingAddressSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties.savedBillingAddressSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties.loaddedContactSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( 'tr' ).replaceWith( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties.savedContact = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties.associatedContactSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
}

/**
 * Initialise l'objet "wpshop" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.wpshop.doliSynchro = {};
window.eoxiaJS.wpshop.doliSynchro.completed = false;

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.wpshop.doliSynchro.init = function() {
	jQuery( document ).on( 'keyup', '.synchro-single .filter-entry', window.eoxiaJS.wpshop.doliSynchro.filter );
	jQuery( document ).on( 'click', '.synchro-single li', window.eoxiaJS.wpshop.doliSynchro.clickEntry );

	jQuery( document ).on( 'modal-opened', '.modal-sync', function() {
		if ( 0 < jQuery( '.waiting-item' ).length ) {
			window.eoxiaJS.wpshop.doliSynchro.declareUpdateForm();
			window.eoxiaJS.wpshop.doliSynchro.requestUpdate();
			window.addEventListener( 'beforeunload', window.eoxiaJS.wpshop.doliSynchro.safeExit );
		}
	});
};

window.eoxiaJS.wpshop.doliSynchro.filter = function( event ) {
	var entries = jQuery( '.synchro-single ul.select li' );
	entries.show();

	var val = jQuery( this ).val().toLowerCase();

	for ( var i = 0; i < entries.length; i++ ) {
		if ( jQuery( entries[i] ).text().toLowerCase().indexOf( val ) == -1 ) {
			jQuery( entries[i] ).hide();
		}
	}
};

window.eoxiaJS.wpshop.doliSynchro.clickEntry = function( event ) {
	jQuery( '.synchro-single li.active' ).removeClass( 'active' );
	jQuery( this ).addClass( 'active' );
	jQuery( '.synchro-single input[name="entry_id"]' ).val( jQuery( this ).data( 'id' ) );
};

/**
 * Déclare les formulaires pour les mises à jour et leur fonctionnement.
 *
 * @type {void}
 */
window.eoxiaJS.wpshop.doliSynchro.declareUpdateForm = function() {
	jQuery( '.item' ).find( 'form' ).ajaxForm({
		dataType: 'json',
		success: function( responseText, statusText, xhr, $form ) {
			if ( ! responseText.data.updateComplete ) {
				$form.find( '.item-stats' ).html( responseText.data.progression );
				$form.find( 'input[name="done_number"]' ).val( responseText.data.doneElementNumber );
				$form.find( '.item-progression' ).css( 'width', responseText.data.progressionPerCent + '%' );

				if ( responseText.data.done ) {
					$form.closest( '.item' ).removeClass( 'waiting-item' );
					$form.closest( '.item' ).removeClass( 'in-progress-item' );
					$form.closest( '.item' ).addClass( 'done-item' );
					$form.find( '.item-stats' ).html( responseText.data.doneDescription );
				}
			} else {
				if ( ! window.eoxiaJS.wpshop.doliSynchro.completed ) {
					$form.find( '.item-stats' ).html( responseText.data.progression );
					$form.find( 'input[name="done_number"]' ).val( responseText.data.doneElementNumber );
					$form.find( '.item-progression' ).css( 'width', responseText.data.progressionPerCent + '%' );

					if ( responseText.data.done ) {
						$form.closest( '.item' ).removeClass( 'waiting-item' );
						$form.closest( '.item' ).removeClass( 'in-progress-item' );
						$form.closest( '.item' ).addClass( 'done-item' );
						$form.find( '.item-stats' ).html( responseText.data.doneDescription );
					}

					window.eoxiaJS.wpshop.doliSynchro.completed = true;
					jQuery( '.general-message' ).html( responseText.data.doneDescription );
					window.removeEventListener( 'beforeunload', window.eoxiaJS.wpshop.doliSynchro.safeExit );
				}
			}

			window.eoxiaJS.wpshop.doliSynchro.requestUpdate();
		}
	});
};

/**
 * Lancement du processus de mixe à jour: On prned le premier formulaire ayant la classe 'waiting-item'
 *
 * @return {void}
 */
window.eoxiaJS.wpshop.doliSynchro.requestUpdate = function() {
	if ( ! window.eoxiaJS.wpshop.doliSynchro.completed ) {
		var currentUpdateItemID = '#' + jQuery( '.waiting-item:first' ).attr( 'id' );

		jQuery( currentUpdateItemID ).addClass( 'in-progress-item' );
		jQuery( currentUpdateItemID ).find( 'form' ).submit();

	}
};

/**
 * Vérification avant la fermeture de la page si la mise à jour est terminée.
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @param  {WindowEventHandlers} event L'évènement de la fenêtre.
 * @return {string}
 */
window.eoxiaJS.wpshop.doliSynchro.safeExit = function( event ) {
	var confirmationMessage = taskManager.wpshopconfirmExit;
	if ( taskManager.wpshopUrlPage === event.currentTarget.adminpage ) {
		event.returnValue = confirmationMessage;
		return confirmationMessage;
	}
};

/**
 * @todo: voir processus de MAJ des MU.
 *
 * @type {Object}
 */
window.eoxiaJS.wpshop.doliSynchro.requestUpdateFunc = {
	endMethod: []
};

window.eoxiaJS.wpshop.doliSynchro.loadedModalSynchroSingle = function( triggeredElement, response ) {
	jQuery( 'body' ).append( response.data.view );
}

window.eoxiaJS.wpshop.doliSynchro.goSync = function (triggeredElement) {
	jQuery( triggeredElement ).closest( '.wpeo-modal' ).addClass( 'modal-force-display' );

	return true;
}


window.eoxiaJS.wpshop.doliSynchro.associatedAndSynchronized = function (triggeredElement, response) {
	var modal = jQuery( triggeredElement ).closest( '.wpeo-modal' );
	modal.removeClass( 'modal-force-display' );

	modal.find( 'button-light' ).hide();

	modal.find( '.mask' ).fadeIn();

}

/**
 * Gestion JS des produits.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.product = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.product.init = function() {};

/**
 * Gestion JS des produits.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.transfertData = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.transfertData.init = function() {
	if ( jQuery( '.transfert-data' ).length > 0 ) {
		window.eoxiaJS.wpshop.transfertData.start(0);
	}
};

window.eoxiaJS.wpshop.transfertData.start = function(index, index_error) {
	var data = {
		action: 'wps_transfert_data',
		number_customers: jQuery( '.wrap input[name=number_customers]' ).val(),
		index: index,
		index_error: index_error,
		key_query: jQuery( '.wrap input[name=key_query]').val()
	};

	jQuery.post( window.ajaxurl, data, function( response ) {
		jQuery( '.wrap ul.output' ).append( response.data.output );
		jQuery( '.wrap ul.errors' ).append( response.data.errors );
		jQuery( '.wrap input[name=index]' ).val( response.data.index );
		jQuery( '.wrap input[name=key_query]' ).val( response.data.key_query );

		if ( ! response.data.done ) {
			window.eoxiaJS.wpshop.transfertData.start( response.data.index, response.data.index_error );
		}
	} );
}
