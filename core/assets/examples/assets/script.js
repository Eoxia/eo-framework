$(document).ready(function() {
	w3.includeHTML(function() {
		Prism.highlightAll();
	});

	$('.scroll-to').on('click', function() { // Au clic sur un élément
		var page = $(this).attr('href'); // Page cible
		var speed = 750; // Durée de l'animation (en ms)
		$('html, body').animate( { scrollTop: $(page).offset().top - 80 }, speed ); // Go
		return false;
	});

	$('body').on('click','#button-load', function() {
		setTimeoutClass( $(this), 'load' );
	});
	$('body').on('click','#button-success', function() {
		setTimeoutClass( $(this), 'success' );
	});
	$('body').on('click','#button-error', function() {
		setTimeoutClass( $(this), 'error' );
	});
});

window.eoxiaJS.example.init = function() {

}

function setTimeoutClass( element, className ) {
	element.addClass( className );
	setTimeout( function() {
		element.removeClass( className );
	}, 2000 );
}
