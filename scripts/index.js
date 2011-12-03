$(document).ready(function() {
	/* Add handlers for background image swapping */
	$("div.project").mouseenter(function() {
		var project = $(this).attr("name");

		/* Replace whitespaces with %20 for URI */
		var regex1 = /\s/gi; 	
		project = project.replace( regex1, "%20");

		/* Remove ? (and other characters if needed...) */
		var regex2 = /\?/gi;  
		project = project.replace( regex2, "");

		$(this).css("background-image", "url(media/" + project + "/icon.png)");
	}).mouseleave(function() {
		var project = $(this).attr("name");

		/* Replace whitespaces with %20 for URI */
		var regex1 = /\s/gi; 	
		project = project.replace( regex1, "%20");

		/* Remove ? (and other characters if needed...) */
		var regex2 = /\?/gi;  
		project = project.replace( regex2, "");

		$(this).css("background-image", "url(media/" + project + "/icon_gray.png)");
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

