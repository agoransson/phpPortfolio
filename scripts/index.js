$(document).ready(function() {

	$("div.project").mouseenter(function() {
		$(this).children("img").fadeOut('slow', function() {
			// Animation complete.
		});
	}).mouseleave(function() {
		$(this).children("img").fadeIn('slow', function() {
			// Animation complete.
		});
	});

	/* Opening project detail */
	$("div.project").click(function(){
		window.location = "project.php?id=" + $(this).attr("id");
	});

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

