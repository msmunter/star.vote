// $(document).ready(function() {
	
// });

function updateStatus(msg)
{
	$('#statusMsg').fadeOut(100, function() {
		$('#statusMsg').html(msg).fadeIn(100);
	});
}

function clearStatus()
{
	$('#statusMsg').fadeOut(100, function() {
		$('#statusMsg').html('');
	});
}

function disableButtons()
{
	$('#finalizeSurveyButton').prop("disabled", true);
}

function enableButtons()
{
	$('#finalizeSurveyButton').prop("disabled", false);
}

function finalizeSurvey() {
	disableButtons();
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxfinalizesurvey', 
		ajax: '1',
		surveyID: $('#surveyID').val()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
			enableButtons();
		} else {
			updateStatus(jData.result);
		}
	});
}