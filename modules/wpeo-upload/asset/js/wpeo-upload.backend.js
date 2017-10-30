/**
 * Initialise l'objet "upload" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 0.1.0-alpha
 * @version 1.2.0
 * @copyright 2017
 * @author Jimmy Latour <jimmy@eoxia.com>
 */

window.eoxiaJS.upload = {};

/**
 * Keep the button in memory.
 *
 * @type {Object}
 */
window.eoxiaJS.upload.currentButton;

/**
 * Keep the media frame in memory.
 * @type {Object}
 */
window.eoxiaJS.upload.mediaFrame;

/**
 * Keep the selected media in memory.
 * @type {Object}
 */
window.eoxiaJS.upload.selectedInfos = {
	JSON: undefined,
	fileID: undefined
};

/**
 * Init func.
 *
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.1.0-alpha
 */
window.eoxiaJS.upload.init = function() {
	window.eoxiaJS.upload.event();
};

/**
 * Event func.
 *
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 1.2.0
 */
window.eoxiaJS.upload.event = function() {
	jQuery( document ).on( 'click', '.media:not(.loading), .wpeo-upload-list a.upload:not(.loading)', window.eoxiaJS.upload.openPopup );
	jQuery( document ).on( 'click', '.media-toolbar, .media-modal-close, .media-button-insert', function( event ) { event.stopPropagation(); } );
};

/**
 * Open the media frame from WordPress or the custom gallery.
 *
 * @param  {MouseEvent} event  The mouse state.
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.2.0-alpha
 */
window.eoxiaJS.upload.openPopup = function( event ) {
	window.eoxiaJS.upload.currentButton = jQuery( this );
	event.preventDefault();

	if ( jQuery( this ).hasClass( 'no-file' ) || jQuery( this ).is( "a" ) ) {
		window.eoxiaJS.upload.openMediaFrame();
	} else {
		window.eoxiaJS.gallery.open();
	}
};

/**
 * Open the media frame from WordPress.
 *
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.2.0-alpha
 */
window.eoxiaJS.upload.openMediaFrame = function() {
	window.eoxiaJS.upload.mediaFrame = new window.wp.media.view.MediaFrame.Post({
		'library':{
			'type': window.eoxiaJS.upload.currentButton.data( 'mime-type' )
		}
	}).open();
	window.eoxiaJS.upload.mediaFrame.on( 'insert', function() { window.eoxiaJS.upload.selectedFile(); } );
};

/**
 * Get the media selected and call associateFile.
 *
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.2.0-alpha
 */
window.eoxiaJS.upload.selectedFile = function() {
	window.eoxiaJS.upload.mediaFrame.state().get( 'selection' ).map( function( attachment ) {
		window.eoxiaJS.upload.selectedInfos.JSON = attachment.toJSON();
		window.eoxiaJS.upload.selectedInfos.id = attachment.id;
	} );
	window.eoxiaJS.upload.associateFile();
};

/**
 * Make request for associate file
 *
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.2.0-alpha
 */
window.eoxiaJS.upload.associateFile = function() {
	var data = {
		action: 'eo_upload_associate_file',
		file_id: window.eoxiaJS.upload.selectedInfos.id
	};
	var key = '';
	window.eoxiaJS.upload.currentButton.get_data( function( attrData ) {
		for ( key in attrData ) {
			data[key] = attrData[key];
		}
	} );
	window.eoxiaJS.upload.currentButton.addClass( 'loading' );
	jQuery.post( window.ajaxurl, data, function( response ) {
		window.eoxiaJS.upload.refreshButton( response.data );

		if ( 'gallery' === response.data.display_type ) {
			window.eoxiaJS.gallery.open( false );
		}
	} );
};

/**
 * Update the view of the button
 *
 * @param  {Object} data Data of button.
 * @return {void}
 *
 * @since 0.1.0-alpha
 * @version 1.2.0
 */
window.eoxiaJS.upload.refreshButton = function( data ) {
	if( window.eoxiaJS.upload.currentButton.is( 'a' ) ) {
		window.eoxiaJS.upload.currentButton.removeClass( 'loading' );
		if ( ! data.id ) {
			window.eoxiaJS.upload.currentButton.closest( 'div' ).find( 'ul' ).append( data.view );
		}
	} else {
		if ( data.view ) {
			if ( window.eoxiaJS.upload.currentButton.data( 'custom-class' ) ) {
				jQuery( 'span.media[data-id="' + window.eoxiaJS.upload.currentButton.data( 'id' ) + '"].' + window.eoxiaJS.upload.currentButton.data( 'custom-class' ) ).replaceWith( data.view );
			} else {
				jQuery( 'span.media[data-id="' + window.eoxiaJS.upload.currentButton.data( 'id' ) + '"]' ).replaceWith( data.view );
			}
		} else {
			window.eoxiaJS.upload.currentButton.find( 'img' ).replaceWith( data.media );
			window.eoxiaJS.upload.currentButton.find( 'i' ).hide();
			window.eoxiaJS.upload.currentButton.find( 'input[type="hidden"]' ).val( window.eoxiaJS.upload.selectedInfos.JSON.id );
		}
	}
};

window.eoxiaJS.gallery = {};

/**
 * Init func.
 *
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.1.0-alpha
 */
window.eoxiaJS.gallery.init = function() {
	window.eoxiaJS.gallery.event();
};

/**
 * Event func.
 *
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.1.0-alpha
 */
window.eoxiaJS.gallery.event = function() {
	jQuery( document ).on( 'keyup', window.eoxiaJS.gallery.keyup );
	jQuery( document ).on( 'click', '.eo-gallery', function( event ) { event.preventDefault(); return false; } );
	jQuery( document ).on( 'click', '.eo-gallery .navigation .prev', window.eoxiaJS.gallery.prevPicture );
	jQuery( document ).on( 'click', '.eo-gallery .navigation .next', window.eoxiaJS.gallery.nextPicture );
	jQuery( document ).on( 'click', '.eo-gallery .close', window.eoxiaJS.gallery.close );
};

/**
 * Make request for open gallery
 *
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.2.0-alpha
 */
window.eoxiaJS.gallery.open = function( append = true ) {
	var data = {
		action: 'eo_upload_load_gallery'
	};
	var key = '';
	window.eoxiaJS.upload.currentButton.get_data( function( attrData ) {
		for ( key in attrData ) {
			data[key] = attrData[key];
		}
	} );
	window.eoxiaJS.upload.currentButton.addClass( 'loading' );

	if ( append ) {
		jQuery( '.eo-gallery' ).remove();
	}

	jQuery.post( ajaxurl, data, function( response ) {
		if ( append ) {
			jQuery( '#wpwrap' ).append( response.data.view );
		} else {
			jQuery( '.eo-gallery' ).replaceWith( response.data.view );
		}
		window.eoxiaJS.upload.currentButton.removeClass( 'loading' );
	});
};

/**
 * Next and Previous picture in gallery
 *
 * @param  {KeyEvent} event Keyboard state.
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.1.0-alpha
 */
window.eoxiaJS.gallery.keyup = function( event ) {
	if ( 37 === event.keyCode ) {
		window.eoxiaJS.gallery.prevPicture();
	} else if ( 39 === event.keyCode ) {
		window.eoxiaJS.gallery.nextPicture();
	} else if ( 27 === event.keyCode ) {
		jQuery( '.eo-gallery .close' ).click();
	}
};

/**
 * Prev picture func.
 *
 * @param  {KeyEvent} event Keyboard state.
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.1.0-alpha
 */
window.eoxiaJS.gallery.prevPicture = function( event ) {
	if ( jQuery( '.eo-gallery .image-list li.current' ).prev().length <= 0 ) {
		jQuery( '.eo-gallery .image-list li.current' ).toggleClass( 'current hidden' );
		jQuery( '.eo-gallery .image-list li:last' ).toggleClass( 'hidden current' );
	}	else {
		jQuery( '.eo-gallery .image-list li.current' ).toggleClass( 'current hidden' ).prev().toggleClass( 'hidden current' );
	}

	jQuery( '.eo-gallery .edit-thumbnail-id' ).attr( 'data-file-id', jQuery( '.eo-gallery .current' ).attr( 'data-id' ) );
};

/**
 * Next picture func.
 *
 * @param  {KeyEvent} event Keyboard state.
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.1.0-alpha
 */
window.eoxiaJS.gallery.nextPicture = function( event ) {
	if ( jQuery( '.eo-gallery .image-list li.current' ).next().length <= 0 ) {
		jQuery( '.eo-gallery .image-list li.current' ).toggleClass( 'current hidden' );
		jQuery( '.eo-gallery .image-list li:first' ).toggleClass( 'hidden current' );
	} else {
		jQuery( '.eo-gallery .image-list li.current' ).toggleClass( 'current hidden' ).next().toggleClass( 'hidden current' );
	}

	jQuery( '.eo-gallery .edit-thumbnail-id' ).attr( 'data-file-id', jQuery( '.eo-gallery .current' ).attr( 'data-id' ) );
};

/**
 * Close the gallery
 *
 * @param  {KeyEvent} event Keyboard state
 * @return void
 *
 * @since 0.1.0-alpha
 * @version 0.1.0-alpha
 */
window.eoxiaJS.gallery.close = function( event ) {
	jQuery( '.eo-gallery' ).remove();
};

/**
 * Le callback en cas de réussite à la requête Ajax "dissociate_file".
 * Remplaces les boutons pour ouvrir la popup "galerie"
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 0.1.0-alpha
 * @version 0.2.0-alpha
 */
window.eoxiaJS.gallery.dissociatedFileSuccess = function( element, response ) {
	if ( response.data.close_popup ) {
		jQuery( '.eo-gallery' ).remove();
	}

	jQuery( '.eo-gallery .image-list .current' ).remove();
	jQuery( '.eo-gallery .prev' ).click();
	window.eoxiaJS.upload.refreshButton( response.data );
};

/**
 * Le callback en cas de réussite à la requête Ajax "eo_set_thumbnail".
 * Remplaces les boutons pour ouvrir la popup "galerie"
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 0.1.0-alpha
 * @version 0.1.0-alpha
 */
window.eoxiaJS.gallery.successfulSetThumbnail = function( element, response ) {
	window.eoxiaJS.upload.refreshButton( response.data );
};
