$(document).ready(function() {
	
});

function updateStatus(msg)
{
	$('#statusMsg').fadeOut(100, function() {
		$('#statusMsg').html(msg).fadeIn(100);
	});
}

function disableInputs()
{
	$('#pollQuestion').prop("disabled", true);
	$("#pollAnswers :input").prop("disabled", true);
}

function disableButtons()
{
	$('#addAnswerButton, #createPollButton').prop("disabled", true);
}

function enableInputs()
{
	$('#pollQuestion').prop("disabled", false);
	$("#pollAnswers :input").prop("disabled", false);
}

function enableButtons()
{
	$('#addAnswerButton, #createPollButton').prop("disabled", false);
}

function addAnswer()
{
	var nextAnswerID = $('#pollAnswers input').length + 1;
	$.post("/", { 
		c: 'poll', 
		a: 'ajaxaddanswer', 
		ajax: '1', 
		nextAnswerID: nextAnswerID
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
		} else if (jData.nextAnswerID) {
			// Success, add the line
			$('#pollAnswers').append(jData.nextAnswer).enhanceWithin();
		} else {
			alert('ERROR: failed to add answer');
		}
	});
}

function createPoll()
{
	// Disable buttons
	disableButtons();
	//alert($('#pollQuestion').val()+' :: '+$('#pollAnswers').serialize());
	$.post("/", { 
		c: 'poll', 
		a: 'ajaxinsertpoll', 
		ajax: '1',
		pollQuestion: $('#pollQuestion').val(),
		pollAnswers: $('#pollAnswers').serialize()
	}, function(data) {
		// Disable inputs
		disableInputs();
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
			enableInputs();
			enableButtons();
		} else if (jData.html) {
			// Success, update status and go see poll
			updateStatus(jData.html);
			window.location = '/poll/results/'+jData.pollID+'/';
		} else {
			alert('ERROR: Poll seems to have saved but no ID was returned');
		}
	});
}