var eoMenu = {
	$,

	init: function( $ ) {
		eoMenu.$ = $;
		eoMenu.event();
	},

	event: function() {
		eoMenu.$( document ).on( 'click', '.nav-wrap .minimize-menu', eoMenu.handleMinimizeMenu );
	},

	handleMinimizeMenu: function (event) {
		if (eoMenu.$(this).find('i').hasClass('fa-arrow-left')) {
			eoMenu.$('.nav-wrap').addClass('wrap-reduce');
			eoMenu.$('.content-wrap').addClass('content-reduce');
			eoMenu.$(this).find('i').removeClass('fa-arrow-left').addClass('fa-arrow-right');
		} else {
			eoMenu.$('.nav-wrap').removeClass('wrap-reduce');
			eoMenu.$('.content-wrap').removeClass('content-reduce');
			eoMenu.$(this).find('i').addClass('fa-arrow-left').removeClass('fa-arrow-right');
		}

		event.preventDefault();
	}
};

jQuery( document ).ready(eoMenu.init);
