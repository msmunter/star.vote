/* Remember: poll.js gets included with the index */
$(document).ready(function() {
	adjustColumns();
	$(window).resize(function() {
		adjustColumns();
	});
	$('#pollQuestion').focus();
});

function adjustColumns() {
	if ($(window).width() > 800) {
		$('.columnOne').addClass('ui-block-a');
		$('.columnTwo').addClass('ui-block-b');
	} else {
		$('.columnOne').removeClass('ui-block-a');
		$('.columnTwo').removeClass('ui-block-b');
	}
}