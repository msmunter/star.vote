$(document).ready(function() {
	adjustColumns();
	$(window).resize(function() {
		adjustColumns();
	});
});

function loadMorePolls(pollType, index) {
	if (pollType == "r") {
		var itemLength = $('#recentPollContainer .pollLink').length;
	} else {
		var itemLength = $('#popularPollContainer .pollLink').length;
	}
	$.post("/", { 
		c: 'poll', 
		a: 'ajaxloadmorepolls', 
		ajax: '1',
		pollType: pollType,
		index: itemLength
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			$('#statusMsg').html("ERROR: "+jData.error);
		} else {
			if (pollType == 'r') {
				$('#recentPollContainer').append(jData.html).enhanceWithin();
			} else {
				$('#popularPollContainer').append(jData.html).enhanceWithin();
			}
			adjustColumns();
		}
	});
}

function adjustColumns() {
	if ($(window).width() > 420) {
		$('.columnOne').addClass('ui-block-a');
		$('.columnTwo').addClass('ui-block-b');
	} else {
		$('.columnOne').removeClass('ui-block-a');
		$('.columnTwo').removeClass('ui-block-b');
	}
}