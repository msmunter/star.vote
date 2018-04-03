$(document).ready(function() {
	adjustColumns();
	$(window).resize(function() {
		adjustColumns();
	});
});

function loadMorePolls(index) {
	var itemLength = $('#pollContainer .pollLink').length;
	$.post("/", { 
		c: 'poll', 
		a: 'ajaxloadmorepolls', 
		ajax: '1',
		index: itemLength
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			$('#statusMsg').html("ERROR: "+jData.error);
		} else {
			$('#pollContainer').append(jData.html).enhanceWithin();
			adjustColumns();
		}
	});
}

function loadMoreSurveys(index) {
	var itemLength = $('#surveyContainer .surveyLink').length;
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxloadmoresurveys', 
		ajax: '1',
		index: itemLength
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			$('#statusMsg').html("ERROR: "+jData.error);
		} else {
			$('#surveyContainer').append(jData.html).enhanceWithin();
			adjustColumns();
		}
	});
}

function adjustColumns() {
	if ($(window).width() > 800) {
		$('.columnOne').addClass('ui-block-a');
		$('.columnTwo').addClass('ui-block-b');
	} else {
		$('.columnOne').removeClass('ui-block-a');
		$('.columnTwo').removeClass('ui-block-b');
	}
}