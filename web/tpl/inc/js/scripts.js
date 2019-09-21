$(document).ready(function(){
	// Init collapsing navbar
    $('nav>button').click(function () {
        if ( $('nav>#navbar').is(':hidden') ) {
            $('nav>#navbar').slideDown();
        } else {
            $('nav>#navbar').slideUp();
        }
    });
	
	// Init improve visibility button
	if (Cookies.get('highcontrast') == 'True') {
		if ($('body').hasClass('im-vis') == false) {
			$('body').addClass('im-vis');
		}
	}
	$("#improve-visibility").click(function() {
		if ($('body').hasClass('im-vis')) {
			Cookies.remove('highcontrast');
			$('body').removeClass('im-vis');
		} else {
			Cookies.set('highcontrast','True');
			$('body').addClass('im-vis');
		}
	});
});