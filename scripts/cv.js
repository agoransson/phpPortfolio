$(document).ready(function() {
	/* The popup div */
	$(function() {
		var moveLeft = 20;
		var moveDown = 10;

		$('a#trigger').hover(function(e) {
			$('div#email_popup').show();
		}, function() {
			$('div#email_popup').hide();
		});

		$('a#trigger').mousemove(function(e) {
			$("div#email_popup").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
		});
	});
});
